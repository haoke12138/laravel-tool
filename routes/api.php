<?php

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use ZHK\Tool\Http\Controllers as ctrl;

Route::group([
    'prefix'     => 'file-upload',
    'middleware' => config('haoke.file.upload.middleware'),
], function (Router $router) {
    $router->post('/image', [ctrl\FileController::class, 'image']);
    $router->post('/file', [ctrl\FileController::class, 'file']);
});
