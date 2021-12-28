<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WsController extends Controller
{
    public function connect($websocket, Request $request)
    {
        $userData = socket_data($websocket->getSender());
        $user = token_user($request->token);
        $userData["user"] = $user;
        socket_data($userData);

        echo "Conectat - " .
            ($user ? $user->name : "vizitator") .
            " - " .
            $websocket->getSender() .
            "\n";
    }

    public function disconnect($websocket)
    {
        $userData = socket_data($websocket->getSender());
        echo "Deconectat - " .
            ($userData["user"] ? $userData["user"]->name : "vizitator") .
            " - " .
            $websocket->getSender() .
            "\n";
        cache(["ws:" . $websocket->getSender() => null]);
    }

    public function subscribers($websocket)
    {
        $userData = socket_data($websocket->getSender());
        $data = $userData["user"]->subscriptions()->get();
        $websocket->emit("subscribers", socket_response($data));
    }
}
