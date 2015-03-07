<?php

namespace Telenok\Core\Interfaces\Widget\Group;

abstract class Controller {
    
    use \Telenok\Core\Support\PackageLoad; 

    protected $key = '';
    protected $icon = 'fa fa-desktop'; 
	protected $package;
    protected $languageDirectory = 'widget-group';
    protected $widgetGroupModel;

    public function getName()
    {
        return $this->LL('name');
    }

    public function getIcon()
    {
        return $this->icon;
    }

    public function getKey()
    {
        return $this->key;
    }

    public function setWidgetGroupModel($model)
    {
        $this->widgetGroupModel = $model;

        return $this;
    }

    public function getWidgetGroupModel()
    {
        return $this->widgetGroupModel;
    }
}

