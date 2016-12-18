<?php

namespace Telenok\Core\Abstraction\Translation;

/**
 * @class Telenok.Core.Abstraction.Translation.Controller
 */
abstract class Controller
{
    public static $keys = [];

    public static function get($key = '')
    {
        return array_get(static::$keys, $key);
    }
}
