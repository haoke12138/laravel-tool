<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use ZHK\Tool\Models\Model as BaseModel;
use ZHK\Tool\Models\Exceptions\NotFoundException;
use ZHK\Tool\Models\Exceptions\ParamException;
use Illuminate\Support\Facades\DB;
use DateTimeInterface;

abstract class Model extends BaseModel
{

}
