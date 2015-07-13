<?php namespace Telenok\Core\Security;

class Guard extends \Illuminate\Auth\Guard {

    public function check()
    { 
        return parent::check() && $this->user()->active;
    }

    /*
     * app('auth')->cannot(\App\Telenok\Core\Model\Security\Permission->code eg: 'write', \App\Telenok\Core\Model\Security\Resource->code 'log')
     * app('auth')->cannot(222, \News $news)
     * app('auth')->cannot(\App\Telenok\Core\Model\Security\Permission $read, \User $user)
     * app('auth')->cannot(\App\Telenok\Core\Model\Security\Permission $read, ['object_type.language.%'])
    */
    public function cannot($permissionCode = null, $resourceCode = null)
    {
        return !$this->can($permissionCode, $resourceCode);
    }

    /*
     * app('auth')->can(\App\Telenok\Core\Model\Security\Permission->code eg: 'write', \App\Telenok\Core\Model\Security\Resource->code 'log')
     * app('auth')->can(222, \News $news)
     * app('auth')->can(\App\Telenok\Core\Model\Security\Permission $read, \User $user)
     * app('auth')->can(\App\Telenok\Core\Model\Security\Permission $read, ['object_type.language.%'])
    */
    public function can($permissionCode = null, $resourceCode = null)
    { 
        if (!config('app.acl.enabled')) 
        {
			return true;
        }
		
        if ($this->check()) 
        {
            if (\App\Telenok\Core\Security\Acl::user()->can($permissionCode, $resourceCode))
            {
                return true;
            }
            else if (\App\Telenok\Core\Security\Acl::subject('user_authorized')->can($permissionCode, $resourceCode))
            {
                return true;
            }
        }
        else 
        {
            return \App\Telenok\Core\Security\Acl::subject('user_unauthorized')->can($permissionCode, $resourceCode);
        } 
		
        return false;
    }
    
    public function hasRole($id = null)
    { 
        if ($this->check())
        {
            return \App\Telenok\Core\Security\Acl::user()->hasRole($id);
        }

        return false;
    }
}

