<?php

namespace Telenok\Core\Model\System;

/**
 * @class Telenok.Core.Model.System.Folder
 * @extends Telenok.Core.Abstraction.Eloquent.Object.Model
 */
class Folder extends \App\Vendor\Telenok\Core\Abstraction\Eloquent\Object\Model {

    protected $table = 'folder';
    protected $ruleList = ['title' => ['required', 'min:1'], 'code' => ['required', 'unique:folder,code,:id:,id']];

}
