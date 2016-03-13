<?php


namespace Laasti\HtmlApp;

use Laasti\Directions\Route;
use Laasti\Http\Application as CoreApp;
use League\Container\Container;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequestFactory;

class Application
{
    const CLASS_METHOD_EXTRACTOR = "/^(.+)::(.+)$/";
    protected $coreApp;

    public function __construct(CoreApp $app)
    {
        $this->coreApp = $app;
    }

    public static function create($configFiles = [])
    {
        $container = new Container;

        $container->add('config_files', $configFiles);
        $container->addServiceProvider(new \Laasti\Core\Providers\ConfigFilesProvider());
        $container->share('container', $container);
        $container->share('Interop\Container\ContainerInterface', $container);
        $container->add('error_formatter.kernel', 'Laasti\Http\HttpKernel')->withArgument('peels.exceptions');
        $container->share('error_formatter', 'Laasti\Core\Exceptions\PrettyBooBooFormatter')->withArguments(['error_formatter.kernel', 'peels.exceptions']);
        $container->add('Mustache_Engine');
        $container->add('Laasti\Views\Engines\TemplateEngineInterface', 'Laasti\Views\Engines\Mustache')->withArguments([
            'Mustache_Engine', $container->get('config')['views']['locations']
        ]);
        $container->share('kernel', 'Laasti\Http\HttpKernel')->withArgument('peels.http');
        $container->addServiceProvider('Laasti\Directions\Providers\LeagueDirectionsProvider');
        $container->addServiceProvider('Laasti\Peels\Providers\LeaguePeelsProvider');
        $container->addServiceProvider('Laasti\Log\MonologProvider');
        //$container->addServiceProvider('Laasti\Lazydata\LazyDataProvider');
        $container->addServiceProvider('Laasti\Lazydata\Providers\LeagueLazydataProvider');
        $container->addServiceProvider('Laasti\Views\Providers\LeagueViewsProvider');
        $container->addServiceProvider('Laasti\SymfonyTranslationProvider\SymfonyTranslationProvider');
        $coreApp = new CoreApp($container);

        return new static($coreApp);
    }

    public function run(RequestInterface $request = null, ResponseInterface $response = null)
    {
        if (is_null($request)) {
            $request = ServerRequestFactory::fromGlobals();
        }
        if (is_null($response)) {
            $response = new Response;
        }
        $this->container()->get('error_formatter')->setRequest($request)->setResponse($response);
        //Default routing middleware should be the last middleware added
        $this->container()->get('peels.http')->unshift('directions.default::find');
        $this->container()->get('peels.http')->push('directions.default::dispatch');
        
        $this->coreApp->run($request, $response);
    }
    
    public function container()
    {
        return $this->coreApp->getContainer();
    }

    public function allConfig()
    {
        return $this->coreApp->getConfigArray();
    }

    public function config($key, $default = null)
    {
        return $this->coreApp->getConfig($key, $default);
    }

    public function setConfig($key, $value)
    {
        return $this->coreApp->setConfig($key, $value);
    }

    public function logger()
    {
        return $this->coreApp->getLogger();
    }

    public function middleware($middleware, $stack = 'http')
    {
        $this->container()->get('peels.'.$stack)->push($middleware);
        return $this;
    }

    public function exception($exceptionClass, $handler)
    {
        $this->container()->get('error_formatter')->setHandler($exceptionClass, $this->resolve($handler));
        return $this;
    }

    /**
     *
     * @param type $method
     * @param type $route
     * @param type $callable
     * @return Route
     */
    public function route($method, $route, $callable)
    {
        return $this->container()->get('directions.default')->add($method, $route, $callable);
    }

    protected function resolve($callableMiddleware)
    {
        $matches = [];
        if (is_string($callableMiddleware) && preg_match(self::CLASS_METHOD_EXTRACTOR, $callableMiddleware, $matches)) {
            list($matchedString, $class, $method) = $matches;
            if ($this->container()->has($class)) {
                return [$this->container()->get($class), $method];
            }
        } else if (is_string($callableMiddleware) && $this->container()->has($callableMiddleware)) {
            return $this->container()->get($callableMiddleware);
        }

        if (is_callable($callableMiddleware)) {
            return $callableMiddleware;
        }
        throw new \InvalidArgumentException('Callable not resolvable: '.(is_object($callableMiddleware) ? get_class($callableMiddleware) : $callableMiddleware));
    }
}
