<?php namespace Telenok\Core\Abstraction\Widget\Group;

/**
 * @class Telenok.Core.Abstraction.Widget.Group.Controller
 * Base controller for group widgets.
 * 
 * @extends Telenok.Core.Abstraction.Controller.Controller
 */
abstract class Controller extends \Telenok\Core\Abstraction\Controller\Controller { 
 
    /**
     * @protected
     * @property {String} $icon
     * Class of widget's icon.
     * @member Telenok.Core.Abstraction.Widget.Group.Controller
     */	
    protected $icon = 'fa fa-desktop';
    
    /**
     * @protected
     * @property {Telenok.Core.Abstraction.Eloquent.Object.Model} $widgetModel
     * Model present group widget in database.
     * @member Telenok.Core.Abstraction.Widget.Group.Controller
     */
    protected $widgetGroupModel;
    
    /**
     * @protected
     * @property {String} $languageDirectory
     * Language directory for widgets.
     * @member Telenok.Core.Abstraction.Widget.Group.Controller
     */
    protected $languageDirectory = 'widget-group';

    /**
     * @method getIcon
     * Return icon class.
     * @return {String}
     * @member Telenok.Core.Abstraction.Widget.Group.Controller
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * @method setWidgetGroupModel
     * Set group widget's model.
     * @param {Telenok.Core.Abstraction.Eloquent.Object.Model} $param
     * @return {Telenok.Core.Abstraction.Widget.Group.Controller}
     * @member Telenok.Core.Abstraction.Widget.Group.Controller
     */
    public function setWidgetGroupModel($model)
    {
        $this->widgetGroupModel = $model;

        return $this;
    }

    /**
     * @method getWidgetGroupModel
     * Return group widget's model.
     * @return {Telenok.Core.Abstraction.Widget.Group.Controller}
     * @member Telenok.Core.Abstraction.Widget.Group.Controller
     */
    public function getWidgetGroupModel()
    {
        return $this->widgetGroupModel;
    }

    /**
     * @method children
     * Return children for current widget group.
     * @return {Illuminate.Support.Collection}
     * @member Telenok.Core.Abstraction.Widget.Group.Controller
     */
	public function children()
	{
		return app('telenok.config.repository')->getWidget()->filter(function($item)
				{
					return $this->getKey() == $item->getParent();
				});
	}
}
