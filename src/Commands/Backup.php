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

class Backup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zhk:backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "备份当前数据库";

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
        $path = storage_path("backup");
        if (!file_exists($path)) {
            mkdir($path, 0755, true);
            file_put_contents("$path/.gitignore", "*");
        }
        exec("mysqldump -uroot $database > $path/$database-" . date("YmdHis"). '.sql');
        $this->info("完成备份!");
    }

}
