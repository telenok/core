<?php

namespace Telenok\Core\Module\Users\ProfileEdit;

/**
 * @class Telenok.Core.Module.Users.ProfileEdit.Controller
 * @extends Telenok.Core.Abstraction.Presentation.TreeTabObject.Controller
 */
class Controller extends \Telenok\Core\Abstraction\Presentation\TreeTabObject\Controller {

    protected $key = 'users-profile-edit';
    protected $parent = 'users';
    protected $modelListClass = '\App\Vendor\Telenok\Core\Model\User\User';
    protected $presentation = 'tree-tab-users-profile-edit';
    protected $presentationContentView = 'core::module.users-profile-edit.content';
    protected $presentationView = 'core::module.users-profile-edit.presentation';
    protected $presentationModelView = 'core::module.users-profile-edit.model';
    protected $presentationFormModelView = 'core::module.users-profile-edit.form';
    protected $presentationFormFieldListView = 'core::module.users-profile-edit.form-field-list';

    public function topMenuMain()
    {
        $collection = collect();

        $collection->put('key', 'user-name');
        $collection->put('parent', false);
        $collection->put('order', 100000);
        $collection->put('li', '<li class="light-blue user-profile">');
        $collection->put('content', '<a data-toggle="dropdown" href="#" class="user-menu dropdown-toggle">
                <img class="nav-user-photo" src="' . (app('auth')->user()->avatar_path ? : 'packages/telenok/core/image/anonym.png') . '" title="Anonym">
                <span id="user_info">
                     ' . $this->LL('welcome', ['username' => app('auth')->user()->username]) . '
                </span>
                <i class="fa fa-caret-down"></i>
            </a>');

        return $collection;
    }

    public function topMenuLogout()
    {
        $collection = collect();

        $collection->put('parent', 'user-name');
        $collection->put('key', 'log-off');
        $collection->put('order', 100000);
        $collection->put('devider_before', false);
        $collection->put('devider_after', false);
        $collection->put('content', '<a href="#" onclick="jQuery.ajax({url:\'' . route('telenok.logout') . '\', method: \'post\'}).done(function() { window.location = window.location; } ); return false;"><i class="fa fa-power-off"></i> ' . $this->LL('btn.logout') . '</a>');

        return $collection;
    }

    public function topMenuProfileEdit()
    {
        $collection = collect();

        $collection->put('parent', 'user-name');
        $collection->put('key', 'log-off');
        $collection->put('order', 1000);
        $collection->put('devider_before', false);
        $collection->put('devider_after', false);
        $collection->put('content', '<a href="#" onclick=\'
            telenok.addModule("object-sequence", "/telenok/module/users-profile-edit/action-param",
                function(moduleKey) {
                    param = telenok.getModule(moduleKey);
                    param.addTree = false;
                    param.addTab = true;
                    telenok.setModuleParam(moduleKey, param);
                    telenok.processModuleContent(moduleKey);
                }
            ); return false;\'><i class="fa fa-power-off"></i> ' . $this->LL('btn.profile') . '</a>');

        return $collection;
    }

}
