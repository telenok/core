<?php namespace Telenok\Core\Interfaces\Module\Group;

class Controller extends \Telenok\Core\Interfaces\Controller\Controller { 
     
    protected $icon = 'fa fa-desktop'; 
    protected $btn = 'btn-info'; 
    protected $modelGroupModule;  
 
	public function __construct()
	{
		$this->languageDirectory = 'module-group';
	}
	
    public function getButton()
    {
        return $this->btn;
    }

    public function getIcon()
    {
        return $this->icon;
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