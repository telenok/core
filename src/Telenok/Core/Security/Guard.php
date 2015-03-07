<?php

namespace Telenok\Core\Security;

class Guard extends \Illuminate\Auth\Guard {

    public function check()
    { 
        return parent::check() && $this->user()->active;
    }

    /*
     * \Auth::cannot(\App\Model\Telenok\Security\Permission->code eg: 'write', \App\Model\Telenok\Security\Resource->code 'log')
     * \Auth::cannot(222, \News $news)
     * \Auth::cannot(\App\Model\Telenok\Security\Permission $read, \User $user)
     * \Auth::cannot(\App\Model\Telenok\Security\Permission $read, ['object_type.language.%'])
    */
    public function cannot($permissionCode = null, $resourceCode = null)
    {
        return !$this->can($permissionCode, $resourceCode);
    }

    /*
     * \Auth::can(\App\Model\Telenok\Security\Permission->code eg: 'write', \App\Model\Telenok\Security\Resource->code 'log')
     * \Auth::can(222, \News $news)
     * \Auth::can(\App\Model\Telenok\Security\Permission $read, \User $user)
     * \Auth::can(\App\Model\Telenok\Security\Permission $read, ['object_type.language.%'])
    */
    public function can($permissionCode = null, $resourceCode = null)
    { 
        if (!\Config::get('app.acl.enabled')) 
        {
			return true;
        }
		
        if ($this->check()) 
        {
            if (\Telenok\Core\Security\Acl::user()->can($permissionCode, $resourceCode))
            {
                return true;
            }
            else if (\Telenok\Core\Security\Acl::subject('user_authorized')->can($permissionCode, $resourceCode))
            {
                return true;
            }
        }
        else 
        {
            return \Telenok\Core\Security\Acl::subject('user_unauthorized')->can($permissionCode, $resourceCode);
        } 
		
        return false;
    }
    
    public function hasRole($id = null)
    { 
        if ($this->check())
        {
            return \Telenok\Core\Security\Acl::user()->hasRole($id);
        }

        return false;
    }
}

