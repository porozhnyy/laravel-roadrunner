<?php

declare(strict_types=1);

namespace Hunternnm\LaravelRoadrunner\Resetters;

use Illuminate\Contracts\Container\Container;
use Hunternnm\LaravelRoadrunner\RoadrunnerLaravelBridge;

/**
 * Class RebindViewContainer
 * Original file https://github.com/swooletw/laravel-swoole/blob/master/src/Server/Resetters/RebindViewContainer.php.
 */
class RebindViewContainer implements ResetterContract
{
    /**
     * @var Container
     */
    protected $container;

    /**
     * @var array
     */
    protected $shared;

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
        $view = $app->make('view');

        $closure = function () use ($app) {
            $this->container = $app;
            $this->shared['app'] = $app;
        };

        $resetView = $closure->bindTo($view, $view);
        $resetView();

        return $app;
    }
}
