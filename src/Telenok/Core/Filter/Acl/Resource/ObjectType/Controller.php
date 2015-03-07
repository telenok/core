<?php

namespace Telenok\Core\Filter\Acl\Resource\ObjectType;

class Controller extends \Telenok\Core\Interfaces\Filter\Acl\Resource\Controller {

    public $key = 'object-type'; 

    public function filterCan($queryCommon, $queryWhere, $resource, $permission, $subject)
    {
		$resourceType = new \App\Model\Telenok\Security\Resource();
		$spr = new \App\Model\Telenok\Security\SubjectPermissionResource();
		$now = \Carbon\Carbon::now();
		
		$queryCommon->leftJoin($resourceType->getTable() . ' as resource_type_permission_user_filter_object_type', function($join) use ($now, $resourceType)
		{
			$join->on(\DB::raw('CONCAT("object_type.", otype.code)'), '=', 'resource_type_permission_user_filter_object_type.code');
			$join->on('resource_type_permission_user_filter_object_type.' . $resourceType->getDeletedAtColumn(), ' is ', \DB::raw("null"));
			$join->where('resource_type_permission_user_filter_object_type.active', '=', 1);
			$join->where('resource_type_permission_user_filter_object_type.active_at_start', '<=', $now);
			$join->where('resource_type_permission_user_filter_object_type.active_at_end', '>=', $now);
		}); 

		// verify user's right via SubjectPermissionResource on resource with code like "object.some_object_type_code" eg "object.object_type"
		if ($subject instanceof \Telenok\Core\Model\User\User)
		{
			$role = new \App\Model\Telenok\Security\Role();
			$group = new \App\Model\Telenok\User\Group();
 
			$queryCommon->leftJoin($spr->getTable() . ' as spr_permission_user_filter_object_type', function($join) use ($spr, $permission, $now)
			{
				$join->on('resource_type_permission_user_filter_object_type.id', '=', 'spr_permission_user_filter_object_type.acl_resource_object_sequence');
				$join->where('spr_permission_user_filter_object_type.acl_permission_object_sequence', '=', $permission->getKey());
				$join->on('spr_permission_user_filter_object_type.' . $spr->getDeletedAtColumn(), ' is ', \DB::raw("null"));
				$join->where('spr_permission_user_filter_object_type.active', '=', 1);
				$join->where('spr_permission_user_filter_object_type.active_at_start', '<=', $now);
				$join->where('spr_permission_user_filter_object_type.active_at_end', '>=', $now);
			}); 

			$queryCommon->leftJoin($role->getTable() . ' as role_permission_user_filter_object_type', function($join) use ($role, $now)
			{
				$join->on('spr_permission_user_filter_object_type.acl_subject_object_sequence', '=', 'role_permission_user_filter_object_type.id');
				$join->on('role_permission_user_filter_object_type.' . $role->getDeletedAtColumn(), ' is ', \DB::raw("null"));
				$join->where('role_permission_user_filter_object_type.active', '=', 1);
				$join->where('role_permission_user_filter_object_type.active_at_start', '<=', $now);
				$join->where('role_permission_user_filter_object_type.active_at_end', '>=', $now);
			}); 

			$queryCommon->leftJoin('pivot_relation_m2m_role_group as pivot_relation_m2m_role_group_filter_object_type', function($join)
			{
				$join->on('role_permission_user_filter_object_type.id', '=', 'pivot_relation_m2m_role_group_filter_object_type.role');
			}); 

			$queryCommon->leftJoin($group->getTable() . ' as group_permission_user_filter_object_type', function($join) use ($group, $now)
			{
				$join->on('pivot_relation_m2m_role_group_filter_object_type.role_group', '=', 'group_permission_user_filter_object_type.id');
				$join->on('group_permission_user_filter_object_type.' . $group->getDeletedAtColumn(), ' is ', \DB::raw("null"));
				$join->where('group_permission_user_filter_object_type.active', '=', 1);
				$join->where('group_permission_user_filter_object_type.active_at_start', '<=', $now);
				$join->where('group_permission_user_filter_object_type.active_at_end', '>=', $now);
			}); 

			$queryCommon->leftJoin('pivot_relation_m2m_group_user as pivot_relation_m2m_group_user_filter_object_type', function($join)
			{
				$join->on('group_permission_user_filter_object_type.id', '=', 'pivot_relation_m2m_group_user_filter_object_type.group');
			}); 

			$queryCommon->leftJoin($subject->getTable() . ' as user_permission_user_filter_object_type', function($join) use ($subject, $now)
			{
				$join->on('pivot_relation_m2m_group_user_filter_object_type.group_user', '=', 'user_permission_user_filter_object_type.id');
				$join->on('user_permission_user_filter_object_type.' . $subject->getDeletedAtColumn(), ' is ', \DB::raw("null"));
				$join->where('user_permission_user_filter_object_type.active', '=', 1);
				$join->where('user_permission_user_filter_object_type.active_at_start', '<=', $now);
				$join->where('user_permission_user_filter_object_type.active_at_end', '>=', $now);
			}); 
			  
            $queryWhere->OrWhereNotNull('user_permission_user_filter_object_type.id');
		}

		// verify direct right of subject via SubjectPermissionResource on resource with code like "object.some_object_type_code" eg "object.object_type"
		$queryCommon->leftJoin($spr->getTable() . ' as spr_filter_object_type_direct', function($join) use ($spr, $subject, $permission, $now)
		{
			$join->on('resource_type_permission_user_filter_object_type.id', '=', 'spr_filter_object_type_direct.acl_resource_object_sequence');
			$join->where('spr_filter_object_type_direct.acl_permission_object_sequence', '=', $permission->getKey());
			$join->where('spr_filter_object_type_direct.acl_subject_object_sequence', '=', $subject->getKey());
			$join->on('spr_filter_object_type_direct.' . $spr->getDeletedAtColumn(), ' is ', \DB::raw("null"));
			$join->where('spr_filter_object_type_direct.active', '=', 1);
			$join->where('spr_filter_object_type_direct.active_at_start', '<=', $now);
			$join->where('spr_filter_object_type_direct.active_at_end', '>=', $now);
		});

		$queryWhere->OrWhereNotNull('spr_filter_object_type_direct.id');
	}

