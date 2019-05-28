<?php
namespace App\Blog;

use App\Blog\Actions\BlogAction;
use Framework\Module;
use Framework\Renderer;
use Framework\Router;
use phpDocumentor\Reflection\Types\String_;

class BlogModule extends Module
{


    const DEFINITIONS = __DIR__ . '/config.php';


    const MIGRATIONS = __DIR__ . '/db/migrations';


    const SEEDS = __DIR__ . '/db/seeds';


    public function __construct($prefix, Router $router, Renderer\RendererInterface $renderer)
    {

        $renderer->addPath('blog', __DIR__ . '/views');
        $router->get($prefix, BlogAction::class, 'blog.index');
        $router->get($prefix . '/{slug:[a-z\-0-9]+}-{id:[0-9]+}', BlogAction::class, 'blog.show');
    }
}
