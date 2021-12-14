<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Events\ChannelCreated;

class Channel extends Model
{
    use HasFactory;
    protected $table = "channels";
    protected $fillable = ["youtube_id", "name", "avatar", "data"];
    protected $casts = ["data" => "array"];
    protected $appends = ["topic"];

    protected $dispatchesEvents = [
        "created" => ChannelCreated::class,
    ];

    public function getTopicAttribute()
    {
        return "https://www.youtube.com/xml/feeds/videos.xml?channel_id={$this->attributes["youtube_id"]}";
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
}
