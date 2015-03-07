<?php

namespace Telenok\Core\Filter\Acl\Resource\DirectRight;

class Controller extends \Telenok\Core\Interfaces\Filter\Acl\Resource\Controller {

    public $key = 'direct-right'; 

    public function filterCan($queryCommon, $queryWhere, $resource, $permission, $subject)
    {
		$now = \Carbon\Carbon::now();
		$sequence = new \App\Model\Telenok\Object\Sequence();
		$spr = new \App\Model\Telenok\Security\SubjectPermissionResource();
		
		// verify user's right via SubjectPermissionResource on resource with code like "object.some_object_type_code" eg "object.object_type"
		if ($subject instanceof \Telenok\Core\Model\User\User)
		{
			$role = new \App\Model\Telenok\Security\Role();
			$group = new \App\Model\Telenok\User\Group();
 
			$queryCommon->leftJoin($spr->getTable() . ' as spr_permission_user_filter_direct_right', function($join) use ($spr, $sequence, $permission, $now)
			{
				$join->on($sequence->getTable() . '.id', '=', 'spr_permission_user_filter_direct_right.acl_resource_object_sequence');
				$join->where('spr_permission_user_filter_direct_right.acl_permission_object_sequence', '=', $permission->getKey());
				$join->on('spr_permission_user_filter_direct_right.' . $spr->getDeletedAtColumn(), ' is ', \DB::raw("null"));
				$join->where('spr_permission_user_filter_direct_right.active', '=', 1);
				$join->where('spr_permission_user_filter_direct_right.active_at_start', '<=', $now);
				$join->where('spr_permission_user_filter_direct_right.active_at_end', '>=', $now);
			}); 

			$queryCommon->leftJoin($role->getTable() . ' as role_permission_user_filter_direct_right', function($join) use ($role, $now)
			{
				$join->on('spr_permission_user_filter_direct_right.acl_subject_object_sequence', '=', 'role_permission_user_filter_direct_right.id');
				$join->on('role_permission_user_filter_direct_right.' . $role->getDeletedAtColumn(), ' is ', \DB::raw("null"));
				$join->where('role_permission_user_filter_direct_right.active', '=', 1);
				$join->where('role_permission_user_filter_direct_right.active_at_start', '<=', $now);
				$join->where('role_permission_user_filter_direct_right.active_at_end', '>=', $now);
			}); 

			$queryCommon->leftJoin('pivot_relation_m2m_role_group as pivot_relation_m2m_role_group_filter_direct_right', function($join)
			{
				$join->on('role_permission_user_filter_direct_right.id', '=', 'pivot_relation_m2m_role_group_filter_direct_right.role');
			}); 

			$queryCommon->leftJoin($group->getTable() . ' as group_permission_user_filter_direct_right', function($join) use ($group, $now)
			{
				$join->on('pivot_relation_m2m_role_group_filter_direct_right.role_group', '=', 'group_permission_user_filter_direct_right.id');
				$join->on('group_permission_user_filter_direct_right.' . $group->getDeletedAtColumn(), ' is ', \DB::raw("null"));
				$join->where('group_permission_user_filter_direct_right.active', '=', 1);
				$join->where('group_permission_user_filter_direct_right.active_at_start', '<=', $now);
				$join->where('group_permission_user_filter_direct_right.active_at_end', '>=', $now);
			}); 

			$queryCommon->leftJoin('pivot_relation_m2m_group_user as pivot_relation_m2m_group_user_filter_direct_right', function($join)
			{
				$join->on('group_permission_user_filter_direct_right.id', '=', 'pivot_relation_m2m_group_user_filter_direct_right.group');
			}); 

			$queryCommon->leftJoin($subject->getTable() . ' as user_permission_user_filter_direct_right', function($join) use ($subject, $now)
			{
				$join->on('pivot_relation_m2m_group_user_filter_direct_right.group_user', '=', 'user_permission_user_filter_direct_right.id');
				$join->on('user_permission_user_filter_direct_right.' . $subject->getDeletedAtColumn(), ' is ', \DB::raw("null"));
				$join->where('user_permission_user_filter_direct_right.active', '=', 1);
				$join->where('user_permission_user_filter_direct_right.active_at_start', '<=', $now);
				$join->where('user_permission_user_filter_direct_right.active_at_end', '>=', $now);
				$join->where('user_permission_user_filter_direct_right.id', '=', $subject->getKey());
			}); 
			  
            $queryWhere->OrWhereNotNull('user_permission_user_filter_direct_right.id');
		}
		
		// verify direct right of subject via SubjectPermissionResource on resource
		$queryCommon->leftJoin($spr->getTable() . ' as spr_filter_direct_right', function($join) use ($spr, $sequence, $subject, $permission, $now)
		{
			$join->on($sequence->getTable() . '.id', '=', 'spr_filter_direct_right.acl_resource_object_sequence');
			$join->where('spr_filter_direct_right.acl_permission_object_sequence', '=', $permission->getKey());
			$join->where('spr_filter_direct_right.acl_subject_object_sequence', '=', $subject->getKey());
			$join->on('spr_filter_direct_right.' . $spr->getDeletedAtColumn(), ' is ', \DB::raw("null"));
			$join->where('spr_filter_direct_right.active', '=', 1);
			$join->where('spr_filter_direct_right.active_at_start', '<=', $now);
			$join->where('spr_filter_direct_right.active_at_end', '>=', $now);
		}); 

		$queryWhere->OrWhereNotNull('spr_filter_direct_right.id');
	}
	
