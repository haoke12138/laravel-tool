<?php

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use Dcat\Admin\Admin;

Route::group([
    'prefix'     => config('haoke.route.admin.prefix'),
    'namespace'  => config('haoke.route.admin.namespace'),
    'middleware' => config('haoke.route.admin.middleware'),
], function (Router $router) {
    $router->get('/website-setting', 'SettingController@index');
});




// 自定义插件路由
if (file_exists(base_path('router.json'))) {
    Route::group([
        'prefix'     => config('haoke.route.admin.prefix'),
        'namespace'  => config('admin.route.namespace'),
        'middleware' => config('haoke.route.admin.middleware'),
    ], function (Router $router) {
        $route = json_decode(file_get_contents(base_path('router.json')), true);
        if (!empty($route['admin'])) {
            foreach ($route['admin'] as $key => $item) {
                $method = $item['method'];
                $r = $router->$method($item['uri'], $item['controller']);
                if ($method != 'resource') {
                    $r->name($key);
                }
            }
        }
    });
}

