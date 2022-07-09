<?php

namespace App\Http\Controllers;

use App\Events\ChannelUpdated;
use App\Models\Channel;
use Illuminate\Http\Request;

class ChannelController extends Controller
{
    public function get(Request $request)
    {
        $channels = Channel::online()->latest("updated_at")->get();
        return [
            "success" => true,
            "channels" => $channels,
        ];
    }
    
    public function find(Request $request, $slug)
    {
        $channel = Channel::where("slug", $slug)->first();
        if (!$channel) {
            return ["success" => false, "message" => "Channel not found"];
        }
        return [
            "success" => true,
            "channel" => $channel,
        ];
    }

    public function channelUpdated(Request $request)
    {
        if ($request->get("sig", "") !== env("INTERNAL_API_SIG")) {
            abort(401);
        }
        $channel = Channel::find($request->channelId);
        if (!$channel) {
            return ["success" => false];
        }
        broadcast(new ChannelUpdated($channel));
        return ["success" => true];
    }
}
