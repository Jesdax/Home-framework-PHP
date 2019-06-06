<?php

return [
    'admin.prefix' => '/admin',
    'admin.widgets' => [],
    \App\Admin\AdminTwigExtension::class => \DI\autowire()->constructor(\DI\get('admin.widgets')),
    \App\Admin\AdminModule::class => \DI\autowire()->constructorParameter('prefix', \DI\get('admin.prefix')),
    \App\Admin\DashboardAction::class => \DI\autowire()->constructorParameter('widgets', \DI\get('admin.widgets'))
];
