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
        try {
            service('ZHK.Tool:Init')->replaceStubFile();
            $this->info("dcat存根文件已完成替换");
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }
}
