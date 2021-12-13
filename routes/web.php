<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get("/", function () {
    return redirect("/f");
});

Route::get("/dada", function () {
    $result = new \WhichBrowser\Parser(request()->header("User-Agent"));
    $device =
        $result->browser->name .
        " " .
        $result->browser->version->toNumber() .
        " on " .
        $result->os->toString();
    $country = request()->header("CF-IPCountry");
    echo "Device: $device from $country";

    return view("welcome");
});

Route::get("/youtube/login", function () {
    return \Socialite::driver("youtube")
        ->stateless()
        ->redirect();
});

Route::get("/youtube/callback", function () {
    $social = null;
    try {
        $social = @\Socialite::driver("youtube")
            ->stateless()
            ->user();
    } catch (\Exception $e) {
    }

    $auth = [
        "logged" => false,
    ];
    if ($social !== null) {
        $user = \App\Models\User::where("youtube_id", $social->id)->first();
        if (!$user) {
            $user = new \App\Models\User();
        }
        $user->youtube_id = $social->id;
        $user->name = $social->nickname;
        $user->avatar = $social->avatar;
        $user->save();

        $result = new \WhichBrowser\Parser(request()->header("User-Agent"));
        $device =
            $result->browser->name .
            " " .
            $result->browser->version->toNumber() .
            " on " .
            $result->os->toString();
        $country = request()->header("CF-IPCountry");
        $token = $user->createToken("$device from $country");

        $auth = [
            "logged" => true,
            "user" => $user,
            "token" => $token->plainTextToken,
        ];
    }
    return view("youtube.callback", compact("auth"));
    // $subs = Youtube::decodeList(
    //     Youtube::api_get(
    //         "https://youtube.googleapis.com/youtube/v3/subscriptions",
    //         [
    //             "part" => "snippet",
    //             "channelId" => $social->id,
    //             // "channelId" => "UCgb_d0gAPFJjNO0QDAM6pbg",
    //             "maxResults" => 50,
    //         ]
    //     )
    // );
    // dump($subs);
});

Route::get("/youtube/subscribe", function () {
    $pubsub = new \Pubsubhubbub\Subscriber\Subscriber(
        "https://pubsubhubbub.appspot.com",
        "https://youtubegaming.live/youtube/webhook"
    );

    $sub = $pubsub->subscribe(
        "https://www.youtube.com/xml/feeds/videos.xml?channel_id=UCWxlUwW9BgGISaakjGM37aw"
    );
});

Route::get("/youtube/webhook", function () {
    echo request()->hub_challenge;
    logger()->debug("dada youtube hook call " . json_encode(request()->all()));
});
