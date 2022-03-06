<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('test:email {email?}', function ($email = 'aandreyy96@gmail.com') {
    try {
        $sent = \Mail::send([], [], function ($message) use ($email) {
            $message->to($email)
                ->subject('test email from laravel')
                ->setBody(" test dada email", 'text/html');
        });
        $this->info('Email sent successfully');
    } catch (\Exception $e) {
        $this->error($e->getMessage());
    }
})->purpose('test email');
