<?php

return [
    'force_https' => env('RR_FORCE_HTTPS', true),

    'pre_resolved' => [
        'view',
        'files',
        'session',
        'session.store',
        'routes',
        'db',
        'db.factory',
        'cache',
        'cache.store',
        'config',
        'cookie',
        'encrypter',
        'hash',
        'router',
        'translator',
        'url',
        'log',
    ],

    'resetters' => [
        Hunternnm\LaravelRoadrunner\Resetters\ClearInstances::class,
        Hunternnm\LaravelRoadrunner\Resetters\ResetConfig::class,
        Hunternnm\LaravelRoadrunner\Resetters\ResetSession::class,
        Hunternnm\LaravelRoadrunner\Resetters\ResetCookie::class,
        Hunternnm\LaravelRoadrunner\Resetters\BindRequest::class,
        Hunternnm\LaravelRoadrunner\Resetters\RebindKernelContainer::class,
        Hunternnm\LaravelRoadrunner\Resetters\RebindRouterContainer::class,
        Hunternnm\LaravelRoadrunner\Resetters\RebindViewContainer::class,
        Hunternnm\LaravelRoadrunner\Resetters\ResetProviders::class,
    ],

    'instances' => [
        'auth',
    ],

    'providers' => [
        Illuminate\Pagination\PaginationServiceProvider::class,
        App\Providers\AuthServiceProvider::class,
    ],
];
