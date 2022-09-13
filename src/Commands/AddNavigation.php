<?php

namespace ZHK\Tool\Commands;

use App\Models\Model;
use Illuminate\Console\Command;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use \Exception;
use ZHK\Tool\Common\ArrayTool;
use ZHK\Tool\Models\Menu;

class AddNavigation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zhk:add_navigation';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "添加导航栏模块";

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // 添加菜单
        $uri = '/navigation';
        Menu::where('uri', $uri)->delete();
        Menu::insert([
            'parent_id' => 0,
            'order' => 20,
            'title' => '导航栏管理',
            'icon' => 'fa-area-chart',
            'uri' => $uri,
            'extension' => '',
            'show' => 1,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        // 添加访问路由
        write_route(function (&$route) use ($uri) {
            $route['admin']['navigation'] = [
                'uri' => $uri,
                'method' => 'resource',
                'controller' => 'NavigationController'
            ];
        });

        // 镜像文件复制
        app('files')->copy(__DIR__ . '/../Mirror/Controllers/NavigationController.php', admin_path('Controllers/NavigationController.php'));
        app('files')->copy(__DIR__ . '/../Mirror/Repositories/Navigation.php', admin_path('Repositories/Navigation.php'));
        app('files')->copy(__DIR__ . '/../Mirror/Models/Navigation.php', app_path('Models/Navigation.php'));
        app('files')->copy(__DIR__ . '/../../resources/lang/zh_CN/navigation.php', base_path('resources/lang/zh_CN/navigation.php'));
        app('files')->copy(__DIR__ . '/../../database/migrations/2022_06_29_114005_create_navigations_table.php', base_path('database/migrations/2022_06_29_114005_create_navigations_table.php'));

        // 执行migrate
        Artisan::call('migrate');
    }

}
