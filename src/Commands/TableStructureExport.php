<?php

namespace ZHK\Tool\Commands;

use App\Models\Model;
use Illuminate\Console\Command;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use \Exception;
use ZHK\Tool\Common\ArrayTool;

class TableStructureExport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zhk:table_structure_export';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "导出数据库的表结构";

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
        $tables = Db::select('show tables');
        if (empty($tables)) {
            throw new \Exception('暂无数据表');
        }

        $database = config('database.connections.mysql.database');

        $tables = array_column(json_decode(json_encode($tables), true), 'Tables_in_'.$database);

        $columns = DB::table('information_schema.columns')
            ->select('column_name', 'data_type', 'column_comment', 'table_name', 'is_nullable', 'character_maximum_length', 'column_comment', 'column_default')
            ->where('table_schema', $database)
            ->whereIn('table_name', $tables)
            ->whereNotIn('column_name', ['id', 'created_at', 'updated_at'])
            ->get();
        $columns = json_decode($columns, true);


        $tableNameFiledName = in_array('table_name', array_keys(reset($columns))) ? 'table_name' : 'TABLE_NAME';
        $columns = ArrayTool::group($columns, $tableNameFiledName);

        $filePath = public_path($database . '数据库结构文档.csv');

        $bom = chr(0xEF).chr(0xBB).chr(0xBF); // 解决中文乱码
        file_put_contents($filePath, $bom);
        foreach ($columns as $name => $column) {
            file_put_contents($filePath, "表名, $name\n", FILE_APPEND);
            file_put_contents($filePath, ", 字段名称, 字段类型, 注释, 是否为空, 默认值\n", FILE_APPEND);

            foreach ($column as $col) {
                $dataType = in_array('DATA_TYPE', array_keys($col)) ? 'DATA_TYPE' : 'data_type';
                $column_name = in_array('COLUMN_NAME', array_keys($col)) ? 'COLUMN_NAME' : 'column_name';
                $column_comment = in_array('COLUMN_COMMENT', array_keys($col)) ? 'COLUMN_COMMENT' : 'column_comment';
                $isNullable = in_array('IS_NULLABLE', array_keys($col)) ? 'IS_NULLABLE' : 'is_nullable';
                $column_default = in_array('COLUMN_DEFAULT', array_keys($col)) ? 'COLUMN_DEFAULT' : 'column_default';

                $row = [];
                $row[] = $col[$column_name];                    // 字段名
                $row[] = $col[$dataType];                       // 字段类型
                $row[] = $col[$column_comment] ?? 'null';       // 注释
                $row[] = $col[$isNullable] ? '是' : '否';        // 是否为空
                $row[] = $col[$column_default] ?? 'null';       // 默认值
                file_put_contents($filePath, ', '.join(', ', $row)."\n", FILE_APPEND);
            }

            file_put_contents($filePath, "\n\n\n", FILE_APPEND);
        }
        $this->info("\n\n");
        $this->info("已完成, 文件地址为: " . $filePath);
    }

}
