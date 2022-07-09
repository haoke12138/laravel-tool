<?php

namespace ZHK\Tool\Commands;

use App\Models\Model;
use Illuminate\Console\Command;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use \Exception;
use ZHK\Tool\Common\ArrayTool;

class ReplaceStubFile extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zhk:replace_stub_file';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "替换存根文件";

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
        $dir = base_path('vendor/dcat/laravel-admin/src/Scaffold/stubs');
        if (is_dir($dir) && file_exists(config_path('haoke.php')) && !config('haoke.is_genderate')){
            dump('开始替换dcat');
            exec('rm ' . $dir.'/model.stub');
            app('files')->copy(__DIR__. '/../dcat-stub/model.stub', $dir.'/model.stub');
            exec('chmod 777 ' . $dir.'/model.stub');

            exec('rm ' . $dir.'/repository.stub');
            app('files')->copy(__DIR__. '/../dcat-stub/repository.stub', $dir.'/repository.stub');
            exec('chmod 777 ' . $dir.'/repository.stub');
            $this->info("dcat存根文件已完成替换");
        }
        file_put_contents(app()->environmentFilePath(), "\nZHK_GENERATE=true", FILE_APPEND);
    }
}
