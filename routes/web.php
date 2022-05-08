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

Route::get("/youtube/login", [YoutubeController::class, "login"]);
Route::get("/youtube/callback", [YoutubeController::class, "callback"]);
Route::get("/youtube/webhook", [YoutubeController::class, "webhook"])->name(
    "youtube.webhook"
);
