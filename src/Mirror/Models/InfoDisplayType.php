<?php

namespace App\Models;

use ZHK\Tool\Models\InfoDisplayType as Model;

class InfoDisplayType extends Model
{
    public function getJsonInfoAttribute($key)
    {
        return json_decode($key, 1);
    }

    public function setJsonInfoAttribute($key)
    {
        $this->attributes['json_info'] = json_encode($key, 256);
    }
}
