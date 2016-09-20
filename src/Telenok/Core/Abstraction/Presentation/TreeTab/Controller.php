<?php

namespace Telenok\Core\Abstraction\Presentation\TreeTab;

use \Telenok\Core\Contract\Presentation\Presentation;
use \Telenok\Core\Contract\Eloquent\EloquentProcessController;

/**
 * @class Telenok.Core.Abstraction.Presentation.TreeTab.Controller
 * Base controller for presentation "tree-tab".
 *
 * @uses Telenok.Core.Contract.Presentation.Presentation
 * @uses Telenok.Core.Contract.Eloquent.EloquentProcessController
 * @extends Telenok.Core.Abstraction.Module.Controller
 */
abstract class Controller extends \Telenok\Core\Abstraction\Module\Controller implements Presentation, EloquentProcessController {

    /**
     * @protected
     * @property {String} $tabKey
     * Key of presentation's tabs.
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
     */
    protected $tabKey = '';

    /**
     * @protected
     * @property {String} $presentation
     * Key of presentation.
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
     */
    protected $presentation = 'tree-tab';

    /**
     * @protected
     * @property {String} $presentationModuleKey
     * Key of presentation's module.
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
     */
    protected $presentationModuleKey = '';

    /**
     * @protected
     * @property {String} $presentationView
     * Presentation's initial view.
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
     */
    protected $presentationView = 'core::presentation.tree-tab.presentation';

    /**
     * @protected
     * @property {String} $presentationTreeView
     * Presentation's initial tree's view.
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
     */
    protected $presentationTreeView = 'core::presentation.tree-tab.tree';

    /**
     * @protected
     * @property {String} $presentationContentView
     * Presentation's initial content's 'view.
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
     */
    protected $presentationContentView = 'core::presentation.tree-tab.content';

    /**
     * @protected
     * @property {String} $presentationModelView
     * Presentation's initial model's view.
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
     */
    protected $presentationModelView = 'core::presentation.tree-tab.model';

    /**
     * @protected
     * @property {String} $presentationFormModelView
     * Presentation's initial model's form view.
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
     */
    protected $presentationFormModelView = 'core::presentation.tree-tab.form';

    /**
     * @protected
     * @property {String} $presentationFormFieldListView
     * Presentation's initial models' fields view.
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
     */
    protected $presentationFormFieldListView = 'core::presentation.tree-tab.form-field-list';

    /**
     * @protected
     * @property {String} $routerActionParam
     * Name of custom action-param router.
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
     */
    protected $routerActionParam = '';

    /**
     * @protected
     * @property {String} $routerList
     * Name of custom list router.
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
     */
    protected $routerList = '';

    /**
     * @protected
     * @property {String} $routerContent
     * Name of custom content router.
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
     */
    protected $routerContent = '';

    /**
     * @protected
     * @property {String} $routerCreate
     * Name of custom create router.
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
     */
    protected $routerCreate = '';

    /**
     * @protected
     * @property {String} $routerEdit
     * Name of custom edit router.
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
     */
    protected $routerEdit = '';

    /**
     * @protected
     * @property {String} $routerDelete
     * Name of custom delete router.
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
     */
    protected $routerDelete = '';

    /**
     * @protected
     * @property {String} $routerStore
     * Name of custom store router.
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
     */
    protected $routerStore = '';

    /**
     * @protected
     * @property {String} $routerUpdate
     * Name of custom update router.
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
     */
    protected $routerUpdate = '';

    /**
     * @protected
     * @property {String} $routerListEdit
     * Name of custom edit list router.
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
     */
    protected $routerListEdit = '';

    /**
     * @protected
     * @property {String} $routerListDelete
     * Name of custom delete list router.
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
     */
    protected $routerListDelete = '';

    /**
     * @protected
     * @property {String} $routerLock
     * Name of custom lock router.
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
     */
    protected $routerLock = '';

    /**
     * @protected
     * @property {String} $routerListLock
     * Name of custom list lock router.
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
     */
    protected $routerListLock = '';

    /**
     * @protected
     * @property {String} $routerListUnlock
     * Name of custom list unlock router.
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
     */
    protected $routerListUnlock = '';

    /**
     * @protected
     * @property {String} $routerListTree
     * Name of custom tree router.
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
     */
    protected $routerListTree = '';

