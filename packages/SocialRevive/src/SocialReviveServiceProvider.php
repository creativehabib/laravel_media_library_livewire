<?php

namespace Habib\SocialRevive;

use Illuminate\Support\ServiceProvider;

class SocialReviveServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/social-revive.php',
            'social-revive'
        );
    }

    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        $this->publishes([
            __DIR__.'/../config/social-revive.php' => config_path('social-revive.php'),
        ], 'social-revive-config');
    }
}
