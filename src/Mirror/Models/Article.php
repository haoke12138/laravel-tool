<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use ZHK\Tool\Models\Article\Article as Model;

class Article extends Model
{
    use SoftDeletes;

    protected $table = 'article';
    protected $guarded = [];

    public function getByTopVisited($type, $limit = 10)
    {
        return $this->where('article_type', $type)->orderBy('order')->orderBy('visited', 'desc')
            ->OrderBy('published_at', 'desc')->limit($limit)->get();
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
        return $this->orderBy('order', 'asc')->latest('published_at')->where('article_type', $type)->limit($limit)->get();
    }
}
