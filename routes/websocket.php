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
    $userData = socket_data($websocket->getSender());
    $user = token_user($request->token);
    $userData["user"] = $user;
    socket_data($userData);

    echo "Conectat - " .
        ($user ? $user->name : "vizitator") .
        " - " .
        $websocket->getSender() .
        "\n";
});

Websocket::on("disconnect", function ($websocket) {
    $userData = socket_data($websocket->getSender());
    echo "Deconectat - " .
        ($userData["user"] ? $userData["user"]->name : "vizitator") .
        " - " .
        $websocket->getSender() .
        "\n";
    cache(["ws:" . $websocket->getSender() => null]);
});

Websocket::on("example", function ($websocket, $data) {
    $websocket->emit("message", $data);
});
