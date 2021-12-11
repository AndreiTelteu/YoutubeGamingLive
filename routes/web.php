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
    return view("welcome");
});

Route::get("/youtube/login", function () {
    return \Socialite::driver("youtube")
        ->stateless()
        ->redirect();
});

Route::get("/youtube/callback", function () {
    $user = \Socialite::driver("youtube")
        ->stateless()
        ->user();
    dump([
        "id" => $user->id,
        "name" => $user->nickname,
        "avatar" => $user->avatar,
        "all" => $user,
    ]);
    $subs = Youtube::decodeList(
        Youtube::api_get(
            "https://youtube.googleapis.com/youtube/v3/subscriptions",
            [
                "part" => "snippet",
                "channelId" => $user->id,
                // "channelId" => "UCgb_d0gAPFJjNO0QDAM6pbg",
                "maxResults" => 50,
            ]
        )
    );
    dump($subs);
});
