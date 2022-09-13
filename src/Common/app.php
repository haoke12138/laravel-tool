<?php

use \Illuminate\Support\Collection;
if (! function_exists('collect_tree')) {
    /**
     * @see 完成无限级分类
     * @param $array
     * @param string $key
     * @param int $id
     * @return array
     */
    function collect_tree(Collection $array, $key='parent_id', $id = 0, $orderBy = 'order', $sortType = SORT_ASC)
    {
        $array = $array->sortBy($orderBy, SORT_REGULAR, $sortType == SORT_DESC); // desending 是 true时为倒序

        $newArray = $filterArray = $array->filter(function ($a) use($key, $id) {
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
     * @see 获取导航栏
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
     * @see 获取导航栏
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
     * @see 获取当前页面
     * @param $slug
     * @return array
     */
    function get_current_page($slug)
    {
        $slugName = explode('.', $slug);
        $nar = model('Navigation')->where('slug', head($slugName))->orderBy('order')->first();
        if (!empty($nar)) {
            $nar['father'] = $nar['parent_id'] != 0 ? model('Navigation')->where('id', $nar['parent_id'])->value('title') : '';
            $nar['fatherSlug'] = $nar['parent_id'] != 0 ? model('Navigation')->where('id', $nar['parent_id'])->value('slug') : '';
            $nar['fatherLink'] = $nar['parent_id'] != 0 ? model('Navigation')->where('id', $nar['parent_id'])->value('link') : '';
        }

        return empty($nar) ? [] : $nar;
    }
}

if (!function_exists('get_parent_slug')) {
    /**
     * 获取上级标识, 若为顶级返回当前标识
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
        if (!function_exists($file)) {
            file_put_contents($file, '[]');
        }

        $route = json_decode(file_get_contents($file, true));

        $v = $closure($route);
        $route = $v && is_array($v) ? $v : $route;

        file_put_contents($file, json_encode($route, 256));
    }
}