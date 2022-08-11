<?php

return [
    'is_genderate' => env('ZHK_GENERATE', false),
    'is_applets' => env('ZHK_APPLETS', false),

    'route' => [
        'admin' => [
            'prefix' => env('ADMIN_ROUTE_PREFIX', 'admin'),
            'namespace' => 'ZHK\\Tool\\Admin\\Controllers',
            'middleware' => ['web', 'admin'],
        ]
    ],
];