<?php

namespace App\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Redis;

class SocketStart
{
    // implements ShouldQueue
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
     * @param    $event
     * @return void
     */
    public function handle()
    {
        echo "startdada 111";
        // Redis::subscribe(["channel-updated"], function ($message) {
        //     echo $message;
        // });
        // app("swoole.server")->on("workerStart", function ($s, $w) {
        //     echo "wstart";
        //     Redis::subscribe(["channel-updated"], function ($message) {
        //         echo $message;
        //     });
        // });
    }
}
