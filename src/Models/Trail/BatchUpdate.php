<?php

namespace ZHK\Tool\Models\Trail;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

trait BatchUpdate
{
    public function updateBatch($multipleData = [], $tableName = null)
    {
        $tableName = empty($tableName) ? $this->getTable() : $tableName;
        $tableColumn = Schema::getColumnListing($tableName);

        if (empty($multipleData)) {
            throw new \Exception("批量更新数据不能为空");
        }
        $firstRow = current($multipleData);  // 获取第一个元素数据
        $dataColumn = array_keys($firstRow); // 获取修改字段

        // 默认以id为条件更新，如果没有ID则以第一个字段为条件
        $referenceColumn = isset($firstRow['id']) ? 'id' : current($dataColumn); //
        unset($dataColumn[0]); // 清除条件字段

        // 拼接sql语句
        $updateColumn = array_intersect($dataColumn, $tableColumn);
        $updateSql = "UPDATE " . $tableName . " SET ";
        $sets  = [];
        $bindings = [];
        foreach ($updateColumn as $uColumn) {
            $setSql = "`" . $uColumn . "` = CASE ";
            foreach ($multipleData as $data) {
                $setSql .= "WHEN `" . $referenceColumn . "` = ? THEN ? ";

                $bindings[] = $data[$referenceColumn];
                $bindings[] = $data[$uColumn];
            }
            $setSql .= "ELSE `" . $uColumn . "` END ";
            $sets[] = $setSql;
        }
        $updateSql .= implode(', ', $sets);
        $whereIn = collect($multipleData)->pluck($referenceColumn)->values()->all();
        $bindings = array_merge($bindings, $whereIn);
        $whereIn = rtrim(str_repeat('?,', count($whereIn)), ',');
        $updateSql = rtrim($updateSql, ", ") . " WHERE `" . $referenceColumn . "` IN (" . $whereIn . ")";

        // 传入预处理sql语句和对应绑定数据
        DB::update($updateSql, $bindings);
    }
}
