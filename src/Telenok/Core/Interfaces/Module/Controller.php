<?php namespace Telenok\Core\Interfaces\Module;

class Controller extends \Telenok\Core\Interfaces\Controller\Controller implements \Telenok\Core\Interfaces\Module\IModule {

    protected $permissionKey = '';
    protected $parent = '';
    protected $group = '';
    protected $icon = 'fa fa-desktop';  
    protected $modelModule; 
    protected $modelRepository; 

    public function __construct()
    {
		if (!app()->runningInConsole())
		{
			$this->beforeFilter(function()
			{
				if (!app('auth')->can('read', $this->getPermissionKey()))
				{
					return app('redirect')->route('error.access-denied');
				}
			});
		}
		
		$this->languageDirectory = 'module';
    }

    public function getHeader()
    {
        return $this->LL('header.title');
    }    

    public function getHeaderDescription()
    {
        return $this->LL('header.description');
    }

    public function setPermissionKey($param = '')
    {
        $this->permissionKey = $param;

        return $this;
    }	

    public function getPermissionKey()
    {
        return $this->permissionKey ?: 'module.' . $this->getKey();
    }	

    public function getParent()
    {
        return $this->parent;
    }  

    public function getIcon()
    {
        return $this->icon;
    }

    public function getGroup()
    {
        return $this->group;
    }  
    
    public function setModelModule($model)
    {
        $this->modelModule = $model;
        
        return $this;
    }
    
    public function getModelModule()
    {
        return $this->modelModule;
    }

    public function children()
    {
        return app('telenok.config.repository')->getModule()->filter(function($item) 
        {
            return $this->getKey() == $item->getParent();
        });
    }

    public function parent()
    {
        if (!$this->getParent()) return false;
        
        return app('telenok.config.repository')->getModule()->get($this->getParent());
    }

    public function isParentAndSingle()
    {
        $collection = app('telenok.config.repository')->getModule()->filter(function($item) {
            return $item->getParent() == $this->getKey();
        });
        
        return !$this->getParent() && $collection->isEmpty();
    }  

    public function getRouterActionParam($param = [])
    {
		return route("cmf.module.{$this->getKey()}.action.param", $param);
    }  
	
    public function getActionParam()
    {
        return json_encode(array(
            'presentationBlockKey' => $this->getPresentation(),
			'presentationModuleKey' => $this->getPresentationModuleKey(),
            'presentationBlockContent' => $this->getPresentationContent(),
            'key' => $this->getKey(),
            'url' => route("cmf.module.{$this->getKey()}"),
            'breadcrumbs' => $this->getBreadcrumbs(),
            'pageHeader' => $this->getPageHeader(), 
        ));
    }

    public function getBreadcrumbs()
    {
        $breadcrumbs = [];
        
        if ($this->getParent()) $breadcrumbs[] = $this->parent()->getName();
        
        $breadcrumbs[] = $this->getName();
        
        return $breadcrumbs;
    }

    public function getPageHeader()
    {
        return [$this->getHeader(), $this->getHeaderDescription()];
    }


}

