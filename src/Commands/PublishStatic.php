<?php

namespace ZHK\Tool\Commands;

use App\Models\Model;
use Illuminate\Console\Command;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use \Exception;
use League\Flysystem\Adapter\Local as LocalAdapter;
use League\Flysystem\Filesystem as Flysystem;
use League\Flysystem\MountManager;
use ZHK\Tool\Common\ArrayTool;
use ZHK\Tool\Models\Menu;

class PublishStatic extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zhk:publish-static';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "发布静态文件";

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
            // 复制文件夹内的内容
            service('ZHK.Tool:Init')->moveManagedFiles(new MountManager([
                'from' => new Flysystem(new LocalAdapter(haoke_path('resources/assets'))),
                'to' => new Flysystem(new LocalAdapter(public_path('haoke'))),
            ]));

            $this->info("完成!");
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }
}