    /**
     * @protected
     * @property {String} $modelListClass
     * Class name of model in list view.
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
     */
    protected $modelListClass = '';

    /**
     * @protected
     * @property {String} $modelTreeClass
     * Class name of model in tree view.
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
     */
    protected $modelTreeClass = '';

    /**
     * @protected
     * @property {String} $pageLength
     * Amount of rows to show in list.
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
     */
    protected $pageLength = 15;

    /**
     * @protected
     * @property {String} $additionalViewParam
     * Additional view parameters.
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
     */
    protected $additionalViewParam = [];

    /**
     * @protected
     * @property {String} $lockInListPeriod
     * Amount of minutes to lock model's record after it locked in list.
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
     */
    protected $lockInListPeriod = 10;

    /**
     * @protected
     * @property {String} $lockInFormPeriod
     * Amount of minutes to lock model's record after it opened in form.
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
     */
    protected $lockInFormPeriod = 20;

    /**
     * @protected
     * @property {String} $displayType
     * Presentation's initial view.
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
     */
    protected $displayType = 1;

    /**
     * @static
     * @protected
     * @property {Integer} $DISPLAY_TYPE_STANDART
     * Whether model showed in form in tab.
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
     */
    public static $DISPLAY_TYPE_STANDART = 1;

    /**
     * @static
     * @protected
     * @property {Integer} $DISPLAY_TYPE_WIZARD
     * Whether model showed in form in modal window.
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
     */
    public static $DISPLAY_TYPE_WIZARD = 2;

    /**
     * @method getLockInListPeriod
     * Return amount of minuts to lock record in list.
     * @return {Number}
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
     */
    public function getLockInListPeriod()
    {
        return $this->lockInListPeriod;
    }

    /**
     * @method setLockInListPeriod
     * Set amount of minuts to lock record in list.
     * @param {Number} $param
     * @return {Telenok.Core.Abstraction.Presentation.TreeTab.Controller}
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
     */
    public function setLockInListPeriod($param = 10)
    {
        $this->lockInListPeriod = $param;

        return $this;
    }

    /**
     * @method getLockInFormPeriod
     * Return amount of minuts to lock record in form.
     * @return {Number}
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
     */
    public function getLockInFormPeriod()
    {
        return $this->lockInFormPeriod;
    }

    /**
     * @method setLockInFormPeriod
     * Set amount of minuts to lock record in form.
     * @param {Number} $param
     * @return {Telenok.Core.Abstraction.Presentation.TreeTab.Controller}
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
     */
    public function setLockInFormPeriod($param = 10)
    {
        $this->lockInFormPeriod = $param;

        return $this;
    }

    /**
     * @method getPresentation
     * Return presentation's key.
     * @return {String}
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
     */
    public function getPresentation()
    {
        return $this->presentation;
    }

    /**
     * @method setPresentation
     * Set presentation's key.
     * @param {String} $param
     * @return {Telenok.Core.Abstraction.Presentation.TreeTab.Controller}
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
     */
    public function setPresentation($key)
    {
        $this->presentation = $key;

        return $this;
    }

    /**
     * @method getPresentationModuleKey
     * Return key of presentation's module.
     * @return {String}
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
     */
    public function getPresentationModuleKey()
    {
        return $this->presentationModuleKey ? : $this->presentation . '-' . $this->getKey();
    }

    /**
     * @method setPresentationModuleKey
     * Set key of presentation's module.
     * @param {String} $param
     * @return {Telenok.Core.Abstraction.Presentation.TreeTab.Controller}
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
     */
    public function setPresentationModuleKey($key)
    {
        $this->presentationModuleKey = $key;

        return $this;
    }

    /**
     * @method getPresentationView
     * Return view of presentation.
     * @return {String}
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
     */
    public function getPresentationView()
    {
        return $this->presentationView;
    }

    /**
     * @method setPresentationView
     * Set presentation view.
     * @param {String} $key
     * @return {Telenok.Core.Abstraction.Presentation.TreeTab.Controller}
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
     */
    public function setPresentationView($key)
    {
        $this->presentationView = $key;

        return $this;
    }

    /**
     * @method getPresentationTreeView
     * Return presentation's view of tree.
     * @return {String}
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
     */
    public function getPresentationTreeView()
    {
        return $this->presentationTreeView;
    }

