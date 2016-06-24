<?php

namespace Telenok\Core\Model\Web;

/**
 * @class Telenok.Core.Model.Web.ModuleGroup
 * @extends Telenok.Core.Abstraction.Eloquent.Object.Model
 */
class ModuleGroup extends \App\Vendor\Telenok\Core\Abstraction\Eloquent\Object\Model {

    protected $ruleList = ['title' => ['required', 'min:1']];
    protected $table = 'module_group';

}
