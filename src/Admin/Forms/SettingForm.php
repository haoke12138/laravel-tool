<?php

namespace ZHK\Tool\Admin\Forms;

use App\Common\Tool;
use Dcat\Admin\Form\Field;
use Dcat\Admin\Widgets\Form;

class SettingForm extends Form
{
    use Setting;

    /**
     * @var Field\Text
     */
    public $admin_name, $app_name, $app_en_name;
    /**
     * @var Field\Textarea
     */
    public $desc, $keyword;
    /**
     * @var Field\Image
     */
    public $logo;
    /**
     * @var Field\File
     */
    public $favicon;

    /**
     * Build a form here.
     */
    public function form()
    {
        $this->admin_name = $this->text('admin.name', '网站后台名称');
        $this->app_name = $this->text('app.name', '网站前台名称');
        if (config('haoke.has_en')) {
            $this->app_en_name = $this->text('app.en_name', '网站前台英文名称');
        }
        $this->keyword = $this->textarea('app.keywords', '网站关键字');
        $this->desc = $this->textarea('app.desc', '网站描述信息');
        $this->logo = setAdminImage($this, 'admin.logo-url', '网站logo');

        $this->favicon = setAdminVideo($this, 'admin.favicon-url', '网站图标')
            ->help('推荐使用ico文件,仅支持ico,jpg,png,gif,jpeg')
            ->accept('ico,jpg,png,gif,jpeg');
        Tool::adminSettingForm($this);

        if (config('haoke.is_applets')) {
            $this->divider('小程序配置');
            $this->text('app.app_id', '小程序APP ID');
            $this->text('app.secret', '小程序SECRET');
            $this->text('app.mch_id', '支付商户号');
            $this->text('app.mch_key', '商户号密匙');
            $this->text('app.cert_path', '证书路径')->help('没有退款可为空');
            $this->text('app.key_path', '证书KEY路径')->help('没有退款可为空');
        }
    }
}
