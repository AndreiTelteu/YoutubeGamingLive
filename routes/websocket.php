<?php

use Illuminate\Http\Request;
use App\Http\Controllers\WsController;
use SwooleTW\Http\Websocket\Facades\Websocket;

Websocket::on("connect", WsController::class . "@connect");
Websocket::on("disconnect", WsController::class . "@disconnect");

Websocket::on("subscribers", WsController::class . "@subscribers");
