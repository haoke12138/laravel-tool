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
        $this->registerModel();
        $this->registerService();
        $this->registerRepository();

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/stub' => base_path('stubs'),
                __DIR__ . '/Mirror/Models/Model.php' => app_path('Models/Model.php'),
                __DIR__ . '/Mirror/Services/Service.php' => app_path('Services/Service.php'),
                __DIR__ . '/Mirror/Repositories/EloquentRepository.php' => app_path('Admin/Repositories/EloquentRepository.php'),
                __DIR__ . '/config.php' => config_path('haoke.php'),
                __DIR__ . '/../resources/lang/zh_CN/validation.php' => base_path('resources/lang/zh_CN/validation.php'),
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

    private function registerModel()
    {
        $app = app();

        // 获取命名空间
        $getNamespace = function ($slug, $type) {
            $name = explode('::', $slug);
            if (count($name) == 2) {
                $type = $type == 'Models' ? 'Entities' : $type;
                $namespace = "Modules\\{$name[0]}\\{$type}";
            }

            if (count($name) == 1) {
                $namespace = "App\\{$type}";
            }

            if (empty($namespace)) {
                throw  new \Exception("unrecognized $slug parameter !");
            }

            $name = explode('.', end($name));
            if (count($name) == 2) {
                $namespace = $namespace . '\\' . $name[0];
            }

            return [$namespace, end($name)];
        };

        $app['model'] = function ($app, $slug) use ($getNamespace) {
            $param = [];
            if (is_array($slug)) {
                $param = end($slug) == head($slug) ? [] : end($slug);
                $slug = head($slug);
            }

            if (!empty($app["@{$slug}Models"])) {
                return $app["@{$slug}Models"];
            }
            list($namespace, $classname) = $getNamespace($slug, 'Models');

            // 获取创建model的方法
            $class = $namespace . '\\' . $classname;
            $model = new $class($param);

            return $app["@{$slug}Models"] = $model;
        };
    }

    private function registerService()
    {
        $app = app();

        // 获取命名空间
        $getNamespace = function ($slug, $type) {
            $name = explode('::', $slug);
            if (count($name) == 2) {
                $namespace = "Modules\\{$name[0]}\\Services";
            }

            if (count($name) == 1) {
                $namespace = "App\\{$type}";
            }

            if (empty($namespace)) {
                throw  new \Exception("unrecognized $slug parameter !");
            }

            $name = explode('.', end($name));
            if (count($name) == 2) {
                $namespace = $namespace . '\\' . $name[0];
            }

            return [$namespace, end($name)];
        };

        $app['service'] = function ($app, $slug) use ($getNamespace) {
            $param = [];
            if (is_array($slug)) {
                $param = end($slug) == head($slug) ? [] : end($slug);
                $slug = head($slug);
            }

            if (!empty($app["@{$slug}Services"])) {
                return $app["@{$slug}Services"];
            }
            list($namespace, $classname) = $getNamespace($slug, 'Services');

            // 获取创建model的方法
            $class = $namespace . '\\' . $classname;

            return $app["@{$slug}Services"] = new $class($param);
        };
    }

    private function registerRepository()
    {
        $app = app();

        // 获取命名空间
        $getNamespace = function ($slug, $type) {
            $name = explode('::', $slug);
            if (count($name) == 2) {
                $namespace = "Modules\\{$name[0]}\\Repositories";
            }

            if (count($name) == 1) {
                $namespace = "App\\Admin\\{$type}";
            }

            if (empty($namespace)) {
                throw  new \Exception("unrecognized $slug parameter !");
            }

            $name = explode('.', end($name));
            if (count($name) == 2) {
                $namespace = $namespace . '\\' . $name[0];
            }

            return [$namespace, end($name)];
        };

        $app['repository'] = function ($app, $slug) use ($getNamespace) {
            $param = [];
            if (is_array($slug)) {
                $param = end($slug) == head($slug) ? [] : end($slug);
                $slug = head($slug);
            }

            if (!empty($app["@{$slug}Repositories"])) {
                return $app["@{$slug}Repositories"];
            }
            list($namespace, $classname) = $getNamespace($slug, 'Repositories');

            // 获取创建model的方法
            $class = $namespace . '\\' . $classname;

            return $app["@{$slug}Repositories"] = new $class($param);
        };
    }
}

//if (! function_exists('trans')) {
    /**
     * Translate the given message.
     *
     * @param  string|null  $key
     * @param  array  $replace
     * @param  string|null  $locale
     * @return \Illuminate\Contracts\Translation\Translator|string|array|null
     */

//}