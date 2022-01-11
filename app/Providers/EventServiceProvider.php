<?php

namespace App\Providers;

use App\Events\ChannelCreated;
use App\Listeners\SubscribePubSubHub;
use App\Listeners\SocketStart;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [SendEmailVerificationNotification::class],

        \SocialiteProviders\Manager\SocialiteWasCalled::class => [
            \SocialiteProviders\YouTube\YouTubeExtendSocialite::class .
            "@handle",
        ],
        ChannelCreated::class => [SubscribePubSubHub::class],
        "swoole.start" => [SocketStart::class],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
