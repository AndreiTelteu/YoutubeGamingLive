<?php

namespace App\Http\Controllers;

use App\Models\Channel;
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
        $data = [];
        $channels = $userData["user"]->subscriptions()->get();
        foreach ($channels as $channel) {
            $data[] = $channel->only([
                "id",
                "updated_at",
                "youtube_id",
                "name",
                "online",
                "slug",
                "avatar",
                "country",
            ]);
        }
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
        $channel = Channel::where("slug", $data["slug"])->first();
        if (!$channel) {
            return ["success" => false, "message" => "Channel not found"];
        }
        // $channel->online = $channel->checkIfLive();
        return [
            "success" => true,
            "channel" => $channel,
        ];
    }
}
