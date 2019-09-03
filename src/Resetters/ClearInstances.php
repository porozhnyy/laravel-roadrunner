<?php

declare(strict_types=1);

namespace Hunternnm\LaravelRoadrunner\Resetters;

use Illuminate\Contracts\Container\Container;
use Hunternnm\LaravelRoadrunner\RoadrunnerLaravelBridge;

/**
 * Class ClearInstances
 * Original file https://github.com/swooletw/laravel-swoole/blob/master/src/Server/Resetters/ClearInstances.php.
 */
class ClearInstances implements ResetterContract
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
        $instances = $sandbox->getConfig()->get('roadrunner.instances', []);

        foreach ($instances as $instance) {
            $app->forgetInstance($instance);
        }

        return $app;
    }
}
