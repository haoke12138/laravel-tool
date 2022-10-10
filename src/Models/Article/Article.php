<?php

namespace ZHK\Tool\Models\Article;

use ZHK\Tool\Models\Model;

class Article extends Model
{
    protected $table = 'article';
    protected $guarded = [];
    public $prev = null;
    public $next = null;

    public function previous($type = 'news')
    {
        if ($this->prev) {
            return $this->prev;
        }
        return $this->prev = $this->where('article_type', $type)->latest('id')->first();
    }

    public function nexts($type = 'news')
    {
        if ($this->next) {
            return $this->next;
        }

        return $this->next = $this->where('article_type', $type)->where($prefix . 'article.id', '>', $this->id)->first();
    }

    public function getHome($type = 'news', $limit = 3)
    {
        return $this->whereIn('category_id', $ids)->where('article_type', $type)->latest('published_time')->limit($limit)->get();
    }

    public function getDate()
    {
        return head(explode(' ', $this->published_time));
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
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

    public function searchArticles($request)
    {
        $page = $request->get('page');
        $keyword = $request->get('keyword');
        $limit = 9;
        $articles = $this->where('title', 'like', "%{$keyword}%");
        $count = $articles->count();
        $articles = $articles->offset(($page - 1) * $limit)->limit($limit)->orderBy('order')->latest('published_time')->get();

        return ['countPage' => ceil($count / $limit), 'count' => $count, "articles" => $articles];
    }

    public function getArticle($id)
    {
        $article = $this->where('enable', 1)->where('id', $id)->first();
        if ($article) {
            $article->increment('visited', 1);
            return $article;
        }
    }

    public function getTdkAttribute($value)
    {
        return json_decode($value, true);
    }

    public function setTdkAttribute($value)
    {
        $this->attributes['tdk'] = json_encode($value);
    }

    protected function declares()
    {
        return [
            'titleLike' => 'title like ?'
        ];
    }
}