<?php

namespace Telenok\Core\Model\System;

class Folder extends \Telenok\Core\Interfaces\Eloquent\Object\Model {

	protected $table = 'folder';
	protected $ruleList = ['title' => ['required', 'min:1'], 'code' => ['required', 'unique:folder,code,:id:,id']];

} 