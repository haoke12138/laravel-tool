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
        $database = env('DB_DATABASE');
        if (empty($database)) {
            $this->error(".env 文件中 DB_DATABASE 未设置");
        }
        $username = env('DB_USERNAME');
        if (empty($username)) {
            $this->error(".env 文件中 DB_USERNAME 未设置");
        }
        $password = env('DB_PASSWORD');
        if (empty($password)) {
            $this->error(".env 文件中 DB_PASSWORD 未设置");
        }
        exec("mysql -uroot -e 'CREATE DATABASE `$database` DEFAULT CHARACTER SET utf8 DEFAULT COLLATE utf8_general_ci'");
        exec("mysql -uroot -e \"create user '$username'@'localhost' identified by '$password';\"");
        exec("mysql -uroot -e 'grant all on $database.* to $username@localhost;'");
        $this->info("数据库和用户已经添加完成!");
    }

}
