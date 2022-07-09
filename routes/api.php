<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ChannelController;
use App\Http\Controllers\YoutubeController;

Broadcast::routes(["middleware" => ["auth:api"]]);

Route::get("/node", function () {
    dump(gethostname());
    dump($_SERVER['SERVER_ADDR']);
    // dd($_SERVER);
});
// Route::get("/info", function () {
//     phpinfo();
// });

Route::post("/youtube/manual-login", [YoutubeController::class, "manual_login"]);
Route::middleware(["auth:api"])->group(function () {
    Route::prefix("/user")->group(function () {
        Route::get("/", [UserController::class, "get"]);
        Route::get("/subscriptions", [UserController::class, "subscriptions"]);
    });
});

Route::prefix("/channel")->group(function () {
    Route::get("/{slug}", [ChannelController::class, "find"]);
    Route::post("/event-updated", [ChannelController::class, "channelUpdated"]);
});
Route::get("/channels", [ChannelController::class, "get"]);
