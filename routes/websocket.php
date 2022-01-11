<?php

use Illuminate\Http\Request;
use App\Http\Controllers\WsController;
use SwooleTW\Http\Websocket\Facades\Websocket;
use Illuminate\Support\Facades\Redis;

Websocket::on("connect", WsController::class . "@connect");
Websocket::on("disconnect", WsController::class . "@disconnect");

Websocket::on("api", WsController::class . "@api");

Websocket::on("subscribers", WsController::class . "@subscribers");

// Redis::subscribe(["channel-updated"], function ($message) {
//     echo $message;
// });

// app(\SwooleTW\Http\Server\Facades\Server::class);
// app("swoole.server")->on("workerStart", function ($s, $w) {
//     echo "wstart";
//     Redis::subscribe(["channel-updated"], function ($message) {
//         echo $message;
//     });
// });
