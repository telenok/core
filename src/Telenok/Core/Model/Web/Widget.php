<?php

namespace Telenok\Core\Model\Web;

class Widget extends \Telenok\Core\Interfaces\Eloquent\Object\Model {

	protected $ruleList = ['title' => ['required', 'min:1']];
	protected $table = 'widget';

}

