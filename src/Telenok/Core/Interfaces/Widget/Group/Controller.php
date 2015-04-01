<?php namespace Telenok\Core\Interfaces\Widget\Group;

abstract class Controller extends \Telenok\Core\Interfaces\Controller\Controller { 
 
    protected $icon = 'fa fa-desktop';
    protected $languageDirectory = 'widget-group';
    protected $widgetGroupModel;

    public function getIcon()
    {
        return $this->icon;
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

