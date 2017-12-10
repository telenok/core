<?php namespace Telenok\Core\Abstraction\Module;

/**
 * @class Telenok.Core.Abstraction.Module.Controller
 * @extends Telenok.Core.Abstraction.Controller.Controller
 */
abstract class Controller extends \Telenok\Core\Abstraction\Controller\Controller implements \Telenok\Core\Contract\Module\Module {

    protected $permissionKey = '';
    protected $parent = '';
    protected $group = '';
    protected $icon = 'fa fa-desktop';  
    protected $modelModule; 
    protected $modelRepository;
    protected $languageDirectory = 'module';
    protected $order = 1;

    public function __construct()
    {
        $this->middleware('auth.backend.module:' . $this->getPermissionKey()); 
    }

    public function getHeader()
    {
        return $this->LL('header.title');
    }

    public function getOrder()
    {
        return $this->order;
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
        return app('telenok.repository')->getModule()->filter(function($item)
        {
            return $this->getKey() == $item->getParent();
        });
    }

    public function parent_()
    {
        if (!$this->getParent()) return false;
        
        return app('telenok.repository')->getModule()->get($this->getParent());
    }

    public function isParentAndSingle()
    {
        $collection = app('telenok.repository')->getModule()->filter(function($item) {
            return $item->getParent() == $this->getKey();
        });
        
        return !$this->getParent() && $collection->isEmpty();
    }  

    public function getRouterActionParam($param = [])
    {
		return app('router')->has($name = $this->getVendorName() . ".module.{$this->getKey()}.action.param") ? route($name, $param) : '';
    }  
	
    public function getActionParam()
    {
        return json_encode(array(
            'key' => $this->getKey(),
            'url' => app('router')->has($route = $this->getVendorName() . ".module.{$this->getKey()}") ? route($route): '/module-route-not-exists',
            'breadcrumbs' => $this->getBreadcrumbs(),
            'pageHeader' => $this->getPageHeader(), 
        ));
    }

    /**
     * @return string
     */
    public function getContentNavigoHandler()
    {
        $actionParams = is_array($actionParam = $this->getActionParam()) ? $actionParam : json_decode($actionParam);
        $url = is_array($actionParams)?array_get($actionParams, 'url') : object_get($actionParams, 'url');

        $str = '
                (function (params, query)
                {
        ';

        if ($this->getRouterActionParam()) {
            $str .= '
                        jQuery("ul.telenok-sidebar li").removeClass("active");
                        jQuery("a[data-menu=\'module-' . $this->getParent() . '\']").closest("li").addClass("open active");
                        jQuery("a[data-menu=\'module-' . $this->getParent() . '-' . $this->getKey() . '\']").closest("li").addClass("active");
                ';
        } else {
            $str .= '
                        jQuery("ul.telenok-sidebar li").removeClass("active");
                        jQuery("a[data-menu=\'module-' . $this->getKey() . '\']").closest("li").addClass("open active");
                ';
        }

        $str .= '        
                        return telenok.addModule(
                            "' . $this->getKey() . '",
                            "' . $this->getRouterActionParam() . '",
                            function(moduleKey)
                            {
                                var deferred = jQuery.Deferred();

                                var presentationParams = telenok.getModule(moduleKey) || {};
                                telenok.processModuleContent(moduleKey);
                ';

        if ($url) {
            $str .= '
                                jQuery(document).on("'. $this->getKey() . '.list.load.complete", function() {
                                    deferred.resolve();
                                });
                                
                                telenok.getPresentation(presentationParams.presentationModuleKey).addTabByURL(jQuery.extend({}, {
                                        url: "' . $url . '"
                                    }, ' . ($actionParams ? json_encode($actionParams) : "{}") . '));
                            ';
        } else {
            $str .= '
                                deferred.resolve();
            ';
        }
        $str .= '
                                return deferred;
                            }
                        );
                    }
                )
        ';

        return $str;
    }

    /**
     * @method getNavigoRouterCode
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
     */
    public function getNavigoRouterCode()
    {
        $str = '
            (function() {
/*
                telenok
                    .getRouter()
                    .on({
                        "/module/' . $this->getKey() . '" : ' . $this->getContentNavigoHandler() . '
                    }).resolve();
*/
            })();
        ';

        return $str;
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
