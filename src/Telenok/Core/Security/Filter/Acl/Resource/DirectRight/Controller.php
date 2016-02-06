<?php

namespace Telenok\Core\Security\Filter\Acl\Resource\DirectRight;

/**
 * @class Telenok.Core.Security.Filter.Acl.Resource.DirectRight.Controller
 * @extends Telenok.Core.Interfaces.Security.Filter.Acl.Resource.Controller
 * Class filtering direct rights access to resource
 */
class Controller extends \Telenok\Core\Interfaces\Security\Filter\Acl\Resource\Controller {

    /**
     * @property
     * @member Telenok.Core.Security.Filter.Acl.Resource.DirectRight.Controller
     * @protected
     * Key of filter
     */
    protected $key = 'direct-right';

    /**
     * @method
     * @member Telenok.Core.Security.Filter.Acl.Resource.DirectRight.Controller
     * @public
     * Key of filter
     */
    public function filterCan($queryCommon, $queryWhere, $resource, $permission, $subject)
    {
        $r = range_minutes($this->getCacheMinutes());
        $sequence = new \App\Telenok\Core\Model\Object\Sequence();
        $spr = new \App\Telenok\Core\Model\Security\SubjectPermissionResource();

        // verify user's right via SubjectPermissionResource on resource with code like "object.some_object_type_code" eg "object.object_type"
        if ($subject instanceof \Telenok\Core\Model\User\User)
        {
            $role = new \App\Telenok\Core\Model\Security\Role();
            $group = new \App\Telenok\Core\Model\User\Group();

            $queryCommon->leftJoin($spr->getTable() . ' as spr_permission_user_filter_direct_right', function($join) use ($spr, $sequence, $permission, $r)
            {
                $join->on($sequence->getTable() . '.id', '=', 'spr_permission_user_filter_direct_right.acl_resource_object_sequence');
                $join->where('spr_permission_user_filter_direct_right.acl_permission_object_sequence', '=', $permission->getKey());
                $join->on('spr_permission_user_filter_direct_right.' . $spr->getDeletedAtColumn(), ' is ', app('db')->raw("null"));
                $join->where('spr_permission_user_filter_direct_right.active', '=', 1);
                $join->where('spr_permission_user_filter_direct_right.active_at_start', '<=', $r[1]);
                $join->where('spr_permission_user_filter_direct_right.active_at_end', '>=', $r[0]);
            });

            $queryCommon->leftJoin($role->getTable() . ' as role_permission_user_filter_direct_right', function($join) use ($role, $r)
            {
                $join->on('spr_permission_user_filter_direct_right.acl_subject_object_sequence', '=', 'role_permission_user_filter_direct_right.id');
                $join->on('role_permission_user_filter_direct_right.' . $role->getDeletedAtColumn(), ' is ', app('db')->raw("null"));
                $join->where('role_permission_user_filter_direct_right.active', '=', 1);
                $join->where('role_permission_user_filter_direct_right.active_at_start', '<=', $r[1]);
                $join->where('role_permission_user_filter_direct_right.active_at_end', '>=', $r[0]);
            });

            $queryCommon->leftJoin('pivot_relation_m2m_role_group as pivot_relation_m2m_role_group_filter_direct_right', function($join)
            {
                $join->on('role_permission_user_filter_direct_right.id', '=', 'pivot_relation_m2m_role_group_filter_direct_right.role');
            });

            $queryCommon->leftJoin($group->getTable() . ' as group_permission_user_filter_direct_right', function($join) use ($group, $r)
            {
                $join->on('pivot_relation_m2m_role_group_filter_direct_right.role_group', '=', 'group_permission_user_filter_direct_right.id');
                $join->on('group_permission_user_filter_direct_right.' . $group->getDeletedAtColumn(), ' is ', app('db')->raw("null"));
                $join->where('group_permission_user_filter_direct_right.active', '=', 1);
                $join->where('group_permission_user_filter_direct_right.active_at_start', '<=', $r[1]);
                $join->where('group_permission_user_filter_direct_right.active_at_end', '>=', $r[0]);
            });

            $queryCommon->leftJoin('pivot_relation_m2m_group_user as pivot_relation_m2m_group_user_filter_direct_right', function($join)
            {
                $join->on('group_permission_user_filter_direct_right.id', '=', 'pivot_relation_m2m_group_user_filter_direct_right.group');
            });

            $queryCommon->leftJoin($subject->getTable() . ' as user_permission_user_filter_direct_right', function($join) use ($subject, $r)
            {
                $join->on('pivot_relation_m2m_group_user_filter_direct_right.group_user', '=', 'user_permission_user_filter_direct_right.id');
                $join->on('user_permission_user_filter_direct_right.' . $subject->getDeletedAtColumn(), ' is ', app('db')->raw("null"));
                $join->where('user_permission_user_filter_direct_right.active', '=', 1);
                $join->where('user_permission_user_filter_direct_right.active_at_start', '<=', $r[1]);
                $join->where('user_permission_user_filter_direct_right.active_at_end', '>=', $r[0]);
                $join->where('user_permission_user_filter_direct_right.id', '=', $subject->getKey());
            });

            $queryWhere->OrWhereNotNull('user_permission_user_filter_direct_right.id');
        }

        // verify direct right of subject via SubjectPermissionResource on resource
        $queryCommon->leftJoin($spr->getTable() . ' as spr_filter_direct_right', function($join) use ($spr, $sequence, $subject, $permission, $r)
        {
            $join->on($sequence->getTable() . '.id', '=', 'spr_filter_direct_right.acl_resource_object_sequence');
            $join->where('spr_filter_direct_right.acl_permission_object_sequence', '=', $permission->getKey());
            $join->where('spr_filter_direct_right.acl_subject_object_sequence', '=', $subject->getKey());
            $join->on('spr_filter_direct_right.' . $spr->getDeletedAtColumn(), ' is ', app('db')->raw("null"));
            $join->where('spr_filter_direct_right.active', '=', 1);
            $join->where('spr_filter_direct_right.active_at_start', '<=', $r[1]);
            $join->where('spr_filter_direct_right.active_at_end', '>=', $r[0]);
        });

        $queryWhere->OrWhereNotNull('spr_filter_direct_right.id');
    }

