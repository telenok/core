<?php namespace Telenok\Core\Security;

class Acl
{
    protected $subject;
    protected $subjects;
    protected $subjectCollision = 1;
    
    const SUBJECT_COLLISION_ONE = 1;
    const SUBJECT_COLLISION_ALL = 2;
    const SUBJECT_COLLISION_ANY = 3;

    public function __construct($subject = null)
    {
        $this->subject = $subject;
        $this->subjects = collect();
    } 
    
	/* 
     * Set resource as internal variable for manipulating
     * 
     * \App\Telenok\Core\Security\Acl::resource(200)
     * \App\Telenok\Core\Security\Acl::resource('control_panel')
     * \App\Telenok\Core\Security\Acl::resource(\App\Telenok\Core\Model\User\User $user)
     * 
     */
    public static function resource($id = null)
    {
		$resource = null;
		
        if ($id instanceof \Illuminate\Database\Eloquent\Model)
        {
            $resource = $id;
        }
        else if (is_numeric($id))
        {
            $resource = \App\Telenok\Core\Model\Object\Sequence::find($id);
        }
		else if (is_string($id))
        {
            $resource = \App\Telenok\Core\Model\Security\Resource::where('code', $id)->first();
        }
		
		if (!$resource) 
		{
            throw new \Exception('Can\'t find resource');
		}

        return new static($resource);
    }

    /* 
     * Set subject as internal variable for manipulating
     * 
     * \App\Telenok\Core\Security\Acl::subject(200)
     * \App\Telenok\Core\Security\Acl::subject('user_unauthorized')
     * \App\Telenok\Core\Security\Acl::subject('user_any')
     * \App\Telenok\Core\Security\Acl::subject(\App\Telenok\Core\Model\User\User $user)
     * 
     */
    public static function subject($id = null)
    {
		$subject = null;
		
        if ($id instanceof \Illuminate\Database\Eloquent\Model)
        {
            $subject = $id;
        }
        else if (is_numeric($id))
        {
            $subject = \App\Telenok\Core\Model\Object\Sequence::find($id);
        }
        else if (is_scalar($id))
        {
            $subject = \App\Telenok\Core\Model\Security\Resource::where('code', $id)->first();
        }
		
		if (!$subject) 
		{
            throw new \Exception('Can\'t find subject');
		}

        return new static($subject);
    }

    /* 
     * Set subject as internal variable for manipulating
     * 
     * Alias for ::subjectAll(). Please, look subjectAll()
     * 
     */
    public static function subjects($subjects = [])
    {
        return static::subjectAll($subjects);
    }

    /* 
     * Set subject as internal variable for manipulating
     * 
     * \App\Telenok\Core\Security\Acl::subjectAll([200, 'user_unauthorized'])
     * \App\Telenok\Core\Security\Acl::subjectAll([$user, 'user_unauthorized'])
     * 
     */
    public static function subjectAll($subjects = [])
    {
        $acl = new static;
        $acl->setCollision(static::SUBJECT_COLLISION_ALL);

        foreach($subjects as $subject)
        {
            $acl->addSubjects($subject);
        }

        return $this;
    }
    
    /* 
     * Set subject as internal variable for manipulating
     * 
     * \App\Telenok\Core\Security\Acl::subjectAny([200, 'user_unauthorized'])
     * \App\Telenok\Core\Security\Acl::subjectAny([$user, 'user_unauthorized'])
     * 
     */
    public static function subjectAny($subjects = [])
    {
        $acl = new static;
        $acl->setCollision(static::SUBJECT_COLLISION_ANY);

        foreach($subjects as $subject)
        {
            $acl->addSubjects($subject);
        }

        return $acl;
    }

	public function getSubject()
	{
		return $this->subject;
	}

	public function addSubjects($subject)
	{
		return $this->subjects->push($subject);
	}

	public function getSubjects()
	{
		return $this->subjects;
	}

    public function setCollision($param)
    {
        $this->subjectCollision = $param;
        
        return $this;
    }

    public function getCollision()
    {
        return $this->subjectCollision;
    }

