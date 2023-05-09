<?php

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use \Illuminate\Support\Collection;

if (!function_exists('collect_tree')) {
    /**
     * @deprecated 无限级分类(集合)
     * @param Collection $array
     * @param string $key
     * @param int $id
     * @param string $orderBy
     * @param int $sortType
     * @return array
     */
    function collect_tree(Collection $array, $key = 'parent_id', $id = 0, $orderBy = 'order', $sortType = SORT_ASC)
    {
        $array = $array->sortBy($orderBy, SORT_REGULAR, $sortType == SORT_DESC); // desending 是 true时为倒序

        $newArray = $filterArray = $array->filter(function ($a) use ($key, $id) {
            return $a[$key] == $id;
        });

        foreach ($filterArray as $k => $filter) {
            $newArray[$k]['child'] = collect_tree($array, $key, $filter['id'], $orderBy, $sortType);
        }

        return $newArray->values();
    }
}

if (!function_exists('get_navigate_set')) {
    /**
     * @deprecated 获取导航栏
     * @return array
     */
    function get_navigate_set()
    {
        $nar = model('Navigation')->where('enable', 1)->get();
        return collect_tree($nar, 'parent_id');
    }
}

if (!function_exists('get_navigate')) {
    /**
     * @deprecated 获取导航栏
     * @return array
     */
    function get_navigate()
    {
        $nar = model('Navigation')->where('enable', 1)->orderBy('order')->get()->toArray();
        $nar = array_index($nar, 'id');

        return array_tree($nar, 'parent_id');
    }
}

if (!function_exists('get_current_page')) {
    /**
     * @deprecated 获取当前页面导航信息
     * @param $slug
     * @return array
     */
    function get_current_page($slug)
    {
        $nar = model('Navigation')->where('slug', $slug)->orderBy('order')->first();
        if (empty($nar)) {
            $slugName = explode('.', $slug);
            $nar = model('Navigation')->where('slug', head($slugName))->orderBy('order')->first();
        }
        if (!empty($nar)) {
            $nar['father'] = $nar['parent_id'] != 0 ? model('Navigation')->where('id', $nar['parent_id'])->value('title') : '';
            $nar['fatherSlug'] = $nar['parent_id'] != 0 ? model('Navigation')->where('id', $nar['parent_id'])->value('slug') : '';
            $nar['fatherLink'] = $nar['parent_id'] != 0 ? model('Navigation')->where('id', $nar['parent_id'])->first()->getLink() : '';
        }

        return empty($nar) ? [] : $nar;
    }
}

if (!function_exists('get_parent_slug')) {
    /**
     * @deprecated 获取上级标识, 若为顶级返回当前标识
     * @param $slug
     * @return string
     */
    function get_parent_slug($slug)
    {
        $parentId = model('Navigation')->where('slug', $slug)->value('parent_id');
        if (!$parentId) {
            return $slug;
        }

        return model('Navigation')->where('id', $parentId)->value('slug');
    }
}

if (!function_exists('write_route')) {
    /**
     * 写入路由
     *
     * @return mixed
     */
    function write_route($closure)
    {
        // 添加访问路由
        $file = base_path('router.json');
        if (!file_exists($file)) {
            file_put_contents($file, '[]');
        }

        $route = json_decode(file_get_contents($file), true);

        $v = $closure($route);
        $route = $v && is_array($v) ? $v : $route;
        file_put_contents($file, json_encode($route, 256));
    }
}

if (!function_exists('return_api')) {
    /**  api返回
     * @param Closure $closure 形参obj对象属性有 data 返回参数, msg 返回信息, request 请求, code 状态码
     * @param Request $request
     * @param int $code
     * @return JsonResponse 返回参数 code 回执码 正常为200, [ msg 返回信息, data 返回参数 ]
     */
    function return_api(\Closure $closure, Request $request = null, int $code = 200)
    {
        $request = empty($request) ? request() : $request;
        $obj = new \App\Common\Object_(['data' => null, 'msg' => null, 'request' => $request, 'code' => $code]);
        try {
            $closure($obj);
            $res = ['code' => 200];
            if (isset($obj->data)) $res['data'] = $obj->data;
            if (isset($obj->msg)) $res['msg'] = $obj->msg;
        } catch (\Exception $e) {
            $res = ['code' => $e->getCode() ? $e->getCode() : 500, 'msg' => $e->getMessage()];
        }

        return response()->json($res, $obj->code);
    }
}


if (!function_exists('mobile_encode')) {
    /**
     * @deprecated 手机隐藏显示
     * @param $mobile
     * @param string $str
     * @return string
     */
    function mobile_encode($mobile, $str = 'x')
    {

        return substr($mobile, 0, 3) . $str . $str . $str . $str . substr($mobile, -4, 4);
    }
}
