<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Events\ChannelCreated;
use Youtube;

class Channel extends Model
{
    use HasFactory;

    protected $table = "channels";
    protected $fillable = [
        "youtube_id",
        "slug",
        "name",
        "data",
        "country",
        "avatar",
        "avatar_medium",
        "banner_image",
    ];
    protected $casts = ["data" => "array"];
    protected $appends = ["topic", "online"];

    public $online = false;

    protected $dispatchesEvents = [
        "created" => ChannelCreated::class,
    ];

    public function getTopicAttribute()
    {
        return "https://www.youtube.com/xml/feeds/videos.xml?channel_id={$this->attributes["youtube_id"]}";
    }

    public function getOnlineAttribute()
    {
        return $this->online;
    }

    public static function parseTopicUrl($topic)
    {
        $query = parse_url($topic, PHP_URL_QUERY);
        parse_str($query, $result);
        return optional($result)["channel_id"] ? $result["channel_id"] : null;
    }

    public static function findByTopic($topic)
    {
        $channel_id = self::parseTopicUrl($topic);
        if (!$channel_id) {
            return null;
        }
        return self::where("youtube_id", $channel_id)->first();
    }

    public function checkIfLive()
    {
        $params = [
            "channelId" => $this->youtube_id,
            "part" => "snippet",
            "type" => "channel",
            "maxResults" => 1,
        ];
        $data = json_decode(
            Youtube::api_get(
                "https://youtube.googleapis.com/youtube/v3/search",
                $params
            )
        );
        if (
            $data &&
            $data->items &&
            $data->items[0] &&
            $data->items[0]->snippet->liveBroadcastContent == "live"
        ) {
            return true;
        }
        return false;
    }
}
