<?php

namespace Telenok\Core\Model\Web;

/**
 * @class Telenok.Core.Model.Web.Module
 * @extends Telenok.Core.Abstraction.Eloquent.Object.Model
 */
class Module extends \App\Vendor\Telenok\Core\Abstraction\Eloquent\Object\Model
{
    protected $ruleList = ['title' => ['required', 'min:1']];
    protected $table = 'module';
}
