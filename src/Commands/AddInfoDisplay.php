<?php

namespace ZHK\Tool\Commands;

use App\Models\Model;
use Illuminate\Console\Command;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use \Exception;
use ZHK\Tool\Common\ArrayTool;
use ZHK\Tool\Models\Menu;

class AddInfoDisplay extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zhk:add_info_display {--visit} {--link}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "添加信息显示模块 --visit 添加菜单栏链接, --link 添加访问链接";

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
        $uri = '/info-display';
        if ($this->option('visit')) {
            Menu::where('uri', $uri)->orWhere('uri', "$uri-type")->delete();
            Menu::insert([
                [
                    'parent_id' => 0,
                    'order' => 20,
                    'title' => '信息显示',
                    'icon' => '',
                    'uri' => $uri,
                    'extension' => '',
                    'show' => 1,
                    'created_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'parent_id' => 0,
                    'order' => 20,
                    'title' => '信息显示类型',
                    'icon' => '',
                    'uri' => "$uri-type",
                    'extension' => '',
                    'show' => 1,
                    'created_at' => date('Y-m-d H:i:s'),
                ]
            ]);
        }

        // 添加访问路由
        if ($this->option('link')) {
            $route = json_decode(file_get_contents(__DIR__ . '/../../routes/router.json'), true);
            $route['admin']['info-display'] = [
                'uri' => $uri,
                'method' => 'resource',
                'controller' => 'InfoDisplayController'
            ];
            $route['admin']['info-display-type'] = [
                'uri' => "$uri-type",
                'method' => 'resource',
                'controller' => 'InfoDisplayTypeController'
            ];
            file_put_contents(__DIR__ . '/../../routes/router.json', json_encode($route, 256));
        }

        // 镜像文件复制
        app('files')->copy(__DIR__ . '/../Mirror/Controllers/InfoDisplayController.php', admin_path('Controllers/InfoDisplayController.php'));
        app('files')->copy(__DIR__ . '/../Mirror/Controllers/InfoDisplayTypeController.php', admin_path('Controllers/InfoDisplayTypeController.php'));

        app('files')->copy(__DIR__ . '/../Mirror/Repositories/InfoDisplay.php', admin_path('Repositories/InfoDisplay.php'));
        app('files')->copy(__DIR__ . '/../Mirror/Repositories/InfoDisplayType.php', admin_path('Repositories/InfoDisplayType.php'));

        app('files')->copy(__DIR__ . '/../Mirror/Models/InfoDisplay.php', app_path('Models/InfoDisplay.php'));
        app('files')->copy(__DIR__ . '/../Mirror/Models/InfoDisplayType.php', app_path('Models/InfoDisplayType.php'));

        app('files')->copy(__DIR__ . '/../../database/migrations/2022_07_05_134819_create_info_display_table.php', base_path('database/migrations/2022_07_05_134819_create_info_display_table.php'));
        app('files')->copy(__DIR__ . '/../../database/migrations/2022_08_11_150641_create_info_display_type_table.php', base_path('database/migrations/2022_08_11_150641_create_info_display_type_table.php'));

        // 执行migrate
        exec('php artisan migrate');
    }

}
