<?php

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