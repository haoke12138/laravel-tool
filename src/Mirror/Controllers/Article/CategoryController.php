<?php

namespace App\Admin\Controllers\Article;

use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Grid\Displayers\Actions;
use Dcat\Admin\Show;
use App\Admin\Repositories\ArticleCategory;
use ZHK\Tool\Admin\Controllers\Article\CategoryController as AdminController;

class CategoryController extends AdminController
{
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(new ArticleCategory(), function (Grid $grid) {
            $grid->model()->where('type', 'news')->orderBy('order');
            $grid->column('id')->sortable();
            $grid->column('title');
//            $grid->column('select_img')->image('', 50);
//            $grid->column('unselect_img')->image('', 50);
//            $grid->column('order');
            $grid->column('created_at', '创建时间');
            $grid->column('updated_at', '更新时间')->sortable();
            $grid->actions(function (Actions $actions) {
               if ($this->id == 1) {
                   $actions->disableDelete();
               }
            });

            $grid->filter(function (Grid\Filter $filter) {
                $filter->like('title');
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
        return Show::make($id, new ArticleCategory(), function (Show $show) {
            $show->field('id');
            $show->field('title');
//            $show->field('select_img')->image('', 50);
//            $show->field('unselect_img')->image('', 50);
            $show->field('created_at', '创建时间');
            $show->field('updated_at', '更新时间');
            if ($show->model()->id == 1) {
                $show->disableDeleteButton();
            }
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Form::make(new ArticleCategory(), function (Form $form) {
            $form->display('id');
            $form->text('title')->required();
            $form->hidden('type')->value('news');
            if ($form->isEditing() && $form->model()->id == 1) {
                $form->disableDeleteButton();
            }
//            setAdminImage($form, 'select_img');
//            setAdminImage($form, 'unselect_img');
//            $form->number('order')->default(100);

            $form->display('created_at', '创建时间');
            $form->display('updated_at', '更新时间');
        });
    }
}
