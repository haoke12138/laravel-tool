<?php

namespace ZHK\Tool;

use Illuminate\Support\ServiceProvider;
use ZHK\Tool\Commands\GenerateMultiLanguageTable;
use ZHK\Tool\Commands\TableStructureExport;

class ToolServiceProvider extends ServiceProvider
{
    protected $commands = [
        GenerateMultiLanguageTable::class,
        TableStructureExport::class,
    ];

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
        $paths = [
            __DIR__. '/stub' => base_path('stub'),
            __DIR__. '/Models' => app_path('Models'),
            __DIR__. '/Services' => app_path('Service'),
            __DIR__. '/config.php' => config_path('haoke.php'),
        ];
        $this->publishes($paths);

        $dir = base_path('vendor/dcat/laravel-admin/src/Scaffold/stubs');

        if (is_dir($dir) && file_exists(config_path('haoke.php')) && !config('haoke.is_genderate')){
            dump('开始复制');
            exec('rm ' . $dir.'/model.stub');
            app('files')->copy(__DIR__. '/dcat-stub/model.stub', $dir.'/model.stub');
            exec('chmod 777 ' . $dir.'/model.stub');

            exec('rm ' . $dir.'/repository.stub');
            app('files')->copy(__DIR__. '/dcat-stub/repository.stub', $dir.'/repository.stub');
            exec('chmod 777 ' . $dir.'/repository.stub');

            file_put_contents(app()->environmentFilePath(), "\nZHK_GENERATE=true", FILE_APPEND);
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->commands($this->commands);
    }

    private function refisterCommand()
    {

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