    /**
     * @method setPresentationTreeView
     * Set presentation's tree view.
     * @param {String} $key
     * @return {Telenok.Core.Abstraction.Presentation.TreeTab.Controller}
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
     */
    public function setPresentationTreeView($key)
    {
        $this->presentationTreeView = $key;

        return $this;
    }

    /**
     * @method getPresentationContentView
     * Return presentation's content view.
     * @return {String}
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
     */
    public function getPresentationContentView()
    {
        return $this->presentationContentView;
    }

    /**
     * @method setPresentationContentView
     * Set presentation's content view.
     * @param {String} $key
     * @return {Telenok.Core.Abstraction.Presentation.TreeTab.Controller}
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
     */
    public function setPresentationContentView($key)
    {
        $this->presentationContentView = $key;

        return $this;
    }

    /**
     * @method getPresentationModelView
     * Return presentation's model view.
     * @return {String}
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
     */
    public function getPresentationModelView()
    {
        return $this->presentationModelView;
    }

    /**
     * @method setPresentationModelView
     * Set presentation's model view.
     * @param {String} $key
     * @return {Telenok.Core.Abstraction.Presentation.TreeTab.Controller}
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
     */
    public function setPresentationModelView($key)
    {
        $this->presentationModelView = $key;

        return $this;
    }

    /**
     * @method getPresentationFormFieldListView
     * Return presentation's form field list view.
     * @return {String}
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
     */
    public function getPresentationFormFieldListView()
    {
        return $this->presentationFormFieldListView;
    }

    /**
     * @method setPresentationFormFieldListView
     * Set presentation's form field list view.
     * @param {String} $key
     * @return {Telenok.Core.Abstraction.Presentation.TreeTab.Controller}
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
     */
    public function setPresentationFormFieldListView($key)
    {
        $this->presentationFormFieldListView = $key;

        return $this;
    }

    /**
     * @method getPresentationFormModelView
     * Return presentation's form model view.
     * @return {String}
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
     */
    public function getPresentationFormModelView()
    {
        return $this->presentationFormModelView;
    }

    /**
     * @method setPresentationFormModelView
     * Set presentation's form model view.
     * @param {String} $key
     * @return {Telenok.Core.Abstraction.Presentation.TreeTab.Controller}
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
     */
    public function setPresentationFormModelView($key)
    {
        $this->presentationFormModelView = $key;

        return $this;
    }

    /**
     * @method getTabKey
     * Return key of tab.
     * @return {String}
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
     */
    public function getTabKey()
    {
        return $this->tabKey ? : $this->getKey();
    }

    /**
     * @method setTabKey
     * Set key of tab.
     * @param {String} $param
     * @return {Telenok.Core.Abstraction.Presentation.TreeTab.Controller}
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
     */
    public function setTabKey($key)
    {
        $this->tabKey = $key;

        return $this;
    }

    /**
     * @method setRouterActionParam
     * Set router action param.
     * @param {String} $param
     * @return {Telenok.Core.Abstraction.Presentation.TreeTab.Controller}
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
     */
    public function setRouterActionParam($param)
    {
        $this->routerActionParam = $param;

        return $this;
    }

    /**
     * @method getRouterActionParam
     * Return router action param.
     * @param {Array} $param
     * Router's attributes.
     * @return {Illuminate.Routing.Router}
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
     */
    public function getRouterActionParam($param = [])
    {
        return route($this->routerActionParam ? : $this->getVendorName() . ".module.{$this->getKey()}.action.param", $param);
    }

    /**
     * @method setRouterList
     * Set router list.
     * @param {String} $param
     * @return {Telenok.Core.Abstraction.Presentation.TreeTab.Controller}
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
     */
    public function setRouterList($param)
    {
        $this->routerList = $param;

        return $this;
    }

    /**
     * @method getRouterList
     * Return router list.
     * @param {Array} $param
     * Router's attributes.
     * @return {Illuminate.Routing.Router}
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
     */
    public function getRouterList($param = [])
    {
        return route($this->routerList ? : $this->getVendorName() . ".module.{$this->getKey()}.list", $param);
    }

    /**
     * @method setRouterContent
     * Set router content.
     * @param {String} $param
     * @return {Telenok.Core.Abstraction.Presentation.TreeTab.Controller}
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
     */
    public function setRouterContent($param)
    {
        $this->routerContent = $param;

        return $this;
    }

