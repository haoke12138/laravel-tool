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

class AddDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zhk:add_database';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "根据.env中的mysql配置信息添加数据库以及对应的用户";

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
        try {
            service('ZHK.Tool:Init')->initDatabase();
            $this->info("完成!");
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }

}
