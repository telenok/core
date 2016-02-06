<?php

namespace Telenok\Core\Model\Web;

/**
 * @class Telenok.Core.Model.Web.Module
 * @extends Telenok.Core.Interfaces.Eloquent.Object.Model
 */
class Module extends \App\Telenok\Core\Interfaces\Eloquent\Object\Model {

    protected $ruleList = ['title' => ['required', 'min:1']];
    protected $table = 'module';

}
