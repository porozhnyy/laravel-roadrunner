# Laravel-Roadrunner
Simple Laravel-[Roadrunner](https://github.com/spiral/roadrunner) bridge 

## Install

```shell
$ composer require hunternnm/laravel-roadrunner
```

If you need - publish the config file to change implementations (ie. resetters and more)
```bash
php artisan vendor:publish --provider="Hunternnm\LaravelRoadrunner\RoadrunnerServiceProvider" --tag=config
```

##Usage

The package is ready for production and contains a simple psr-worker(`bin/roadrunner-worker`) to run from scratch. If you need customize worker - see [Example](#Example)

### <a name="Example"></a>Example

Example `.rr.yaml` for run Laravel
```yaml
http:
  address: 0.0.0.0:8000
  workers:
    command: "php vendor/bin/roadrunner-worker"
    pool:
      numWorkers: 16
      destroyTimeout: 3
      maxJobs:  0
rpc:
  enable: true
  listen: tcp://:6001
```

And then run roadrunner `./rr -w PATH_TO_YOUR_PROJECT`

####Custom `psr-worker.php`
```php
<?php

use Hunternnm\LaravelRoadrunner\RoadrunnerLaravelBridge;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Spiral\Goridge;
use Spiral\RoadRunner;
use Symfony\Bridge\PsrHttpMessage\Factory\HttpFoundationFactory;
use Symfony\Bridge\PsrHttpMessage\Factory\PsrHttpFactory;
use Zend\Diactoros\ResponseFactory;
use Zend\Diactoros\ServerRequestFactory;
use Zend\Diactoros\StreamFactory;
use Zend\Diactoros\UploadedFileFactory;

ini_set('display_errors', 'stderr');
require 'vendor/autoload.php';

$worker = new RoadRunner\Worker(new Goridge\StreamRelay(STDIN, STDOUT));
$psr7 = new RoadRunner\PSR7Client($worker);
$httpFoundationFactory = new HttpFoundationFactory();

/** @var Application $app */
$app = require __DIR__.'/bootstrap/app.php';

$rr = new RoadrunnerLaravelBridge($app, __DIR__);

$psr7factory = new PsrHttpFactory(
    new ServerRequestFactory(),
    new StreamFactory(),
    new UploadedFileFactory(),
    new ResponseFactory()
);

while ($req = $psr7->acceptRequest()) {
    try {
        $symfonyRequest = $httpFoundationFactory->createRequest($req);
        $request = Request::createFromBase($symfonyRequest);

        $response = $rr->request($request);

        $psr7response = $psr7factory->createResponse($response);
        $psr7->respond($psr7response);
    } catch (Throwable $e) {
        $psr7->getWorker()->error((string) $e);
    }
}

```