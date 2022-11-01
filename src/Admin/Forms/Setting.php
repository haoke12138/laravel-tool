<?php

namespace ZHK\Tool\Admin\Forms;

use Arr;

trait Setting
{
    public function handle(array $input)
    {
        admin_setting(array_merge(Arr::dot($this->decodeSetting()), Arr::dot($input)));

        return $this->response()->success('配置完成, 请刷新页面查看!')->refresh();
    }

    public function default()
    {
        return $this->decodeSetting();
    }

    protected function decodeSetting()
    {
        $setting = admin_setting()->toArray();
        foreach ($setting as &$item) {
            if ($arr = json_decode($item, true)) {
                $item = $arr;
            }
        }

        return $setting;
    }
}