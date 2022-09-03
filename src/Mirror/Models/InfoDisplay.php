<?php

namespace App\Models;

use ZHK\Tool\Models\InfoDisplay as Model;

class InfoDisplay extends Model
{
    protected $fillable = ['title', 'icon', 'desc', 'type', 'category_id'];
}
