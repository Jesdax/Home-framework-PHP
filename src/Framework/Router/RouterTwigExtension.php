<?php
namespace Framework\Router;



use Framework\Router;
use phpDocumentor\Reflection\Types\String_;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class RouterTwigExtension extends AbstractExtension
{


    private $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('path', [$this, 'pathFor'])
        ];
    }


    public function pathFor($path, array $params = [])
    {
        return $this->router->generateUri($path, $params);
    }
}
