<?php

namespace App\Services;

use App\Services\Exceptions\NotFundException;

class Service
{
    public function notFound($message = '')
    {
        return new NotFundException($message);
    }
}
