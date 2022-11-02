<?php

namespace ZHK\Tool;

use Illuminate\Support\ServiceProvider;

class ToolServiceProvider extends ServiceProvider
{

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // 自动加载model, service, repository
        new \ZHK\Tool\Common\Dbu(app());

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/stubs' => base_path('stubs'),
                __DIR__ . '/Mirror/Models/Model.php' => app_path('Models/Model.php'),
                __DIR__ . '/Mirror/Services/Service.php' => app_path('Services/Service.php'),
                __DIR__ . '/Mirror/Repositories/EloquentRepository.php' => app_path('Admin/Repositories/EloquentRepository.php'),
                __DIR__ . '/Mirror/Common/Tool.php' => app_path('Common/Tool.php'),
                __DIR__ . '/config.php' => config_path('haoke.php'),
                __DIR__ . '/../resources/lang/zh_CN/validation.php' => base_path('resources/lang/zh_CN/validation.php'),
                __DIR__ . '/../resources/views/layout/title.blade.php' => base_path('resources/views/layout/title.blade.php'),
                __DIR__ . '/../resources/views/layout/map.blade.php' => base_path('resources/views/layout/map.blade.php'),
            ]);
        }

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
//        $this->loadJsonTranslationsFrom(__DIR__ . '/../resources/lang-json');
//        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'haoke');

        // 绑定中间件内核
        $this->app->singleton(\Illuminate\Contracts\Http\Kernel::class, \ZHK\Tool\Http\Kernel::class);
        $this->bootConsole();
    }

    /**
     * 自动加载控制台命令
     */
    private function bootConsole()
    {
        $commands = array_filter(array_map(function ($v) {
            if (!in_array($v, ['.', '..'])) {
                $v = '\ZHK\Tool\Commands\\' . head(explode('.', $v));
                return $v;
            }
        }, scandir(__DIR__ . '/Commands')));

        $this->commands($commands);
    }
}
