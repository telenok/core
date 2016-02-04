<?php namespace Telenok\Core\Interfaces\Presentation\TreeTab;

use \Telenok\Core\Interfaces\Presentation\IPresentation;
use \Telenok\Core\Interfaces\Controller\IEloquentProcessController;

/**
 * @class Telenok.Core.Interfaces.Presentation.TreeTab.Controller
 * Base controller for presentation "tree-tab".
 * 
 * @uses Telenok.Core.Interfaces.Presentation.IPresentation
 * @uses Telenok.Core.Interfaces.Controller.IEloquentProcessController
 * @extends Telenok.Core.Interfaces.Module.Controller
 */
class Controller extends \Telenok\Core\Interfaces\Module\Controller implements IPresentation, IEloquentProcessController {

    /**
     * @protected
     * @property {String} $tabKey
     * Key of presentation's tabs.
     * @member Telenok.Core.Interfaces.Presentation.TreeTab.Controller
     */	
    protected $tabKey = '';

    /**
     * @protected
     * @property {String} $presentation
     * Key of presentation.
     * @member Telenok.Core.Interfaces.Presentation.TreeTab.Controller
     */	
    protected $presentation = 'tree-tab';
    
    /**
     * @protected
     * @property {String} $presentationModuleKey
     * Key of presentation's module.
     * @member Telenok.Core.Interfaces.Presentation.TreeTab.Controller
     */	
    protected $presentationModuleKey = '';
    
    /**
     * @protected
     * @property {String} $presentationView
     * Presentation's initial view.
     * @member Telenok.Core.Interfaces.Presentation.TreeTab.Controller
     */	
    protected $presentationView = 'core::presentation.tree-tab.presentation';
    
    /**
     * @protected
     * @property {String} $presentationTreeView
     * Presentation's initial tree's view.
     * @member Telenok.Core.Interfaces.Presentation.TreeTab.Controller
     */	
    protected $presentationTreeView = 'core::presentation.tree-tab.tree';
    
    /**
     * @protected
     * @property {String} $presentationContentView
     * Presentation's initial content's 'view.
     * @member Telenok.Core.Interfaces.Presentation.TreeTab.Controller
     */	
    protected $presentationContentView = 'core::presentation.tree-tab.content';
    
    /**
     * @protected
     * @property {String} $presentationModelView
     * Presentation's initial model's view.
     * @member Telenok.Core.Interfaces.Presentation.TreeTab.Controller
     */	
    protected $presentationModelView = 'core::presentation.tree-tab.model';
    
    /**
     * @protected
     * @property {String} $presentationFormModelView
     * Presentation's initial model's form view.
     * @member Telenok.Core.Interfaces.Presentation.TreeTab.Controller
     */	
    protected $presentationFormModelView = 'core::presentation.tree-tab.form';
    
    /**
     * @protected
     * @property {String} $presentationFormFieldListView
     * Presentation's initial models' fields view.
     * @member Telenok.Core.Interfaces.Presentation.TreeTab.Controller
     */	
    protected $presentationFormFieldListView = 'core::presentation.tree-tab.form-field-list';
    
    /**
     * @protected
     * @property {String} $routerActionParam
     * Name of custom action-param router.
     * @member Telenok.Core.Interfaces.Presentation.TreeTab.Controller
     */	
    protected $routerActionParam = '';
    
    /**
     * @protected
     * @property {String} $routerList
     * Name of custom list router.
     * @member Telenok.Core.Interfaces.Presentation.TreeTab.Controller
     */	
    protected $routerList = '';
    
    /**
     * @protected
     * @property {String} $routerContent
     * Name of custom content router.
     * @member Telenok.Core.Interfaces.Presentation.TreeTab.Controller
     */	
    protected $routerContent = '';
    
    /**
     * @protected
     * @property {String} $routerCreate
     * Name of custom create router.
     * @member Telenok.Core.Interfaces.Presentation.TreeTab.Controller
     */	
    protected $routerCreate = '';
    
    /**
     * @protected
     * @property {String} $routerEdit
     * Name of custom edit router.
     * @member Telenok.Core.Interfaces.Presentation.TreeTab.Controller
     */	
    protected $routerEdit = '';
    
    /**
     * @protected
     * @property {String} $routerDelete
     * Name of custom delete router.
     * @member Telenok.Core.Interfaces.Presentation.TreeTab.Controller
     */	
    protected $routerDelete = '';
    
    /**
     * @protected
     * @property {String} $routerStore
     * Name of custom store router.
     * @member Telenok.Core.Interfaces.Presentation.TreeTab.Controller
     */	
    protected $routerStore = '';
    
    /**
     * @protected
     * @property {String} $routerUpdate
     * Name of custom update router.
     * @member Telenok.Core.Interfaces.Presentation.TreeTab.Controller
     */	
    protected $routerUpdate = '';
    
    /**
     * @protected
     * @property {String} $routerListEdit
     * Name of custom edit list router.
     * @member Telenok.Core.Interfaces.Presentation.TreeTab.Controller
     */	
    protected $routerListEdit = '';
    
    /**
     * @protected
     * @property {String} $routerListDelete
     * Name of custom delete list router.
     * @member Telenok.Core.Interfaces.Presentation.TreeTab.Controller
     */	
    protected $routerListDelete = '';
    
    /**
     * @protected
     * @property {String} $routerLock
     * Name of custom lock router.
     * @member Telenok.Core.Interfaces.Presentation.TreeTab.Controller
     */	
    protected $routerLock = '';
    
    /**
     * @protected
     * @property {String} $routerListLock
     * Name of custom list lock router.
     * @member Telenok.Core.Interfaces.Presentation.TreeTab.Controller
     */	
    protected $routerListLock = '';
    
    /**
     * @protected
     * @property {String} $routerListUnlock
     * Name of custom list unlock router.
     * @member Telenok.Core.Interfaces.Presentation.TreeTab.Controller
     */	
    protected $routerListUnlock = '';
    
    /**
     * @protected
     * @property {String} $routerListTree
     * Name of custom tree router.
     * @member Telenok.Core.Interfaces.Presentation.TreeTab.Controller
     */	
    protected $routerListTree = '';
    
    /**
     * @protected
     * @property {String} $modelListClass
     * Class name of model in list view.
     * @member Telenok.Core.Interfaces.Presentation.TreeTab.Controller
     */	
    protected $modelListClass = '';
    
    /**
     * @protected
     * @property {String} $modelTreeClass
     * Class name of model in tree view.
     * @member Telenok.Core.Interfaces.Presentation.TreeTab.Controller
     */	
    protected $modelTreeClass = '';
    
    /**
     * @protected
     * @property {String} $pageLength
     * Amount of rows to show in list.
     * @member Telenok.Core.Interfaces.Presentation.TreeTab.Controller
     */	
    protected $pageLength = 15;
    
    /**
     * @protected
     * @property {String} $additionalViewParam
     * Additional view parameters.
     * @member Telenok.Core.Interfaces.Presentation.TreeTab.Controller
     */	
    protected $additionalViewParam = [];
    
    /**
     * @protected
     * @property {String} $lockInListPeriod
     * Amount of minutes to lock model's record after it locked in list.
     * @member Telenok.Core.Interfaces.Presentation.TreeTab.Controller
     */	
    protected $lockInListPeriod = 10;
    
    /**
     * @protected
     * @property {String} $lockInFormPeriod
     * Amount of minutes to lock model's record after it opened in form.
     * @member Telenok.Core.Interfaces.Presentation.TreeTab.Controller
     */	
    protected $lockInFormPeriod = 20;
    
    /**
     * @protected
     * @property {String} $displayType
     * Presentation's initial view.
     * @member Telenok.Core.Interfaces.Presentation.TreeTab.Controller
     */	
    protected $displayType = 1;
    
    /**
     * @static
     * @protected
     * @property {Integer} $DISPLAY_TYPE_STANDART
     * Whether model showed in form in tab.
     * @member Telenok.Core.Interfaces.Presentation.TreeTab.Controller
     */	
    public static $DISPLAY_TYPE_STANDART = 1;
    
    /**
     * @static
     * @protected
     * @property {Integer} $DISPLAY_TYPE_WIZARD
     * Whether model showed in form in modal window.
     * @member Telenok.Core.Interfaces.Presentation.TreeTab.Controller
     */	
    public static $DISPLAY_TYPE_WIZARD = 2;

    /**
     * @method setWidgetGroupModel
     * Set group widget's model.
     * @param {Telenok.Core.Interfaces.Eloquent.Object.Model} $param
     * @return {Telenok.Core.Interfaces.Widget.Group.Controller}
     * @member Telenok.Core.Interfaces.Presentation.TreeTab.Controller
     */
    public function getLockInListPeriod()
    {
        return $this->lockInListPeriod;
    }

