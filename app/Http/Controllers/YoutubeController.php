<?php

namespace App\Http\Controllers;

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

    public function webhook(Request $request)
    {
        logger()->debug("youtube hook call " . json_encode($request->all()));
        echo $request->hub_challenge;
    }
}
