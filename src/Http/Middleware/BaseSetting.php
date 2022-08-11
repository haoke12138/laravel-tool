<?php

namespace ZHK\Tool\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Route;

class BaseSetting
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // 依赖dcat-admin的admin操作
        if (function_exists('admin_setting')) {
            $setting = admin_setting()->toArray();
            foreach ($setting as &$item) {
                if ($arr = json_decode($item, true)) {
                    $item = $arr;
                }
            }
            $setting = array_filter(\Arr::dot($setting)); // 多维数组转一维, 中间用.连接
            if (!empty($setting['admin.logo-url'])) {
                $setting['admin.logo-url'] = file_path($setting['admin.logo-url']);
            }
            if (!empty($setting['admin.favicon-url'])) {
                $setting['admin.favicon-url'] = file_path($setting['admin.favicon-url']);
            }
            !empty($setting) && config($setting);
        }

        // 添加多语言设置
        app()->setLocale(session('app.locale') ?: 'zh_CN');

        return $next($request);
    }
}
