<?php

namespace ZHK\Tool\Admin\Controllers\Article;

use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use App\Admin\Repositories\Article;
use App\Models\Article\ArticleCategory;
use Dcat\Admin\Http\Controllers\AdminController;

class ArticleController extends AdminController
{
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make($this->getArticleRep(['category']), function (Grid $grid) {
            $grid->model()->where('article_type', 'news')
                ->orderBy('published_at', 'desc')->orderBy('id', 'desc');
//            $grid->model()->orderBy('order')->orderBy('published_at', 'desc')->orderBy('id', 'desc');
            $grid->column('id')->sortable();
            $grid->column('title')->limit(30);
            $grid->column('category.title');
            $grid->column('thumbnail')->image('', 50);
            $grid->column('enable')->using(['未发布', '已发布']);
            $grid->column('source');
            $grid->column('author');
            $grid->column('published_at')->sortable();
            $grid->column('created_at', '创建时间');
            $grid->column('updated_at', '更新时间')->sortable();

            $grid->filter(function (Grid\Filter $filter) {
                $filter->like('title');
                $filter->equal('category_id')->select($this->getCateOptions());
                $filter->like('source');
                $filter->like('author');
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
        return Show::make($id, $this->getArticleRep(['category']), function (Show $show) {
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

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Form::make($this->getArticleRep(), function (Form $form) {
            $form->tab('基本信息', function (Form $form) {
                $form->display('id');
                $form->text('title')->required();
                $form->select('category_id')->options($this->getCateOptions())->required();
                $form->image('thumbnail')->autoUpload()->required()->accept('jpg,png,gif,jpeg')->removable(false)->retainable();
                $form->text('source')->default(session('app.locale') == 'en' ? 'This Site' : '本站');
                $form->text('author')->default(session('app.locale') == 'en' ? 'Anonymous' : '佚名');
                $form->textarea('desc')->rules('max: 255');
                $form->hidden('article_type')->value('news');
//                $form->hidden('type')->default('0');
                $form->radio('type')->options(['否', '是'])->required()->when(1, function ($form) {
                    $form->url('link');
                });
                $form->editor('content');
                $form->switch('enable');
                $form->date('published_at')->default(date('Y-m-d H:i:s'));
//                $form->number('order')->default(100);
//                $form->hidden('width')->value(0);
//                $form->hidden('height')->value(0);

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

    /**
     * @param array $param
     * @return Article
     */
    protected function getArticleRep(array $param = [])
    {
        return repository('Article', $param);
    }

    protected function getCateOptions()
    {
        return model('Article.Category')->getOptions(['type' => 'news']);
    }
}
