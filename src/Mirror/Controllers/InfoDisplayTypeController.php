<?php

namespace App\Admin\Controllers;

use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use App\Admin\Repositories\InfoDisplayType;
use ZHK\Tool\Admin\Controllers\InfoDisplayTypeController as AdminController;

class InfoDisplayTypeController extends AdminController
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
