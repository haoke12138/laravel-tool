<?php

namespace ZHK\Tool;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use ZHK\Tool\Commands\GenerateMultiLanguageTable;
use ZHK\Tool\Commands\TableStructureExport;
use ZHK\Tool\Commands\ReplaceStubFile;

class RouterServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->routes(function () {
            Route::prefix('api')
                ->middleware('api')
                ->group(__DIR__ . "/../routes/api.php");

            Route::middleware('web')
                ->group(__DIR__ . "/../routes/web.php");

            Route::middleware('web')
                ->group(__DIR__ . "/../routes/admin.php");
        });
    }
}