    /* 
     * Set user as internal variable for manipulating
     * 
     * \App\Telenok\Core\Security\Acl::user() - for logged user
     * \App\Telenok\Core\Security\Acl::user(2)
     * \App\Telenok\Core\Security\Acl::user(\App\Telenok\Core\Model\User\User $user)
     * 
     */
    public static function user($id = null)
    {
        $user = null;
        
        if ($id === null)
        {
            $user = app('auth')->user();
        }
        else if ($id instanceof \Telenok\Core\Model\User\User)
        {
			$user = $id;
        }
        else if (is_numeric($id))
        {
            $user = \App\Telenok\Core\Model\User\User::find($id);
        }
		
		if (!$user) 
		{
            throw new \Exception('Can\'t find user');
		}

        return new static($user);
    }

    /* 
     * Set role as internal variable for manipulating
     * 
     * \App\Telenok\Core\Security\Acl::role('administrator')
     * \App\Telenok\Core\Security\Acl::role(2)
     * \App\Telenok\Core\Security\Acl::role(\App\Telenok\Core\Model\Security\Role $role)
     * 
     */
    public static function role($id = null)
    {
        $role = null;
		
		if ($id instanceof \Telenok\Core\Model\Security\Role)
		{
			$role = $id;
		}
		else if (is_scalar($id))
		{
			$role = \App\Telenok\Core\Model\Security\Role::where('code', $id)->orWhere('id', $id)->first();
		}

		if (!$role) 
		{
            throw new \Exception('Can\'t find role');
		}

        return new static($role);
    }
    
    /* 
     * Set group as internal variable for manipulating
     * 
     * \App\Telenok\Core\Security\Acl::group('administrator')
     * \App\Telenok\Core\Security\Acl::group(2)
     * \App\Telenok\Core\Security\Acl::group(\App\Telenok\Core\Model\User\Group $group)
     * 
     */
    public static function group($id = null)
    {
        $group = null;

		if ($id instanceof \Telenok\Core\Model\User\Group)
        {
            $group = $id;
        }
        else if (is_scalar($id))
        {
            $group = \App\Telenok\Core\Model\User\Group::where('code', $id)->orWhere('id', $id)->first();
        }
		
		if (!$group) 
		{
            throw new \Exception('Can\'t find group');
		} 

        return new static($group);
    }

    /* 
     * Add role 
     * 
     * \App\Telenok\Core\Security\Acl::addRole(['en' => 'News writers'], 'news_writers')
     * \App\Telenok\Core\Security\Acl::addRole('News writers', 'news_writers')
     * 
     */
    public static function addRole($title = [], $code = null)
    {
        if (!$code)
        {
            throw new \Exception('Code cant be empty');
        }

        $role = (new \App\Telenok\Core\Model\Security\Role())->storeOrUpdate([
            'title' => $title,
            'code' => $code,
            'active' => 1,
        ]);

        return new static($role);
    }

    /* 
     * Delete role
     * 
     * \App\Telenok\Core\Security\Acl::deleteRole(2)
     * \App\Telenok\Core\Security\Acl::deleteRole(\App\Telenok\Core\Model\Security\Role $role)
     * 
     */
    public static function deleteRole($id = null)
    {
        $role = null;
        
        if ($id instanceof \Telenok\Core\Model\Security\Role)
        {
            $role = $id;
        }
        else if (is_scalar($id))
        {
            $role = \App\Telenok\Core\Model\Security\Role::where('code', $id)->orWhere('id', $id)->first(); 
        }

        if ($role)
        {
            $role->forceDelete();
        }

        return new static();
    }

    /* 
     * Add resource 
     * 
     * \App\Telenok\Core\Security\Acl::addResource('file', ['en' => 'File'])
     * \App\Telenok\Core\Security\Acl::addResource('file', 'File')
     * 
     */
    public static function addResource($code = null, $title = [])
    {
        if (!$code)
        {
            throw new \Exception('Code should be set');
        }

        (new \App\Telenok\Core\Model\Security\Resource())->storeOrUpdate([
            'title' => (empty($title) ? 'Resource ' . $code : $title),
            'code' => $code,
            'active' => 1,
        ]);

        return new static();
    }
    
