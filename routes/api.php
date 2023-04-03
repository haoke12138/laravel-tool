<?php

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use ZHK\Tool\Http\Controllers as ctrl;

// 文件上传
Route::group([
    'prefix'     => 'file-upload',
    'middleware' => config('haoke.file.upload.middleware'),
], function (Router $router) {
    $router->post('/image', [ctrl\FileController::class, 'image']);
    $router->post('/file', [ctrl\FileController::class, 'file']);
});

// 文件管理器
Route::group([
    'prefix'     => 'zhk/media-selector',
    'middleware' => config('haoke.file.upload.middleware'),
    'as' => config('haoke.route.api.name_prefix')
], function (Router $router) {
    $router->post('add-group', [ctrl\DcatMediaSelectorController::class, 'addGroup'])->name('media.add-group');
    $router->post('media-move', [ctrl\DcatMediaSelectorController::class, 'move'])->name('media.move');
    $router->get('media-list', [ctrl\DcatMediaSelectorController::class, 'getMediaList'])->name('media.list');
    $router->post('media-upload', [ctrl\DcatMediaSelectorController::class, 'upload'])->name('media.upload');
    $router->post('media-delete', [ctrl\DcatMediaSelectorController::class, 'delete'])->name('media.delete');
});
