<?php

use App\Models\Navigation;

if (!function_exists('array_parts')) {
    function array_parts(array $array, array $keys)
    {
        foreach (array_keys($array) as $key) {
            if (!in_array($key, $keys)) {
                unset($array[$key]);
            }
        }

        return $array;
    }
}

if (!function_exists('member')) {
    function member($request)
    {
        return json_decode($request->attributes->get('member'));
    }
}

if (! function_exists('array_index')) {
    /**
     * @see 将二维数组$array中的每个一维数组的$name元素对应的值作为二维数组的当前一维数组的键
     *
     * @param array $array
     * @param $name
     * @return array
     */
    function array_index(array $array, $name)
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
}

if (! function_exists('array_group')) {
    /**
     * 数组分组
     * @param array $array
     * @param $key
     * @return array
     */
    function array_group(array $array, $key)
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
}

if (! function_exists('group')) {
    /**
     * 数组分组
     * @param array $array
     * @param $key
     * @return array
     */
    function group( array $arr, string $key , string $resultKey = '')
    {
        foreach ($arr as $k => $v) {
            $data[$v[$key]][] = !empty($resultKey) ? $v[$resultKey] : $v;
        }
        return $data;
    }
}

if (! function_exists('file_path')) {
    function file_path($url)
    {
        return empty($url) ? '' : asset('storage/' . $url);
    }
}


if (! function_exists('model')) {
    /**
     * 获取模型
     * @param $slug
     * @param array $param
     * @return string
     */
    function model($slug, array $param = [])
    {
        return app('model', [$slug, $param]);
    }
}

if (! function_exists('service')) {
    /**
     * 获取服务
     * @param $slug
     * @param array $param
     * @return string
     */
    function service($slug, array $param = [])
    {
        return app('service', [$slug, $param]);
    }
}

if (! function_exists('repository')) {
    /**
     * 获取仓库
     * @param $slug
     * @param array $param
     * @return string
     */
    function repository($slug, array $param = [])
    {
        return app('repository', [$slug, $param]);
    }
}

if (!function_exists('generateOrderSn')) {
    /**
     * 生成订单号
     * @param string $prefix
     * @return string
     */
    function generateOrderSn($prefix = '')
    {
        return $prefix . date('YmdHis', time()) . mt_rand(10000, 99999);
    }
}

if (! function_exists('array_tree')) {
    /**
     * @see 完成无限级分类
     * @param $array
     * @param string $key
     * @param int $id
     * @return array
     */
    function array_tree($array, $key='parent_id', $id = 0, $orderBy = 'order', $sortType = SORT_DESC)
    {
        // 排序
        $orderBy = array_column($array, $orderBy);
        array_multisort($orderBy, $sortType, $array); // 根据$orderBy数组进行排序

        $newArray = $filterArray = array_filter($array, function ($a) use($key, $id) {
            return $a[$key] == $id;
        });

        foreach ($filterArray as $k => $filter) {
            $newArray[$k]['child'] = array_tree($array, $key, $filter['id'], $orderBy, $sortType);
        }

        return $newArray;
    }
}

if (!function_exists('getNavigate')) {
    /**
     * @see 获取导航栏
     * @return array
     */
    function getNavigate()
    {
        $nar = Navigation::where('enable', 1)
            ->orderBy('order')->get()->toArray();
        $nar = array_index($nar, 'id');

        return array_tree($nar, 'parent_id');
    }
}

if (!function_exists('getCurrentPage')) {
    /**
     * @see 获取当前页面
     * @param $slug
     * @return array
     */
    function getCurrentPage($slug)
    {
        $slugName = explode('.', $slug);
        $nar = Navigation::where('slug', head($slugName))
            ->orderBy('order')->first();
        if (!empty($nar)) {
            $nar['father'] = $nar['parent_id'] != 0 ? Navigation::where('id', $nar['parent_id'])->value('title') : '';
            $nar['fatherSlug'] = $nar['parent_id'] != 0 ? Navigation::where('id', $nar['parent_id'])->value('slug') : '';
            $nar['fatherLink'] = $nar['parent_id'] != 0 ? Navigation::where('id', $nar['parent_id'])->value('link') : '';
        }

        return empty($nar) ? [] : $nar;
    }
}

if (!function_exists('getContact')) {
    /**
     * @see 获取页脚联系我们
     * @return array
     */
    function getContact()
    {
        return config('app');
    }
}


if (!function_exists('crossJoin')) {
    /**
     * 数据笛卡尔积排列
     * @param $arr
     * @return array|mixed
     */
    function crossJoin (array $arr) {
        #删除数组中的第一个元素  并返回被删除的元素
        $result = array_shift($arr);

        #循环并删除数组的下一个元素
        while ( $arr2 = array_shift($arr) ) {
            #将该数组的第一个元素重新赋值到新的变量中
            $firstArr = $result;

            #清空变量
            $result = [];

            #循环数组内第一个元素
            foreach ( $firstArr as $v ) {

                #循环数组内第二个元素
                foreach ( $arr2 as $val ) {
                    #恒定格式
                    !is_array($v) && $v = array($v);
                    !is_array($val) && $val = array($val);

                    #数组合并
                    $result[] = array_merge_recursive($v,$val);
                }
            }
        }

        return $result;
    }
}

