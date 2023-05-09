<?php

namespace ZHK\Tool\Models\Article;

use App\Models\Model;

class Category extends Model
{
    protected $table = 'article_category';

    public function article()
    {
        return $this->hasMany(model('Article')->getClassname());
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
