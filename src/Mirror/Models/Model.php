<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use ZHK\Tool\Models\Model as BaseModel;

abstract class Model extends BaseModel
{
    use HasFactory;
}