    /**
     * @method getRouterContent
     * Return router content.
     * @param {Array} $param
     * Router's attributes.
     * @return {Illuminate.Routing.Router}
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
     */
    public function getRouterContent($param = [])
    {
        return route($this->routerContent ? : $this->getVendorName() . ".module.{$this->getKey()}", $param);
    }

    /**
     * @method setRouterCreate
     * Set router create.
     * @param {String} $param
     * @return {Telenok.Core.Abstraction.Presentation.TreeTab.Controller}
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
     */
    public function setRouterCreate($param)
    {
        $this->routerCreate = $param;

        return $this;
    }

    /**
     * @method getRouterCreate
     * Return router create.
     * @param {String} $param
     * @return {Illuminate.Routing.Router}
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
     */
    public function getRouterCreate($param = [])
    {
        return route($this->routerCreate ? : $this->getVendorName() . ".module.{$this->getKey()}." . ($this->isDisplayTypeWizard() ? "wizard." : "") . "create", $param);
    }

    /**
     * @method setRouterEdit
     * Set router edit.
     * @param {String} $param
     * @return {Telenok.Core.Abstraction.Presentation.TreeTab.Controller}
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
     */
    public function setRouterEdit($param)
    {
        $this->routerEdit = $param;

        return $this;
    }

    /**
     * @method getRouterEdit
     * Return router edit.
     * @param {String} $param
     * @return {Illuminate.Routing.Router}
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
     */
    public function getRouterEdit($param = [])
    {
        return route($this->routerEdit ? : $this->getVendorName() . ".module.{$this->getKey()}." . ($this->isDisplayTypeWizard() ? "wizard." : "") . "edit", $param);
    }

    /**
     * @method setRouterDelete
     * Set router delete.
     * @param {String} $param
     * @return {Telenok.Core.Abstraction.Presentation.TreeTab.Controller}
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
     */
    public function setRouterDelete($param)
    {
        $this->routerDelete = $param;

        return $this;
    }

    /**
     * @method getRouterDelete
     * Return router delete.
     * @param {String} $param
     * @return {Illuminate.Routing.Router}
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
     */
    public function getRouterDelete($param = [])
    {
        return route($this->routerDelete ? : $this->getVendorName() . ".module.{$this->getKey()}." . ($this->isDisplayTypeWizard() ? "wizard." : "") . "delete", $param);
    }

    /**
     * @method setRouterStore
     * Set router store.
     * @param {String} $param
     * @return {Telenok.Core.Abstraction.Presentation.TreeTab.Controller}
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
     */
    public function setRouterStore($param)
    {
        $this->routerStore = $param;

        return $this;
    }

    /**
     * @method getRouterStore
     * Return router store.
     * @param {String} $param
     * @return {Illuminate.Routing.Router}
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
     */
    public function getRouterStore($param = [])
    {
        return route($this->routerStore ? : $this->getVendorName() . ".module.{$this->getKey()}." . ($this->isDisplayTypeWizard() ? "wizard." : "") . "store", $param);
    }

    /**
     * @method setRouterUpdate
     * Set router update.
     * @param {String} $param
     * @return {Telenok.Core.Abstraction.Presentation.TreeTab.Controller}
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
     */
    public function setRouterUpdate($param)
    {
        $this->routerUpdate = $param;

        return $this;
    }

    /**
     * @method getRouterUpdate
     * Return router update.
     * @param {String} $param
     * @return {Illuminate.Routing.Router}
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
     */
    public function getRouterUpdate($param = [])
    {
        return route($this->routerUpdate ? : $this->getVendorName() . ".module.{$this->getKey()}." . ($this->isDisplayTypeWizard() ? "wizard." : "") . "update", $param);
    }

    /**
     * @method setRouterListEdit
     * Set router list edit.
     * @param {String} $param
     * @return {Telenok.Core.Abstraction.Presentation.TreeTab.Controller}
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
     */
    public function setRouterListEdit($param)
    {
        $this->routerListEdit = $param;

        return $this;
    }

    /**
     * @method getRouterListEdit
     * Return router list edit.
     * @param {String} $param
     * @return {Illuminate.Routing.Router}
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
     */
    public function getRouterListEdit($param = [])
    {
        return route($this->routerListEdit ? : $this->getVendorName() . ".module.{$this->getKey()}.list.edit", $param);
    }

