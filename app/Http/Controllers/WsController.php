<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

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

    public function api($websocket, $data)
    {
        $userData = socket_data($websocket->getSender());
        $function = "api" . Str::studly($data["name"]);
        $result = call_user_func_array(
            [$this, $function],
            [$userData, $data["data"]]
        );
        $websocket->emit(
            "api",
            $data["uuid"] . "---|---" . socket_response($result)
        );
    }

    public function apiChannelDetails($userData, $data)
    {
        $data["count"] = 0;
        for ($i = 1; $i < 10; $i++) {
            $data["count"] += count($userData["user"]->subscriptions()->get());
        }
        return $data;
    }
}
