<?php

namespace Telenok\Core\Model\Security;

/**
 * @class Telenok.Core.Model.Security.Permission
 * @extends Telenok.Core.Abstraction.Eloquent.Object.Model
 */
class Permission extends \App\Vendor\Telenok\Core\Abstraction\Eloquent\Object\Model
{
    protected $ruleList = ['title' => ['required', 'min:1'], 'code' => ['required', 'unique:permission,code,:id:,id', 'regex:/^[A-Za-z][A-Za-z0-9_.-]*$/']];
    protected $table = 'permission';

    public function setCodeAttribute($value)
    {
        $this->attributes['code'] = str_replace(' ', '', strtolower($value));
    }

    public function aclPermission()
    {
        return $this->hasMany('\App\Vendor\Telenok\Core\Model\Security\SubjectPermissionResource', 'acl_permission_object_sequence');
    }

    public function permissionTypeObjectType()
    {
        return $this->belongsToMany('\App\Vendor\Telenok\Core\Model\Object\Type', 'pivot_relation_m2m_permission_type_object_type', 'permission_type', 'permission_type_object_type')->withTimestamps();
    }
}
