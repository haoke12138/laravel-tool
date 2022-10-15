<?php

namespace ZHK\Tool\Admin\Controllers;

use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use App\Admin\Repositories\InfoDisplay;
use Dcat\Admin\Http\Controllers\AdminController;

class InfoDisplayController extends AdminController
{
    protected $title = '信息显示';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(new InfoDisplay(), function (Grid $grid) {
            $grid->model()->where('type', '');

            $grid->column('id')->sortable();
            $grid->column('title', '标题');
            $grid->column('subtitle', '副标题');
            $grid->column('icon')->image('', 50);
            $grid->column('desc', '简介');
            $grid->column('link', '链接');
            $grid->column('files', '文件');
            $grid->column('other_info', '其他信息');
            $grid->column('json_info', 'json信息');
            $grid->column('category_id', '所属分类');
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
        return Show::make($id, new InfoDisplay(), function (Show $show) {
            $show->field('id');
            $show->field('title', '标题');
            $show->field('subtitle', '副标题');
            $show->field('icon')->image();
            $show->field('desc', '简介');
            $show->field('link', '链接');
            $show->field('files', '文件')->file();
            $show->field('other_info', '其他信息');
            $show->field('json_info', 'json信息');
            $show->field('category_id', '所属分类');
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
        return Form::make(new InfoDisplay(), function (Form $form) {
            $form->display('id');
            $form->text('title', '标题')->required();
            $form->text('subtitle', '副标题');
            setAdminImage($form, 'icon')->required();
            $form->textarea('desc', '简介');
            $form->url('link', '链接');
            setAdminVideo($form, 'files', '文件')->required();
            $form->editor('other_info', '其他信息');
            $form->list('json_info', 'json信息');
            $form->select('category_id', '所属分类')->options(model('InfoDisplayType')->getOptions());
            $form->hidden('type')->value('');

            $form->display('created_at');
            $form->display('updated_at');
        });
    }
}
