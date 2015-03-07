<?php

namespace Telenok\Core\Model\Security;

class Role extends \Telenok\Core\Interfaces\Eloquent\Object\Model {

	protected $ruleList = ['title' => ['required', 'min:1']/* , 'code' => ['required', 'unique:role,code,:id:,id', 'regex:/^[A-Za-z][A-Za-z0-9_.-]*$/'] */];
	protected $table = 'role';

	public function setCodeAttribute($value)
	{
		$this->attributes['code'] = str_replace(' ', '', strtolower($value));
	}

    public function roleGroup()
    {
        return $this->belongsToMany('\App\Model\Telenok\User\Group', 'pivot_relation_m2m_role_group', 'role', 'role_group')->withTimestamps();
    }
}