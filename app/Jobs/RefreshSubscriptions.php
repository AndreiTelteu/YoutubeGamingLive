<?php

namespace App\Jobs;

use App\Models\Channel;
use Youtube;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class RefreshSubscriptions implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $user;
    private $pageToken = null;
    private $subIds = [];
    private $missingSlug = [];

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        while ($this->syncPage()) {
        }

        $this->user->subscriptions()->sync($this->subIds);

        $this->resolveSlugs();
    }

    public function syncPage()
    {
        $perPage = 50;
        $params = [
            "part" => "snippet",
            "channelId" => $this->user->youtube_id,
            "maxResults" => $perPage,
        ];
        if ($this->pageToken) {
            $params["pageToken"] = $this->pageToken;
        }
        $data = json_decode(
            Youtube::api_get(
                "https://youtube.googleapis.com/youtube/v3/subscriptions",
                $params
            )
        );
        if (!$data) {
            return false;
        }
        $this->pageToken = null;
        if (isset($data->nextPageToken)) {
            $this->pageToken = $data->nextPageToken;
        }
        $subscribers = $data->items;

        foreach ($subscribers as $item) {
            $channel = Channel::where(
                "youtube_id",
                $item->snippet->resourceId->channelId
            )->first();
            if (!$channel) {
                $channel = new Channel();
                $channel->slug = $item->snippet->resourceId->channelId;
                $channel->data = [
                    "subscribed" => false,
                ];
            }
            $channel->youtube_id = $item->snippet->resourceId->channelId;
            $channel->name = $item->snippet->title;
            $channel->avatar = $item->snippet->thumbnails->default->url;
            $chData = $channel->data;
            $chData["description"] = $item->snippet->description;
            $channel->data = $chData;
            $channel->save();
            $this->subIds[] = $channel->id;
            if (!$channel->slug || $channel->slug == $channel->youtube_id) {
                $this->missingSlug[] = $channel;
            }
        }

        return $this->pageToken != null;
    }

    public function resolveSlugs()
    {
        if (count($this->missingSlug) == 0) {
            return;
        }

        $allChannels = collect($this->missingSlug);
        foreach ($allChannels->chunk(50) as $channels) {
            $ids = $channels->pluck("youtube_id")->toArray();
            $params = [
                "part" => "snippet,brandingSettings",
                "id" => implode(",", $ids),
                "maxResults" => 50,
            ];
            $data = json_decode(
                Youtube::api_get(
                    "https://youtube.googleapis.com/youtube/v3/channels",
                    $params
                )
            );
            if (!$data) {
                return false;
            }
            $subscribers = $data->items;
            logger()->debug(
                "refresh data for " . count($data->items) . "channels",
                $data->items
            );
            foreach ($subscribers as $item) {
                $channel = $channels->where("youtube_id", $item->id)->first();
                if (!$channel || !$item->snippet) {
                    continue;
                }

                if (optional($item->snippet)->customUrl) {
                    $channel->slug = $item->snippet->customUrl;
                }
                if (optional($item->snippet)->country) {
                    $channel->country = $item->snippet->country;
                }

                if (
                    optional($item->snippet)->thumbnails &&
                    $item->snippet->thumbnails->medium
                ) {
                    $channel->avatar_medium =
                        $item->snippet->thumbnails->medium->url;
                }
                if (
                    optional($item->brandingSettings)->image &&
                    optional($item->brandingSettings)->image->bannerExternalUrl
                ) {
                    $channel->banner_image =
                        $item->brandingSettings->image->bannerExternalUrl;
                }

                $channel->save();
            }
        }
    }
}
