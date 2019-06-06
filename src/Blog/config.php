<?php

use App\Blog\BlogModule;
use function \DI\{autowire, get};


return [
    'blog.prefix' => '/blog',
    'admin.widgets' => \DI\add([
        get(\App\Blog\BlogWidget::class)
    ])

];
