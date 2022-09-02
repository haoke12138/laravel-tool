<?php

namespace ZHK\Tool\Admin\Forms;

use Arr;
use Dcat\Admin\Widgets\Form;

class SettingForm extends Form
{
    public function handle(array $input)
    {
        admin_setting(array_merge(Arr::dot($this->decodeSetting()), Arr::dot($input)));

        return $this->response()->success('配置完成, 请刷新页面查看!')->refresh();
    }

    /**
     * Build a form here.
     */
    public function form()
    {
        $this->text('admin.name', '网站后台名称');
        $this->text('app.name', '网站前台名称');
        if (config('haoke.has_en')) {
            $this->text('app.en_name', '网站前台英文名称');
        }
        $this->textarea('app.keywords', '网站关键字');
        $this->textarea('app.desc', '网站描述信息');
        $this->image('admin.logo-url', '网站logo')
            ->accept('jpg,png,gif,jpeg')->uniqueName()->autoUpload();
        $this->image('admin.favicon-url', '网站图标')
            ->accept('jpg,png,gif,jpeg')->uniqueName()->autoUpload();

        if (config('haoke.is_applets')) {
            $this->divider('小程序配置');
            $this->text('app.app_id', '小程序APP ID');
            $this->text('app.secret', '小程序SECRET');
            $this->text('app.mch_id', '支付商户号');
            $this->text('app.mch_key', '商户号密匙');
            $this->text('app.cert_path', '证书路径');
            $this->text('app.key_path', '证书KEY路径');
        }

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
