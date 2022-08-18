<?php

namespace ZHK\Tool\Models;

use Dcat\Admin\Traits\ModelTree;

class Navigation extends Model
{
    use ModelTree;

    public $types = ['禁用', '启用'];

    public function getSlugByLink($link)
    {
        return $this->where('link', $link)->value('slug');
    }

    public function getTdkAttribute($value)
    {
        return json_decode($value, true);
    }

    public function setTdkAttribute($value)
    {
        $this->attributes['tdk'] = json_encode($value, 256);
    }

    protected function declares()
    {
        return [];
    }
}
