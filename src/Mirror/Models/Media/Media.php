<?php

namespace App\Models\Media;

use ZHK\Tool\Models\Media\Media as Model;

class Media extends Model
{
    public $timestamps = false;

    public function mediaGroup()
    {
        return $this->belongsTo(model('Media.Group')->getClassname());
    }

    protected function declares()
    {
        return [
            'keyword' => "(file_name like ? or file_ext like ?)",
        ];
    }
}
