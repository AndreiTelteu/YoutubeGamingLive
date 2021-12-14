<?php

namespace App\Listeners;

use App\Events\ChannelCreated;
use Pubsubhubbub\Subscriber\Subscriber;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SubscribePubSubHub implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\ChannelCreated  $event
     * @return void
     */
    public function handle(ChannelCreated $event)
    {
        $channel = $event->channel;
        $pubsub = new Subscriber(
            "https://pubsubhubbub.appspot.com",
            route("youtube.webhook")
        );
        $pubsub->subscribe($channel->topic);
    }
}
