<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public $guard;
    public function __construct()
    {
        $this->guard = Auth::guard("api");
    }

    public function get()
    {
        return $this->guard->user();
    }

    public function subscriptions()
    {
        $user = $this->guard->user();
        $data = [];
        $channels = $user->subscriptions()->get();
        foreach ($channels as $channel) {
            $data[] = $channel->only([
                "id",
                "updated_at",
                "youtube_id",
                "name",
                "online",
                "online_streams",
                "slug",
                "avatar",
                "country",
            ]);
        }
        return $data;
    }
}
