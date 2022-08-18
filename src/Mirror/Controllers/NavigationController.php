<?php

namespace App\Admin\Controllers;

use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use ZHK\Tool\Admin\Controllers\NavigationController as Base;

class NavigationController extends Base
{
    protected function grid()
    {
        return parent::grid();
    }

    protected function detail($id)
    {
        return parent::detail($id);
    }

    protected function form()
    {
        return parent::form();
    }
}
