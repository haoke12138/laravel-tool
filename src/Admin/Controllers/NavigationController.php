<?php

namespace ZHK\Tool\Admin\Controllers;

use App\Admin\Repositories\Navigation;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Http\Controllers\AdminController;

class NavigationController extends AdminController
{
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(new Navigation(), function (Grid $grid) {
            $grid->column('id')->sortable();
            $grid->column('title')->tree();
            $grid->column('slug')->label();
            $grid->column('image')->image('', 50);
            $grid->column('mobile_image')->image('', 50);
            $grid->column('parent_id')->display(function ($id) {
                return $this->getValue(['id' => $id]) ?: admin_trans_label('root');
            });
            $grid->column('order')->editable(true);
            $grid->column('enable')->switch();
            $grid->column('created_at');
            $grid->column('updated_at')->sortable();

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
        return Show::make($id, new Navigation(), function (Show $show) {
            $show->field('id');
            $show->field('title');
            $show->field('slug');
            $show->field('image')->image();
            $show->field('mobile_image')->image();
            $show->field('parent_id')->as(function ($id) {
                return $this->getValue(['id' => $id]) ?: admin_trans_label('root');
            });
            $show->field('link');
            $show->field('order');
            $show->field('tdk')->unescape()->as(function ($v) {
                return "标题: {$v['TITLE']} <br> 关键字: {$v['KEYWORD']}<br>描述信息 {$v['DESC']}";
            });
            $show->field('is_external_link')->using(['站内', '站外']);
            $show->field('enable')->using(['隐藏', '显示']);
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
        return Form::make(new Navigation(), function (Form $form) {
            $form->tab('基本设置', function (Form $form) {
                $form->display('id');
                $form->text('title')->required()->rules('string|max: 20');
                $form->text('slug')->required()->rules('string|max: 50');
                setAdminImage($form, 'image')->required();
                setAdminImage($form, 'mobile_image')->required();
                $form->embeds('banner_info', '', function (Form\EmbeddedForm $form) {
                    $form->text('banner_title', 'banner标题');
                    $form->textarea('banner_subtitle', 'banner副标题');
                });
                $form->select('parent_id')->options(model('Navigation')->selectOptions())->default(0);
                $form->radio('is_external_link')->options(['否', '是'])->when(1, function (Form $form) {
                    $form->url('external_link');
                })->when(0, function (Form $form) {
                    $form->text('link');
                })->default(0);
                $form->number('order')->default(100);
                $form->switch('enable')->default(1);

                $form->display('created_at');
                $form->display('updated_at');
            });

            $form->tab('SEO', function (Form $form) {
                $form->embeds('tdk', '', function ($form) {
                    $form->text('TITLE')->rules('max: 20');
                    $form->textarea('KEYWORD');
                    $form->textarea('DESC');
                });
            });
        });
    }
}
