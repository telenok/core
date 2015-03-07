<?php

namespace Telenok\Core\Model\Web;

class WidgetGroup extends \Telenok\Core\Interfaces\Eloquent\Object\Model {

	protected $ruleList = ['title' => ['required', 'min:1']];
	protected $table = 'widget_group';

}

