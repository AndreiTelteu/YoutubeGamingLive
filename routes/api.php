<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ChannelController;

Broadcast::routes(["middleware" => ["auth:api"]]);

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
