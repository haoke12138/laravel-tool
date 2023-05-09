<?php

return [
    // 判断是否为小程序
    'is_applets' => env('ZHK_APPLETS', false),
    // 判断是否有英文版
    'has_en' => env('ZHK_HAS_EN', false),

    /**
     * 后台相关配置
     */
    'route' => [
        'admin' => [
            'prefix' => env('ADMIN_ROUTE_PREFIX', 'admin'),
            'namespace' => 'ZHK\\Tool\\Admin\\Controllers',
            'middleware' => ['web', 'admin'],
        ]
    ],

    /**
     * 文件上传配置
     */
    'file' => [
      'upload' => [
          'middleware' => []
      ]
    ],
];