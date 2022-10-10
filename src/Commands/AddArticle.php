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

class AddArticle extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zhk:add_article {--visit} {--link}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "添加文章模块 --visit 添加菜单栏链接, --link 添加访问链接";

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
        $uri = '/article';
        Menu::where('uri', $uri)->orWhere('uri', "$uri-category")->delete();
        $menu = Menu::query()->create([
            'parent_id' => 0,
            'order' => 20,
            'title' => '文章管理',
            'icon' => '',
            'uri' => $uri,
            'extension' => '',
            'show' => 1,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        Menu::insert([
            [
                'parent_id' => $menu->id,
                'order' => 20,
                'title' => '文章分类',
                'icon' => '',
                'uri' => "$uri-category",
                'extension' => '',
                'show' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'parent_id' => $menu->id,
                'order' => 20,
                'title' => '文章列表',
                'icon' => '',
                'uri' => $uri,
                'extension' => '',
                'show' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ]
        ]);

        write_route(function (&$route) use ($uri) {
            $route['admin']['article'] = [
                'uri' => $uri,
                'method' => 'resource',
                'controller' => 'Article\ArticleController'
            ];
            $route['admin']['article-category'] = [
                'uri' => "$uri-category",
                'method' => 'resource',
                'controller' => 'Article\CategoryController'
            ];
        });

        $this->copyController();
        app('files')->copy(__DIR__ . '/../Mirror/Repositories/Article.php', admin_path('Repositories/Article.php'));
        app('files')->copy(__DIR__ . '/../Mirror/Repositories/ArticleCategory.php', admin_path('Repositories/ArticleCategory.php'));

        $this->copyModel();

        app('files')->copy(__DIR__ . '/../../database/migrations/2021_06_04_080426_create_article_table.php', base_path('database/migrations/2021_06_04_080426_create_article_table.php'));
        app('files')->copy(__DIR__ . '/../../database/migrations/2021_06_04_080435_create_article_category_table.php', base_path('database/migrations/2021_06_04_080435_create_article_category_table.php'));

        app('files')->copy(__DIR__ . '/../../resources/lang/zh_CN/article.php', base_path('resources/lang/zh_CN/article.php'));
        app('files')->copy(__DIR__ . '/../../resources/lang/zh_CN/category.php', base_path('resources/lang/zh_CN/category.php'));


        // 执行migrate
        Artisan::call('migrate');
    }

    private function copyController()
    {
        $dirPath = admin_path('Controllers/Article');
        if (!file_exists($dirPath)) {
            mkdir($dirPath, 0755, true);
        }
        app('files')->copy(__DIR__ . '/../Mirror/Controllers/Article/ArticleController.php', $dirPath . '/ArticleController.php');
        app('files')->copy(__DIR__ . '/../Mirror/Controllers/Article/CategoryController.php', $dirPath . '/CategoryController.php');
    }

    private function copyModel()
    {
        $dirPath = app_path('Models/Article');
        if (!file_exists($dirPath)) {
            mkdir($dirPath, 0755, true);
        }
        app('files')->copy(__DIR__ . '/../Mirror/Models/Article/Article.php', $dirPath . '/Article.php');
        app('files')->copy(__DIR__ . '/../Mirror/Models/Article/Category.php', $dirPath . '/Category.php');
    }

}