    /**
     * @method setWidgetGroupModel
     * Set group widget's model.
     * @param {Telenok.Core.Interfaces.Eloquent.Object.Model} $param
     * @return {Telenok.Core.Interfaces.Widget.Group.Controller}
     * @member Telenok.Core.Interfaces.Presentation.TreeTab.Controller
     */
    public function setLockInListPeriod($param = 3600)
    {
        $this->lockInListPeriod = $param;

        return $this;
    }
    
    /**
     * @method setWidgetGroupModel
     * Set group widget's model.
     * @param {Telenok.Core.Interfaces.Eloquent.Object.Model} $param
     * @return {Telenok.Core.Interfaces.Widget.Group.Controller}
     * @member Telenok.Core.Interfaces.Presentation.TreeTab.Controller
     */
    public function getLockInFormPeriod()
    {
        return $this->lockInFormPeriod;
    }

    /**
     * @method setWidgetGroupModel
     * Set group widget's model.
     * @param {Telenok.Core.Interfaces.Eloquent.Object.Model} $param
     * @return {Telenok.Core.Interfaces.Widget.Group.Controller}
     * @member Telenok.Core.Interfaces.Presentation.TreeTab.Controller
     */
    public function setLockInFormPeriod($param = 300)
    {
        $this->lockInFormPeriod = $param;

        return $this;
    }

    /**
     * @method setWidgetGroupModel
     * Set group widget's model.
     * @param {Telenok.Core.Interfaces.Eloquent.Object.Model} $param
     * @return {Telenok.Core.Interfaces.Widget.Group.Controller}
     * @member Telenok.Core.Interfaces.Presentation.TreeTab.Controller
     */
    public function getPresentation()
    {
        return $this->presentation;
    }

    /**
     * @method setWidgetGroupModel
     * Set group widget's model.
     * @param {Telenok.Core.Interfaces.Eloquent.Object.Model} $param
     * @return {Telenok.Core.Interfaces.Widget.Group.Controller}
     * @member Telenok.Core.Interfaces.Presentation.TreeTab.Controller
     */
    public function setPresentation($key)
    {
        $this->presentation = $key;

        return $this;
    }

    /**
     * @method setWidgetGroupModel
     * Set group widget's model.
     * @param {Telenok.Core.Interfaces.Eloquent.Object.Model} $param
     * @return {Telenok.Core.Interfaces.Widget.Group.Controller}
     * @member Telenok.Core.Interfaces.Presentation.TreeTab.Controller
     */
    public function getPresentationModuleKey()
    {
        return $this->presentationModuleKey ? : $this->presentation . '-' . $this->getKey();
    }

    /**
     * @method setWidgetGroupModel
     * Set group widget's model.
     * @param {Telenok.Core.Interfaces.Eloquent.Object.Model} $param
     * @return {Telenok.Core.Interfaces.Widget.Group.Controller}
     * @member Telenok.Core.Interfaces.Presentation.TreeTab.Controller
     */
    public function setPresentationModuleKey($key)
    {
        $this->presentationModuleKey = $key;

        return $this;
    }

    /**
     * @method setWidgetGroupModel
     * Set group widget's model.
     * @param {Telenok.Core.Interfaces.Eloquent.Object.Model} $param
     * @return {Telenok.Core.Interfaces.Widget.Group.Controller}
     * @member Telenok.Core.Interfaces.Presentation.TreeTab.Controller
     */
    public function getPresentationView()
    {
        return $this->presentationView;
    }

    /**
     * @method setWidgetGroupModel
     * Set group widget's model.
     * @param {Telenok.Core.Interfaces.Eloquent.Object.Model} $param
     * @return {Telenok.Core.Interfaces.Widget.Group.Controller}
     * @member Telenok.Core.Interfaces.Presentation.TreeTab.Controller
     */
    public function setPresentationView($key)
    {
        $this->presentationView = $key;

        return $this;
    }

    /**
     * @method setWidgetGroupModel
     * Set group widget's model.
     * @param {Telenok.Core.Interfaces.Eloquent.Object.Model} $param
     * @return {Telenok.Core.Interfaces.Widget.Group.Controller}
     * @member Telenok.Core.Interfaces.Presentation.TreeTab.Controller
     */
    public function getPresentationTreeView()
    {
        return $this->presentationTreeView;
    }

    /**
     * @method setWidgetGroupModel
     * Set group widget's model.
     * @param {Telenok.Core.Interfaces.Eloquent.Object.Model} $param
     * @return {Telenok.Core.Interfaces.Widget.Group.Controller}
     * @member Telenok.Core.Interfaces.Presentation.TreeTab.Controller
     */
    public function setPresentationTreeView($key)
    {
        $this->presentationTreeView = $key;

        return $this;
    }

    /**
     * @method setWidgetGroupModel
     * Set group widget's model.
     * @param {Telenok.Core.Interfaces.Eloquent.Object.Model} $param
     * @return {Telenok.Core.Interfaces.Widget.Group.Controller}
     * @member Telenok.Core.Interfaces.Presentation.TreeTab.Controller
     */
    public function getPresentationContentView()
    {
        return $this->presentationContentView;
    }

    /**
     * @method setWidgetGroupModel
     * Set group widget's model.
     * @param {Telenok.Core.Interfaces.Eloquent.Object.Model} $param
     * @return {Telenok.Core.Interfaces.Widget.Group.Controller}
     * @member Telenok.Core.Interfaces.Presentation.TreeTab.Controller
     */
    public function setPresentationContentView($key)
    {
        $this->presentationContentView = $key;

        return $this;
    }

    /**
     * @method setWidgetGroupModel
     * Set group widget's model.
     * @param {Telenok.Core.Interfaces.Eloquent.Object.Model} $param
     * @return {Telenok.Core.Interfaces.Widget.Group.Controller}
     * @member Telenok.Core.Interfaces.Presentation.TreeTab.Controller
     */
    public function getPresentationModelView()
    {
        return $this->presentationModelView;
    }

    /**
     * @method setWidgetGroupModel
     * Set group widget's model.
     * @param {Telenok.Core.Interfaces.Eloquent.Object.Model} $param
     * @return {Telenok.Core.Interfaces.Widget.Group.Controller}
     * @member Telenok.Core.Interfaces.Presentation.TreeTab.Controller
     */
    public function setPresentationModelView($key)
    {
        $this->presentationModelView = $key;

        return $this;
    }

    /**
     * @method setWidgetGroupModel
     * Set group widget's model.
     * @param {Telenok.Core.Interfaces.Eloquent.Object.Model} $param
     * @return {Telenok.Core.Interfaces.Widget.Group.Controller}
     * @member Telenok.Core.Interfaces.Presentation.TreeTab.Controller
     */
    public function getPresentationFormFieldListView()
    {
        return $this->presentationFormFieldListView;
    }

    /**
     * @method setWidgetGroupModel
     * Set group widget's model.
     * @param {Telenok.Core.Interfaces.Eloquent.Object.Model} $param
     * @return {Telenok.Core.Interfaces.Widget.Group.Controller}
     * @member Telenok.Core.Interfaces.Presentation.TreeTab.Controller
     */
    public function setPresentationFormFieldListView($key)
    {
        $this->presentationFormFieldListView = $key;

        return $this;
    }

    /**
     * @method setWidgetGroupModel
     * Set group widget's model.
     * @param {Telenok.Core.Interfaces.Eloquent.Object.Model} $param
     * @return {Telenok.Core.Interfaces.Widget.Group.Controller}
     * @member Telenok.Core.Interfaces.Presentation.TreeTab.Controller
     */
    public function getPresentationFormModelView()
    {
        return $this->presentationFormModelView;
    }

    /**
     * @method setWidgetGroupModel
     * Set group widget's model.
     * @param {Telenok.Core.Interfaces.Eloquent.Object.Model} $param
     * @return {Telenok.Core.Interfaces.Widget.Group.Controller}
     * @member Telenok.Core.Interfaces.Presentation.TreeTab.Controller
     */
    public function setPresentationFormModelView($key)
    {
        $this->presentationFormModelView = $key;

        return $this;
    }

    /**
     * @method setWidgetGroupModel
     * Set group widget's model.
     * @param {Telenok.Core.Interfaces.Eloquent.Object.Model} $param
     * @return {Telenok.Core.Interfaces.Widget.Group.Controller}
     * @member Telenok.Core.Interfaces.Presentation.TreeTab.Controller
     */
    public function getTabKey()
    {
        return $this->tabKey ? : $this->getKey();
    }

    /**
     * @method setWidgetGroupModel
     * Set group widget's model.
     * @param {Telenok.Core.Interfaces.Eloquent.Object.Model} $param
     * @return {Telenok.Core.Interfaces.Widget.Group.Controller}
     * @member Telenok.Core.Interfaces.Presentation.TreeTab.Controller
     */
    public function setTabKey($key)
    {
        $this->tabKey = $key;

        return $this;
    }

    /**
     * @method setWidgetGroupModel
     * Set group widget's model.
     * @param {Telenok.Core.Interfaces.Eloquent.Object.Model} $param
     * @return {Telenok.Core.Interfaces.Widget.Group.Controller}
     * @member Telenok.Core.Interfaces.Presentation.TreeTab.Controller
     */
    public function setRouterActionParam($param)
    {
        $this->routerActionParam = $param;

        return $this;
    }

