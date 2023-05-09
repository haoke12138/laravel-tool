<?php

if (!function_exists('array_parts')) {
    /**
     * @deprecated 数组过滤
     * @param array $array
     * @param array $keys
     * @return array
     */
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

if (!function_exists('array_index')) {
    /**
     * @deprecated 将二维数组$array中的每个一维数组的$name元素对应的值作为二维数组的当前一维数组的键
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

if (!function_exists('array_group')) {
    /**
     * @deprecated 数组分组
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

if (!function_exists('file_path')) {
    /**
     * @deprecated 获取文件地址
     * @param $url
     * @return string
     */
    function file_path($url)
    {
        if (empty($url)) return '';

        return in_array(substr($url, 0, 5), ['https', 'http:']) ? $url : asset('storage/' . $url);
    }
}

if (!function_exists('link_path')) {
    /**
     * @deprecated 获取链接地址
     * @param string|null $link
     * @return mixed|string
     */
    function link_path($link = null)
    {
        if (empty($link)) {
            return 'javascript:;';
        }
        return in_array(substr($link, 0, 5), ['https', 'http:']) ? $link : asset($link);
    }
}

if (!function_exists('page_desc')) {
    /**
     * @deprecated 将textarea获取的数据转换成html
     * @param $str
     * @param string $replace
     * @return string|string[]
     */
    function page_desc($str, $replace = '<br>')
    {
        $str = str_replace("\n", $replace, $str, $count);
        if (!$count) {
            $str = str_replace("\r", $replace, $str, $count);
        }

        return $str;
    }
}

if (!function_exists('generateVerifyCode')) {
    /**
     * @deprecated 生成验证码
     * @param string $prefix
     * @return string
     */
    function generateVerifyCode($prefix = '', $count = 16)
    {
        if ($count > 10) {
            $count = $count - 10;
            $prefix = $prefix . date('ymd', time());
        }
        if ($count > 8) {
            $count = $count - 8;
            $prefix = $prefix . date('ymd', time());
        }
        if ($count > 6) {
            $count = $count - 6;
            $prefix = $prefix . date('md', time());
        }

        return $prefix . mt_rand(str_pad(1, $count, 0), str_pad(9, $count, 9));
    }
}

if (!function_exists('model')) {
    /**
     * @deprecated 获取模型
     * @param $slug
     * @param array $param
     * @return \App\Models\Model
     */
    function model($slug, array $param = [])
    {
        return app('model', [$slug, $param]);
    }
}

if (!function_exists('service')) {
    /**
     * @deprecated 获取服务
     * @param $slug
     * @param array $param
     * @return \App\Services\Service
     */
    function service($slug, array $param = [])
    {
        return app('service', [$slug, $param]);
    }
}

if (!function_exists('repository')) {
    /**
     * @deprecated 获取仓库
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
     * @deprecated 生成订单号
     * @param string $prefix
     * @return string
     */
    function generateOrderSn($prefix = '')
    {
        return $prefix . date('YmdHis', time()) . mt_rand(10000, 99999);
    }
}

if (!function_exists('make_log_path')) {
    /**
     * @deprecated 根据日期动态生成日志地址
     * @param $name
     * @param int $level 目录分级 3 目录结构精确到日, 2 精确到月 1 精确到年
     * @return string
     */
    function make_log_path($name, $level = 3)
    {
        $year = date('Y');
        $month = date('m');
        $day = date('d');
        if ($level == 3) {
            return "logs/$name/$year/$month/$day.log";
        }
        if ($level == 2) {
            return "logs/$name/$year/$month-$day.log";
        }

        return "logs/$name/$year-$month-$day.log";
    }
}

if (!function_exists('array_tree')) {
    /**
     * @deprecated 无限级分类
     * @param $array
     * @param string $key
     * @param int $id
     * @return array
     */
    function array_tree($array, $key = 'parent_id', $id = 0, $orderBy = 'order', $sortType = SORT_ASC)
    {
        if ($array instanceof Illuminate\Support\Collection && function_exists('collect_tree')) {
            return collect_tree($array, $key, $id, $orderBy, $sortType);
        }
        // 排序
        array_multisort(array_column($array, $orderBy), $sortType, $array); // 根据$orderBy数组进行排序

        $newArray = $filterArray = array_filter($array, function ($a) use ($key, $id) {
            return $a[$key] == $id;
        });

        foreach ($filterArray as $k => $filter) {
            $newArray[$k]['child'] = array_tree($array, $key, $filter['id'], $orderBy, $sortType);
        }

        return array_values($newArray);
    }
}


if (!function_exists('crossJoin')) {
    /**
     * 数据笛卡尔积排列
     * @param $arr
     * @return array|mixed
     */
    function crossJoin(array $arr)
    {
        #删除数组中的第一个元素  并返回被删除的元素
        $result = array_shift($arr);

        #循环并删除数组的下一个元素
        while ($arr2 = array_shift($arr)) {
            #将该数组的第一个元素重新赋值到新的变量中
            $firstArr = $result;

            #清空变量
            $result = [];

            #循环数组内第一个元素
            foreach ($firstArr as $v) {

                #循环数组内第二个元素
                foreach ($arr2 as $val) {
                    #恒定格式
                    !is_array($v) && $v = array($v);
                    !is_array($val) && $val = array($val);

                    #数组合并
                    $result[] = array_merge_recursive($v, $val);
                }
            }
        }

        return $result;
    }
}

if (!function_exists('redis')) {
    /**
     * @return mixed|Redis
     */
    function redis()
    {
        return \Illuminate\Support\Facades\Redis::client();
    }
}

if (!function_exists('is_mobile')) {
    /**
     * @deprecated 判断是否为手机号
     * @param $mobile
     * @return bool
     */
    function is_mobile($mobile)
    {
        return preg_match("/^1[3456789]{1}\d{9}$/", $mobile);
    }
}

if (!function_exists('file_download')) {
    /**
     * 文件下载
     * @param string $filepath
     * @throws Exception
     */
    function file_download($filepath = '', $filename = '')
    {
        if (!file_exists($filepath)) throw new \Exception('找不到文件!');

        //以只读和二进制模式打开文件
        $file = fopen($filepath, "rb");
        //告诉浏览器这是一个文件流格式的文件
        Header("Content-type: application/octet-stream");
        //请求范围的度量单位
        Header("Accept-Ranges: bytes");
        //Content-Length是指定包含于请求或响应中数据的字节长度
        Header("Accept-Length: " . filesize($filepath));
        //用来告诉浏览器，文件是可以当做附件被下载，下载后的文件名称为$file_name该变量的值。

        $filename = empty($filename) ? last(explode('/', $filepath)) : $filename;
        Header("Content-Disposition: attachment; filename={$filename}");
        //读取文件内容并直接输出到浏览器
        echo fread($file, filesize($filepath));
        fclose($file);
        exit();
    }
}

if (!function_exists('price')) {
    /**
     * @deprecated 格式化价格
     * @param $mobile
     * @return bool
     */
    function price($price)
    {
        return number_format($price, 2);
    }
}

if (!function_exists('dd')) {
    /**
     * 仿laravel 断点
     * @param mixed $args
     */
    function dd(...$args)
    {
        dump(...$args);
        exit();
    }
}

if (!function_exists('dump')) {
    /**
     * 仿laravel 打印
     * @param mixed $args
     */
    function dump(...$args)
    {
        echo '<pre>';
        var_dump(...$args);
        echo '</pre>';
    }
}

if (!function_exists('head')) {
    function head($array)
    {
        return reset($array);
    }
}

if (!function_exists('last')) {
    function last($array)
    {
        return end($array);
    }
}