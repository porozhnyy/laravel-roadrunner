<?php

declare(strict_types=1);

namespace Hunternnm\LaravelRoadrunner\Resetters;

use Illuminate\Contracts\Container\Container;
use Hunternnm\LaravelRoadrunner\RoadrunnerLaravelBridge;

/**
 * Interface ResetterContract
 * Original file https://github.com/swooletw/laravel-swoole/blob/master/src/Server/Resetters/ResetterContract.php.
 */
interface ResetterContract
{
    /**
     * "handle" function for resetting app.
     *
     * @param Container               $app
     * @param RoadrunnerLaravelBridge $sandbox
     */
    public function handle(Container $app, RoadrunnerLaravelBridge $sandbox);
}