    /**
     * @method setWidgetGroupModel
     * Set group widget's model.
     * @param {Telenok.Core.Interfaces.Eloquent.Object.Model} $param
     * @return {Telenok.Core.Interfaces.Widget.Group.Controller}
     * @member Telenok.Core.Interfaces.Presentation.TreeTab.Controller
     */
    public function getRouterActionParam($param = [])
    {
        return route($this->routerActionParam ? : $this->getVendorName() . ".module.{$this->getKey()}.action.param", $param);
    }

    /**
     * @method setWidgetGroupModel
     * Set group widget's model.
     * @param {Telenok.Core.Interfaces.Eloquent.Object.Model} $param
     * @return {Telenok.Core.Interfaces.Widget.Group.Controller}
     * @member Telenok.Core.Interfaces.Presentation.TreeTab.Controller
     */
    public function setRouterList($param)
    {
        $this->routerList = $param;

        return $this;
    }
    
    /**
     * @method setWidgetGroupModel
     * Set group widget's model.
     * @param {Telenok.Core.Interfaces.Eloquent.Object.Model} $param
     * @return {Telenok.Core.Interfaces.Widget.Group.Controller}
     * @member Telenok.Core.Interfaces.Presentation.TreeTab.Controller
     */
    public function getRouterList($param = [])
    {
        return route($this->routerList ? : $this->getVendorName() . ".module.{$this->getKey()}.list", $param);
    }

    /**
     * @method setWidgetGroupModel
     * Set group widget's model.
     * @param {Telenok.Core.Interfaces.Eloquent.Object.Model} $param
     * @return {Telenok.Core.Interfaces.Widget.Group.Controller}
     * @member Telenok.Core.Interfaces.Presentation.TreeTab.Controller
     */
    public function setRouterContent($param)
    {
        $this->routerContent = $param;

        return $this;
    }

    /**
     * @method setWidgetGroupModel
     * Set group widget's model.
     * @param {Telenok.Core.Interfaces.Eloquent.Object.Model} $param
     * @return {Telenok.Core.Interfaces.Widget.Group.Controller}
     * @member Telenok.Core.Interfaces.Presentation.TreeTab.Controller
     */
    public function getRouterContent($param = [])
    {
        return route($this->routerContent ? : $this->getVendorName() . ".module.{$this->getKey()}", $param);
    }

    /**
     * @method setWidgetGroupModel
     * Set group widget's model.
     * @param {Telenok.Core.Interfaces.Eloquent.Object.Model} $param
     * @return {Telenok.Core.Interfaces.Widget.Group.Controller}
     * @member Telenok.Core.Interfaces.Presentation.TreeTab.Controller
     */
    public function setRouterCreate($param)
    {
        $this->routerCreate = $param;

        return $this;
    }

    /**
     * @method setWidgetGroupModel
     * Set group widget's model.
     * @param {Telenok.Core.Interfaces.Eloquent.Object.Model} $param
     * @return {Telenok.Core.Interfaces.Widget.Group.Controller}
     * @member Telenok.Core.Interfaces.Presentation.TreeTab.Controller
     */
    public function getRouterCreate($param = [])
    {
        return route($this->routerCreate ? : $this->getVendorName() . ".module.{$this->getKey()}." . ($this->isDisplayTypeWizard() ? "wizard." : "") . "create", $param);
    }

    /**
     * @method setWidgetGroupModel
     * Set group widget's model.
     * @param {Telenok.Core.Interfaces.Eloquent.Object.Model} $param
     * @return {Telenok.Core.Interfaces.Widget.Group.Controller}
     * @member Telenok.Core.Interfaces.Presentation.TreeTab.Controller
     */
    public function setRouterEdit($param)
    {
        $this->routerEdit = $param;

        return $this;
    }

    /**
     * @method setWidgetGroupModel
     * Set group widget's model.
     * @param {Telenok.Core.Interfaces.Eloquent.Object.Model} $param
     * @return {Telenok.Core.Interfaces.Widget.Group.Controller}
     * @member Telenok.Core.Interfaces.Presentation.TreeTab.Controller
     */
    public function getRouterEdit($param = [])
    {
        return route($this->routerEdit ? : $this->getVendorName() . ".module.{$this->getKey()}." . ($this->isDisplayTypeWizard() ? "wizard." : "") . "edit", $param);
    }

    /**
     * @method setWidgetGroupModel
     * Set group widget's model.
     * @param {Telenok.Core.Interfaces.Eloquent.Object.Model} $param
     * @return {Telenok.Core.Interfaces.Widget.Group.Controller}
     * @member Telenok.Core.Interfaces.Presentation.TreeTab.Controller
     */
    public function setRouterDelete($param)
    {
        $this->routerDelete = $param;

        return $this;
    }

    /**
     * @method setWidgetGroupModel
     * Set group widget's model.
     * @param {Telenok.Core.Interfaces.Eloquent.Object.Model} $param
     * @return {Telenok.Core.Interfaces.Widget.Group.Controller}
     * @member Telenok.Core.Interfaces.Presentation.TreeTab.Controller
     */
    public function getRouterDelete($param = [])
    {
        return route($this->routerDelete ? : $this->getVendorName() . ".module.{$this->getKey()}." . ($this->isDisplayTypeWizard() ? "wizard." : "") . "delete", $param);
    }

    /**
     * @method setWidgetGroupModel
     * Set group widget's model.
     * @param {Telenok.Core.Interfaces.Eloquent.Object.Model} $param
     * @return {Telenok.Core.Interfaces.Widget.Group.Controller}
     * @member Telenok.Core.Interfaces.Presentation.TreeTab.Controller
     */
    public function setRouterStore($param)
    {
        $this->routerStore = $param;

        return $this;
    }

    /**
     * @method setWidgetGroupModel
     * Set group widget's model.
     * @param {Telenok.Core.Interfaces.Eloquent.Object.Model} $param
     * @return {Telenok.Core.Interfaces.Widget.Group.Controller}
     * @member Telenok.Core.Interfaces.Presentation.TreeTab.Controller
     */
    public function getRouterStore($param = [])
    {
        return route($this->routerStore ? : $this->getVendorName() . ".module.{$this->getKey()}." . ($this->isDisplayTypeWizard() ? "wizard." : "") . "store", $param);
    }

    /**
     * @method setWidgetGroupModel
     * Set group widget's model.
     * @param {Telenok.Core.Interfaces.Eloquent.Object.Model} $param
     * @return {Telenok.Core.Interfaces.Widget.Group.Controller}
     * @member Telenok.Core.Interfaces.Presentation.TreeTab.Controller
     */
    public function setRouterUpdate($param)
    {
        $this->routerUpdate = $param;

        return $this;
    }

    /**
     * @method setWidgetGroupModel
     * Set group widget's model.
     * @param {Telenok.Core.Interfaces.Eloquent.Object.Model} $param
     * @return {Telenok.Core.Interfaces.Widget.Group.Controller}
     * @member Telenok.Core.Interfaces.Presentation.TreeTab.Controller
     */
    public function getRouterUpdate($param = [])
    {
        return route($this->routerUpdate ? : $this->getVendorName() . ".module.{$this->getKey()}." . ($this->isDisplayTypeWizard() ? "wizard." : "") . "update", $param);
    }

    /**
     * @method setWidgetGroupModel
     * Set group widget's model.
     * @param {Telenok.Core.Interfaces.Eloquent.Object.Model} $param
     * @return {Telenok.Core.Interfaces.Widget.Group.Controller}
     * @member Telenok.Core.Interfaces.Presentation.TreeTab.Controller
     */
    public function setRouterListEdit($param)
    {
        $this->routerListEdit = $param;

        return $this;
    }

    /**
     * @method setWidgetGroupModel
     * Set group widget's model.
     * @param {Telenok.Core.Interfaces.Eloquent.Object.Model} $param
     * @return {Telenok.Core.Interfaces.Widget.Group.Controller}
     * @member Telenok.Core.Interfaces.Presentation.TreeTab.Controller
     */
    public function getRouterListEdit($param = [])
    {
        return route($this->routerListEdit ? : $this->getVendorName() . ".module.{$this->getKey()}.list.edit", $param);
    }

    /**
     * @method setWidgetGroupModel
     * Set group widget's model.
     * @param {Telenok.Core.Interfaces.Eloquent.Object.Model} $param
     * @return {Telenok.Core.Interfaces.Widget.Group.Controller}
     * @member Telenok.Core.Interfaces.Presentation.TreeTab.Controller
     */
    public function setRouterListDelete($param)
    {
        $this->routerListDelete = $param;

        return $this;
    }

