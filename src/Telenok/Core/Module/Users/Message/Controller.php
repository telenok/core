<?php namespace Telenok\Core\Module\Users\Message;
  
use \Illuminate\Database\Eloquent\ModelNotFoundException as ModelNotFoundException;

class Controller extends \Telenok\Core\Interfaces\Presentation\Simple\Controller { 

    public function getParent()
    {
        return 'users';
    }

    public function getKey()
    {
        return 'user-message';
    }

    public static function getPackage()
    {
        return 'core';
    }

    public function getModelList()
    {
        return '\App\Telenok\Core\Model\User\Message';
    }

    public function getTreeContent()
    {
        return "";
    }
    
    public function getAdditionalViewParam()
    {
        return [];
    }
    
    
}

