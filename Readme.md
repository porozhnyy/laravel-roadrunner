# Laravel-Roadrunner
Simple Laravel-Roadrunner bridge 

### Example `psr-worker.php`
```php
<?php

use Illuminate\Http\Request;
use Spiral\Goridge;
use Spiral\RoadRunner;
use Symfony\Bridge\PsrHttpMessage\Factory\DiactorosFactory;
use Symfony\Bridge\PsrHttpMessage\Factory\HttpFoundationFactory;

ini_set('display_errors', 'stderr');
require 'vendor/autoload.php';

$worker = new RoadRunner\Worker(new Goridge\StreamRelay(STDIN, STDOUT));
$psr7 = new RoadRunner\PSR7Client($worker);
$httpFoundationFactory = new HttpFoundationFactory();

/** @var \Illuminate\Foundation\Application $app */
$app = require __DIR__.'/bootstrap/app.php';

$rr = new \Hunternnm\LaravelRoadrunner\RoadrunnerLaravelBridge($app, __DIR__);

while ($req = $psr7->acceptRequest()) {
    try {
        $symfonyRequest = $httpFoundationFactory->createRequest($req);
        $request = Request::createFromBase($symfonyRequest);

        $response = $rr->request($request);

        $psr7factory = new DiactorosFactory();
        $psr7response = $psr7factory->createResponse($response);
        $psr7->respond($psr7response);
    } catch (Throwable $e) {
        $psr7->getWorker()->error((string) $e);
    }
}

```