    /**
     * @method setRouterListDelete
     * Set router list delete.
     * @param {String} $param
     * @return {Telenok.Core.Abstraction.Presentation.TreeTab.Controller}
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
     */
    public function setRouterListDelete($param)
    {
        $this->routerListDelete = $param;

        return $this;
    }

    /**
     * @method getRouterListDelete
     * Return router list delete.
     * @param {String} $param
     * @return {Illuminate.Routing.Router}
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
     */
    public function getRouterListDelete($param = [])
    {
        return route($this->routerListDelete ? : $this->getVendorName() . ".module.{$this->getKey()}.list.delete", $param);
    }

    /**
     * @method setRouterListLock
     * Set router list lock.
     * @param {String} $param
     * @return {Telenok.Core.Abstraction.Presentation.TreeTab.Controller}
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
     */
    public function setRouterListLock($param)
    {
        $this->routerListLock = $param;

        return $this;
    }

    /**
     * @method getRouterLock
     * Return router lock.
     * @param {String} $param
     * @return {Illuminate.Routing.Router}
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
     */
    public function getRouterLock($param = [])
    {
        return route($this->routerLock ? : $this->getVendorName() . ".module.{$this->getKey()}.lock", $param);
    }

    /**
     * @method getRouterListLock
     * Return router list lock.
     * @param {String} $param
     * @return {Illuminate.Routing.Router}
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
     */
    public function getRouterListLock($param = [])
    {
        return route($this->routerListLock ? : $this->getVendorName() . ".module.{$this->getKey()}.list.lock", $param);
    }

    /**
     * @method setRouterListUnlock
     * Set router list unlock.
     * @param {String} $param
     * @return {Telenok.Core.Abstraction.Presentation.TreeTab.Controller}
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
     */
    public function setRouterListUnlock($param)
    {
        $this->routerListUnlock = $param;

        return $this;
    }

    /**
     * @method getRouterListUnlock
     * Return router list unlock.
     * @param {String} $param
     * @return {Illuminate.Routing.Router}
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
     */
    public function getRouterListUnlock($param = [])
    {
        return route($this->routerListUnlock ? : $this->getVendorName() . ".module.{$this->getKey()}.list.unlock", $param);
    }

    /**
     * @method setRouterListTree
     * Set router list tree.
     * @param {String} $param
     * @return {Telenok.Core.Abstraction.Presentation.TreeTab.Controller}
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
     */
    public function setRouterListTree($param)
    {
        $this->routerListTree = $param;

        return $this;
    }

    /**
     * @method getRouterListTree
     * Return router list tree.
     * @param {String} $param
     * @return {Illuminate.Routing.Router}
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
     */
    public function getRouterListTree($param = [])
    {
        return route($this->routerListTree ? : $this->getVendorName() . ".module.{$this->getKey()}.list.tree", $param);
    }

    /**
     * @method setModelListClass
     * Set class of list's model.
     * @param {String} $param
     * Class name.
     * @return {Telenok.Core.Abstraction.Presentation.TreeTab.Controller}
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
     */
    public function setModelListClass($param)
    {
        $this->modelListClass = $param;

        return $this;
    }

    /**
     * @method getModelListClass
     * Return class of list's model.
     * @return {String}
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
     */
    public function getModelListClass()
    {
        return $this->modelListClass;
    }

    /**
     * @method setModelTreeClass
     * Set class of tree's model.
     * @param {String} $param
     * @return {Telenok.Core.Abstraction.Presentation.TreeTab.Controller}
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
     */
    public function setModelTreeClass($param)
    {
        $this->modelTreeClass = $param;

        return $this;
    }

    /**
     * @method getModelTreeClass
     * Return model's tree class name.
     * @return {String}
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
     */
    public function getModelTreeClass()
    {
        return $this->modelTreeClass;
    }

    /**
     * @method getModelList
     * Return model's list class object.
     * @return {Telenok.Core.Abstraction.Eloquent.Object.Model}
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
     */
    public function getModelList()
    {
        return app($this->getModelListClass());
    }

    /**
     * @method getModelTree
     * Return model's tree class object.
     * @return {Telenok.Core.Abstraction.Eloquent.Object.Model}
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
     */
    public function getModelTree()
    {
        return app($this->getModelTreeClass());
    }