    /* 
     * Delete resource
     * 
     * \App\Telenok\Core\Security\Acl::deleteResource(2)
     * \App\Telenok\Core\Security\Acl::deleteResource(\App\Telenok\Core\Model\Security\Resource $resource)
     * 
     */
    public static function deleteResource($id = null)
    {
        $resource = null;
        
        if ($id instanceof \Telenok\Core\Model\Security\Resource)
        {
            $resource = $id;
        }
        else if (is_scalar($id))
        {
            $resource = \App\Telenok\Core\Model\Security\Resource::where('code', $id)->orWhere('id', $id)->first(); 
        }

        if ($resource)
        {
            $resource->forceDelete();
        }
        
        return new static();
    }

    /* 
     * Add permission 
     * 
     * \App\Telenok\Core\Security\Acl::addPermission(['en' => 'Search'], 'search')
     * \App\Telenok\Core\Security\Acl::addPermission('Search', 'search')
     * 
     */
    public static function addPermission($title = [], $code = null)
    {
        if (!$code)
        {
            throw new \Exception('Code should be set');
        }
        
        (new \App\Telenok\Core\Model\Security\Permission())->storeOrUpdate([
            'title' => $title,
            'code' => $code,
            'active' => 1,
        ]);

        return new static();
    } 

    /* 
     * Delete permission
     * 
     * \App\Telenok\Core\Security\Acl::deletePermission()
     * \App\Telenok\Core\Security\Acl::deletePermission(2)
     * \App\Telenok\Core\Security\Acl::deletePermission(\App\Telenok\Core\Model\Security\Permission $permission)
     * 
     */
    public static function deletePermission($id = null)
    {
        $permission = null;
        
        if ($id instanceof \Telenok\Core\Model\Security\Permission)
        {
            $permission = $id;
        }
        else if (is_scalar($id))
        {
            $permission = \App\Telenok\Core\Model\Security\Permission::where('code', $id)->orWhere('id', $id)->first(); 
        }

        if ($permission)
        {
            $permission->forceDelete();
        }
        
        return new static();
    }

