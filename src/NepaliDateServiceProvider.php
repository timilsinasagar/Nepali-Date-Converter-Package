<?php
// src/NepaliDateServiceProvider.php

namespace Sagartimilsina\NepaliDate;

use Illuminate\Support\ServiceProvider;
use Sagartimilsina\NepaliDate\Converter\BsAdConverter;
use Sagartimilsina\NepaliDate\Services\NepaliDateService;

class NepaliDateServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/nepali-date.php', 'nepali-date');

        $this->app->singleton(NepaliDateManager::class, function ($app) {
            return new NepaliDateManager(new BsAdConverter());
        });

        $this->app->alias(NepaliDateManager::class, 'nepali-date');

        $this->app->singleton(NepaliDateService::class, function ($app) {
            return new NepaliDateService($app->make(NepaliDateManager::class));
        });
    }

    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'nepali-date');

        if (config('nepali-date.demo_route_enabled', false)) {
            \Illuminate\Support\Facades\Route::middleware('web')
                ->group(__DIR__ . '/../routes/web.php');
        }

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/nepali-date.php' => config_path('nepali-date.php'),
            ], 'nepali-date-config');

            $this->publishes([
                __DIR__ . '/../resources/views' => resource_path('views/vendor/nepali-date'),
            ], 'nepali-date-views');
        }
    }
}
