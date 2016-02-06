<?php namespace Telenok\Core\Interfaces\Module;

/**
 * @class Telenok.Core.Interfaces.Module.Controller
 * @extends Telenok.Core.Interfaces.Controller.Controller
 */
class Controller extends \Telenok\Core\Interfaces\Controller\Controller implements \Telenok\Core\Interfaces\Module\IModule {

    protected $permissionKey = '';
    protected $parent = '';
    protected $group = '';
    protected $icon = 'fa fa-desktop';  
    protected $modelModule; 
    protected $modelRepository; 
    protected $languageDirectory = 'module';

    public function __construct()
    {
        $this->middleware('auth.backend.module:' . $this->getPermissionKey()); 
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

    public function parent_()
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
		return route($this->getVendorName() . ".module.{$this->getKey()}.action.param", $param);
    }  
	
    public function getActionParam()
    {
        return json_encode(array(
            'presentationBlockKey' => $this->getPresentation(),
			'presentationModuleKey' => $this->getPresentationModuleKey(),
            'presentationBlockContent' => $this->getPresentationContent(),
            'key' => $this->getKey(),
            'url' => route($this->getVendorName() . ".module.{$this->getKey()}"),
            'breadcrumbs' => $this->getBreadcrumbs(),
            'pageHeader' => $this->getPageHeader(), 
        ));
    }

    public function getBreadcrumbs()
    {
        $breadcrumbs = [];
        
        if ($this->getParent()) $breadcrumbs[] = $this->parent_()->getName();
        
        $breadcrumbs[] = $this->getName();
        
        return $breadcrumbs;
    }

    public function getPageHeader()
    {
        return [$this->getHeader(), $this->getHeaderDescription()];
    }


}

