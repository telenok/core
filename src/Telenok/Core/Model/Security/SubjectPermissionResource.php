<?php

namespace Telenok\Core\Model\Security;

class SubjectPermissionResource extends \Telenok\Core\Interfaces\Eloquent\Object\Model {

	protected $table = 'subject_permission_resource';
	protected $ruleList = ['title' => ['required', 'min:1'], 'code' => ['required', 'unique:subject_permission_resource,code,:id:,id', 'regex:/^[A-Za-z][A-Za-z0-9_.-]*$/']];

	public function aclPermissionPermission()
	{
		return $this->belongsTo('\App\Model\Telenok\Security\Permission', 'acl_permission_object_sequence');
	}

	public function aclResourceObjectSequence()
	{
		return $this->belongsTo('\App\Model\Telenok\Object\Sequence', 'acl_resource_object_sequence');
	}

	public function aclSubjectObjectSequence()
	{
		return $this->belongsTo('\App\Model\Telenok\Object\Sequence', 'acl_subject_object_sequence');
	} 

    public function aclPermissionObjectSequence()
    {
        return $this->belongsTo('\App\Model\Telenok\Object\Sequence', 'acl_permission_object_sequence');
    }
}