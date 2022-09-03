<?php

namespace ZHK\Tool\Admin\Controllers;

use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use App\Admin\Repositories\InfoDisplayType;
use Dcat\Admin\Http\Controllers\AdminController;

class InfoDisplayTypeController extends AdminController
{
    protected $title = '信息显示类型';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(new InfoDisplayType(), function (Grid $grid) {
            $grid->model()->where('type', '');

            $grid->column('id')->sortable();
            $grid->column('title', '标题');
            $grid->column('subtitle', '副标题');
            $grid->column('image', '图片')->image('', 50);
            $grid->column('link', '链接');
            $grid->column('created_at');
            $grid->column('updated_at')->sortable();

            $grid->filter(function (Grid\Filter $filter) {
                $filter->like('title', '标题');
                $filter->like('subtitle', '副标题');
            });
        });
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     *
     * @return Show
     */
    protected function detail($id)
    {
        return Show::make($id, new InfoDisplayType(), function (Show $show) {
            $show->field('id');
            $show->field('title', '标题');
            $show->field('subtitle', '副标题');
            $show->field('image', '图片')->image();
            $show->field('link', '链接');
            $show->field('created_at');
            $show->field('updated_at');
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Form::make(new InfoDisplayType(), function (Form $form) {
            $form->display('id');
            $form->text('title', '标题')->required();
            $form->text('subtitle', '副标题');
            setAdminImage($form, 'image', '图片')->required();
            $form->url('link', '链接');
            $form->hidden('type')->value('');

            $form->display('created_at');
            $form->display('updated_at');
        });
    }
}
