<?php

namespace App\Providers;

use App\Factories\UserFactory;
use Illuminate\Support\ServiceProvider;

class UserFactoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(UserFactory::class, function ($app) {
            return new UserFactory();
        });
    }
}
