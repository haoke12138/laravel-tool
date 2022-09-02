<?php

namespace ZHK\Tool\Admin\Controllers;

use Dcat\Admin\Widgets\Card;
use ZHK\Tool\Admin\Forms\SettingForm;
use App\Http\Controllers\Controller;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Layout\Row;
use Dcat\Admin\Widgets\Tab;
use ZHK\Tool\Repositories\Navigation;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;

class SettingController extends Controller
{
    public function index(Content $content)
    {
        return $content
            ->title('网站设置')
            ->description(trans('admin.setting'))
            ->body(new Card(new SettingForm()));
    }

}