    /**
     * @method setWidgetGroupModel
     * Set group widget's model.
     * @param {Telenok.Core.Interfaces.Eloquent.Object.Model} $param
     * @return {Telenok.Core.Interfaces.Widget.Group.Controller}
     * @member Telenok.Core.Interfaces.Presentation.TreeTab.Controller
     */
    public function getRouterListDelete($param = [])
    {
        return route($this->routerListDelete ? : $this->getVendorName() . ".module.{$this->getKey()}.list.delete", $param);
    }

    /**
     * @method setWidgetGroupModel
     * Set group widget's model.
     * @param {Telenok.Core.Interfaces.Eloquent.Object.Model} $param
     * @return {Telenok.Core.Interfaces.Widget.Group.Controller}
     * @member Telenok.Core.Interfaces.Presentation.TreeTab.Controller
     */
    public function setRouterListLock($param)
    {
        $this->routerListLock = $param;

        return $this;
    }

    /**
     * @method setWidgetGroupModel
     * Set group widget's model.
     * @param {Telenok.Core.Interfaces.Eloquent.Object.Model} $param
     * @return {Telenok.Core.Interfaces.Widget.Group.Controller}
     * @member Telenok.Core.Interfaces.Presentation.TreeTab.Controller
     */
    public function getRouterLock($param = [])
    {
        return route($this->routerLock ? : $this->getVendorName() . ".module.{$this->getKey()}.lock", $param);
    }

    /**
     * @method setWidgetGroupModel
     * Set group widget's model.
     * @param {Telenok.Core.Interfaces.Eloquent.Object.Model} $param
     * @return {Telenok.Core.Interfaces.Widget.Group.Controller}
     * @member Telenok.Core.Interfaces.Presentation.TreeTab.Controller
     */
    public function getRouterListLock($param = [])
    {
        return route($this->routerListLock ? : $this->getVendorName() . ".module.{$this->getKey()}.list.lock", $param);
    }

    /**
     * @method setWidgetGroupModel
     * Set group widget's model.
     * @param {Telenok.Core.Interfaces.Eloquent.Object.Model} $param
     * @return {Telenok.Core.Interfaces.Widget.Group.Controller}
     * @member Telenok.Core.Interfaces.Presentation.TreeTab.Controller
     */
    public function setRouterListUnlock($param)
    {
        $this->routerListUnlock = $param;

        return $this;
    }

    /**
     * @method setWidgetGroupModel
     * Set group widget's model.
     * @param {Telenok.Core.Interfaces.Eloquent.Object.Model} $param
     * @return {Telenok.Core.Interfaces.Widget.Group.Controller}
     * @member Telenok.Core.Interfaces.Presentation.TreeTab.Controller
     */
    public function getRouterListUnlock($param = [])
    {
        return route($this->routerListUnlock ? : $this->getVendorName() . ".module.{$this->getKey()}.list.unlock", $param);
    }

    /**
     * @method setWidgetGroupModel
     * Set group widget's model.
     * @param {Telenok.Core.Interfaces.Eloquent.Object.Model} $param
     * @return {Telenok.Core.Interfaces.Widget.Group.Controller}
     * @member Telenok.Core.Interfaces.Presentation.TreeTab.Controller
     */
    public function setRouterListTree($param)
    {
        $this->routerListTree = $param;

        return $this;
    }

    /**
     * @method setWidgetGroupModel
     * Set group widget's model.
     * @param {Telenok.Core.Interfaces.Eloquent.Object.Model} $param
     * @return {Telenok.Core.Interfaces.Widget.Group.Controller}
     * @member Telenok.Core.Interfaces.Presentation.TreeTab.Controller
     */
    public function getRouterListTree($param = [])
    {
        return route($this->routerListTree ? : $this->getVendorName() . ".module.{$this->getKey()}.list.tree", $param);
    }

    /**
     * @method setWidgetGroupModel
     * Set group widget's model.
     * @param {Telenok.Core.Interfaces.Eloquent.Object.Model} $param
     * @return {Telenok.Core.Interfaces.Widget.Group.Controller}
     * @member Telenok.Core.Interfaces.Presentation.TreeTab.Controller
     */
    public function setModelListClass($param)
    {
        $this->modelListClass = $param;

        return $this;
    }

    /**
     * @method setWidgetGroupModel
     * Set group widget's model.
     * @param {Telenok.Core.Interfaces.Eloquent.Object.Model} $param
     * @return {Telenok.Core.Interfaces.Widget.Group.Controller}
     * @member Telenok.Core.Interfaces.Presentation.TreeTab.Controller
     */
    public function getModelListClass()
    {
        return $this->modelListClass;
    }

    /**
     * @method setWidgetGroupModel
     * Set group widget's model.
     * @param {Telenok.Core.Interfaces.Eloquent.Object.Model} $param
     * @return {Telenok.Core.Interfaces.Widget.Group.Controller}
     * @member Telenok.Core.Interfaces.Presentation.TreeTab.Controller
     */
    public function setModelTreeClass($param)
    {
        $this->modelTreeClass = $param;

        return $this;
    }

    /**
     * @method setWidgetGroupModel
     * Set group widget's model.
     * @param {Telenok.Core.Interfaces.Eloquent.Object.Model} $param
     * @return {Telenok.Core.Interfaces.Widget.Group.Controller}
     * @member Telenok.Core.Interfaces.Presentation.TreeTab.Controller
     */
    public function getModelTreeClass()
    {
        return $this->modelTreeClass;
    }

    /**
     * @method setWidgetGroupModel
     * Set group widget's model.
     * @param {Telenok.Core.Interfaces.Eloquent.Object.Model} $param
     * @return {Telenok.Core.Interfaces.Widget.Group.Controller}
     * @member Telenok.Core.Interfaces.Presentation.TreeTab.Controller
     */
    public function getModelList()
    {
        return app($this->getModelListClass());
    }

    /**
     * @method setWidgetGroupModel
     * Set group widget's model.
     * @param {Telenok.Core.Interfaces.Eloquent.Object.Model} $param
     * @return {Telenok.Core.Interfaces.Widget.Group.Controller}
     * @member Telenok.Core.Interfaces.Presentation.TreeTab.Controller
     */
    public function getModelTree()
    {
        return app($this->getModelTreeClass());
    }

    /**
     * @method setWidgetGroupModel
     * Set group widget's model.
     * @param {Telenok.Core.Interfaces.Eloquent.Object.Model} $param
     * @return {Telenok.Core.Interfaces.Widget.Group.Controller}
     * @member Telenok.Core.Interfaces.Presentation.TreeTab.Controller
     */
    public function getTypeList()
    {
        return $this->getModelList()->type();
    }

    /**
     * @method setWidgetGroupModel
     * Set group widget's model.
     * @param {Telenok.Core.Interfaces.Eloquent.Object.Model} $param
     * @return {Telenok.Core.Interfaces.Widget.Group.Controller}
     * @member Telenok.Core.Interfaces.Presentation.TreeTab.Controller
     */
    public function getTypeTree()
    {
        return $this->getModelTree()->type();
    }

    /**
     * @method setWidgetGroupModel
     * Set group widget's model.
     * @param {Telenok.Core.Interfaces.Eloquent.Object.Model} $param
     * @return {Telenok.Core.Interfaces.Widget.Group.Controller}
     * @member Telenok.Core.Interfaces.Presentation.TreeTab.Controller
     */
    public function getModel($id)
    {
        return \App\Telenok\Core\Model\Object\Sequence::getModel($id);
    }

    /**
     * @method setWidgetGroupModel
     * Set group widget's model.
     * @param {Telenok.Core.Interfaces.Eloquent.Object.Model} $param
     * @return {Telenok.Core.Interfaces.Widget.Group.Controller}
     * @member Telenok.Core.Interfaces.Presentation.TreeTab.Controller
     */
    public function getModelTrashed($id)
    {
        return \App\Telenok\Core\Model\Object\Sequence::getModelTrashed($id);
    }

    /**
     * @method setWidgetGroupModel
     * Set group widget's model.
     * @param {Telenok.Core.Interfaces.Eloquent.Object.Model} $param
     * @return {Telenok.Core.Interfaces.Widget.Group.Controller}
     * @member Telenok.Core.Interfaces.Presentation.TreeTab.Controller
     */
    public function getType($id)
    {
        return \App\Telenok\Core\Model\Object\Type::where('id', $id)->orWhere('code', $id)->active()->firstOrFail();
    }

    /**
     * @method setWidgetGroupModel
     * Set group widget's model.
     * @param {Telenok.Core.Interfaces.Eloquent.Object.Model} $param
     * @return {Telenok.Core.Interfaces.Widget.Group.Controller}
     * @member Telenok.Core.Interfaces.Presentation.TreeTab.Controller
     */
    public function getTypeByModelId($id)
    {
        return \App\Telenok\Core\Model\Object\Sequence::withTrashed()->findOrFail($id)->sequencesObjectType;
    }

    /**
     * @method setWidgetGroupModel
     * Set group widget's model.
     * @param {Telenok.Core.Interfaces.Eloquent.Object.Model} $param
     * @return {Telenok.Core.Interfaces.Widget.Group.Controller}
     * @member Telenok.Core.Interfaces.Presentation.TreeTab.Controller
     */
    public function getModelByTypeId($id)
    {
        return app($this->getType($id)->class_model);
    }

