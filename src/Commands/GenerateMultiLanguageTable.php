<?php

namespace ZHK\Tool\Commands;

use App\Models\Model;
use Illuminate\Console\Command;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use \Exception;
use ZHK\Tool\Common\ArrayTool;

class GenerateMultiLanguageTable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zhk:language_multi_generate {lang} {table?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "生成多语言数据表, 参数: lang[语种, 必填], table[表名, 选填]";

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
        $tables = array_map('reset', Db::select('show tables'));
        $tables = array_diff($tables, Model::$multilingualFiltering); // 排除掉不用复制的数据表

        $locale = ['en', 'tw'];
        $tables = array_filter(array_map(function ($table) use ($locale) { // 过滤其他语言的数据表
            if (!in_array(explode('_', $table)[0], $locale)) {
                return $table;
            }
        }, $tables));

        $input = $this->argument('table');
        if (!empty($input) && !in_array($input, $tables)) {
            $this->error("{$input} 表不存在");
            exit();
        }
        $tables = empty($input) ? $tables : [$input];

        $columns = DB::table('information_schema.columns')
            ->select('column_name', 'data_type', 'column_comment', 'table_name', 'is_nullable', 'character_maximum_length', 'column_comment')
            ->where('table_schema', config('database.connections.mysql.database'))
            ->whereIn('table_name', $tables)
            ->whereNotIn('column_name', ['id', 'created_at', 'updated_at'])
            ->get();
        $columns = json_decode($columns, true);

        if (!count($columns)) {
            $this->error("没有可以生成的表!");
        }

        $tableNameFiledName = in_array('table_name', array_keys(reset($columns))) ? 'table_name' : 'TABLE_NAME';
        $columns = ArrayTool::group($columns, $tableNameFiledName);

        $lang = $this->argument('lang');
        foreach ($columns as $name => $column) {
            try {
                Schema::create($lang . '_' . $name, function (Blueprint $table) use ($column) {
                        $table->increments('id');
                        foreach ($column as $col) {
                            $dataType = in_array('DATA_TYPE', array_keys($col)) ? 'DATA_TYPE' : 'data_type';
                            $type = $this->dateType($col[$dataType]);

                            $column_name = in_array('COLUMN_NAME', array_keys($col)) ? 'COLUMN_NAME' : 'column_name';
                            $column_comment = in_array('COLUMN_COMMENT', array_keys($col)) ? 'COLUMN_COMMENT' : 'column_comment';
                            $a = $table->$type($col[$column_name])->comment($col[$column_comment]);

                            $isNullable = in_array('IS_NULLABLE', array_keys($col)) ? 'IS_NULLABLE' : 'is_nullable';
                            if ($col[$isNullable]) {
                                $a->nullable();
                            }
                        }
                    $table->timestamps();
                });
            } catch (\Exception $e) {
                $this->error("{$name} 已跳过! {$e->getMessage()} {$e->getLine()}");
            }
        }
        $this->info("\n\n");
        $this->info("已完成");
    }

    private function dateType($name = null)
    {
        $dataType = [
            'char' => 'char',
            'bigint' => 'bigInteger',
            'tinyint' => 'tinyInteger',
            'varchar' => 'string',
            'longtext' => 'longText',
            'int' => 'integer',
            'text' => 'text',
            'date' => 'date',
            'datetime' => 'dateTime',
            'json' => 'json'
        ];

        return empty($name) ? $dataType : $dataType[$name];
    }
}
