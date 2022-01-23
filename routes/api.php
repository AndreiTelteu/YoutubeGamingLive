<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ChannelController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Broadcast::routes(["middleware" => ["auth:api"]]);

Route::post("/socket-event-channel", [WsController::class, "httpChannel"]);

Route::middleware(["auth:api"])->group(function () {
    Route::prefix("/user")->group(function () {
        Route::get("/", [UserController::class, "get"]);
        Route::get("/subscriptions", [UserController::class, "subscriptions"]);
    });
});

Route::prefix("/channel")->group(function () {
    Route::get("/{slug}", [ChannelController::class, "find"]);
});
