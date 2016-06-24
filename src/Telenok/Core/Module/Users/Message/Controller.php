<?php

namespace Telenok\Core\Module\Users\Message;

use \Illuminate\Database\Eloquent\ModelNotFoundException as ModelNotFoundException;

/**
 * @class Telenok.Core.Module.Users.Message.Controller
 * @extends Telenok.Core.Abstraction.Presentation.Simple.Controller
 */
class Controller extends \Telenok\Core\Abstraction\Presentation\Simple\Controller {

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
        return '\App\Vendor\Telenok\Core\Model\User\Message';
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
