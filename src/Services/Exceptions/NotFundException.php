<?php

namespace ZHK\Tool\Services\Exceptions;

class NotFundException extends \Exception
{
    protected $code = 404;

    protected $message = '找不到资源!';
}
