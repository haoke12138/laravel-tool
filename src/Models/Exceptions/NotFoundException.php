<?php

namespace App\Models\Exceptions;

class NotFoundException extends \Exception
{
    protected $code = 404;
}
