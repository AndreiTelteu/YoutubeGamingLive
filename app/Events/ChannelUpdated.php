<?php

namespace App\Events;

use App\Models\Channel as ChannelModel;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class ChannelUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $channel;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(ChannelModel $channel)
    {
        $this->channel = $channel->only([
            "id",
            "updated_at",
            "youtube_id",
            "name",
            "online",
            "online_streams",
            "slug",
            "avatar",
            "country",
        ]);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel("channel.{$this->channel["id"]}");
    }

    public function broadcastAs()
    {
        return "channel";
    }
}
