<?php

namespace ZHK\Tool\Admin\Forms;

class MapForm extends SettingForm
{
    public function form()
    {
        $this->text('admin.map.keys.baidu', '百度AK')->help('百度AK可以在百度地图开放平台申请');
        // baidu-y 纬度  baidu-x 经度
        $this->map('map.baidu-y', 'map.baidu-x', '经纬度设置')->help('需要在百度AK保存完成后再使用');
    }
}
