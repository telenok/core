<?php

namespace Telenok\Core\Interfaces\Module;

abstract class Controller extends \Illuminate\Routing\Controller implements \Telenok\Core\Interfaces\Module\IModule {

    use \Telenok\Core\Support\PackageLoad;
    
    protected $key = '';
    protected $permissionKey = '';
    protected $parent = '';
    protected $group = '';
    protected $icon = 'fa fa-desktop'; 
    protected $package = '';
    protected $languageDirectory = 'module';
    protected $modelModule; 
    protected $modelRepository;
    protected $request; 

    public function __construct()
    {
        if (!\App::runningInConsole())
        {
            $this->beforeFilter('auth');
            $this->beforeFilter(function()
            {
                if (!\Auth::can('read', $this->getPermissionKey()))
                {
                    return \Redirect::route('error.access-denied');
                }
            });
        } 
    }
	
    public function getName()
    {
        return $this->LL('name');
    }
    
    public function getHeader()
    {
        return $this->LL('header.title');
    }    
    
    public function getHeaderDescription()
    {
        return $this->LL('header.description');
    }    

    public function setKey($key)
    {
        $this->key = $key;
        
        return $this;
    }

    public function getKey()
    {
        return $this->key;
    }

    public function setPermissionKey($param = '')
    {
        $this->permissionKey = $param;

        return $this;
    }	
    
    public function setRequest(\Illuminate\Http\Request $param = null)
    {
        $this->request = $param;
        
        return $this;
    }

    public function getRequest()
    {
        return $this->request;
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
        return app('telenok.config')->getModule()->filter(function($item) 
        {
            return $this->getKey() == $item->getParent();
        });
    }

    public function parent()
    {
        if (!$this->getParent()) return false;
        
        return app('telenok.config')->getModule()->get($this->getParent());
    }

    public function isParentAndSingle()
    {
        $collection = app('telenok.config')->getModule()->filter(function($item) {
            return $item->getParent() == $this->getKey();
        });
        
        return !$this->getParent() && $collection->isEmpty();
    }  

    public function getRouterActionParam($param = [])
    {
		return \URL::route("cmf.module.{$this->getKey()}.action.param", $param);
    }  
	
    public function getActionParam()
    {
        return json_encode(array(
            'presentationBlockKey' => $this->getPresentation(),
			'presentationModuleKey' => $this->getPresentationModuleKey(),
            'presentationBlockContent' => $this->getPresentationContent(),
            'key' => $this->getKey(),
            'url' => \URL::route("cmf.module.{$this->getKey()}"),
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