    /**
     * @method getTypeList
     * Return Object Type of model's list class object.
     * @return {Telenok.Core.Abstraction.Eloquent.Object.Model}
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
     */
    public function getTypeList()
    {
        return $this->getModelList()->type();
    }

    /**
     * @method getTypeTree
     * Return Object Type of model's tree class object.
     * @return {Telenok.Core.Abstraction.Eloquent.Object.Model}
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
     */
    public function getTypeTree()
    {
        return $this->getModelTree()->type();
    }

    /**
     * @method getModel
     * Return Eloquent object by id.
     * @return {Telenok.Core.Abstraction.Eloquent.Object.Model}
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
     */
    public function getModel($id)
    {
        return \App\Vendor\Telenok\Core\Model\Object\Sequence::getModel($id);
    }

    /**
     * @method getModelTrashed
     * Return Eloquent object by id. Object can be trashed.
     * @return {Telenok.Core.Abstraction.Eloquent.Object.Model}
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
     */
    public function getModelTrashed($id)
    {
        return \App\Vendor\Telenok\Core\Model\Object\Sequence::getModelTrashed($id);
    }

    /**
     * @method getType
     * Return Object Type by id or its code.
     * @return {Telenok.Core.Abstraction.Eloquent.Object.Model}
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
     */
    public function getType($id)
    {
        return \App\Vendor\Telenok\Core\Model\Object\Type::where('id', $id)->orWhere('code', $id)->active()->firstOrFail();
    }

    /**
     * @method getTypeByModelId
     * Return Object Type by related model via its id.
     * @param {Integer} $param
     * @return {Telenok.Core.Abstraction.Eloquent.Object.Model}
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
     */
    public function getTypeByModelId($id)
    {
        return \App\Vendor\Telenok\Core\Model\Object\Sequence::withTrashed()->findOrFail($id)->sequencesObjectType;
    }

    /**
     * @method getModelByTypeId
     * Return new Eloquent object by related Object Type via its id.
     * @param {Integer} $param
     * @return {Telenok.Core.Abstraction.Presentation.TreeTab.Controller}
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
     */
    public function getModelByTypeId($id)
    {
        return app($this->getType($id)->class_model);
    }

    /**
     * @method validate
     * Validate input before saving.
     * @param {Telenok.Core.Abstraction.Eloquent.Object.Model} $model
     * @param {Illuminate.Support.Collection} $param
     * @param {Array} $message
     * @return {Telenok.Core.Abstraction.Presentation.TreeTab.Controller}
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
     */
    public function validate($model = null, $input = null, $message = [])
    {
        return $this;
    }

    /**
     * @method validator
     * Return new validator.
     * @param {Telenok.Core.Abstraction.Eloquent.Object.Model} $model
     * @param {Illuminate.Support.Collection} $param
     * @param {Array} $message
     * @param {Array} $customAttribute
     * @return {Telenok.Core.Abstraction.Presentation.TreeTab.Controller}
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
     */
    public function validator($model = null, $input = [], $message = [], $customAttribute = [])
    {
        return app('\Telenok\Core\Support\Validator\Model')
                        ->setModel($model ? : $this->getModelList())
                        ->setInput($input)
                        ->setMessage($message)
                        ->setCustomAttribute($customAttribute);
    }

    /**
     * @method validateException
     * Return new exception.
     * @return {Telenok.Core.Abstraction.Presentation.TreeTab.Controller}
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
     */
    public function validateException()
    {
        return new \Telenok\Core\Support\Exception\Validator;
    }

