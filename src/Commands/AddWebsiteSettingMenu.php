<?php

namespace ZHK\Tool\Commands;

use App\Models\Model;
use Illuminate\Console\Command;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use \Exception;
use ZHK\Tool\Common\ArrayTool;

class AddWebsiteSettingMenu extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zhk:add_website_setting_menu';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "添加后台网站设置菜单";

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
        DB::table('admin_menu')->where('title', 'WebsiteSetting')->delete();
        DB::table('admin_menu')->insert([
            'parent_id' => 0,
            'order' => 20,
            'title' => 'WebsiteSetting',
            'icon' => 'fa-asterisk',
            'uri' => 'website-setting',
            'extension' => '',
            'show' => 1,
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }

}
