<?php

namespace ZHK\Tool\Common;

class ArrayTool
{
    public static function index(array $array, $name)
    {
        $indexedArray = array();

        if (empty($array)) {
            return $indexedArray;
        }

        foreach ($array as $item) {
            if (isset($item[$name])) {
                $indexedArray[$item[$name]] = $item;
                continue;
            }
        }

        return $indexedArray;
    }

    public static function indexByObj($Obj, $name)
    {
        $indexedArray = array();
        if (empty($Obj)) {
            return $indexedArray;
        }

        foreach ($Obj as $item) {
            if (isset($item->$name)) {
                $indexedArray[$item->$name] = $item;
                continue;
            }
        }
        return $indexedArray;
    }

    public static function parts(array $array, array $keys)
    {
        foreach (array_keys($array) as $key) {
            if (!in_array($key, $keys)) {
                unset($array[$key]);
            }
        }

        return $array;
    }

    public static function columns(array $array, array $columnNames)
    {
        if (empty($array) || empty($columnNames)) {
            return array();
        }

        $columns = array();

        foreach ($array as $item) {
            foreach ($columnNames as $key) {
                $value = isset($item[$key]) ? $item[$key] : '';
                $columns[$key][] = $value;
            }
        }

        return array_values($columns);
    }

    public static function group(array $array, $key)
    {
        $grouped = array();

        foreach ($array as $item) {
            if (empty($grouped[$item[$key]])) {
                $grouped[$item[$key]] = array();
            }

            $grouped[$item[$key]][] = $item;
        }

        return $grouped;
    }

    public static function keyValuePair($array, $key, $value)
    {
        $grouped = array();

        foreach ($array as $item) {
            if (!empty($item[$key]) && !empty($item[$value])) {
                $grouped[$item[$key]] = $item[$value];
            }
        }

        return $grouped;
    }

    public static function keyValuePairToArr($array, $key, $value)
    {
        $grouped = array();
        foreach ($array as $name => $item) {
            $grouped[] = [$key => $name, $value => $item];
        }

        return $grouped;
    }

    /**
     * 完成无限级分类
     *
     * @param $array
     * @param string $key
     * @param int $id
     * @return array
     */
    public static function toTree($array, $key='parent_id', $id = 0)
    {
        $newArray = $filterArray = array_filter($array, function ($a) use($key, $id) {
            return $a[$key] == $id;
        });

        foreach ($filterArray as $k => $filter) {
            $newArray[$k]['child'] = self::toTree($array, $key, $filter['id']);
        }

        return $newArray;
    }

    /**
     * 只含有id的无限级分类
     *
     * @param $array
     * @param string $key
     * @param int $id
     * @return array
     */
    public static function toTreeKey($array, $key='parent_id', $id = 0)
    {
        $filterArray = array_filter($array, function ($a) use($key, $id) {
            return $a[$key] == $id;
        });

        $newArray = [];
        $filterKey = array_column($filterArray, 'id');
        foreach ($filterKey as $k) {
            $newArray[$k] = self::toTreeKey($array, $key, $k);
        }

        return $newArray;
    }
}
