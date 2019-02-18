<?php
/*
 * idea is taken from the package https://github.com/swooletw/laravel-swoole
 */

namespace Hunternnm\LaravelRoadrunner;

use Exception;
use Illuminate\Container\Container as ContainerInstance;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Facade;
use Hunternnm\LaravelRoadrunner\Resetters\ResetterContract;
use ReflectionException;
use ReflectionObject;
use Throwable;

class RoadrunnerLaravelBridge
{
    /**
     * @var Container
     */
    private $baseApp;
    /**
     * @var string|null
     */
    private $basePath;

    /**
     * @var array
     */
    private $resetters = [];

    /**
     * @var array
     */
    private $providers = [];

    /**
     * @var Repository|mixed
     */
    private $config;

    /**
     * @var
     */
    private $request;

    /**
     * RoadRunner constructor.
     *
     * @param Container $baseApp
     * @param string|null $basePath
     *
     * @throws ReflectionException
     * @throws Exception
     */
    public function __construct(Container $baseApp, ?string $basePath = null)
    {
        $this->baseApp = $baseApp;
        $this->basePath = $basePath ?? base_path();
        $this->bootstrap();
    }

    /**
     * @throws ReflectionException
     * @throws Exception
     */
    protected function bootstrap()
    {
        $kernel = $this->baseApp->make(Kernel::class);

        $reflection = new ReflectionObject($kernel);
        $bootstrappersMethod = $reflection->getMethod('bootstrappers');
        $bootstrappersMethod->setAccessible(true);
        $bootstrappers = $bootstrappersMethod->invoke($kernel);

        array_splice($bootstrappers, -2, 0, ['Illuminate\Foundation\Bootstrap\SetRequestForConsole']);
        $this->baseApp->bootstrapWith($bootstrappers);

        $resolves = $this->baseApp->make('config')
            ->get('roadrunner.pre_resolved', []);

        foreach ($resolves as $abstract) {
            if ($this->getBaseApp()->offsetExists($abstract)) {
                $this->getBaseApp()->make($abstract);
            }
        }
    }

    protected function setInitialProviders()
    {
        $app = $this->getBaseApp();
        $providers = $this->config->get('roadrunner.providers', []);

        foreach ($providers as $provider) {
            if (class_exists($provider) && !in_array($provider, $this->providers)) {
                $providerClass = new $provider($app);
                $this->providers[$provider] = $providerClass;
            }
        }
    }

    /**
     * @throws Exception
     */
    protected function setInitialResetters()
    {
        $app = $this->getBaseApp();
        $resetters = $this->config->get('roadrunner.resetters', []);

        foreach ($resetters as $resetter) {
            $resetterClass = $app->make($resetter);
            if (!$resetterClass instanceof ResetterContract) {
                throw new Exception("{$resetter} must implement " . ResetterContract::class);
            }
            $this->resetters[$resetter] = $resetterClass;
        }
    }

    public function request(Request $request)
    {
        $this->setRequest($request);
        $sandbox = clone $this->getBaseApp();
        $this->setInstance($sandbox);
        $this->resetApp($sandbox);

        try {
            $kernel = $sandbox->make(Kernel::class);
            $response = $kernel->handle($request);
            $kernel->terminate($request, $response);
            unset($sandbox);

            return $response;
        } catch (Throwable $e) {
            //todo
        } finally {
            $this->setInstance($this->getBaseApp());
        }
    }

    public function resetApp(Container $app)
    {
        $this->config = clone $this->baseApp->make(Repository::class);

        $this->setInitialProviders();
        $this->setInitialResetters();
        foreach ($this->resetters as $resetter) {
            $resetter->handle($app, $this);
        }
    }

    /**
     * @param ContainerInstance $app
     */
    public function setInstance(ContainerInstance $app)
    {
        $app->instance('app', $app);
        $app->instance(ContainerInstance::class, $app);

        ContainerInstance::setInstance($app);
        Facade::clearResolvedInstances();
        Facade::setFacadeApplication($app);
    }

    /**
     * @return Container
     */
    public function getBaseApp(): Container
    {
        return $this->baseApp;
    }

    /**
     * @param Container $baseApp
     */
    public function setBaseApp(Container $baseApp): void
    {
        $this->baseApp = $baseApp;
    }

    /**
     * @return string|null
     */
    public function getBasePath(): ?string
    {
        return $this->basePath;
    }

    /**
     * @param string|null $basePath
     */
    public function setBasePath(?string $basePath): void
    {
        $this->basePath = $basePath;
    }

    /**
     * @return Repository|mixed
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @param Repository|mixed $config
     */
    public function setConfig($config): void
    {
        $this->config = $config;
    }

    /**
     * @return array
     */
    public function getProviders(): array
    {
        return $this->providers;
    }

    /**
     * @param array $providers
     */
    public function setProviders(array $providers): void
    {
        $this->providers = $providers;
    }

    /**
     * @return array
     */
    public function getResetters(): array
    {
        return $this->resetters;
    }

    /**
     * @param array $resetters
     */
    public function setResetters(array $resetters): void
    {
        $this->resetters = $resetters;
    }

    /**
     * @return mixed
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @param mixed $request
     */
    public function setRequest($request): void
    {
        $this->request = $request;
    }
}