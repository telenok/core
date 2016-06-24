<?php

namespace Telenok\Core\Model\System;
use Telenok\Core\Event\CompileSetting;

/**
 * @class Telenok.Core.Model.System.Setting
 * @extends Telenok.Core.Abstraction.Eloquent.Object.Model
 */
class Setting extends \App\Vendor\Telenok\Core\Abstraction\Eloquent\Object\Model {

    protected $table = 'setting';

    public static function boot()
    {
        parent::boot();

        static::saved(function($model)
        {
            app('events')->fire(new CompileSetting());
        });

        static::deleted(function($model)
        {
            app('events')->fire(new CompileSetting());
        });
    }

}
