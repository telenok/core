<?php namespace Telenok\Core\Interfaces\Widget\Group;

class Controller extends \Telenok\Core\Interfaces\Controller\Controller { 
 
    protected $icon = 'fa fa-desktop'; 
    protected $widgetGroupModel;

	public function __construct()
	{
		$this->languageDirectory = 'widget-group';

		parent::__construct();
	}
	
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