    /**
     * @method setWidgetGroupModel
     * Set group widget's model.
     * @param {Telenok.Core.Interfaces.Eloquent.Object.Model} $param
     * @return {Telenok.Core.Interfaces.Widget.Group.Controller}
     * @member Telenok.Core.Interfaces.Presentation.TreeTab.Controller
     */
    public function validate($model = null, $input = null, $message = [])
    {
        return $this;
    }

    /**
     * @method setWidgetGroupModel
     * Set group widget's model.
     * @param {Telenok.Core.Interfaces.Eloquent.Object.Model} $param
     * @return {Telenok.Core.Interfaces.Widget.Group.Controller}
     * @member Telenok.Core.Interfaces.Presentation.TreeTab.Controller
     */
    public function validator($model = null, $input = [], $message = [], $customAttribute = [])
    {
        return app('\Telenok\Core\Interfaces\Validator\Model')
                        ->setModel($model ? : $this->getModelList())
                        ->setInput($input)
                        ->setMessage($message)
                        ->setCustomAttribute($customAttribute);
    }

    /**
     * @method setWidgetGroupModel
     * Set group widget's model.
     * @param {Telenok.Core.Interfaces.Eloquent.Object.Model} $param
     * @return {Telenok.Core.Interfaces.Widget.Group.Controller}
     * @member Telenok.Core.Interfaces.Presentation.TreeTab.Controller
     */
    public function validateException()
    {
        return new \Telenok\Core\Support\Exception\Validator;
    }

    /**
     * @method setWidgetGroupModel
     * Set group widget's model.
     * @param {Telenok.Core.Interfaces.Eloquent.Object.Model} $param
     * @return {Telenok.Core.Interfaces.Widget.Group.Controller}
     * @member Telenok.Core.Interfaces.Presentation.TreeTab.Controller
     */
    public function getActionParam()
    {
        return json_encode([
            'presentation' => $this->getPresentation(),
            'presentationModuleKey' => $this->getPresentationModuleKey(),
            'presentationContent' => $this->getPresentationContent(),
            'key' => $this->getKey(),
            'treeContent' => $this->getTreeContent(),
            'url' => $this->getRouterContent(),
            'breadcrumbs' => $this->getBreadcrumbs(),
            'pageHeader' => $this->getPageHeader(),
            'uniqueId' => str_random(),
        ]);
    }

    /**
     * @method setWidgetGroupModel
     * Set group widget's model.
     * @param {Telenok.Core.Interfaces.Eloquent.Object.Model} $param
     * @return {Telenok.Core.Interfaces.Widget.Group.Controller}
     * @member Telenok.Core.Interfaces.Presentation.TreeTab.Controller
     */
    public function getPresentationContent()
    {
        return view($this->getPresentationView(), [
                    'presentation' => $this->getPresentation(),
                    'presentationModuleKey' => $this->getPresentationModuleKey(),
                    'controller' => $this,
                    'uniqueId' => str_random(),
                    'pageLength' => $this->pageLength
                ])->render();
    }

    /**
     * @method setWidgetGroupModel
     * Set group widget's model.
     * @param {Telenok.Core.Interfaces.Eloquent.Object.Model} $param
     * @return {Telenok.Core.Interfaces.Widget.Group.Controller}
     * @member Telenok.Core.Interfaces.Presentation.TreeTab.Controller
     */
    public function getContent()
    {
        $model = $this->getModelList();

        return [
            'tabKey' => $this->getTabKey(),
            'tabLabel' => $this->LL('list.name'),
            'tabContent' => view($this->getPresentationContentView(), array_merge([
                'controller' => $this,
                'fields' => $model->getFieldList(),
                'fieldsFilter' => $this->getModelFieldFilter(),
                'gridId' => $this->getGridId(),
                'uniqueId' => str_random(),
                            ], $this->getAdditionalViewParam()))->render()
        ];
    }

    /**
     * @method setWidgetGroupModel
     * Set group widget's model.
     * @param {Telenok.Core.Interfaces.Eloquent.Object.Model} $param
     * @return {Telenok.Core.Interfaces.Widget.Group.Controller}
     * @member Telenok.Core.Interfaces.Presentation.TreeTab.Controller
     */
    public function getTreeContent()
    {
        return view($this->getPresentationTreeView(), [
                    'controller' => $this,
                    'treeChoose' => $this->LL('title.tree'),
                    'id' => str_random(),
                ])->render();
    }

    /**
     * @method setWidgetGroupModel
     * Set group widget's model.
     * @param {Telenok.Core.Interfaces.Eloquent.Object.Model} $param
     * @return {Telenok.Core.Interfaces.Widget.Group.Controller}
     * @member Telenok.Core.Interfaces.Presentation.TreeTab.Controller
     */
    public function getFilterQueryLike($value, $query, $model, $field)
    {
        $query->where(function($query) use ($value, $model, $field)
        {
            collect(explode(' ', $value))
                    ->filter(function($i)
                    {
                        return trim($i);
                    })
                    ->each(function($i) use ($query, $model, $field)
                    {
                        $query->orWhere($model->getTable() . '.' . $field, 'like', '%' . trim($i) . '%');
                    });

            $query->orWhere($model->getTable() . '.id', intval($value));
        });
    }

    /**
     * @method setWidgetGroupModel
     * Set group widget's model.
     * @param {Telenok.Core.Interfaces.Eloquent.Object.Model} $param
     * @return {Telenok.Core.Interfaces.Widget.Group.Controller}
     * @member Telenok.Core.Interfaces.Presentation.TreeTab.Controller
     */
    public function getFilterQuery($model, $query)
    {
        $input = $this->getRequest();

        if (($str = trim($input->input('search.value'))) || ($str = trim($input->input('term'))))
        {
            $this->getFilterQueryLike($str, $query, $model, 'title');
        }

        if ($input->input('multifield_search', false))
        {
            $this->getFilterSubQuery($input->input('filter', []), $model, $query);
        }
        else
        {
            $this->getFilterSubQuery(null, $model, $query);
        }

        if ($input->input('order', 0) && ($orderByField = $input->input("columns.{$input->input('order.0.column')}.data")))
        {
            if (($model instanceof \Telenok\Core\Interfaces\Eloquent\Object\Model && $model->getFieldList()->filter(function($item) use ($orderByField)
                    {
                        return $orderByField === $item->code;
                    })->count()) || !($model instanceof \Telenok\Core\Interfaces\Eloquent\Object\Model))
            {
                $query->orderBy($model->getTable() . '.' . $orderByField, $input->input('order.0.dir') == 'asc' ? 'asc' : 'desc');
            }
        }
    }

    /**
     * @method setWidgetGroupModel
     * Set group widget's model.
     * @param {Telenok.Core.Interfaces.Eloquent.Object.Model} $param
     * @return {Telenok.Core.Interfaces.Widget.Group.Controller}
     * @member Telenok.Core.Interfaces.Presentation.TreeTab.Controller
     */
    public function getFilterSubQuery($input, $model, $query)
    {
        foreach ($input as $name => $value)
        {
            $query->where(function($query) use ($value, $name, $model)
            {
                collect(explode(' ', $value))
                        ->reject(function($i)
                        {
                            return !trim($i);
                        })
                        ->each(function($i) use ($query, $name, $model)
                        {
                            $query->where($model->getTable() . '.' . $name, 'like', '%' . trim($i) . '%');
                        });
            });
        }
    }

    /**
     * @method setWidgetGroupModel
     * Set group widget's model.
     * @param {Telenok.Core.Interfaces.Eloquent.Object.Model} $param
     * @return {Telenok.Core.Interfaces.Widget.Group.Controller}
     * @member Telenok.Core.Interfaces.Presentation.TreeTab.Controller
     */
    public function getListItem($model = null)
    {
        $id = $this->getRequest()->input('treeId', 0);

        $query = $model->withTrashed();

        if ($model->treeForming())
        {
            $query->withTreeAttr();

            if ($id)
            {
                $query->where(function($query) use ($model, $id)
                {
                    $query->where('pivot_relation_m2m_tree.tree_pid', $id)
                            ->orWhere($model->getTable() . '.id', $id);
                });
            }
        }
        else
        {
            $query->where($model->getTable() . '.id', $id);
        }

        $query->withPermission();

        $this->getFilterQuery($model, $query);

        return $query->groupBy($model->getTable() . '.id')
                        ->orderBy($model->getTable() . '.updated_at', 'desc')
                        ->skip($this->getRequest()->input('start', 0))
                        ->take($this->getRequest()->input('length', $this->pageLength) + 1)
                        ->get();
    }

