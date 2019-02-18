<?php

namespace Hunternnm\LaravelRoadrunner\Resetters;

use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Http\Kernel;
use Hunternnm\LaravelRoadrunner\RoadrunnerLaravelBridge;

/**
 * Class RebindKernelContainer
 * Original file https://github.com/swooletw/laravel-swoole/blob/master/src/Server/Resetters/RebindKernelContainer.php
 */
class RebindKernelContainer implements ResetterContract
{
    /**
     * @var Container
     */
    protected $app;

    /**
     * "handle" function for resetting app.
     *
     * @param Container  $app
     * @param RoadrunnerLaravelBridge $sandbox
     *
     * @return Container
     */
    public function handle(Container $app, RoadrunnerLaravelBridge $sandbox)
    {
        $kernel = $app->make(Kernel::class);

        $closure = function () use ($app) {
            $this->app = $app;
        };

        $resetKernel = $closure->bindTo($kernel, $kernel);
        $resetKernel();

        return $app;
    }
}
