<?php

declare(strict_types=1);

namespace Hunternnm\LaravelRoadrunner\Resetters;

use Hunternnm\LaravelRoadrunner\RoadrunnerLaravelBridge;
use Illuminate\Contracts\Container\Container;

/**
 * Class ResetConfig
 * Original file https://github.com/swooletw/laravel-swoole/blob/master/src/Server/Resetters/ResetConfig.php.
 */
class ResetConfig implements ResetterContract
{
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
        $app->instance('config', clone $sandbox->getConfig());

        return $app;
    }
}
