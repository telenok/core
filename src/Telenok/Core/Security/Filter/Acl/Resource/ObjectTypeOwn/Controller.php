<?php namespace Telenok\Core\Security\Filter\Acl\Resource\ObjectTypeOwn;

class Controller extends \Telenok\Core\Interfaces\Security\Filter\Acl\Resource\Controller {

    protected $key = 'object-type-own';
	
    public function filterCan($queryCommon, $queryWhere, $resource, $permission, $subject)
    {
		$resourceType = new \App\Telenok\Core\Model\Security\Resource();
		$sequence = new \App\Telenok\Core\Model\Object\Sequence();
		$spr = new \App\Telenok\Core\Model\Security\SubjectPermissionResource();
        $r = range_minutes($this->getCacheMinutes());

		$queryCommon->leftJoin($resourceType->getTable() . ' as resource_type_permission_user_filter_object_type_own', function($join) use ($r, $resourceType)
		{
			$join->on(\DB::raw('CONCAT("object_type.", otype.code, ".own")'), '=', 'resource_type_permission_user_filter_object_type_own.code');
			$join->on('resource_type_permission_user_filter_object_type_own.' . $resourceType->getDeletedAtColumn(), ' is ', \DB::raw("null"));
			$join->where('resource_type_permission_user_filter_object_type_own.active', '=', 1);
			$join->where('resource_type_permission_user_filter_object_type_own.active_at_start', '<=', $r);
			$join->where('resource_type_permission_user_filter_object_type_own.active_at_end', '>=', $r);
		}); 
		
		// verify user's right via SubjectPermissionResource on resource with code like "object.some_object_type_code" eg "object.object_type"
		if ($subject instanceof \Telenok\Core\Model\User\User)
		{
			$role = new \App\Telenok\Core\Model\Security\Role();
			$group = new \App\Telenok\Core\Model\User\Group();
 
			$queryCommon->leftJoin($spr->getTable() . ' as spr_permission_user_filter_object_type_own', function($join) use ($spr, $permission, $r)
			{
				$join->on('resource_type_permission_user_filter_object_type_own.id', '=', 'spr_permission_user_filter_object_type_own.acl_resource_object_sequence');
				$join->where('spr_permission_user_filter_object_type_own.acl_permission_object_sequence', '=', $permission->getKey());
				$join->on('spr_permission_user_filter_object_type_own.' . $spr->getDeletedAtColumn(), ' is ', \DB::raw("null"));
				$join->where('spr_permission_user_filter_object_type_own.active', '=', 1);
				$join->where('spr_permission_user_filter_object_type_own.active_at_start', '<=', $r);
				$join->where('spr_permission_user_filter_object_type_own.active_at_end', '>=', $r);
			}); 

			$queryCommon->leftJoin($role->getTable() . ' as role_permission_user_filter_object_type_own', function($join) use ($role, $r)
			{
				$join->on('spr_permission_user_filter_object_type_own.acl_subject_object_sequence', '=', 'role_permission_user_filter_object_type_own.id');
				$join->on('role_permission_user_filter_object_type_own.' . $role->getDeletedAtColumn(), ' is ', \DB::raw("null"));
				$join->where('role_permission_user_filter_object_type_own.active', '=', 1);
				$join->where('role_permission_user_filter_object_type_own.active_at_start', '<=', $r);
				$join->where('role_permission_user_filter_object_type_own.active_at_end', '>=', $r);
			}); 

			$queryCommon->leftJoin('pivot_relation_m2m_role_group as pivot_relation_m2m_role_group_filter_object_type_own', function($join)
			{
				$join->on('role_permission_user_filter_object_type_own.id', '=', 'pivot_relation_m2m_role_group_filter_object_type_own.role');
			}); 

			$queryCommon->leftJoin($group->getTable() . ' as group_permission_user_filter_object_type_own', function($join) use ($group, $r)
			{
				$join->on('pivot_relation_m2m_role_group_filter_object_type_own.role_group', '=', 'group_permission_user_filter_object_type_own.id');
				$join->on('group_permission_user_filter_object_type_own.' . $group->getDeletedAtColumn(), ' is ', \DB::raw("null"));
				$join->where('group_permission_user_filter_object_type_own.active', '=', 1);
				$join->where('group_permission_user_filter_object_type_own.active_at_start', '<=', $r);
				$join->where('group_permission_user_filter_object_type_own.active_at_end', '>=', $r);
			}); 

			$queryCommon->leftJoin('pivot_relation_m2m_group_user as pivot_relation_m2m_group_user_filter_object_type_own', function($join)
			{
				$join->on('group_permission_user_filter_object_type_own.id', '=', 'pivot_relation_m2m_group_user_filter_object_type_own.group');
			}); 

			$queryCommon->leftJoin($subject->getTable() . ' as user_permission_user_filter_object_type_own', function($join) use ($subject, $r, $sequence)
			{
				$join->on('pivot_relation_m2m_group_user_filter_object_type_own.group_user', '=', 'user_permission_user_filter_object_type_own.id');
				$join->on('user_permission_user_filter_object_type_own.' . $subject->getDeletedAtColumn(), ' is ', \DB::raw("null"));
				$join->where('user_permission_user_filter_object_type_own.id', '=', $subject->getKey());
				$join->where('user_permission_user_filter_object_type_own.active', '=', 1);
				$join->where('user_permission_user_filter_object_type_own.active_at_start', '<=', $r);
				$join->where('user_permission_user_filter_object_type_own.active_at_end', '>=', $r);
				$join->on('user_permission_user_filter_object_type_own.id', '=', $sequence->getTable() . '.created_by_user');
			}); 

			$queryWhere->OrWhereNotNull('user_permission_user_filter_object_type_own.id');
		}
		
		// verify direct right of subject via SubjectPermissionResource on resource with code like "object.some_object_type_code" eg "object.object_type"
		$queryCommon->leftJoin($spr->getTable() . ' as spr_filter_object_type_own_direct', function($join) use ($spr, $subject, $permission, $r, $sequence)
		{
			$join->on('resource_type_permission_user_filter_object_type_own.id', '=', 'spr_filter_object_type_own_direct.acl_resource_object_sequence');
			$join->where('spr_filter_object_type_own_direct.acl_permission_object_sequence', '=', $permission->getKey());
			$join->where($sequence->getTable() . '.created_by_user', '=', $subject->getKey());
			$join->where('spr_filter_object_type_own_direct.acl_subject_object_sequence', '=', $subject->getKey());
			$join->on('spr_filter_object_type_own_direct.' . $spr->getDeletedAtColumn(), ' is ', \DB::raw("null"));
			$join->where('spr_filter_object_type_own_direct.active', '=', 1);
			$join->where('spr_filter_object_type_own_direct.active_at_start', '<=', $r);
			$join->where('spr_filter_object_type_own_direct.active_at_end', '>=', $r);
		});

		$queryWhere->OrWhereNotNull('spr_filter_object_type_own_direct.id');
	}

