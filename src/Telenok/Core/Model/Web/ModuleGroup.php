<?php

namespace Telenok\Core\Model\Web;

/**
 * @class Telenok.Core.Model.Web.ModuleGroup
 * @extends Telenok.Core.Interfaces.Eloquent.Object.Model
 */
class ModuleGroup extends \App\Telenok\Core\Interfaces\Eloquent\Object\Model {

    protected $ruleList = ['title' => ['required', 'min:1']];
    protected $table = 'module_group';

}
