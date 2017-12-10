<?php namespace Telenok\Core\Abstraction\Module\Group;

/**
 * @class Telenok.Core.Abstraction.Module.Group.Controller
 * @aside guide guide_user_module_group 
 */
abstract class Controller extends \Telenok\Core\Abstraction\Controller\Controller { 
     
    protected $icon = 'fa fa-desktop'; 
    protected $btn = 'btn-info'; 
    protected $modelGroupModule;
    protected $languageDirectory = 'module-group';
    protected $order = 1;

    public function getButton()
    {
        return $this->btn;
    }

    public function getIcon()
    {
        return $this->icon;
    }

    public function getOrder()
    {
        return $this->order;
    }

    public function setModelModuleGroup($model)
    {
        $this->modelGroupModule = $model;
        
        return $this;
    }
    
    public function getModelModuleGroup()
    {
        return $this->modelGroupModule;
    }
}