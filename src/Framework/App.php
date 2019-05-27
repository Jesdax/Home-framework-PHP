<?php

namespace Framework;

use GuzzleHttp\Psr7\Response;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class App
{

    /**
     * @var array List of modules
     */
    private $modules = [];


    /**
     * Container
     * @var ContainerInterface
     */
    private $container;


    /**Liste des modules à charger
     * App constructor.
     * @param ContainerInterface $container
     * @param array $modules
     */
    public function __construct(ContainerInterface $container, array $modules = [])
    {
        $this->container = $container;
        foreach ($modules as $module) {
            $this->modules[] = $container->get($module);
        }
    }

    /**
     * @param ServerRequestInterface $request
     * @return \GuzzleHttp\Psr7\MessageTrait|Response|mixed
     * @throws \Exception
     */
    public function run(ServerRequestInterface $request)
    {
        $uri = $request->getUri()->getPath();

        if (!empty($uri) && $uri[-1] === "/") { //If url is not empty && last caract. in url equal "/" so pblm
            return (new Response())
                ->withStatus(301)
                ->withHeader('Location', substr($uri, 0, -1));
        }

        $router = $this->container->get(Router::class);
        $route = $router->match($request);
        if (is_null($route)) {
            return new Response(404, [], '<h1>Error 404</h1>');
        }

        $params = $route->getParams();
        $request = array_reduce(array_keys($params), function ($request, $key) use ($params) {
            return $request->withAttribute($key, $params[$key]);
        }, $request);

        $callback = $route->getCallBack();
        if (is_string($callback)) {
            $callback = $this->container->get($callback);
        }

        $response = call_user_func_array($callback, [$request]);

        if (is_string($response)) {
            return new Response(200, [], $response);
        } elseif ($response instanceof ResponseInterface) {
            return $response;
        } else {
            throw new \Exception('The response is not a string or an instance of ResponseInterface');
        }
    }
}
