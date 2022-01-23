<?php

namespace App\Http\Controllers;

use App\Models\Channel;
use Illuminate\Http\Request;

class ChannelController extends Controller
{
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
}
