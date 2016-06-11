<?php

namespace Telenok\Core\Model\Security;

/**
 * @class Telenok.Core.Model.Security.SubjectPermissionResource
 * @extends Telenok.Core.Abstraction.Eloquent.Object.Model
 */
class SubjectPermissionResource extends \App\Telenok\Core\Abstraction\Eloquent\Object\Model {

    protected $table = 'subject_permission_resource';
    protected $ruleList = ['title' => ['required', 'min:1'], 'code' => ['required', 'unique:subject_permission_resource,code,:id:,id', 'regex:/^[A-Za-z][A-Za-z0-9_.-]*$/']];

    public function aclPermissionPermission()
    {
        return $this->belongsTo('\App\Telenok\Core\Model\Security\Permission', 'acl_permission_object_sequence');
    }

    public function aclResourceObjectSequence()
    {
        return $this->belongsTo('\App\Telenok\Core\Model\Object\Sequence', 'acl_resource_object_sequence');
    }

    public function aclSubjectObjectSequence()
    {
        return $this->belongsTo('\App\Telenok\Core\Model\Object\Sequence', 'acl_subject_object_sequence');
    }

    public function aclPermissionObjectSequence()
    {
        return $this->belongsTo('\App\Telenok\Core\Model\Object\Sequence', 'acl_permission_object_sequence');
    }

}
