<?php

namespace App\Http\Controllers;

use App\Models\Channel;
use Socialite;
use App\Models\User;
use WhichBrowser\Parser;
use Illuminate\Http\Request;

class YoutubeController extends Controller
{
    public function login(Request $request)
    {
        return Socialite::driver("youtube")
            ->stateless()
            ->redirect();
    }

    public function callback(Request $request)
    {
        $social = null;
        try {
            $social = Socialite::driver("youtube")
                ->stateless()
                ->user();
        } catch (\Exception $e) {
        }

        $auth = [
            "logged" => false,
        ];
        if ($social !== null) {
            $user = User::where("youtube_id", $social->id)->first();
            if (!$user) {
                $user = new User();
            }
            $user->youtube_id = $social->id;
            $user->name = $social->nickname;
            $user->avatar = $social->avatar;
            $user->save();
            $user->refreshSubscriptions();

            $result = new Parser($request->header("User-Agent"));
            $device =
                $result->browser->name .
                " " .
                $result->browser->version->toNumber() .
                " on " .
                $result->os->toString();
            $country = $request->header("CF-IPCountry");
            $token = $user->createToken("$device from $country");

            $auth = [
                "logged" => true,
                "user" => $user,
                "token" => $token->plainTextToken,
            ];
        }
        return view("youtube.callback", compact("auth"));
    }

    public function manual_login(Request $request)
    {
        if (!$request->channel_id) {
            return [
                'success' => false,
                'message' => 'Channel url is required'
            ];
        }
        $auth = [
            "logged" => false,
        ];
        $user = User::where("youtube_id", $request->channel_id)->first();
        if (!$user) {
            $user = new User();
        }
        $user->youtube_id = $request->channel_id;
        $user->name = $request->channel_id;
        // $user->avatar = $social->avatar;
        $user->save();
        $user->refreshSubscriptions();

        $result = new Parser($request->header("User-Agent"));
        $device =
            $result->browser->name .
            " " .
            $result->browser->version->toNumber() .
            " on " .
            $result->os->toString();
        $country = $request->header("CF-IPCountry");
        $token = $user->createToken("$device from $country");

        $auth = [
            "logged" => true,
            "user" => $user,
            "token" => $token->plainTextToken,
        ];
        return $auth;
    }

    public function webhook(Request $request)
    {
        logger()->debug("youtube hook call " . json_encode($request->all()));
        echo $request->hub_challenge;

        $channel = Channel::findByTopic($request->hub_topic);
        if ($channel) {
            $data = $channel->data;
            $data["subscribed"] = true;
            $channel->data = $data;
            $channel->save();
        }
    }
}
