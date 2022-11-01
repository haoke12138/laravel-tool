<?php

namespace ZHK\Tool\Common;

class Object_
{
    public function __construct(array $array)
    {
        foreach ($array as $key => $item) {
            $this->$key = $item;
        }
    }
}