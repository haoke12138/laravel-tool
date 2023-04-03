<?php

namespace ZHK\Tool\Models\Media;

use App\Models\Model;

class Group extends Model
{
    protected $table = 'media_group';

    public function media()
    {
        return $this->hasMany(model('Media.Media')->getClassname());
    }

    protected $fillable = ['admin_id', 'name'];

    protected function declares()
    {
        return [];
    }
}
