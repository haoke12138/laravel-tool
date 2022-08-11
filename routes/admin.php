<?php

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use Dcat\Admin\Admin;

Admin::routes();

Route::group([
    'prefix'     => config('haoke.route.admin.prefix'),
    'namespace'  => config('haoke.route.admin.namespace'),
    'middleware' => config('haoke.route.admin.middleware'),
], function (Router $router) {
    $router->get('/website-setting', 'SettingController@index');
});
