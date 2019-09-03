<?php

declare(strict_types=1);

namespace Hunternnm\LaravelRoadrunner\Resetters;

use Illuminate\Contracts\Container\Container;
use Hunternnm\LaravelRoadrunner\RoadrunnerLaravelBridge;

/**
 * Class ResetSession
 * Original file https://github.com/swooletw/laravel-swoole/blob/master/src/Server/Resetters/ResetSession.php.
 */
class ResetSession implements ResetterContract
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
        if (isset($app['session'])) {
            $session = $app->make('session');
            $session->flush();
            $session->regenerate();
        }

        return $app;
    }
}
