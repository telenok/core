<?php namespace Telenok\Core\Interfaces\Security\Filter\Acl\Resource;

/**
 * @class Telenok.Core.Interfaces.Security.Filter.Acl.Resource.Controller
 * Base controller ACL resource.
 */
class Controller {

    /**
     * @protected
     * @property {String} $icon
     * Class of widget's icon.
     * @member Telenok.Core.Interfaces.Security.Filter.Acl.Resource.Controller
     */	
    protected $key = '';
    
    /**
     * @protected
     * @property {Number} $cacheMinutes
     * Amount of minuts for caching. Can be float to cache less then one minute.
     * @member Telenok.Core.Interfaces.Security.Filter.Acl.Resource.Controller
     */	
    protected $cacheMinutes = 5;

    /**
     * @method getKey
     * Return key.
     * @return {String}
     * @member Telenok.Core.Interfaces.Security.Filter.Acl.Resource.Controller
     */
    public function getKey()
    {
        return $this->key;
    } 

    /**
     * @method getCacheMinutes
     * Return cache time.
     * @return {number}
     * @member Telenok.Core.Interfaces.Security.Filter.Acl.Resource.Controller
     */
    public function getCacheMinutes()
    {
        return min(config('cache.db_query.minutes', 0), $this->cacheMinutes);
    }

    /**
     * @method filterCan

     */
    public function filterCan($queryCommon, $queryWhere, $resource, $permission, $subject)
    {
    }
	
    public function filter($queryCommon, $queryWhere, $resource, $permission, $subject)
    {
    }
    
    protected function resourceHasFilter($resource, $permission, $subject)
    {
        $table = $resource instanceof \Telenok\Core\Model\Object\Sequence ? $resource->type()->getTable() : $resource->getTable();

        $resourceFilter = \App\Telenok\Core\Model\Security\Resource::where('code', "object_type.{$table}.{$this->getKey()}")->active()->first();
        
        if (!$resourceFilter)
        {
            return false;
        }
        
        $spr = new \App\Telenok\Core\Model\Security\SubjectPermissionResource();
        
        $spr->where('acl_permission_object_sequence', $permission->getKey());
        $spr->where('acl_resource_object_sequence', $resourceFilter->getKey());
        $spr->where('acl_subject_object_sequence', $subject->getKey());
        $spr->active();
        
        if ($spr->first())
        {
            return true;
        }
        
        if ($subject instanceof \Telenok\Core\Model\User\User)
        {
            $group = new \App\Telenok\Core\Model\User\Group();
            $role = new \App\Telenok\Core\Model\Security\Role();
            $user = new \App\Telenok\Core\Model\User\User();
            $spr = new \App\Telenok\Core\Model\Security\SubjectPermissionResource();
            
            $query = $spr->where('acl_resource_object_sequence', $resourceFilter->getKey());
            $spr->where('acl_permission_object_sequence', $permission->getKey());
            $spr->active();

            $query->join($role->getTable() . ' as role', function($join) use ($spr, $group, $role)
            {
                $join->on('spr.acl_subject_object_sequence', '=', 'role.id');
                $join->on('role.active', '=', 1);
                $join->on('role.' . $spr->getDeletedAtColumn(), ' is ', app('db')->raw('null'));
            });

            $query->join('pivot_relation_m2m_role_group', function($join) use ($spr, $group, $role)
            {
                $join->on('role.id', '=', 'pivot_relation_m2m_role_group.role');
            });

            $query->join($group->getTable() . ' as group', function($join) use ($spr, $group, $role)
            {
                $join->on('pivot_relation_m2m_role_group.role_group', '=', 'group.id');
                $join->on('group.active', '=', 1);
                $join->on('group.' . $spr->getDeletedAtColumn(), ' is ', app('db')->raw('null'));
            });

            $query->join('pivot_relation_m2m_group_user', function($join) use ($spr, $group, $role)
            {
                $join->on('group.id', '=', 'pivot_relation_m2m_group_user.group');
            });

            $query->join($user->getTable() . ' as user', function($join) use ($spr, $group, $role)
            {
                $join->on('pivot_relation_m2m_group_user.group_user', '=', 'user.id');
                $join->on('user.active', '=', 1);
                $join->on('user.' . $spr->getDeletedAtColumn(), ' is ', app('db')->raw('null'));
            });

            if ($query->get()->first())
            {
                return true;
            }
        }

        return false;
    }
}

