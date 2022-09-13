<?php

namespace App\Models;

use ZHK\Tool\Models\Navigation as Model;

class Navigation extends Model
{
    public function setBannerInfoAttribute($v)
    {
        if (is_array($v)) {
            $this->attributes['banner_info'] = json_encode($v, 256);
        }
    }

    public function getBannerInfoAttribute($v)
    {
        return json_decode($v, 1);
    }

    public function getRealLinkAttribute($v)
    {
        return link_path($this->is_external_link ? $this->external_link : $this->link);
    }
}