    public function filter($queryCommon, $queryWhere, $resource, $permission, $subject)
    {
		$now = \Carbon\Carbon::now();
		$spr = new \App\Model\Telenok\Security\SubjectPermissionResource();
		$sequence = new \App\Model\Telenok\Object\Sequence();
		
		// verify user's right via SubjectPermissionResource on resource with code like "object.some_object_type_code" eg "object.object_type"
		if ($subject instanceof \Telenok\Core\Model\User\User)
		{
			$role = new \App\Model\Telenok\Security\Role();
			$group = new \App\Model\Telenok\User\Group();
 
			$queryCommon->leftJoin($spr->getTable() . ' as spr_permission_user_filter_direct_right', function($join) use ($spr, $permission, $now)
			{
				$join->on('osequence.id', '=', 'spr_permission_user_filter_direct_right.acl_resource_object_sequence');
				$join->where('spr_permission_user_filter_direct_right.acl_permission_object_sequence', '=', $permission->getKey());
				$join->on('spr_permission_user_filter_direct_right.' . $spr->getDeletedAtColumn(), ' is ', \DB::raw("null"));
				$join->where('spr_permission_user_filter_direct_right.active', '=', 1);
				$join->where('spr_permission_user_filter_direct_right.active_at_start', '<=', $now);
				$join->where('spr_permission_user_filter_direct_right.active_at_end', '>=', $now);
			}); 

			$queryCommon->leftJoin($role->getTable() . ' as role_permission_user_filter_direct_right', function($join) use ($role, $now)
			{
				$join->on('spr_permission_user_filter_direct_right.acl_subject_object_sequence', '=', 'role_permission_user_filter_direct_right.id');
				$join->on('role_permission_user_filter_direct_right.' . $role->getDeletedAtColumn(), ' is ', \DB::raw("null"));
				$join->where('role_permission_user_filter_direct_right.active', '=', 1);
				$join->where('role_permission_user_filter_direct_right.active_at_start', '<=', $now);
				$join->where('role_permission_user_filter_direct_right.active_at_end', '>=', $now);
			}); 

			$queryCommon->leftJoin('pivot_relation_m2m_role_group as pivot_relation_m2m_role_group_filter_direct_right', function($join)
			{
				$join->on('role_permission_user_filter_direct_right.id', '=', 'pivot_relation_m2m_role_group_filter_direct_right.role');
			}); 

			$queryCommon->leftJoin($group->getTable() . ' as group_permission_user_filter_direct_right', function($join) use ($group, $now)
			{
				$join->on('pivot_relation_m2m_role_group_filter_direct_right.role_group', '=', 'group_permission_user_filter_direct_right.id');
				$join->on('group_permission_user_filter_direct_right.' . $group->getDeletedAtColumn(), ' is ', \DB::raw("null"));
				$join->where('group_permission_user_filter_direct_right.active', '=', 1);
				$join->where('group_permission_user_filter_direct_right.active_at_start', '<=', $now);
				$join->where('group_permission_user_filter_direct_right.active_at_end', '>=', $now);
			}); 

			$queryCommon->leftJoin('pivot_relation_m2m_group_user as pivot_relation_m2m_group_user_filter_direct_right', function($join)
			{
				$join->on('group_permission_user_filter_direct_right.id', '=', 'pivot_relation_m2m_group_user_filter_direct_right.group');
			}); 

			$queryCommon->leftJoin($subject->getTable() . ' as user_permission_user_filter_direct_right', function($join) use ($subject, $now)
			{
				$join->on('pivot_relation_m2m_group_user_filter_direct_right.group_user', '=', 'user_permission_user_filter_direct_right.id');
				$join->on('user_permission_user_filter_direct_right.' . $subject->getDeletedAtColumn(), ' is ', \DB::raw("null"));
				$join->where('user_permission_user_filter_direct_right.active', '=', 1);
				$join->where('user_permission_user_filter_direct_right.active_at_start', '<=', $now);
				$join->where('user_permission_user_filter_direct_right.active_at_end', '>=', $now);
				$join->where('user_permission_user_filter_direct_right.id', '=', $subject->getKey());
			}); 
			  
            $queryWhere->OrWhereNotNull('user_permission_user_filter_direct_right.id');
		}

		// verify direct right of subject via SubjectPermissionResource on resource
		$queryCommon->leftJoin($spr->getTable() . ' as spr_filter_direct_right', function($join) use ($spr, $subject, $permission, $now)
		{
			$join->on('osequence.id', '=', 'spr_filter_direct_right.acl_resource_object_sequence');
			$join->where('spr_filter_direct_right.acl_permission_object_sequence', '=', $permission->getKey());
			$join->where('spr_filter_direct_right.acl_subject_object_sequence', '=', $subject->getKey());
			$join->on('spr_filter_direct_right.' . $spr->getDeletedAtColumn(), ' is ', \DB::raw("null"));
			$join->where('spr_filter_direct_right.active', '=', 1);
			$join->where('spr_filter_direct_right.active_at_start', '<=', $now);
			$join->where('spr_filter_direct_right.active_at_end', '>=', $now);
		}); 

		$queryWhere->OrWhereNotNull('spr_filter_direct_right.id');
	}
}

