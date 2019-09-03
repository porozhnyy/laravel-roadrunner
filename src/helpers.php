<?php

declare(strict_types=1);

use Hunternnm\LaravelRoadrunner\Exceptions\DumpException;
use Symfony\Component\VarDumper\Cloner\VarCloner;
use Symfony\Component\VarDumper\Dumper\HtmlDumper;

if (!function_exists('ddr')) {
    /**
     * dd() analog for roadrunner.
     *
     * @param mixed ...$vars
     *
     * @return mixed
     * @throws DumpException
     */
    function ddr(...$vars)
    {
        if (is_run_in_console()) {
            return dd(...$vars);
        }
        $dumper = new HtmlDumper();
        $fragments = \array_map(function ($argument) use ($dumper) {
            return $dumper->dump((new VarCloner())->cloneVar($argument), true);
        }, $vars);

        throw new DumpException(implode(PHP_EOL, $fragments));
    }
}

if (!\function_exists('is_run_in_console')) {
    function is_run_in_console(): bool
    {
        if (true === (bool) getenv('RR_HTTP')) {
            return false;
        }

        return app()->runningInConsole();
    }
}
