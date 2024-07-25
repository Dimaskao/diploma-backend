<?php

namespace App\Providers;

use App\Services\Response\ResponseService;
use App\Services\SocialNetwork\SearchService;
use Illuminate\Support\ServiceProvider;

class SearchServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(SearchService::class, function ($app) {
            return new SearchService(
                $app->make(ResponseService::class)
            );
        });
    }
}