    public function filter($queryCommon, $queryWhere, $resource, $permission, $subjectCollection)
    {
		$resourceType = new \App\Telenok\Core\Model\Security\Resource();
		$sequence = new \App\Telenok\Core\Model\Object\Sequence();
		$spr = new \App\Telenok\Core\Model\Security\SubjectPermissionResource();
        $r = range_minutes($this->getCacheMinutes());

		$queryCommon->leftJoin($resourceType->getTable() . ' as resource_type_permission_user_filter_object_type_own', function($join) use ($r, $resourceType)
		{
			$join->on(\DB::raw('CONCAT("object_type.", otype.code, ".own")'), '=', 'resource_type_permission_user_filter_object_type_own.code');
			$join->on('resource_type_permission_user_filter_object_type_own.' . $resourceType->getDeletedAtColumn(), ' is ', \DB::raw("null"));
			$join->where('resource_type_permission_user_filter_object_type_own.active', '=', 1);
			$join->where('resource_type_permission_user_filter_object_type_own.active_at_start', '<=', $r);
			$join->where('resource_type_permission_user_filter_object_type_own.active_at_end', '>=', $r);
		}); 

		
        foreach($subjectCollection as $subject)
        {
            // set once first part of query 
            // verify user's right via SubjectPermissionResource on resource with code like "object.some_object_type_code" eg "object.object_type"
            if ($subject instanceof \Telenok\Core\Model\User\User)
            {
                $role = new \App\Telenok\Core\Model\Security\Role();
                $group = new \App\Telenok\Core\Model\User\Group();

                $queryCommon->leftJoin($spr->getTable() . ' as spr_permission_user_filter_object_type_own', function($join) use ($spr, $permission, $r)
                {
                    $join->on('resource_type_permission_user_filter_object_type_own.id', '=', 'spr_permission_user_filter_object_type_own.acl_resource_object_sequence');
                    $join->where('spr_permission_user_filter_object_type_own.acl_permission_object_sequence', '=', $permission->getKey());
                    $join->on('spr_permission_user_filter_object_type_own.' . $spr->getDeletedAtColumn(), ' is ', \DB::raw("null"));
                    $join->where('spr_permission_user_filter_object_type_own.active', '=', 1);
                    $join->where('spr_permission_user_filter_object_type_own.active_at_start', '<=', $r);
                    $join->where('spr_permission_user_filter_object_type_own.active_at_end', '>=', $r);
                }); 

                $queryCommon->leftJoin($role->getTable() . ' as role_permission_user_filter_object_type_own', function($join) use ($role, $r)
                {
                    $join->on('spr_permission_user_filter_object_type_own.acl_subject_object_sequence', '=', 'role_permission_user_filter_object_type_own.id');
                    $join->on('role_permission_user_filter_object_type_own.' . $role->getDeletedAtColumn(), ' is ', \DB::raw("null"));
                    $join->where('role_permission_user_filter_object_type_own.active', '=', 1);
                    $join->where('role_permission_user_filter_object_type_own.active_at_start', '<=', $r);
                    $join->where('role_permission_user_filter_object_type_own.active_at_end', '>=', $r);
                }); 

                $queryCommon->leftJoin('pivot_relation_m2m_role_group as pivot_relation_m2m_role_group_filter_object_type_own', function($join)
                {
                    $join->on('role_permission_user_filter_object_type_own.id', '=', 'pivot_relation_m2m_role_group_filter_object_type_own.role');
                }); 

                $queryCommon->leftJoin($group->getTable() . ' as group_permission_user_filter_object_type_own', function($join) use ($group, $r)
                {
                    $join->on('pivot_relation_m2m_role_group_filter_object_type_own.role_group', '=', 'group_permission_user_filter_object_type_own.id');
                    $join->on('group_permission_user_filter_object_type_own.' . $group->getDeletedAtColumn(), ' is ', \DB::raw("null"));
                    $join->where('group_permission_user_filter_object_type_own.active', '=', 1);
                    $join->where('group_permission_user_filter_object_type_own.active_at_start', '<=', $r);
                    $join->where('group_permission_user_filter_object_type_own.active_at_end', '>=', $r);
                }); 

                $queryCommon->leftJoin('pivot_relation_m2m_group_user as pivot_relation_m2m_group_user_filter_object_type_own', function($join)
                {
                    $join->on('group_permission_user_filter_object_type_own.id', '=', 'pivot_relation_m2m_group_user_filter_object_type_own.group');
                });
                
                break;
            }
		}

        foreach($subjectCollection as $subject)
        {
            $strRnd = str_random();

            // set once first part of query 
            // verify user's right via SubjectPermissionResource on resource with code like "object.some_object_type_code" eg "object.object_type"
            if ($subject instanceof \Telenok\Core\Model\User\User)
            {
                $queryCommon->leftJoin($subject->getTable() . " as user_permission_user_filter_object_type_own{$strRnd}", 
                    function($join) use ($subject, $r, $strRnd)
                    {
                        $join->on("pivot_relation_m2m_group_user_filter_object_type_own.group_user", "=", "user_permission_user_filter_object_type_own{$strRnd}.id");
                        $join->on("user_permission_user_filter_object_type_own{$strRnd}." . $subject->getDeletedAtColumn(), " is ", \DB::raw("null"));
                        $join->where("user_permission_user_filter_object_type_own{$strRnd}.active", "=", 1);
                        $join->where("user_permission_user_filter_object_type_own{$strRnd}.active_at_start", "<=", $r);
                        $join->where("user_permission_user_filter_object_type_own{$strRnd}.active_at_end", ">=", $r);
                        $join->on("osequence.created_by_user", "=", "user_permission_user_filter_object_type_own{$strRnd}.id");
                        $join->where("user_permission_user_filter_object_type_own{$strRnd}.id", "=", $subject->getKey());
                    }); 

                $queryWhere->OrWhereNotNull("user_permission_user_filter_object_type_own{$strRnd}.id");
            }

            // verify direct right of subject via SubjectPermissionResource on resource with code like "object.some_object_type_code" eg "object.object_type"
            $queryCommon->leftJoin($spr->getTable() . " as spr_filter_object_type_own_direct{$strRnd}", 
                function($join) use ($spr, $subject, $permission, $r, $strRnd)
                {
                    $join->on("resource_type_permission_user_filter_object_type_own.id", "=", "spr_filter_object_type_own_direct{$strRnd}.acl_resource_object_sequence");
                    $join->where("spr_filter_object_type_own_direct{$strRnd}.acl_permission_object_sequence", "=", $permission->getKey());
                    $join->where("spr_filter_object_type_own_direct{$strRnd}.acl_subject_object_sequence", "=", $subject->getKey());
                    $join->on("spr_filter_object_type_own_direct{$strRnd}." . $spr->getDeletedAtColumn(), " is ", \DB::raw("null"));
                    $join->where("spr_filter_object_type_own_direct{$strRnd}.active", "=", 1);
                    $join->where("spr_filter_object_type_own_direct{$strRnd}.active_at_start", "<=", $r);
                    $join->where("spr_filter_object_type_own_direct{$strRnd}.active_at_end", ">=", $r);
                    $join->where("osequence.created_by_user", "=", $subject->getKey());
                });

            $queryWhere->OrWhereNotNull("spr_filter_object_type_own_direct{$strRnd}.id");
        }
    }
}