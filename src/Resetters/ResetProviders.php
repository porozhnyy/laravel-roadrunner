<?php

declare(strict_types=1);

namespace Hunternnm\LaravelRoadrunner\Resetters;

use Illuminate\Contracts\Container\Container;
use Hunternnm\LaravelRoadrunner\RoadrunnerLaravelBridge;

/**
 * Class ResetProviders
 * Original file https://github.com/swooletw/laravel-swoole/blob/master/src/Server/Resetters/ResetProviders.php.
 */
class ResetProviders implements ResetterContract
{
    /**
     * @var Container
     */
    protected $app;

    /**
     * "handle" function for resetting app.
     *
     * @param Container               $app
     * @param RoadrunnerLaravelBridge $sandbox
     *
     * @return Container
     */
    public function handle(Container $app, RoadrunnerLaravelBridge $sandbox)
    {
        foreach ($sandbox->getProviders() as $provider) {
            $this->rebindProviderContainer($app, $provider);
            if (method_exists($provider, 'register')) {
                $provider->register();
            }
            if (method_exists($provider, 'boot')) {
                $app->call([$provider, 'boot']);
            }
        }

        return $app;
    }

    /**
     * Rebind service provider's container.
     *
     * @param $app
     * @param $provider
     */
    protected function rebindProviderContainer($app, $provider)
    {
        $closure = function () use ($app) {
            $this->app = $app;
        };

        $resetProvider = $closure->bindTo($provider, $provider);
        $resetProvider();
    }
}