    /* 
     * Set permission to subject
     * 
     * \App\Telenok\Core\Security\Acl::role/subject/user(who)->setPermission(what.can, over.resource)
     * 
     * \App\Telenok\Core\Security\Acl::subjectAny([200, $user])->setPermission('read', 'control_panel')
     * \App\Telenok\Core\Security\Acl::subjectAll([200, $user])->setPermission('read', 'control_panel')
     * \App\Telenok\Core\Security\Acl::role(316)->setPermission('read', 'control_panel')
     * \App\Telenok\Core\Security\Acl::role(316)->setPermission(['read', 'update'], 2341)
     * \App\Telenok\Core\Security\Acl::user(339)->setPermission('read', 'news')
     * \App\Telenok\Core\Security\Acl::role(800)->setPermission(233, 1901)
     * \App\Telenok\Core\Security\Acl::subject(\Process $process)->setPermission(\App\Telenok\Core\Model\Security\Permission $permission, \App\Telenok\Core\Model\Security\Resource $resource)
     * 
     */
    public function setPermission($permissionCode = null, $resourceCode = null)
    {
        if ($this->subjects->count())
        {
            foreach($this->subjects->all() as $subject)
            {
                static::subject($subject)->setPermission($permissionCode, $resourceCode);
            }
            
            return $this;
        }

        if (!$this->subject)
        {
            return $this;
        }

		if (is_array($permissionCode))
		{
			foreach($permissionCode as $pCode)
			{
				$this->setPermission($pCode, $resourceCode);
			}

			return $this;
		}

        if ($permissionCode instanceof \Telenok\Core\Model\Security\Permission)
        {
            $permission = $permissionCode;
        }
        else if (is_scalar($permissionCode))
        {
            $permission = \App\Telenok\Core\Model\Security\Permission::where('code', $permissionCode)->orWhere('id', $permissionCode)->first();
        }
		
		if (!$permission)
		{
            throw new \Exception('Can\'t find permission');
		}

        if ($resourceCode instanceof \Telenok\Core\Interfaces\Eloquent\Object\Model)
        {
            $resource = $resourceCode;
        }
        else if (is_numeric($resourceCode))
        {
			$resource = \App\Telenok\Core\Model\Object\Sequence::find($resourceCode); 
        }
		else if (is_string($resourceCode))
		{
			$resource = \App\Telenok\Core\Model\Security\Resource::where('code', $resourceCode)->first();
		}
		
		if (!$resource)
		{
            throw new \Exception('Can\'t find resource');
		}

        \DB::transaction(function() use ($permission, $resource)
        {
            try
            {
                \App\Telenok\Core\Model\Security\SubjectPermissionResource::where('acl_permission_object_sequence', $permission->getKey())
                        ->where('acl_subject_object_sequence', $this->subject->getKey())
                        ->where('acl_resource_object_sequence', $resource->getKey())
                        ->firstOrFail();
           }
            catch (\Exception $e)
            {
                if ($this->subject instanceof \Telenok\Core\Model\Object\Sequence)
                {
                    $typeSubject = $this->subject->sequencesObjectType()->first();
                }
                else
                {
                    $typeSubject = $this->subject->type();
                }
                
                if ($resource instanceof \Telenok\Core\Model\Object\Sequence)
                {
                    $typeResource = $resource->sequencesObjectType()->first();
                }
                else
                {
                    $typeResource = $resource->type();
                }
				
                $spr = (new \App\Telenok\Core\Model\Security\SubjectPermissionResource())->storeOrUpdate([
                    'title' => '[' . $permission->translate('title') . '] [' . $typeResource->translate('title') . ': ' . $resource->translate('title') . '] by [' . $typeSubject->translate('title') . ': '. $this->subject->translate('title') . '] ',
                    'code' => $permission->code . '__' . $typeResource->code . '_' . $resource->getKey() . '__by_' . $typeSubject->code . '_' . $this->subject->getKey(),
                    'active' => 1,
                ]);

                $permission->aclPermission()->save($spr);
                
                if ($resource instanceof \Telenok\Core\Model\Object\Sequence)
                {
                    $resource->aclResource()->save($spr);
                }
                else
                {
                    $resource->sequence->aclResource()->save($spr);
                }
                
                if ($this->subject instanceof \Telenok\Core\Model\Object\Sequence)
                {
                    $this->subject->aclSubject()->save($spr);
                }
                else
                {
                    $this->subject->sequence->aclSubject()->save($spr);
                }
            }
        });

        return $this;
    }

    /* 
     * Remove permission from resource 
     * 
     * \App\Telenok\Core\Security\Acl::resource(120)->unsetPermission('read') remove all permissions on resource with ID 120
     * \App\Telenok\Core\Security\Acl::role(120)->unsetPermission(null, \User $user) remove all permission from role with ID 120 which assigned to user $user
     * \App\Telenok\Core\Security\Acl::user($admin)->unsetPermission(\App\Telenok\Core\Model\Security\Permission $permission, \SuperAdmin $subject)
     * 
     */
    public function unsetPermission($permissionCode = null, $subjectCode = null)
    {
        if ($this->subjects->count())
        {
            foreach($this->subjects->all() as $subject)
            {
                static::subject($subject)->unsetPermission($permissionCode, $subjectCode);
            }
            
            return $this;
        }

        if (!$this->subject) 
        {
            return $this;
        }
        
        $permission = null;
        $subject = null;

        $resource = $this->subject;

        if ($permissionCode instanceof \Telenok\Core\Model\Security\Permission)
        {
            $permission = $permissionCode;
        }
        else if (is_scalar($permissionCode))
        {
            $permission = \App\Telenok\Core\Model\Security\Permission::where('code', $permissionCode)->orWhere('id', $permissionCode)->first();
        }

        if ($subjectCode instanceof \Telenok\Core\Interfaces\Eloquent\Object\Model)
        {
            $subject = $subjectCode;
        }
        else if (is_numeric($subjectCode))
        {
			$subject = \App\Telenok\Core\Model\Object\Sequence::find($subjectCode); 
        }
		else if (is_string($subjectCode))
		{
            $subject = \App\Telenok\Core\Model\Security\Resource::where('code', $subjectCode)->orWhere('id', $subjectCode)->first();
		}

        $query = \App\Telenok\Core\Model\Security\SubjectPermissionResource::where('acl_resource_object_sequence', $resource->getKey()); 

        if ($permission)
        {
            $query->where('acl_permission_object_sequence', $permission->getKey()); 
        }

        if ($subjectCode)
        {
            $query->where('acl_subject_object_sequence', $subject->getKey());
        }

		$list = $query->get();

		$list->each(function($i)
		{
			$i->forceDelete();
		});

        return $this;
    }
    
