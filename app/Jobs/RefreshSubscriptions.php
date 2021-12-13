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
            }
            $channel->youtube_id = $item->snippet->resourceId->channelId;
            $channel->name = $item->snippet->title;
            $channel->avatar = $item->snippet->thumbnails->default->url;
            $channel->data = [
                "description" => $item->snippet->description,
            ];
            $channel->save();
            $this->subIds[] = $channel->id;
        }

        return $this->pageToken != null;
    }
}
