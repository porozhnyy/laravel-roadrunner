<?php

namespace Hunternnm\LaravelRoadrunner\Resetters;

use Illuminate\Contracts\Container\Container;
use Illuminate\Http\Request;
use Hunternnm\LaravelRoadrunner\RoadrunnerLaravelBridge;

/**
 * Class BindRequest.
 * Original file https://github.com/swooletw/laravel-swoole/blob/master/src/Server/Resetters/BindRequest.php
 */
class BindRequest implements ResetterContract
{
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
        $request = $sandbox->getRequest();

        if ($request instanceof Request) {
            $app->instance('request', $request);
        }

        return $app;
    }
}