    /* 
     * Add group to user
     * 
     * \App\Telenok\Core\Security\Acl::user(2)->setGroup('administrator')
     * \App\Telenok\Core\Security\Acl::user($user)->setGroup(2)
     * \App\Telenok\Core\Security\Acl::user($user)->setGroup(\App\Telenok\Core\Model\User\Group $group)
     * 
     */
    public function setGroup($id = null)
    {
        if ($this->subjects->count())
        {
            foreach($this->subjects->all() as $subject)
            {
                static::subject($subject)->setGroup($id);
            }
            
            return $this;
        }
        
        if (!$this->subject instanceof \Telenok\Core\Model\User\User)
        {
            throw new \Exception('Subject should be instance of \Telenok\Core\Model\User\User');
        }
        
        if ($id instanceof \Telenok\Core\Model\User\Group)
        {
            $group = $id;
        }
        else if (is_scalar($id))
        {
			$group = \App\Telenok\Core\Model\User\Group::where('code', $id)->orWhere('id', $id)->first();
        }
		
		if (!$group)
		{
            throw new \Exception('Can\'t find group');
		}

		$this->subject->group()->save($group);

        return $this;
    }

    /* 
     * Remove group from user
     * 
     * \App\Telenok\Core\Security\Acl::user(2)->unsetGroup(2)
     * \App\Telenok\Core\Security\Acl::user(2)->unsetGroup('super_administrator')
     * \App\Telenok\Core\Security\Acl::user(2)->unsetGroup(\App\Telenok\Core\Model\User\Group $group)
     * 
     */
    public function unsetGroup($id = null)
    {
        if ($this->subjects->count())
        {
            foreach($this->subjects->all() as $subject)
            {
                static::subject($subject)->unsetGroup($id);
            }
            
            return $this;
        }

        if (!$this->subject instanceof \Telenok\Core\Model\User\User)
        {
            throw new \Exception('Subject should be instance of \App\Telenok\Core\Model\User\User');
        }

        if ($id === null)
        {
            $this->subject->group()->detach();
        }
        else if ($id instanceof \Telenok\Core\Model\User\Group)
        {
            $group = $id;
        }
        else if (is_scalar($id))
        {
			$group = \App\Telenok\Core\Model\User\Group::where('code', $id)->orWhere('id', $id)->first();
        }
		
		if (!$group)
		{
            throw new \Exception('Can\'t find group');
		}

        $this->subject->group()->detach($group); 

        return $this;
    }
    
    /* 
     * Add role to group
     * 
     * \App\Telenok\Core\Security\Acl::group($admin)->setRole('super_administrator')
     * \App\Telenok\Core\Security\Acl::group($admin)->setRole(2)
     * \App\Telenok\Core\Security\Acl::group($admin)->setRole(\App\Telenok\Core\Model\Security\Role $role)
     * 
     */
    public function setRole($id = null)
    {
        if ($this->subjects->count())
        {
            foreach($this->subjects->all() as $subject)
            {
                static::subject($subject)->setRole($id);
            }
            
            return $this;
        }

        if (!$this->subject instanceof \Telenok\Core\Model\User\Group)
        {
            throw new \Exception('Subject should be instance of \App\Telenok\Core\Model\User\Group');
        }

        if ($id instanceof \Telenok\Core\Model\Security\Role)
        {
            $role = $id;
        }
        else if (is_scalar($id))
        {
			$role = \App\Telenok\Core\Model\Security\Role::where('code', $id)->orWhere('id', $id)->first();
        }

		if (!$role)
		{
            throw new \Exception('Can\'t find role');
		}

        $this->subject->role()->save($role);

        return $this;
    }

