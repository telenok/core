<?php

namespace Telenok\Core\Model\User;

class Group extends \Telenok\Core\Interfaces\Eloquent\Object\Model {

	protected $ruleList = ['title' => ['required', 'min:1'], 'code' => ['required', 'unique:group,code,:id:,id', 'regex:/^[A-Za-z][A-Za-z0-9_.-]*$/']];
	protected $table = 'group';

	public function setCodeAttribute($value)
	{
		$this->attributes['code'] = str_replace(' ', '', strtolower($value));
	}

    public function role()
    {
        return $this->belongsToMany('\App\Model\Telenok\Security\Role', 'pivot_relation_m2m_role_group', 'role_group', 'role')->withTimestamps();
    }

	public function groupUser()
	{
		return $this->belongsToMany('\App\Model\Telenok\User\User', 'pivot_relation_m2m_group_user', 'group', 'group_user')->withTimestamps();
	}

}
