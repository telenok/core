<?php

namespace Telenok\Core\Interfaces\Translation;

/**
 * @class Telenok.Core.Interfaces.Translation.Controller
 */
abstract class Controller {

    public static $keys = [];

    public static function get($key = '')
    {
        return array_get(static::$keys, $key);
    }

}