    /**
     * @method setWidgetGroupModel
     * Set group widget's model.
     * @param {Telenok.Core.Interfaces.Eloquent.Object.Model} $param
     * @return {Telenok.Core.Interfaces.Widget.Group.Controller}
     * @member Telenok.Core.Interfaces.Presentation.TreeTab.Controller
     */
    public function fillListItem($item = null, \Illuminate\Support\Collection $put = null, $model = null)
    {
        $put->put('tableCheckAll', '<input type="checkbox" class="ace ace-checkbox-2" '
                . 'name="tableCheckAll[]" value="' . $item->getKey() . '"><span class="lbl"></span>');

        foreach ($model->getFieldList() as $field)
        {
            $put->put($field->code, $this->getListItemProcessed($field, $item));
        }

        $put->put('tableManageItem', $this->getListButton($item));

        return $this;
    }

    /**
     * @method setWidgetGroupModel
     * Set group widget's model.
     * @param {Telenok.Core.Interfaces.Eloquent.Object.Model} $param
     * @return {Telenok.Core.Interfaces.Widget.Group.Controller}
     * @member Telenok.Core.Interfaces.Presentation.TreeTab.Controller
     */
    public function getListItemProcessed($field, $item)
    {
        return $item->translate($field->code);
    }

    /**
     * @method setWidgetGroupModel
     * Set group widget's model.
     * @param {Telenok.Core.Interfaces.Eloquent.Object.Model} $param
     * @return {Telenok.Core.Interfaces.Widget.Group.Controller}
     * @member Telenok.Core.Interfaces.Presentation.TreeTab.Controller
     */
    public function getTreeList($id = null)
    {
        $tree = collect();
        $input = $this->getRequest();

        $id = $id === null ? $input->input('treeId', 0) : $id;
        $searchStr = $input->input('search_string');

        try
        {
            $list = $this->getTreeListModel($id, $searchStr);

            if ($searchStr)
            {
                foreach ($list->all() as $l)
                {
                    foreach ($l->parents()->get()->all() as $l_)
                    {
                        $tree->push("#{$l_->getKey()}");
                    }

                    $tree->push("#{$l->getKey()}");
                }
            }
            else
            {
                $parents = $list->lists('id', 'tree_pid');

                foreach ($list as $key => $item)
                {
                    if ($item->tree_pid == $id)
                    {
                        $tree->push([
                            "data" => $item->translate('title'),
                            'attr' => ['id' => $item->getKey(), 'rel' => '', 'title' => 'ID: ' . $item->getKey()],
                            "state" => (isset($parents[$item->getKey()]) ? 'closed' : ''),
                            "metadata" => array_merge(['id' => $item->getKey(), 'gridId' => $this->getGridId()], $this->getTreeListItemProcessed($item)),
                        ]);
                    }
                }
            }
        }
        catch (\Exception $e)
        {
            return $e->getMessage();
        }

        return $tree->all();
    }

    /**
     * @method setWidgetGroupModel
     * Set group widget's model.
     * @param {Telenok.Core.Interfaces.Eloquent.Object.Model} $param
     * @return {Telenok.Core.Interfaces.Widget.Group.Controller}
     * @member Telenok.Core.Interfaces.Presentation.TreeTab.Controller
     */
    public function getTreeListTypes()
    {
        $types = [];

        $types[] = \App\Telenok\Core\Model\Object\Type::where('code', 'folder')->active()->pluck('id');

        if ($this->getModelTreeClass())
        {
            $types[] = $this->getTypeTree()->getKey();
        }

        return $types;
    }

    /**
     * @method setWidgetGroupModel
     * Set group widget's model.
     * @param {Telenok.Core.Interfaces.Eloquent.Object.Model} $param
     * @return {Telenok.Core.Interfaces.Widget.Group.Controller}
     * @member Telenok.Core.Interfaces.Presentation.TreeTab.Controller
     */
    public function getTreeListModel($treeId = 0, $str = '')
    {
        $sequence = app('\App\Telenok\Core\Model\Object\Sequence');

        if ($str)
        {
            $query = $sequence->withTreeAttr();

            $this->getFilterQueryLike($str, $query, $sequence, 'title');
        }
        else
        {
            $types = $this->getTreeListTypes();

            if ($treeId == 0)
            {
                $query = \App\Telenok\Core\Model\Object\Sequence::withChildren(2)->orderBy('pivot_tree_children.tree_order');
            }
            else
            {
                $query = \App\Telenok\Core\Model\Object\Sequence::find($treeId)->children(2)->orderBy('pivot_tree_attr.tree_order')->active();
            }

            $query->whereIn('object_sequence.sequences_object_type', $types);
        }

        $query->where('object_sequence.treeable', 1);
        $query->groupBy('object_sequence.id');
        $query->withPermission('read', null, ['direct-right']);

        return $query->get();
    }

    /**
     * @method setWidgetGroupModel
     * Set group widget's model.
     * @param {Telenok.Core.Interfaces.Eloquent.Object.Model} $param
     * @return {Telenok.Core.Interfaces.Widget.Group.Controller}
     * @member Telenok.Core.Interfaces.Presentation.TreeTab.Controller
     */
    public function getTreeListItemProcessed($item)
    {
        return [];
    }