    /* 
     * Remove role from group
     * 
     * \App\Telenok\Core\Security\Acl::group($admin)->unsetRole() - unset all roles from group
     * \App\Telenok\Core\Security\Acl::group($admin)->unsetRole(2)
     * \App\Telenok\Core\Security\Acl::group($admin)->unsetRole('super_administrator')
     * 
     */
    public function unsetRole($id = null)
    {
        if ($this->subjects->count())
        {
            foreach($this->subjects->all() as $subject)
            {
                static::subject($subject)->unsetRole($id);
            }
            
            return $this;
        }

        if (!$this->subject instanceof \Telenok\Core\Model\User\Group)
        {
            throw new \Exception('Subject should be instance of \Telenok\Core\Model\User\Group');
        }

        if ($id === null)
        {
            $this->subject->role()->detach();
        }
        else if ($id instanceof \App\Telenok\Core\Model\Security\Role)
        {
            $role = $id;
        }
        else if (is_scalar($id))
        {
			$role = \App\Telenok\Core\Model\Security\Role::where('code', $id)->orWhere('id', $id)->first();
        }

		if (!$role)
		{
            throw new \Exception('Can\'t find role');
		}

		$this->subject->role()->detach($role);		
 
        return $this;
    }

    /* 
     * Validate subject's permission
     * 
     * \App\Telenok\Core\Security\Acl::group($admin)->can(\App\Telenok\Core\Model\Security\Permission->code eg: 'write', \App\Telenok\Core\Model\Security\Resource->code 'log')
     * \App\Telenok\Core\Security\Acl::user(103)->can(222, \News $news, ['object-type-own']) - only 'object-type-own' filter used
     * \App\Telenok\Core\Security\Acl::subject(103)->can(\App\Telenok\Core\Model\Security\Permission $read, \User $user)
     * \App\Telenok\Core\Security\Acl::subject(103)->can(12, 'object_type.language')
     * \App\Telenok\Core\Security\Acl::subject(103)->can('read', 148)
     * \App\Telenok\Core\Security\Acl::subject(103)->can('read', [148, 'user_any'])
     * 
     */
    public function can($permissionCode = null, $resourceCode = null, $filterCode = null)
    {
        if ($this->subjects->count())
        {
            foreach($this->subjects->all() as $subject)
            {
                if (is_array($resourceCode))
                {
                    foreach($resourceCode as $r)
                    {
                        if (!($can = static::subject($subject)->can($permissionCode, $r, $filterCode)))
                        {
                            break;
                        }
                    }
                }
                else
                {
                    $can = static::subject($subject)->can($permissionCode, $r, $filterCode);
                }
                
                if ($this->getCollision() == static::SUBJECT_COLLISION_ANY && $can)
                {
                    return true;
                }
                else if ($this->getCollision() == static::SUBJECT_COLLISION_ALL && !$can)
                {
                    return false;
                }
            }

            return true;
        }

        if (!config('app.acl.enabled') || $this->hasRole('super_administrator'))
        {
            return true;
        }

		if (!$this->subject || !\App\Telenok\Core\Model\Object\Sequence::where('id', $this->subject->getKey())->active()->count())
		{
			return false;
		}
		
		$resource = null;
		$permission = null;

        if ($resourceCode instanceof \Telenok\Core\Interfaces\Eloquent\Object\Model)
        {
			$resource = $resourceCode; 
        }
        else if (is_numeric($resourceCode))
        {
			$resource = \App\Telenok\Core\Model\Object\Sequence::where('id', $resourceCode)->first(); 
        }
        else if (is_string($resourceCode))
		{
			$resource = \App\Telenok\Core\Model\Security\Resource::where('code', $resourceCode)->first(); 
		}
		
		if (!$resource)
		{
			return false;
		}
		
		
        if ($permissionCode instanceof \Telenok\Core\Model\Security\Permission)
        {
			$permission = \App\Telenok\Core\Model\Security\Permission::where('id', $resourceCode->getKey())->active()->first(); 
        }
        else if (is_scalar($permissionCode))
        {
            $permission = \App\Telenok\Core\Model\Security\Permission::where('code', $permissionCode)->orWhere('id', $permissionCode)->active()->first();
        }
		
		if (!$permission)
		{
			return false;
		}

		$type = new \App\Telenok\Core\Model\Object\Type();
		$sequence = new \App\Telenok\Core\Model\Object\Sequence();
		$now = \Carbon\Carbon::now();

		$query = $sequence::select($sequence->getTable() . '.id')->where($sequence->getTable() . '.id', $resource->getKey());

		$query->join($type->getTable() . ' as otype', function($join) use ($type, $now, $sequence)
		{
			$join->on($sequence->getTable() . '.sequences_object_type', '=', 'otype.id');
			$join->on('otype.' . $type->getDeletedAtColumn(), ' is ', \DB::raw("null"));
			$join->where('otype.active', '=', 1);
			$join->where('otype.active_at_start', '<=', $now);
			$join->where('otype.active_at_end', '>=', $now);
		}); 
		
		$query->where(function($queryWhere) use ($query, $permission, $resource, $filterCode)
		{
			$queryWhere->where(\DB::raw(1), 0);
			
			$filters = app('telenok.config.repository')->getAclResourceFilter();

			if (!empty($filterCode))
			{
				$filters = $filters->filter(function($i) use ($filterCode) { return in_array($i->getKey(), (array)$filterCode, true); });
			}

			// submit joined query with sequence of resource and linked type
			$filters->each(function($item) use ($query, $queryWhere, $permission, $resource)
			{
				$item->filterCan($query, $queryWhere, $resource, $permission, $this->subject);
			});
		});
		
		return $query->take(1)->count() ? true : false;
    }

