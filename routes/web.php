<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\YoutubeController;

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

Route::get("/youtube/login", [YoutubeController::class, "login"]);
Route::get("/youtube/callback", [YoutubeController::class, "callback"]);
Route::get("/youtube/webhook", [YoutubeController::class, "webhook"]);

Route::get("/youtube/subscribe", function () {
    $pubsub = new \Pubsubhubbub\Subscriber\Subscriber(
        "https://pubsubhubbub.appspot.com",
        "https://youtubegaming.live/youtube/webhook"
    );

    $sub = $pubsub->subscribe(
        "https://www.youtube.com/xml/feeds/videos.xml?channel_id=UCWxlUwW9BgGISaakjGM37aw"
    );
});
