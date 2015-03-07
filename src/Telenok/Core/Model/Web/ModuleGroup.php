<?php

namespace Telenok\Core\Model\Web;

class ModuleGroup extends \Telenok\Core\Interfaces\Eloquent\Object\Model {

	protected $ruleList = ['title' => ['required', 'min:1']];
	protected $table = 'module_group';

}