    public function filter($queryCommon, $queryWhere, $resource, $permission, $subject)
    {
		$resourceType = new \App\Model\Telenok\Security\Resource();
		$spr = new \App\Model\Telenok\Security\SubjectPermissionResource();
		$now = \Carbon\Carbon::now();

		$queryCommon->leftJoin($resourceType->getTable() . ' as resource_type_permission_user_filter_object_type', function($join) use ($now, $resourceType)
		{
			$join->on(\DB::raw('CONCAT("object_type.", otype.code)'), '=', 'resource_type_permission_user_filter_object_type.code');
			$join->on('resource_type_permission_user_filter_object_type.' . $resourceType->getDeletedAtColumn(), ' is ', \DB::raw("null"));
			$join->where('resource_type_permission_user_filter_object_type.active', '=', 1);
			$join->where('resource_type_permission_user_filter_object_type.active_at_start', '<=', $now);
			$join->where('resource_type_permission_user_filter_object_type.active_at_end', '>=', $now);
		}); 
		
		// verify user's right via SubjectPermissionResource on resource with code like "object.some_object_type_code" eg "object.object_type"
		if ($subject instanceof \Telenok\Core\Model\User\User)
		{
			$role = new \App\Model\Telenok\Security\Role();
			$group = new \App\Model\Telenok\User\Group();
 
			$queryCommon->leftJoin($spr->getTable() . ' as spr_permission_user_filter_object_type', function($join) use ($spr, $permission, $now)
			{
				$join->on('resource_type_permission_user_filter_object_type.id', '=', 'spr_permission_user_filter_object_type.acl_resource_object_sequence');
				$join->where('spr_permission_user_filter_object_type.acl_permission_object_sequence', '=', $permission->getKey());
				$join->on('spr_permission_user_filter_object_type.' . $spr->getDeletedAtColumn(), ' is ', \DB::raw("null"));
				$join->where('spr_permission_user_filter_object_type.active', '=', 1);
				$join->where('spr_permission_user_filter_object_type.active_at_start', '<=', $now);
				$join->where('spr_permission_user_filter_object_type.active_at_end', '>=', $now);
			}); 

			$queryCommon->leftJoin($role->getTable() . ' as role_permission_user_filter_object_type', function($join) use ($role, $now)
			{
				$join->on('spr_permission_user_filter_object_type.acl_subject_object_sequence', '=', 'role_permission_user_filter_object_type.id');
				$join->on('role_permission_user_filter_object_type.' . $role->getDeletedAtColumn(), ' is ', \DB::raw("null"));
				$join->where('role_permission_user_filter_object_type.active', '=', 1);
				$join->where('role_permission_user_filter_object_type.active_at_start', '<=', $now);
				$join->where('role_permission_user_filter_object_type.active_at_end', '>=', $now);
			}); 

			$queryCommon->leftJoin('pivot_relation_m2m_role_group as pivot_relation_m2m_role_group_filter_object_type', function($join)
			{
				$join->on('role_permission_user_filter_object_type.id', '=', 'pivot_relation_m2m_role_group_filter_object_type.role');
			}); 

			$queryCommon->leftJoin($group->getTable() . ' as group_permission_user_filter_object_type', function($join) use ($group, $now)
			{
				$join->on('pivot_relation_m2m_role_group_filter_object_type.role_group', '=', 'group_permission_user_filter_object_type.id');
				$join->on('group_permission_user_filter_object_type.' . $group->getDeletedAtColumn(), ' is ', \DB::raw("null"));
				$join->where('group_permission_user_filter_object_type.active', '=', 1);
				$join->where('group_permission_user_filter_object_type.active_at_start', '<=', $now);
				$join->where('group_permission_user_filter_object_type.active_at_end', '>=', $now);
			}); 

			$queryCommon->leftJoin('pivot_relation_m2m_group_user as pivot_relation_m2m_group_user_filter_object_type', function($join)
			{
				$join->on('group_permission_user_filter_object_type.id', '=', 'pivot_relation_m2m_group_user_filter_object_type.group');
			}); 

			$queryCommon->leftJoin($subject->getTable() . ' as user_permission_user_filter_object_type', function($join) use ($subject, $now)
			{
				$join->on('pivot_relation_m2m_group_user_filter_object_type.group_user', '=', 'user_permission_user_filter_object_type.id');
				$join->on('user_permission_user_filter_object_type.' . $subject->getDeletedAtColumn(), ' is ', \DB::raw("null"));
				$join->where('user_permission_user_filter_object_type.active', '=', 1);
				$join->where('user_permission_user_filter_object_type.active_at_start', '<=', $now);
				$join->where('user_permission_user_filter_object_type.active_at_end', '>=', $now);
			}); 
			  
            $queryWhere->OrWhereNotNull('user_permission_user_filter_object_type.id');
		}
		
		// verify direct right of subject via SubjectPermissionResource on resource with code like "object.some_object_type_code" eg "object.object_type"
		$queryCommon->leftJoin($spr->getTable() . ' as spr_filter_object_type_direct', function($join) use ($spr, $subject, $permission, $now)
		{
			$join->on('resource_type_permission_user_filter_object_type.id', '=', 'spr_filter_object_type_direct.acl_resource_object_sequence');
			$join->where('spr_filter_object_type_direct.acl_permission_object_sequence', '=', $permission->getKey());
			$join->where('spr_filter_object_type_direct.acl_subject_object_sequence', '=', $subject->getKey());
			$join->on('spr_filter_object_type_direct.' . $spr->getDeletedAtColumn(), ' is ', \DB::raw("null"));
			$join->where('spr_filter_object_type_direct.active', '=', 1);
			$join->where('spr_filter_object_type_direct.active_at_start', '<=', $now);
			$join->where('spr_filter_object_type_direct.active_at_end', '>=', $now);
		});

		$queryWhere->OrWhereNotNull('spr_filter_object_type_direct.id');
    }
}

