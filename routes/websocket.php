<?php

use Illuminate\Http\Request;
use SwooleTW\Http\Websocket\Facades\Websocket;

/*
|--------------------------------------------------------------------------
| Websocket Routes
|--------------------------------------------------------------------------
|
| Here is where you can register websocket events for your application.
|
*/

Websocket::on("connect", function ($websocket, Request $request) {
    $request->headers->set("Authorization", "Bearer " . $request->token);
    echo "\n";
    echo "Conectat - " . $request->token;
    echo "\n";
    // dump($request->headers->all());
    // dump($request->bearerToken());
    // $model = \Laravel\Sanctum\Sanctum::$personalAccessTokenModel;
    // $accessToken = $model::findToken($request->token);
    try {
        dump([$request->bearerToken(), verify_token($request->token)]);
    } catch (\Exception $e) {
        dump($e);
    }
});

Websocket::on("disconnect", function ($websocket) {
    // called while socket on disconnect
});

Websocket::on("example", function ($websocket, $data) {
    $websocket->emit("message", $data);
});
