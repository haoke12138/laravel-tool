<?php

namespace ZHK\Tool\Models\Article;

use ZHK\Tool\Models\Model;

class Category extends Model
{
    protected $table = 'article_category';

    public function article()
    {
        return $this->hasMany(Article::class);
    }

    public function getByType($type)
    {
        return $this->where('type', $type)->get();
    }

    protected function declares()
    {
        return [];
    }
}
