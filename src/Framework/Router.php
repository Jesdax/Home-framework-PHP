<?php

namespace Framework;



use App\Blog\Actions\PostCrudAction;
use Framework\Router\Route;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Expressive\Router\FastRouteRouter;
use Zend\Expressive\Router\Route as ZendRoute;

/**
 * Class Router
 * Register and match route
 */
class Router
{

    /**
     * @var FastRouteRouter
     */
    private $router;

    public function __construct()
    {
        $this->router = new FastRouteRouter();
    }

    /**
     * @param $path
     * @param $callable
     * @param string|null $name
     */
    public function get($path, $callable, $name = null)
    {
        $this->router->addRoute(new ZendRoute($path, $callable, ['GET'], $name));
    }

    /**
     * @param string $path
     * @param string|callable $callable
     * @param string|null $name
     */
    public function post($path, $callable, $name = null)
    {
        $this->router->addRoute(new ZendRoute($path, $callable, ['POST'], $name));
    }

    /**
     * @param string $path
     * @param string|callable $callable
     * @param string|null $name
     */
    public function delete($path, $callable, $name = null)
    {
        $this->router->addRoute(new ZendRoute($path, $callable, ['DELETE'], $name));
    }


    /**
     * Generate route CRUD BLOG
     * @param string $prefixPath
     * @param $callable
     * @param string $prefixName
     */
    public function crud(string $prefixPath, $callable, string $prefixName)
    {

        $this->get("$prefixPath", $callable, $prefixName . '.index');
        $this->get("$prefixPath/new-article", $callable, $prefixName . '.create');
        $this->post("$prefixPath/new-article", $callable);
        $this->get("$prefixPath/{id:\d+}", $callable, $prefixName . '.edit');
        $this->post("$prefixPath/{id:\d+}", $callable);
        $this->delete("$prefixPath/{id:\d+}", $callable, $prefixName . '.delete');
    }


    /**
     * @param ServerRequestInterface $request
     * @return Route|null
     */
    public function match(ServerRequestInterface $request): ?Route
    {
        //var_dump($request); die();
        $result = $this->router->match($request);
        if ($result->isSuccess()) {
            return new Route(
                $result->getMatchedRouteName(),
                $result->getMatchedMiddleware(),
                $result->getMatchedParams()
            );
        }
        return null;
    }

    /**
     * @param $name
     * @param array $params
     * @return string|null
     */
    public function generateUri($name, array $params = [], array $queryParams = [])
    {
        $uri = $this->router->generateUri($name, $params);
        if (!empty($queryParams)) {
            return $uri . '?' . http_build_query($queryParams);
        }
        return $uri;
    }
}
