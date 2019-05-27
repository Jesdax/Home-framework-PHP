<?php
use Framework\Renderer;
use Framework\Renderer\TwigRendererFactory;

return [
    'views.path' => dirname(__DIR__) . '/views',
    'twig.extensions' => [
        \DI\get(\Framework\Router\RouterTwigExtension::class)
    ],
    \Framework\Router::class => \DI\autowire(),
Renderer\RendererInterface::class => \DI\factory(TwigRendererFactory::class)

];