    /* 
     * Validate subject's permission
     * 
     * \App\Telenok\Core\Security\Acl::group($admin)->cannot(\App\Telenok\Core\Model\Security\Permission->code eg: 'write', \App\Telenok\Core\Model\Security\Resource->code 'log')
     * \App\Telenok\Core\Security\Acl::user(103)->cannot(222, \News $news)
     * \App\Telenok\Core\Security\Acl::subject(103)->cannot(\App\Telenok\Core\Model\Security\Permission $read, \User $user)
     * \App\Telenok\Core\Security\Acl::subject(103)->cannot(\App\Telenok\Core\Model\Security\Permission $read, ['object_type.language.%'])
     * 
     */
    public function cannot($permissionCode = null, $resourceCode = null)
    {
        return !$this->can($permissionCode, $resourceCode);
    }
    
    /* 
     * Validate user's role
     * 
     * \App\Telenok\Core\Security\Acl::user(103)->hasRole('superadmin')
     * \App\Telenok\Core\Security\Acl::user($user)->hasRole(1)
     * \App\Telenok\Core\Security\Acl::user(103)->hasRole(\App\Telenok\Core\Model\Security\Role $role)
     * 
     */
    public function hasRole($id = null)
    {
        if ($this->subjects->count())
        {
            foreach($this->subjects->all() as $subject)
            {
                static::subject($subject)->hasRole($id);
            }
            
            return $this;
        }

        if (!$this->subject instanceof \Telenok\Core\Model\User\User) 
        {
            return false;
        }

        if ($id instanceof \Telenok\Core\Model\Security\Role)
        {
            $role = \App\Telenok\Core\Model\Security\Role::where($id->getKey())->active()->first();
        }
        else if (is_scalar($id))
        {
            $role = \App\Telenok\Core\Model\Security\Role::where('code', $id)->orWhere('id', $id)->active()->first();
        }

        if (!$role)
        {
            return false;
        }

		$now = \Carbon\Carbon::now();

        $spr = $this->subject->with(
		[
            'group' => function($query) use ($now) 
			{ 
				$query->where('group.active', 1)
						->where('group.active_at_start', '<=', $now)
						->where('group.active_at_end', '>=', $now);
			},
            'group.role' => function($query) use ($role, $now) 
			{ 
				$query->where('role.id', $role->getKey())
					->where('role.active', 1)
					->where('role.active_at_start', '<=', $now)
					->where('role.active_at_end', '>=', $now);
			}
        ])
        ->whereId($this->subject->getKey())->get();

        foreach($spr as $user)
        { 
            foreach($user->group as $group)
            { 
                foreach($group->role as $role)
                {
                    return true;
                }
            }
        }

        return false;
    }
}