    /**
     * @method getActionParam
     * Return json presentation's content.
     * @return {String}
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
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
     * @method getPresentationContent
     * Return presentation's content.
     * @return {String}
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
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
     * @method getContent
     * Return content of content view.
     * @return {String}
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
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
     * @method getTreeContent
     * Return content of tree view.
     * @return {String}
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
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
     * @method getFilterQueryLike
     * Return filtered "LIKE" query.
     * @param {mixed} $value
     * @param {Illuminate.Database.Query.Builder} $query
     * @param {Telenok.Core.Abstraction.Eloquent.Object.Model} $model
     * @param {Telenok.Core.Model.Object.Field} $field
     * Object with data of field's configuration.
     * @return {void}
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
     */
    public function getFilterQueryLike($value, $query, $model, $field)
    {
        $query->where(function($query) use ($value, $model, $field)
        {
            $query->where(app('db')->raw(1), 1);

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
     * @method getFilterQuery
     * Return filtered query.
     * @param {Telenok.Core.Abstraction.Eloquent.Object.Model} $model
     * @param {Illuminate.Database.Query.Builder} $query
     * @return {void}
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
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
            if (($model instanceof \Telenok\Core\Abstraction\Eloquent\Object\Model && $model->getFieldList()->filter(function($item) use ($orderByField)
                    {
                        return $orderByField === $item->code;
                    })->count()) || !($model instanceof \Telenok\Core\Abstraction\Eloquent\Object\Model))
            {
                $query->orderBy($model->getTable() . '.' . $orderByField, $input->input('order.0.dir') == 'asc' ? 'asc' : 'desc');
            }
        }
    }

    /**
     * @method getFilterSubQuery
     * Hook for returning filtered query in
     * {@link Telenok.Core.Abstraction.Presentation.TreeTab.Controller#getFilterQuery getFilterQuery}
     * @param {Illuminate.Support.Collection} $input
     * @param {Telenok.Core.Abstraction.Eloquent.Object.Model} $model
     * @param {Illuminate.Database.Query.Builder} $query
     * @return {void}
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
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
     * @method getListItem
     * Return items of $model's rows.
     * @param {Telenok.Core.Abstraction.Eloquent.Object.Model} $model
     * @return {Illuminate.Database.Eloquent.Collection}
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
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
     * @method fillListItem
     * Add items for list.
     * @param {Telenok.Core.Abstraction.Eloquent.Object.Model} $item
     * @param {Illuminate.Support.Collection} $put
     * @param {Telenok.Core.Abstraction.Eloquent.Object.Model} $model
     * @return {Telenok.Core.Abstraction.Presentation.TreeTab.Controller}
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
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
     * @method fillListItemProcessed
     * Process item for list.
     * @param {Telenok.Core.Abstraction.Eloquent.Object.Model} $item
     * @param {Illuminate.Support.Collection} $put
     * @param {Telenok.Core.Abstraction.Eloquent.Object.Model} $model
     * @return {Telenok.Core.Abstraction.Presentation.TreeTab.Controller}
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
     */
    public function fillListItemProcessed($item = null, \Illuminate\Support\Collection $put = null, $model = null)
    {
        return $this;
    }

    /**
     * @method getListItemProcessed
     * Additionally processing item for list.
     * @param {Telenok.Core.Model.Object.Field} $field
     * Object with data of field's configuration.
     * @param {Telenok.Core.Abstraction.Eloquent.Object.Model} $item
     * @return {Telenok.Core.Abstraction.Presentation.TreeTab.Controller}
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
     */
    public function getListItemProcessed($field, $item)
    {
        return $item->translate($field->code);
    }

    /**
     * @method getTreeList
     * Return items for tree.
     * @param {Integer} $id
     * Branch start Id from.
     * @return {Illuminate.Database.Eloquent.Collection}
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
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
     * @method getTreeListTypes
     * Return Ids of Object Types which linked models will selected.
     * @return {Array}
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
     */
    public function getTreeListTypes()
    {
        $types = [];

        $types[] = \App\Vendor\Telenok\Core\Model\Object\Type::where('code', 'folder')->active()->value('id');

        if ($this->getModelTreeClass())
        {
            $types[] = $this->getTypeTree()->getKey();
        }

        return $types;
    }

    /**
     * @method getTreeListModel
     * Return list items of tree.
     * @param {Integer} $treeId
     * @param {String} $str
     * Search in row's title.
     * @return {Illuminate.Database.Eloquent.Collection}
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
     */
    public function getTreeListModel($treeId = 0, $str = '')
    {
        $sequence = app('\App\Vendor\Telenok\Core\Model\Object\Sequence');

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
                $query = \App\Vendor\Telenok\Core\Model\Object\Sequence::withChildren(2)->orderBy('pivot_tree_children.tree_order');
            }
            else
            {
                $query = \App\Vendor\Telenok\Core\Model\Object\Sequence::find($treeId)->children(2)->orderBy('pivot_tree_attr.tree_order')->active();
            }

            $query->whereIn('object_sequence.sequences_object_type', $types);
        }

        $query->where('object_sequence.treeable', 1);
        $query->groupBy('object_sequence.id');
        $query->withPermission('read', null, ['direct-right']);

