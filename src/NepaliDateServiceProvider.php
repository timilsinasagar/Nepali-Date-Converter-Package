<?php
// src/NepaliDateServiceProvider.php

namespace Sagartimilsina\NepaliDate;

use Illuminate\Support\ServiceProvider;
use Sagartimilsina\NepaliDate\Converter\BsAdConverter;

class NepaliDateServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/nepali-date.php', 'nepali-date');

        $this->app->singleton(NepaliDateManager::class, function ($app) {
            return new NepaliDateManager(new BsAdConverter());
        });

        $this->app->alias(NepaliDateManager::class, 'nepali-date');
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/nepali-date.php' => config_path('nepali-date.php'),
            ], 'nepali-date-config');
        }
    }
}