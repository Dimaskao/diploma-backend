<?php

namespace App\Providers;

use App\Events\MessageSent;
use App\Events\UserBanned;
use App\Listeners\LogoutBannedUser;
use App\Listeners\MessageSentListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        MessageSent::class => [
            MessageSentListener::class,
        ],
        UserBanned::class => [
            LogoutBannedUser::class
        ]
    ];

    /**
     * Register any events for your application.
     */
    public function boot()
    {
        parent::boot();
    }
}
