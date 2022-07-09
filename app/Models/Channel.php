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
        "online",
        "online_streams",
        "last_stream_date",
        "last_streams",
        "country",
        "avatar",
        "avatar_medium",
        "banner_image",
    ];

    protected $casts = [
        "data" => "array",
        "online" => "boolean",
        "online_streams" => "array",
        "last_stream_date" => "datetime:Y-m-d H:i:s",
        "last_streams" => "array",
    ];

    protected $appends = ["topic"];

    protected $dispatchesEvents = [
        "created" => ChannelCreated::class,
    ];
    
    public function scopeOnline($query)
    {
        return $query->where('online', true);
    }

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
