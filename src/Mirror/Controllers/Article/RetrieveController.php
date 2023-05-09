<?php

namespace App\Admin\Controllers\Article;

use App\Admin\Actions\Grid\RestoreArticle;
use App\Models\Member\Member;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use App\Admin\Repositories\Article;
use App\Models\Article\Category;
use ZHK\Tool\Admin\Controllers\Article\ArticleController as AdminController;

class RetrieveController extends AdminController
{
    protected $title = '回收站';
    protected $translation = 'article';

    protected function grid(): Grid
    {
        return Grid::make(new Article(['category']), function (Grid $grid) {
            $grid->model()->onlyTrashed()->where('article_type', 'news')
                ->orderBy('published_at', 'desc')->orderBy('id', 'desc');
            $grid->column('id')->sortable();
            $grid->column('title')->limit(30);
            $grid->column('category.title');
            $grid->column('enable')->using(['未发布', '已发布']);
            $grid->column('published_at')->sortable();
            $grid->column('created_at', '创建时间');
            $grid->column('updated_at', '更新时间')->sortable();
            $grid->disableCreateButton();

            $grid->actions(function (Grid\Displayers\Actions $action) {
                $action->append(new RestoreArticle);
            });

            $grid->filter(function (Grid\Filter $filter) {
                $filter->like('title');
                $filter->equal('category_id')->select($this->getCateOptions());
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
    protected function detail($id): Show
    {
        return Show::make($id, new Article(['category']), function (Show $show) {
            $show->field('id');
            $show->field('title');
            $show->field('source');
            $show->field('author');
            $show->field('category.title');
            setAdminTextarea($show, 'desc');
            $show->field('content')->unescape();
            $show->field('thumbnail')->image();
            $show->field('created_at', '创建时间');
            $show->field('updated_at', '更新时间');
        });
    }

    protected function form(): Form
    {
        return Form::make(new Article(), function (Form $form) {
            $form->tab('基本信息', function (Form $form) {
                $form->display('id');
                $form->text('title')->required();
                $form->select('category_id')->options($this->getCateOptions())->required();
                $form->hidden('article_type')->value('news');
//                $form->hidden('type')->default('0');
                $form->radio('type', '是否跳转外部文章')->options(['否', '是'])->required()
                    ->when(1, fn ($form) => $form->url('link', '链接地址'));

                $form->hidden('source')->value('本站');
                $form->hidden('author')->value('佚名');
                $form->editor('content');
                $form->number('visited', '访问量');
                $form->switch('enable');
                $form->datetime('published_at')->default(date('Y-m-d H:i:s'));
//                $form->number('visited', '访问量');
//                $form->number('order')->default(100);

                $form->display('created_at', '创建时间');
                $form->display('updated_at', '更新时间');
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

    protected function getCateOptions(): array
    {
        return model('Article.Category')->getOptions(['type' => 'news']);
    }
}