    /**
     * @method setWidgetGroupModel
     * Set group widget's model.
     * @param {Telenok.Core.Interfaces.Eloquent.Object.Model} $param
     * @return {Telenok.Core.Interfaces.Widget.Group.Controller}
     * @member Telenok.Core.Interfaces.Presentation.TreeTab.Controller
     */
    public function getListButton($item)
    {
        $random = str_random();

        $collection = collect();

        $collection->put('open', ['order' => 0, 'content' =>
            '<div class="dropdown">
                <a class="btn btn-white no-hover btn-transparent btn-xs dropdown-toggle" href="#" role="button" style="border:none;"
                        type="button" id="' . $random . '" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                    <span class="glyphicon glyphicon-menu-hamburger text-muted"></span>
                </a>
                <ul class="dropdown-menu" aria-labelledby="' . $random . '">
            ']);

        $collection->put('close', ['order' => PHP_INT_MAX, 'content' =>
            '</ul>
            </div>']);

        $collection->put('edit', ['order' => 1000, 'content' =>
            '<li><a href="#" onclick="telenok.getPresentation(\''
            . $this->getPresentationModuleKey() . '\').addTabByURL({url : \'' . $this->getRouterEdit(['id' => $item->getKey()]) . '\'}); return false;">'
            . ' <i class="fa fa-pencil"></i> ' . $this->LL('list.btn.edit') . '</a>
                </li>']);

        $collection->put('delete', ['order' => 2000, 'content' =>
            '<li><a href="#" onclick="if (confirm(\'' . $this->LL('notice.sure.delete') . '\')) telenok.getPresentation(\'' . $this->getPresentationModuleKey() . '\').deleteByURL(this, \''
            . $this->getRouterDelete(['id' => $item->getKey()]) . '\'); return false;">'
            . ' <i class="fa fa-trash-o"></i> ' . $this->LL('list.btn.delete') . '</a>
                </li>']);

        app('events')->fire($this->getListButtonEventKey(), $collection);

        return $this->getAdditionalListButton($item, $collection)->sort(function($a, $b)
                {
                    return array_get($a, 'order', 0) > array_get($b, 'order', 0) ? 1 : -1;
                })->implode('content');
    }

    /**
     * @method setWidgetGroupModel
     * Set group widget's model.
     * @param {Telenok.Core.Interfaces.Eloquent.Object.Model} $param
     * @return {Telenok.Core.Interfaces.Widget.Group.Controller}
     * @member Telenok.Core.Interfaces.Presentation.TreeTab.Controller
     */
    public function getListButtonEventKey($param = null)
    {
        return 'telenok.module.' . $this->getKey();
    }

    /**
     * @method setWidgetGroupModel
     * Set group widget's model.
     * @param {Telenok.Core.Interfaces.Eloquent.Object.Model} $param
     * @return {Telenok.Core.Interfaces.Widget.Group.Controller}
     * @member Telenok.Core.Interfaces.Presentation.TreeTab.Controller
     */
    public function getAdditionalListButton($item, $collection)
    {
        return $collection;
    }

    /**
     * @method setWidgetGroupModel
     * Set group widget's model.
     * @param {Telenok.Core.Interfaces.Eloquent.Object.Model} $param
     * @return {Telenok.Core.Interfaces.Widget.Group.Controller}
     * @member Telenok.Core.Interfaces.Presentation.TreeTab.Controller
     */
    public function getAdditionalViewParam()
    {
        return $this->additionalViewParam;
    }

    /**
     * @method setWidgetGroupModel
     * Set group widget's model.
     * @param {Telenok.Core.Interfaces.Eloquent.Object.Model} $param
     * @return {Telenok.Core.Interfaces.Widget.Group.Controller}
     * @member Telenok.Core.Interfaces.Presentation.TreeTab.Controller
     */
    public function setAdditionalViewParam($param = [])
    {
        $this->additionalViewParam = $param;

        return $this;
    }

    /**
     * @method setWidgetGroupModel
     * Set group widget's model.
     * @param {Telenok.Core.Interfaces.Eloquent.Object.Model} $param
     * @return {Telenok.Core.Interfaces.Widget.Group.Controller}
     * @member Telenok.Core.Interfaces.Presentation.TreeTab.Controller
     */
    public function getGridId($key = 'gridId')
    {
        return "{$this->getPresentation()}-{$this->getTabKey()}-{$key}";
    }

    /**
     * @method setWidgetGroupModel
     * Set group widget's model.
     * @param {Telenok.Core.Interfaces.Eloquent.Object.Model} $param
     * @return {Telenok.Core.Interfaces.Widget.Group.Controller}
     * @member Telenok.Core.Interfaces.Presentation.TreeTab.Controller
     */
    public function getModelFieldFilter($model = null)
    {
        return collect();
    }

    /**
     * @method setWidgetGroupModel
     * Set group widget's model.
     * @param {Telenok.Core.Interfaces.Eloquent.Object.Model} $param
     * @return {Telenok.Core.Interfaces.Widget.Group.Controller}
     * @member Telenok.Core.Interfaces.Presentation.TreeTab.Controller
     */
    public function getList()
    {
        $content = [];

        $input = $this->getRequest();

        $draw = $input->input('draw');
        $start = $input->input('start', 0);
        $length = $input->input('length', $this->pageLength);

        $model = $this->getModelList();
        $items = $this->getListItem($model);

        foreach ($items->slice(0, $length, true) as $item)
        {
            $put = collect();

            $this->fillListItem($item, $put, $model);

            $content[] = $put->all();
        }

        return [
            'draw' => $draw,
            'data' => $content,
            'gridId' => $this->getGridId(),
            'recordsTotal' => ($start + $items->count()),
            'recordsFiltered' => ($start + $items->count()),
        ];
    }

    /**
     * @method setWidgetGroupModel
     * Set group widget's model.
     * @param {Telenok.Core.Interfaces.Eloquent.Object.Model} $param
     * @return {Telenok.Core.Interfaces.Widget.Group.Controller}
     * @member Telenok.Core.Interfaces.Presentation.TreeTab.Controller
     */
    public function getRouterParam($action = '', $model = null)
    {
        switch ($action)
        {
            case 'create':
                return [
                    $this->getRouterStore(
                            [
                                'id' => $model->getKey(),
                                'saveBtn' => $this->getRequest()->input('saveBtn', true),
                                'chooseBtn' => $this->getRequest()->input('chooseBtn', false),
                                'chooseSequence' => $this->getRequest()->input('chooseSequence', false)
                ])];
                break;

            case 'edit':
                return [
                    $this->getRouterUpdate(
                            [
                                'id' => $model->getKey(),
                                'saveBtn' => $this->getRequest()->input('saveBtn', true),
                                'chooseBtn' => $this->getRequest()->input('chooseBtn', true),
                                'chooseSequence' => $this->getRequest()->input('chooseSequence', false)
                ])];
                break;

            case 'store':
                return [
                    $this->getRouterUpdate(
                            [
                                'id' => $model->getKey(),
                                'saveBtn' => $this->getRequest()->input('saveBtn', true),
                                'chooseBtn' => $this->getRequest()->input('chooseBtn', true),
                                'chooseSequence' => $this->getRequest()->input('chooseSequence', false)
                ])];
                break;

            case 'update':
                return [
                    $this->getRouterUpdate(
                            [
                                'id' => $model->getKey(),
                                'saveBtn' => $this->getRequest()->input('saveBtn', true),
                                'chooseBtn' => $this->getRequest()->input('chooseBtn', true),
                                'chooseSequence' => $this->getRequest()->input('chooseSequence', false)
                ])];
                break;

            default:
                return [];
        }
    }

    /**
     * @method setWidgetGroupModel
     * Set group widget's model.
     * @param {Telenok.Core.Interfaces.Eloquent.Object.Model} $param
     * @return {Telenok.Core.Interfaces.Widget.Group.Controller}
     * @member Telenok.Core.Interfaces.Presentation.TreeTab.Controller
     */
    public function create()
    {
        return [
            'tabKey' => $this->getTabKey() . '-new-' . str_random(),
            'tabLabel' => $this->LL('list.create'),
            'tabContent' => view("{$this->getPackage()}::module.{$this->getKey()}.model", array_merge(array(
                'controller' => $this,
                'model' => $this->getModelList(),
                'routerParam' => $this->getRouterParam('create'),
                'uniqueId' => str_random(),
                            ), $this->getAdditionalViewParam()))->render()
        ];
    }

    /**
     * @method setWidgetGroupModel
     * Set group widget's model.
     * @param {Telenok.Core.Interfaces.Eloquent.Object.Model} $param
     * @return {Telenok.Core.Interfaces.Widget.Group.Controller}
     * @member Telenok.Core.Interfaces.Presentation.TreeTab.Controller
     */
    public function edit($id = 0)
    {
        $id = $id ? : $this->getRequest()->input('id');

        return [
            'tabKey' => $this->getTabKey() . '-edit-' . $id,
            'tabLabel' => $this->LL('list.edit'),
            'tabContent' => view("{$this->getPackage()}::module.{$this->getKey()}.model", array_merge(array(
                'controller' => $this,
                'model' => $this->getModelList()->find($id),
                'routerParam' => $this->getRouterParam('edit'),
                'uniqueId' => str_random(),
                            ), $this->getAdditionalViewParam()))->render()
        ];
    }

    /**
     * @method setWidgetGroupModel
     * Set group widget's model.
     * @param {Telenok.Core.Interfaces.Eloquent.Object.Model} $param
     * @return {Telenok.Core.Interfaces.Widget.Group.Controller}
     * @member Telenok.Core.Interfaces.Presentation.TreeTab.Controller
     */
    public function editList()
    {
        $content = [];

        $ids = (array) $this->getRequest()->input('tableCheckAll');

        if (empty($ids))
        {
            return \Response::json(['message' => 'Expectation Failed'], 417 /* Expectation Failed */);
        }

        foreach ($ids as $id)
        {
            $content[] = view("{$this->getPackage()}::module.{$this->getKey()}.model", array_merge(array(
                'controller' => $this,
                'model' => $this->getModelList()->find($id),
                'routerParam' => $this->getRouterParam('edit'),
                'uniqueId' => str_random(),
                            ), $this->getAdditionalViewParam()))->render();
        }

        return [
            'tabKey' => $this->getTabKey() . '-edit-' . implode('', $ids),
            'tabLabel' => $this->LL('list.edit'),
            'tabContent' => implode('<div class="hr hr-double hr-dotted hr18"></div>', $content)
        ];
    }

    /**
     * @method setWidgetGroupModel
     * Set group widget's model.
     * @param {Telenok.Core.Interfaces.Eloquent.Object.Model} $param
     * @return {Telenok.Core.Interfaces.Widget.Group.Controller}
     * @member Telenok.Core.Interfaces.Presentation.TreeTab.Controller
     */
    public function deleteProcess($id = null, $force = false)
    {
        $model = $this->getModelTrashed($id);

        if (!app('auth')->can('delete', $id))
        {
            throw new \LogicException($this->LL('error.access'));
        }

        app('db')->transaction(function() use ($model, $force)
        {
            if ($force || $model->trashed())
            {
                $model->forceDelete();
            }
            else
            {
                $model->delete();
            }
        });
    }
    
    /**
     * @method setWidgetGroupModel
     * Set group widget's model.
     * @param {Telenok.Core.Interfaces.Eloquent.Object.Model} $param
     * @return {Telenok.Core.Interfaces.Widget.Group.Controller}
     * @member Telenok.Core.Interfaces.Presentation.TreeTab.Controller
     */
    public function delete($id = null, $force = false)
    {
        try
        {
            $this->deleteProcess($id, $force);

            return ['success' => 1];
        }
        catch (\Exception $e)
        {
            return ['exception' => 1];
        }
    }

    /**
     * @method setWidgetGroupModel
     * Set group widget's model.
     * @param {Telenok.Core.Interfaces.Eloquent.Object.Model} $param
     * @return {Telenok.Core.Interfaces.Widget.Group.Controller}
     * @member Telenok.Core.Interfaces.Presentation.TreeTab.Controller
     */
    public function deleteList($id = null, $ids = [])
    {
        $ids = !empty($ids) ? $ids : (array) $this->getRequest()->input('tableCheckAll');

        if (empty($ids))
        {
            return \Response::json(['message' => 'Expectation Failed'], 417 /* Expectation Failed */);
        }

        $error = false;

        app('db')->transaction(function() use ($ids, &$error)
        {
            try
            {
                $model = $this->getModelList();

                foreach ($ids as $id_)
                {
                    $model::findOrFail($id_)->delete();
                }
            }
            catch (\Exception $e)
            {
                $error = true;
            }
        });

        if ($error)
        {
            return \Response::json(['message' => 'Expectation Failed'], 417 /* Expectation Failed */);
        }
        else
        {
            return \Response::json(['success' => 1]);
        }
    }

    /**
     * @method setWidgetGroupModel
     * Set group widget's model.
     * @param {Telenok.Core.Interfaces.Eloquent.Object.Model} $param
     * @return {Telenok.Core.Interfaces.Widget.Group.Controller}
     * @member Telenok.Core.Interfaces.Presentation.TreeTab.Controller
     */
    public function lock()
    {
        $id = $this->getRequest()->input('id');

        try
        {
            $model = \App\Telenok\Core\Model\Object\Sequence::find($id);

            if ($model && ($model = $model->model) && !$model->locked())
            {
                $model->lock($this->getLockInFormPeriod());
            }
        }
        catch (\Exception $ex)
        {
            return \Response::json(['message' => 'Expectation Failed'], 417 /* Expectation Failed */);
        }

        return \Response::json(['success' => 1]);
    }

    /**
     * @method setWidgetGroupModel
     * Set group widget's model.
     * @param {Telenok.Core.Interfaces.Eloquent.Object.Model} $param
     * @return {Telenok.Core.Interfaces.Widget.Group.Controller}
     * @member Telenok.Core.Interfaces.Presentation.TreeTab.Controller
     */
    public function lockList()
    {
        $tableCheckAll = $this->getRequest()->input('tableCheckAll', []);

        try
        {
            foreach ($tableCheckAll as $id)
            {
                $model = \App\Telenok\Core\Model\Object\Sequence::find($id)->model;

                if ($model && !$model->locked())
                {
                    $model->lock($this->getLockInListPeriod());
                }
            }
        }
        catch (\Exception $ex)
        {
            return \Response::json(['message' => 'Expectation Failed'], 417 /* Expectation Failed */);
        }

        return \Response::json(['success' => 1]);
    }

    /**
     * @method setWidgetGroupModel
     * Set group widget's model.
     * @param {Telenok.Core.Interfaces.Eloquent.Object.Model} $param
     * @return {Telenok.Core.Interfaces.Widget.Group.Controller}
     * @member Telenok.Core.Interfaces.Presentation.TreeTab.Controller
     */
    public function unlockList()
    {
        $tableCheckAll = $this->getRequest()->input('tableCheckAll', []);

        try
        {
            $userId = app('auth')->user()->id;

            foreach ($tableCheckAll as $id)
            {
                $model = \App\Telenok\Core\Model\Object\Sequence::withTrashed()->find($id)->model;

                if ($model && $model->locked_by_user == $userId)
                {
                    $model->unLock();
                }
            }
        }
        catch (\Exception $ex)
        {
            return \Response::json(['message' => 'Expectation Failed'], 417 /* Expectation Failed */);
        }

        return \Response::json(['success' => 1]);
    }

    /**
     * @method setWidgetGroupModel
     * Set group widget's model.
     * @param {Telenok.Core.Interfaces.Eloquent.Object.Model} $param
     * @return {Telenok.Core.Interfaces.Widget.Group.Controller}
     * @member Telenok.Core.Interfaces.Presentation.TreeTab.Controller
     */
    public function store($id = null)
    {
        $input = $this->getRequestCollected();

        $model = null;

        app('db')->transaction(function() use (&$model, $input)
        {
            $model = $this->save($input);
        });

        $return = [];

        $return['content'] = view("{$this->getPackage()}::module.{$this->getKey()}.model", array_merge([
            'controller' => $this,
            'model' => $model,
            'routerParam' => $this->getRouterParam('store'),
            'uniqueId' => str_random(),
            'success' => true,
            'warning' => \Session::get('warning'),
                        ], $this->getAdditionalViewParam()))->render();

        return $return;
    }

    /**
     * @method setWidgetGroupModel
     * Set group widget's model.
     * @param {Telenok.Core.Interfaces.Eloquent.Object.Model} $param
     * @return {Telenok.Core.Interfaces.Widget.Group.Controller}
     * @member Telenok.Core.Interfaces.Presentation.TreeTab.Controller
     */
    public function update($id = null)
    {
        $input = $this->getRequestCollected();

        $model = null;

        app('db')->transaction(function() use (&$model, $input)
        {
            $model = $this->save($input);
        });

        $return = [];

        $return['content'] = view("{$this->getPackage()}::module.{$this->getKey()}.model", array_merge([
            'controller' => $this,
            'model' => $model,
            'routerParam' => $this->getRouterParam('update'),
            'uniqueId' => str_random(),
            'success' => true,
            'warning' => \Session::get('warning'),
                        ], $this->getAdditionalViewParam()))->render();

        return $return;
    }

    /**
     * @method setWidgetGroupModel
     * Set group widget's model.
     * @param {Telenok.Core.Interfaces.Eloquent.Object.Model} $param
     * @return {Telenok.Core.Interfaces.Widget.Group.Controller}
     * @member Telenok.Core.Interfaces.Presentation.TreeTab.Controller
     */
    public function save($input = [], $type = null)
    {
        $input = collect($input);
        $model = $this->getModelList();

        $validator = $this->validator($model, $input->all(), $this->LL('error'), ['table' => $model->getTable()]);

        if ($validator->fails())
        {
            throw $this->validateException()->setMessageError($validator->messages());
        }

        $this->preProcess($model, $type, $input);

        $this->validate($model, $input);

        if ($model->exists && $model->getKey() == $input->get('id'))
        {
            $model->update($input->all());
        }
        else
        {
            $model->fill($input->all())->save();
        }

        if ($input->get('tree_pid') && $model->treeForming())
        {
            try
            {
                $model->makeLastChildOf(\App\Telenok\Core\Model\System\Folder::findOrFail($input->get('tree_pid'))->sequence);
            }
            catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e)
            {
                $model->makeRoot();
            }
        }

        $this->postProcess($model, $type, $input);

        return $model;
    }

    /**
     * @method setWidgetGroupModel
     * Set group widget's model.
     * @param {Telenok.Core.Interfaces.Eloquent.Object.Model} $param
     * @return {Telenok.Core.Interfaces.Widget.Group.Controller}
     * @member Telenok.Core.Interfaces.Presentation.TreeTab.Controller
     */
    public function preProcess($model, $type, $input)
    {
        return $this;
    }

    /**
     * @method setWidgetGroupModel
     * Set group widget's model.
     * @param {Telenok.Core.Interfaces.Eloquent.Object.Model} $param
     * @return {Telenok.Core.Interfaces.Widget.Group.Controller}
     * @member Telenok.Core.Interfaces.Presentation.TreeTab.Controller
     */
    public function postProcess($model, $type, $input)
    {
        return $this;
    }

    /**
     * @method setWidgetGroupModel
     * Set group widget's model.
     * @param {Telenok.Core.Interfaces.Eloquent.Object.Model} $param
     * @return {Telenok.Core.Interfaces.Widget.Group.Controller}
     * @member Telenok.Core.Interfaces.Presentation.TreeTab.Controller
     */
    public function getModelFieldViewKey($field)
    {
        
    }

    /**
     * @method setWidgetGroupModel
     * Set group widget's model.
     * @param {Telenok.Core.Interfaces.Eloquent.Object.Model} $param
     * @return {Telenok.Core.Interfaces.Widget.Group.Controller}
     * @member Telenok.Core.Interfaces.Presentation.TreeTab.Controller
     */
    public function getModelFieldView($field)
    {
        
    }

    /**
     * @method setWidgetGroupModel
     * Set group widget's model.
     * @param {Telenok.Core.Interfaces.Eloquent.Object.Model} $param
     * @return {Telenok.Core.Interfaces.Widget.Group.Controller}
     * @member Telenok.Core.Interfaces.Presentation.TreeTab.Controller
     */
    public function getModelFieldViewVariable($fieldController = null, $model = null, $field = null, $uniqueId = null)
    {
        
    }

    /**
     * @method setWidgetGroupModel
     * Set group widget's model.
     * @param {Telenok.Core.Interfaces.Eloquent.Object.Model} $param
     * @return {Telenok.Core.Interfaces.Widget.Group.Controller}
     * @member Telenok.Core.Interfaces.Presentation.TreeTab.Controller
     */
    public function getDisplayType()
    {
        return $this->displayType;
    }

    /**
     * @method setWidgetGroupModel
     * Set group widget's model.
     * @param {Telenok.Core.Interfaces.Eloquent.Object.Model} $param
     * @return {Telenok.Core.Interfaces.Widget.Group.Controller}
     * @member Telenok.Core.Interfaces.Presentation.TreeTab.Controller
     */
    public function setDisplayType($type)
    {
        $this->displayType = $type;

        return $this;
    }

    /**
     * @method setWidgetGroupModel
     * Set group widget's model.
     * @param {Telenok.Core.Interfaces.Eloquent.Object.Model} $param
     * @return {Telenok.Core.Interfaces.Widget.Group.Controller}
     * @member Telenok.Core.Interfaces.Presentation.TreeTab.Controller
     */
    public function isDisplayTypeWizard()
    {
        return $this->displayType == static::$DISPLAY_TYPE_WIZARD;
    }
}
