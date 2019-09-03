<?php

declare(strict_types=1);

namespace Hunternnm\LaravelRoadrunner;

use Hunternnm\LaravelRoadrunner\Support\Clockwork\ClockworkSupport;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\ServiceProvider;

class RoadrunnerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/roadrunner.php', 'roadrunner');
    }

    /**
     * Bootstrap services.
     * @param UrlGenerator $url
     */
    public function boot(UrlGenerator $url)
    {
        if (function_exists('config_path')) {
            $publishPath = config_path('roadrunner.php');
        } else {
            $publishPath = base_path('config/roadrunner.php');
        }
        $this->publishes([
            __DIR__.'/../config/roadrunner.php' => $publishPath,
        ], 'config');

        if (class_exists('\Clockwork\Support\Laravel\ClockworkSupport')) {
            $this->app->singleton('clockwork.support', function ($app) {
                return new ClockworkSupport($app);
            });
        }

        if (config('roadrunner.force_https')) {
            $url->forceScheme('https');
        }
    }
}
