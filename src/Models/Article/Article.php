<?php

namespace ZHK\Tool\Models\Article;

use App\Models\Model;

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
        return $this->prev = $this->where('article_type', $type)->where('article.id', '<', $this->id)->latest('id')->first();
    }

    public function nexts($type = 'news')
    {
        if ($this->next) {
            return $this->next;
        }

        return $this->next = $this->where('article_type', $type)->where('article.id', '>', $this->id)->first();
    }

    public function getDate()
    {
        return head(explode(' ', $this->published_at));
    }

    public function category()
    {
        return $this->belongsTo(model('Article.Category')->getClassname(), 'category_id', 'id');
    }

    public function getArticle($id, $type = null)
    {
        $conditions = ['enable' => 1, 'id' => $id];
        if ($type) {
            $conditions['article_type'] = $type;
        }
        $article = $this->where($conditions)->first();
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
