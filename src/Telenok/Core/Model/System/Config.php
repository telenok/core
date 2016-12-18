<?php

namespace Telenok\Core\Model\System;

use Telenok\Core\Event\CompileConfig;

/**
 * @class Telenok.Core.Model.System.Config
 * @extends Telenok.Core.Abstraction.Eloquent.Object.Model
 */
class Config extends \App\Vendor\Telenok\Core\Abstraction\Eloquent\Object\Model
{
    protected $table = 'config';

    public static function boot()
    {
        parent::boot();

        static::saved(function ($model) {
            app('events')->fire(new CompileConfig());
        });

        static::deleted(function ($model) {
            app('events')->fire(new CompileConfig());
        });
    }
}