    public function filter($queryCommon, $queryWhere, $resource, $permission, $subjectCollection)
    {
        $r = range_minutes($this->getCacheMinutes());
        $spr = new \App\Telenok\Core\Model\Security\SubjectPermissionResource();

        foreach ($subjectCollection as $subject)
        {
            // set once first part of query 
            // verify user's right via SubjectPermissionResource on resource with code like "object.some_object_type_code" eg "object.object_type"
            if ($subject instanceof \Telenok\Core\Model\User\User)
            {
                $role = new \App\Telenok\Core\Model\Security\Role();
                $group = new \App\Telenok\Core\Model\User\Group();

                $queryCommon->leftJoin($spr->getTable() . ' as spr_permission_user_filter_direct_right', function($join) use ($spr, $permission, $r)
                {
                    $join->on('osequence.id', '=', 'spr_permission_user_filter_direct_right.acl_resource_object_sequence');
                    $join->where('spr_permission_user_filter_direct_right.acl_permission_object_sequence', '=', $permission->getKey());
                    $join->on('spr_permission_user_filter_direct_right.' . $spr->getDeletedAtColumn(), ' is ', app('db')->raw("null"));
                    $join->where('spr_permission_user_filter_direct_right.active', '=', 1);
                    $join->where('spr_permission_user_filter_direct_right.active_at_start', '<=', $r[1]);
                    $join->where('spr_permission_user_filter_direct_right.active_at_end', '>=', $r[0]);
                });

                $queryCommon->leftJoin($role->getTable() . ' as role_permission_user_filter_direct_right', function($join) use ($role, $r)
                {
                    $join->on('spr_permission_user_filter_direct_right.acl_subject_object_sequence', '=', 'role_permission_user_filter_direct_right.id');
                    $join->on('role_permission_user_filter_direct_right.' . $role->getDeletedAtColumn(), ' is ', app('db')->raw("null"));
                    $join->where('role_permission_user_filter_direct_right.active', '=', 1);
                    $join->where('role_permission_user_filter_direct_right.active_at_start', '<=', $r[1]);
                    $join->where('role_permission_user_filter_direct_right.active_at_end', '>=', $r[0]);
                });

                $queryCommon->leftJoin('pivot_relation_m2m_role_group as pivot_relation_m2m_role_group_filter_direct_right', function($join)
                {
                    $join->on('role_permission_user_filter_direct_right.id', '=', 'pivot_relation_m2m_role_group_filter_direct_right.role');
                });

                $queryCommon->leftJoin($group->getTable() . ' as group_permission_user_filter_direct_right', function($join) use ($group, $now)
                {
                    $join->on('pivot_relation_m2m_role_group_filter_direct_right.role_group', '=', 'group_permission_user_filter_direct_right.id');
                    $join->on('group_permission_user_filter_direct_right.' . $group->getDeletedAtColumn(), ' is ', app('db')->raw("null"));
                    $join->where('group_permission_user_filter_direct_right.active', '=', 1);
                    $join->where('group_permission_user_filter_direct_right.active_at_start', '<=', $r[1]);
                    $join->where('group_permission_user_filter_direct_right.active_at_end', '>=', $r[0]);
                });

                $queryCommon->leftJoin('pivot_relation_m2m_group_user as pivot_relation_m2m_group_user_filter_direct_right', function($join)
                {
                    $join->on('group_permission_user_filter_direct_right.id', '=', 'pivot_relation_m2m_group_user_filter_direct_right.group');
                });

                break;
            }
        }

        foreach ($subjectCollection as $subject)
        {
            $strRnd = str_random();

            // verify user's right via SubjectPermissionResource on resource with code like "object.some_object_type_code" eg "object.object_type"
            if ($subject instanceof \Telenok\Core\Model\User\User)
            {
                $queryCommon->leftJoin($subject->getTable() . " as user_permission_user_filter_direct_right{$strRnd}", function($join) use ($subject, $r, $strRnd)
                {
                    $join->on("pivot_relation_m2m_group_user_filter_direct_right.group_user", "=", "user_permission_user_filter_direct_right{$strRnd}.id");
                    $join->on("user_permission_user_filter_direct_right{$strRnd}." . $subject->getDeletedAtColumn(), " is ", app('db')->raw("null"));
                    $join->where("user_permission_user_filter_direct_right{$strRnd}.active", "=", 1);
                    $join->where("user_permission_user_filter_direct_right{$strRnd}.active_at_start", "<=", $r[1]);
                    $join->where("user_permission_user_filter_direct_right{$strRnd}.active_at_end", ">=", $r[0]);
                    $join->where("user_permission_user_filter_direct_right{$strRnd}.id", "=", $subject->getKey());
                });

                $queryWhere->OrWhereNotNull("user_permission_user_filter_direct_right{$strRnd}.id");
            }

            // verify direct right of subject via SubjectPermissionResource on resource
            $queryCommon->leftJoin($spr->getTable() . " as spr_filter_direct_right{$strRnd}", function($join) use ($spr, $subject, $permission, $r, $strRnd)
            {
                $join->on("osequence.id", "=", "spr_filter_direct_right{$strRnd}.acl_resource_object_sequence");
                $join->where("spr_filter_direct_right{$strRnd}.acl_permission_object_sequence", "=", $permission->getKey());
                $join->where("spr_filter_direct_right{$strRnd}.acl_subject_object_sequence", "=", $subject->getKey());
                $join->on("spr_filter_direct_right{$strRnd}." . $spr->getDeletedAtColumn(), " is ", app('db')->raw("null"));
                $join->where("spr_filter_direct_right{$strRnd}.active", "=", 1);
                $join->where("spr_filter_direct_right{$strRnd}.active_at_start", "<=", $r[1]);
                $join->where("spr_filter_direct_right{$strRnd}.active_at_end", ">=", $r[0]);
            });

            $queryWhere->OrWhereNotNull("spr_filter_direct_right{$strRnd}.id");
        }
    }

}
