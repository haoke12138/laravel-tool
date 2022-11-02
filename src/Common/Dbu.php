<?php

namespace ZHK\Tool\Common;

class Dbu
{
    private $app;

    public function __construct($app)
    {
        $this->app = $app;
        $this->app['service'] = function ($app, $param) {
            $this->app = $app;
            list($slug, $param) = $param;
            return $this->make('Services', $slug, $param);
        };
        $this->app['model'] = function ($app, $param) {
            $this->app = $app;
            list($slug, $param) = $param;
            return $this->make('Models', $slug, $param);
        };
        $this->app['repository'] = function ($app, $param) {
            $this->app = $app;
            list($slug, $param) = $param;
            return $this->make('Repositories', 'App.Admin:' . $slug, $param);
        };
    }

    private function make($type, $slug, $param)
    {
        if (is_array($slug)) {
            $param = end($slug) == head($slug) ? [] : end($slug);
            $slug = head($slug);
        }

        $name = '@' . $slug . $type;
        if (!empty($app[$name])) {
            return $app[$name];
        }
        $class = $this->getNamespace($slug, $type);

        return $this->app[$name] = new $class($param);
    }

    private function getNamespace($slug, $type)
    {
        $name = null;
        try {
            list($module, $name) = explode(':', $slug);
        } catch (\ErrorException $e) {
            $module = $slug;
        }

        if (empty($module) && empty($name)) {
            throw new \Exception('$slug 参数格式错误!');
        }

        $namespace = empty($name) ? "App.$type.$module" : "$module.$type.$name";

        return str_replace('.', '\\', $namespace);
    }

}