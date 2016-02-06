<?php

namespace Telenok\Core\Model\Security;

/**
 * @class Telenok.Core.Model.Security.Resource
 * @extends Telenok.Core.Interfaces.Eloquent.Object.Model
 */
class Resource extends \App\Telenok\Core\Interfaces\Eloquent\Object\Model {

    protected $ruleList = ['title' => ['required', 'min:1'], 'code' => ['required', 'unique:resource,code,:id:,id', 'regex:/^[A-Za-z][A-Za-z0-9_.-]*$/']];
    protected $table = 'resource';

    public function setCodeAttribute($value)
    {
        $this->attributes['code'] = str_replace(' ', '', strtolower($value));
    }

}
