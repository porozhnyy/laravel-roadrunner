<?php

namespace Hunternnm\LaravelRoadrunner;

use Illuminate\Support\ServiceProvider;

class RoadrunnerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/roadrunner.php', 'roadrunner');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        if (function_exists('config_path')) {
            $publishPath = config_path('roadrunner.php');
        } else {
            $publishPath = base_path('config/roadrunner.php');
        }
        $this->publishes([
            __DIR__ . '/../config/roadrunner.php' => $publishPath,
        ], 'config');
    }

}