<?php

namespace ZHK\Tool\Services;

use ZHK\Tool\Services\Exceptions\NotFundException;

class Service
{
    public function notFound($message = '')
    {
        return new NotFundException($message);
    }
}