        return $query->get();
    }

    /**
     * @method getTreeListItemProcessed
     * Additionally process item for tree list.
     * @param {Telenok.Core.Abstraction.Eloquent.Object.Model} $item
     * @return {Array}
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
     */
    public function getTreeListItemProcessed($item)
    {
        return [];
    }

    /**
     * @method getListButton
     * Set group widget's model.
     * @param {Telenok.Core.Abstraction.Eloquent.Object.Model} $item
     * @return {Array}
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
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
     * @method getListButtonEventKey
     * Return name of event when adding button to list.
     * @param {mixed} $param
     * @return {String}
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
     */
    public function getListButtonEventKey($param = null)
    {
        return 'telenok.module.' . $this->getKey();
    }

    /**
     * @method getAdditionalListButton
     * Add buttons in list.
     * @param {Telenok.Core.Abstraction.Eloquent.Object.Model} $param
     * @param {Illuminate.Support.Collection} $collection
     * @return {Illuminate.Support.Collection}
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
     */
    public function getAdditionalListButton($item, $collection)
    {
        return $collection;
    }

    /**
     * @method getAdditionalViewParam
     * Return additional view parameters.
     * @return {Array}
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
     */
    public function getAdditionalViewParam()
    {
        return $this->additionalViewParam;
    }

    /**
     * @method setAdditionalViewParam
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
     */
    public function setAdditionalViewParam($param = [])
    {
        $this->additionalViewParam = $param;

        return $this;
    }

    /**
     * @method getGridId
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
     */
    public function getGridId($key = 'gridId')
    {
        return "{$this->getPresentation()}-{$this->getTabKey()}-{$key}";
    }

    /**
     * @method getModelFieldFilter
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
     */
    public function getModelFieldFilter($model = null)
    {
        return collect();
    }

    /**
     * @method getList
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
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
     * @method getRouterParam
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
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
     * @method create
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
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
     * @method edit
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
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
     * @method editList
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
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
     * @method deleteProcess
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
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
     * @method delete
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
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
     * @method deleteList
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
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
     * @method lock
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
     */
    public function lock()
    {
        $id = $this->getRequest()->input('id');

        try
        {
            $model = \App\Vendor\Telenok\Core\Model\Object\Sequence::find($id);

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
     * @method lockList
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
     */
    public function lockList()
    {
        $tableCheckAll = $this->getRequest()->input('tableCheckAll', []);

        try
        {
            foreach ($tableCheckAll as $id)
            {
                $model = \App\Vendor\Telenok\Core\Model\Object\Sequence::find($id)->model;

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
     * @method unlockList
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
     */
    public function unlockList()
    {
        $tableCheckAll = $this->getRequest()->input('tableCheckAll', []);

        try
        {
            $userId = app('auth')->user()->id;

            foreach ($tableCheckAll as $id)
            {
                $model = \App\Vendor\Telenok\Core\Model\Object\Sequence::withTrashed()->find($id)->model;

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
     * @method store
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
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
     * @method update
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
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
     * @method save
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
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
                $model->makeLastChildOf(\App\Vendor\Telenok\Core\Model\System\Folder::findOrFail($input->get('tree_pid'))->sequence);
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
     * @method preProcess
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
     */
    public function preProcess($model, $type, $input)
    {
        return $this;
    }

    /**
     * @method postProcess
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
     */
    public function postProcess($model, $type, $input)
    {
        return $this;
    }

    /**
     * @method getModelFieldViewKey
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
     */
    public function getModelFieldViewKey($field)
    {

    }

    /**
     * @method getModelFieldView
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
     */
    public function getModelFieldView($field)
    {

    }

    /**
     * @method getModelFieldViewVariable
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
     */
    public function getModelFieldViewVariable($fieldController = null, $model = null, $field = null, $uniqueId = null)
    {

    }

    /**
     * @method getDisplayType
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
     */
    public function getDisplayType()
    {
        return $this->displayType;
    }

    /**
     * @method setDisplayType
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
     */
    public function setDisplayType($type)
    {
        $this->displayType = $type;

        return $this;
    }

    /**
     * @method isDisplayTypeWizard
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
     */
    public function isDisplayTypeWizard()
    {
        return $this->displayType == static::$DISPLAY_TYPE_WIZARD;
    }

}
