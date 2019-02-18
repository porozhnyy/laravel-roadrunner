<?php

namespace Hunternnm\LaravelRoadrunner\Resetters;

use Illuminate\Contracts\Container\Container;
use Hunternnm\LaravelRoadrunner\RoadrunnerLaravelBridge;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class RebindRouterContainer
 * Original file https://github.com/swooletw/laravel-swoole/blob/master/src/Server/Resetters/RebindRouterContainer.php
 */
class RebindRouterContainer implements ResetterContract
{
    /**
     * @var Container
     */
    protected $container;

    /**
     * @var mixed
     */
    protected $routes;

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
        $router = $app->make('router');
        $request = $sandbox->getRequest();
        $closure = function () use ($app, $request) {
            $this->container = $app;
            if (null === $request) {
                return;
            }
            try {
                /** @var mixed $route */
                $route = $this->routes->match($request);
                // clear resolved controller
                if (property_exists($route, 'container')) {
                    $route->controller = null;
                }
                // rebind matched route's container
                $route->setContainer($app);
            } catch (NotFoundHttpException $e) {
                // do nothing
            }
        };

        $resetRouter = $closure->bindTo($router, $router);
        $resetRouter();

        return $app;
    }
}
