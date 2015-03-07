<?php

namespace Telenok\Core\Interfaces\Translation;

abstract class Controller {

    public static $keys = [];
    
    public static function get($key = '')
    {
        return array_get(static::$keys, $key);
    }
}

