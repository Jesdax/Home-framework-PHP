<?php

use App\Blog\BlogModule;
use function \DI\{autowire, get};


return [
    'blog.prefix' => '/blog',
    BlogModule::class => autowire()->constructorParameter('prefix', get('blog.prefix'))

];
