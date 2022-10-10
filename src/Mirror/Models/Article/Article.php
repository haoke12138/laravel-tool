<?php

namespace App\Models\Article;

use ZHK\Tool\Models\Article\Article as Model;

class Article extends Model
{
    protected $table = 'article';
    protected $guarded = [];

    public function getHome($type = 'news', $limit = 3)
    {
        return $this->where('article_type', $type)->latest('published_time')->limit($limit)->get();
    }

    public function moreArticles($cateId, $limit = 6, $type = null)
    {
        $article = $this->where('category_id', $cateId)->orderBy('order')->orderBy('created_at', 'desc')->limit($limit);
        if ($type) {
            $article = $article->where('type', $type);
        }
        return $article->get();
    }

    public function topArticles($type = 'news', $limit = 10)
    {
        return $this->orderBy('order')->latest('published_time')->where('type', $type)->limit($limit)->get();
    }
}
