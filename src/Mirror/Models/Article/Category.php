<?php

namespace App\Models\Article;

use ZHK\Tool\Models\Article\Category as Model;

class Category extends Model
{
    protected $table = 'article_category';

    public function getTopArticle($limit = 10, $id = null)
    {
        $id = empty($id) ? $this->id : $id;

        return model('Article.Article')->where('category_id', $id)->orderBy('order')
            ->OrderBy('published_at', 'desc')->limit($limit)->get();
    }

    public function getArticleByTopVisited($limit = 10, $id = null)
    {
        $id = empty($id) ? $this->id : $id;

        return model('Article.Article')->where('category_id', $id)->orderBy('order')
            ->orderBy('visited', 'desc')->OrderBy('published_at', 'desc')->limit($limit)->get();
    }
}
