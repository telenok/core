<?php

namespace Telenok\Core\Module\Objects\Lists;

use \Telenok\Core\Contract\Presentation\Presentation;

/**
 * @class Telenok.Core.Module.Objects.Lists.Controller
 * @extends Telenok.Core.Abstraction.Presentation.TreeTab.Controller
 */
class Controller extends \Telenok\Core\Abstraction\Presentation\TreeTab\Controller {

    protected $key = 'objects-lists';
    protected $parent = 'objects';
    protected $modelTreeClass = '\App\Vendor\Telenok\Core\Model\Object\Type';
    protected $presentation = 'tree-tab-object';
    protected $presentationContentView = 'core::module.objects-lists.content';
    protected $presentationModelView = 'core::module.objects-lists.model';
    protected $presentationTreeView = 'core::module.objects-lists.tree';
    protected $presentationFormModelView = 'core::presentation.tree-tab-object.form';
    protected $presentationFormFieldListView = 'core::presentation.tree-tab-object.form-field-list';

    public function getModelFieldViewKey($field)
    {
        
    }

    public function getModelFieldView($field)
    {
        
    }

    public function getModelFieldViewVariable($fieldController = null, $model = null, $field = null, $uniqueId = null)
    {
        
    }

    public function getActionParam()
    {
        if ($typeId = $this->getRequest()->input('typeId', 0))
        {
            $type = $this->getType($typeId);

            if ($type->classController() && ($controllerProcessing = $this->typeForm($type)) instanceof \Telenok\Core\Contract\Presentation\Presentation)
            {
                return $controllerProcessing->getActionParam();
            }
        }
        else
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
    }

    public function setPresentationModelView($view = '')
    {
        $this->presentationModelView = $view;

        return $this;
    }

    public function getPresentationModelView()
    {
        return $this->presentationModelView;
    }

    public function typeForm($type)
    {
        return app($type->classController())
                        ->setTabKey($this->key)
                        ->setAdditionalViewParam($this->getAdditionalViewParam());
    }

    public function getTreeListTypes()
    {
        $types = \App\Vendor\Telenok\Core\Model\Object\Type::whereIn('code', ['folder', 'object_type'])->active()->get()->pluck('id')->toArray();

        return $types;
    }

    public function getTreeList($id = null)
    {
        $input = $this->getRequest();
        $typeId = $input->input('typeId', 0);

        if ($input->has('treeId'))
        {
            $type = $this->getType($typeId);

            if ($type->classController() && ($controllerProcessing = $this->typeForm($type)) instanceof \Telenok\Core\Contract\Presentation\Presentation)
            {
                return $controllerProcessing->getTreeList();
            }
        }
        else
        {
            return parent::getTreeList($typeId);
        }
    }

    public function getTreeListItemProcessed($item)
    {
        $typeObjectId = \App\Vendor\Telenok\Core\Model\Object\Type::where('code', 'object_type')->value('id');

        $code = '';
        $module = null;

        if ($item->sequences_object_type == $typeObjectId)
        {
            $code = $item->model->code;

            if ($item->model->class_controller)
            {
                $module = app($item->model->class_controller);
            }
        }

        return [
            'gridId' => $this->getGridId($code),
            'typeId' => $item->sequences_object_type,
            'module' => ($module ? 1 : 0),
            'moduleKey' => ($module ? $module->getKey() : ""),
            'moduleRouterActionParam' => ($module ? $module->getRouterActionParam(['typeId' => $item->getKey()]) : ""),
        ];
    }

    public function getTreeContent()
    {
        if ($typeId = $this->getRequest()->input('typeId', 0))
        {
            $type = $this->getType($typeId);

            if ($type->classController() && ($controllerProcessing = $this->typeForm($type)) instanceof \Telenok\Core\Contract\Presentation\Presentation)
            {
                return $controllerProcessing->getTreeContent();
            }
        }
        else
        {
            return view($this->getPresentationTreeView(), array(
                        'controller' => $this,
                        'treeChoose' => $this->LL('title.tree'),
                        'typeId' => 0,
                        'id' => str_random()
                    ))->render();
        }
    }

    public function getContent()
    {
        try
        {
            $model = $this->getModelByTypeId($this->getRequest()->input('typeId', 0));
            $type = $this->getType($this->getRequest()->input('typeId', 0));

            if ($type->classController() && ($controllerProcessing = $this->typeForm($type)) instanceof \Telenok\Core\Contract\Presentation\Presentation)
            {
                return $controllerProcessing->getContent();
            }

            $fields = $model->getFieldList();
        }
        catch (\LogicException $e)
        {
            return ['message' => $e->getMessage()];
        }
        catch (\Exception $e)
        {
            return ['message' => $e->getMessage()];
        }

        return [
            'tabKey' => "{$this->getTabKey()}-{$model->getTable()}",
            'tabLabel' => $type->translate('title'),
            'tabContent' => view($this->getPresentationContentView(), array_merge([
                'controller' => $this,
                'model' => $model,
                'type' => $type,
                'fields' => $fields,
                'fieldsFilter' => $this->getModelFieldFilter($model),
                'gridId' => $this->getGridId($model->getTable()),
                'uniqueId' => str_random(),
                            ], $this->getAdditionalViewParam()))->render()
        ];
    }

    public function getFormContent($model, $type, $fields, $uniqueId)
    {
        return view($this->getPresentationFormModelView(), array_merge([
                    'controller' => $this,
                    'model' => $model,
                    'type' => $type,
                    'fields' => $fields,
                    'uniqueId' => $uniqueId,
                                ], $this->getAdditionalViewParam()))->render();
    }

    public function getModelFieldFilter($model = null)
    {
        return $model->getFieldForm()->filter(function($item)
                {
                    return $item->allow_search;
                });
    }

    public function getFilterSubQuery($input, $model, $query)
    {
        $input = collect($input);
        $controller = app('telenok.config.repository')->getObjectFieldController();

        $model->getFieldForm()->each(function($field) use ($input, $query, $controller, $model)
        {
            if ($field->allow_search && $input->has($field->code))
            {
                $controller->get($field->key)->getFilterQuery($field, $model, $query, $field->code, $input->get($field->code));
            }
            else
            {
                $controller->get($field->key)->getFilterQuery($field, $model, $query, $field->code, null);
            }
        });
    }

    public function getFilterQueryLike($str, $query, $model, $field)
    {
        $query->where(function($query) use ($str, $query, $model, $field)
        {
            $f = $model->getObjectField()->get($field);
            app('telenok.config.repository')
                    ->getObjectFieldController($f->key)
                    ->getFilterQuery($f, $model, $query, $f->code, $str);
        });
    }

    public function getListItem($model = null)
    {
        $query = $model->withPermission()->withTrashed();

        $this->getFilterQuery($model, $query);

        return $query->groupBy($model->getTable() . '.id')
                        ->orderBy($model->getTable() . '.updated_at', 'desc')
                        ->skip($this->getRequest()->input('start', 0))
                        ->take($this->getRequest()->input('length', $this->pageLength) + 1)
                        ->get();
    }

    public function getList()
    {        
        $model = null;
        $content = [];
        $input = $this->getRequest();
        $draw = $input->input('draw');
        $start = $input->input('start', 0);
        $length = $input->input('length', $this->pageLength);
        $typeId = $input->input('typeId', 0);

        try
        {
            if (empty($typeId))
            {
                throw new \Exception('Please, define typeId');
            }

            if (is_array($typeId))
            {
                $id = \App\Vendor\Telenok\Core\Model\Object\Type::where('code', 'object_sequence')->value('id');

                $type = $this->getType($id);
                $model = $this->getModelByTypeId($id);
            }
            else
            {
                $type = $this->getType($typeId);
                $model = $this->getModelByTypeId($typeId);
            }

            if (!is_array($typeId) 
                    && $type->classController() 
                    && ($controllerProcessing = $this->typeForm($type)) instanceof \Telenok\Core\Contract\Presentation\Presentation)
            {
                $items = $controllerProcessing->getListItem($model);
                
                foreach ($items->slice(0, $length, true) as $item)
                {
                    $put = collect();

                    $controllerProcessing->fillListItem($item, $put, $model, $type);
                    $this->fillListItemProcessed($item, $put, $model, $type);

                    $content[] = $put->all();
                }    
            }
            else
            {
                $items = $this->getListItem($model);

                foreach ($items->slice(0, $length, true) as $item)
                {
                    $put = collect();

                    $this->fillListItem($item, $put, $model, $type);
                    $this->fillListItemProcessed($item, $put, $model, $type);

                    $content[] = $put->all();
                }    
            }
        }
        catch (\Exception $e)
        {
            return [
                'data' => [],
                'draw' => $draw,
                'gridId' => $this->getGridId(),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'exception' => $e->getMessage(),
            ];
        }

        return [
            'gridId' => $this->getGridId($model->getTable()),
            'draw' => $draw,
            'data' => $content,
            'recordsTotal' => ($start + $items->count()),
            'recordsFiltered' => ($start + $items->count()),
        ];
    }

    public function fillListItem($item = null, \Illuminate\Support\Collection $put = null, $model = null, $type = null)
    {
        $config = app('telenok.config.repository')->getObjectFieldController();

        $put->put('tableCheckAll', '<input type="checkbox" class="ace ace-checkbox-2" '
            . 'name="tableCheckAll[]" value="' . $item->getKey() . '"><span class="lbl"></span>');

        foreach ($model->getFieldList() as $field)
        {
            $put->put($field->code, $config->get($field->key)->getListFieldContent($field, $item, $type));
        }

        $canDelete = app('auth')->can('delete', $item);

        $put->put('tableManageItem', $this->getListButton($item, $type, $canDelete));

        return $this;
    }

    public function fillListItemProcessed($item = null, \Illuminate\Support\Collection $put = null, $model = null, $type = null)
    {
        return $this;
    }

    public function getListButton($item, $type = null, $canDelete = null)
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
            '<li><a href="#" onclick="telenok.getPresentation(\'' . $this->getPresentationModuleKey() . '\').addTabByURL({url : \''
            . $this->getRouterEdit(['id' => $item->getKey()]) . '\'}); return false;">'
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

    public function createWizard()
    {
        $this->displayType = static::$DISPLAY_TYPE_WIZARD;

        return $this->create();
    }

    public function editWizard($id = 0)
    {
        $this->displayType = static::$DISPLAY_TYPE_WIZARD;

        return $this->edit($id);
    }

    public function storeWizard($id = null)
    {
        $this->displayType = static::$DISPLAY_TYPE_WIZARD;

        return $this->store($id);
    }

    public function updateWizard($id = null)
    {
        $this->displayType = static::$DISPLAY_TYPE_WIZARD;

        return $this->update($id);
    }

    public function deleteWizard($id = null, $force = false)
    {
        $this->displayType = static::$DISPLAY_TYPE_WIZARD;

        return $this->delete($id, $force);
    }

    public function create()
    {
        $input = $this->getRequestCollected();

        $id = $input->get('id');

        $model = $this->getModelByTypeId($id);
        $type = $this->getType($id);
        $fields = $model->getFieldForm();

        if (!app('auth')->can('create', "object_type.{$type->code}"))
        {
            throw new \LogicException($this->LL('error.access'));
        }

        if ($type->classController() && ($controllerProcessing = $this->typeForm($type)) instanceof \Telenok\Core\Contract\Presentation\Presentation)
        {
            return $controllerProcessing->setDisplayType($this->displayType)->create();
        }

        $eventResource = collect(['model' => $model, 'type' => $type, 'fields' => $fields]);

        //\Event::fire('workflow.form.create', (new \Telenok\Core\Workflow\Event())->setResource($eventResource)->setInput($input));

        try
        {
            return [
                'tabKey' => $this->getTabKey() . '-new-' . str_random(),
                'tabLabel' => $type->translate('title'),
                'tabContent' => view($this->getPresentationModelView(), array_merge(array(
                    'controller' => $this,
                    'model' => $eventResource->get('model'),
                    'type' => $eventResource->get('type'),
                    'fields' => $eventResource->get('fields'),
                    'uniqueId' => str_random(),
                    'routerParam' => $this->getRouterParam('create', $eventResource->get('type'), $eventResource->get('model')),
                    'canCreate' => app('auth')->can('create', "object_type.{$eventResource->get('type')->code}"),
                                ), $this->getAdditionalViewParam()))->render()
            ];
        }
        catch (\Exception $ex)
        {
            return [
                'exception' => $ex->getMessage(),
            ];
        }
    }

    public function edit($id = 0)
    {
        $input = $this->getRequestCollected();

        $id = $id ? : $input->get('id');

        $model = $this->getModelTrashed($id);
        $type = $this->getTypeByModelId($id);
        $fields = $model->getFieldForm();

        if (!app('auth')->can('read', $id))
        {
            throw new \LogicException($this->LL('error.access'));
        }

        if ($type->classController() && ($controllerProcessing = $this->typeForm($type)) instanceof \Telenok\Core\Contract\Presentation\Presentation)
        {
            return $controllerProcessing->setDisplayType($this->displayType)->edit($id);
        }

        $eventResource = collect(['model' => $model, 'type' => $type, 'fields' => $fields]);

        //\Event::fire('workflow.form.edit', (new \Telenok\Core\Workflow\Event())->setResource($eventResource)->setInput($input));

        $model->lock();

        try
        {
            return [
                'tabKey' => $this->getTabKey() . '-edit-' . $id,
                'tabLabel' => $type->translate('title') . ' ' . str_limit($eventResource->get('model')->translate('title'), 10),
                'tabContent' => view($this->getPresentationModelView(), array_merge(array(
                    'controller' => $this,
                    'model' => $eventResource->get('model'),
                    'type' => $eventResource->get('type'),
                    'fields' => $eventResource->get('fields'),
                    'uniqueId' => str_random(),
                    'routerParam' => $this->getRouterParam('edit', $eventResource->get('type'), $eventResource->get('model')),
                    'canUpdate' => app('auth')->can('update', $eventResource->get('model')),
                    'canDelete' => app('auth')->can('delete', $eventResource->get('model')),
                                ), $this->getAdditionalViewParam()))->render()
            ];
        }
        catch (\Exception $ex)
        {
            return [
                'exception' => $ex->getMessage(),
            ];
        }
    }

    public function delete($id = null, $force = false)
    {
        $type = $this->getTypeByModelId($id);

        if ($type->classController() && ($controllerProcessing = $this->typeForm($type)) instanceof \Telenok\Core\Contract\Presentation\Presentation)
        {
            return $controllerProcessing->setDisplayType($this->displayType)->delete($id, $force);
        }

        if (!app('auth')->can('delete', $id))
        {
            throw new \LogicException($this->LL('error.access'));
        }

        $model = $this->getModelTrashed($id);

        try
        {
            app('db')->transaction(function() use ($model, $type, $force)
            {
                //\Event::fire('workflow.delete.before', (new \Telenok\Core\Workflow\Event())->setResourceCode("object_type.{$type->code}"));

                if ($force || $model->trashed())
                {
                    $model->forceDelete();
                }
                else
                {
                    $model->delete();
                }

                //\Event::fire('workflow.delete.after', (new \Telenok\Core\Workflow\Event())->setResourceCode("object_type.{$type->code}")->setResource($model));
            });

            return ['success' => 1];
        }
        catch (\Exception $e)
        {
            throw new \LogicException($this->LL('error.access'));
        }
    }

    public function editList()
    {
        $input = $this->getRequestCollected();
        $ids = $input->get('tableCheckAll', []);

        if (empty($ids))
        {
            return \Response::json(['message' => 'Expectation Failed'], 417 /* Expectation Failed */);
        }

        $content = [];

        $model = $this->getModelByTypeId($input->get('id'));
        $type = $this->getType($input->get('id'));
        $fields = $model->getFieldForm();

        foreach ($ids as $id_)
        {
            if (!app('auth')->can('read', $id_))
            {
                continue;
            }

            if ($type->classController() && ($controllerProcessing = $this->typeForm($type)) instanceof \Telenok\Core\Contract\Presentation\Presentation)
            {
                $content[] = with(new \Illuminate\Support\Collection($controllerProcessing->edit($id_)))->get('tabContent');
            }
            else
            {
                $eventResource = collect(['model' => $model::find($id_), 'type' => $type, 'fields' => $fields]);

                //\Event::fire('workflow.form.edit', (new \Telenok\Core\Workflow\Event())->setResource($eventResource)->setInput($input));

                $content[] = view($this->getPresentationModelView(), array_merge(array(
                    'controller' => $this,
                    'model' => $eventResource->get('model'),
                    'type' => $eventResource->get('type'),
                    'fields' => $eventResource->get('fields'),
                    'routerParam' => $this->getRouterParam('edit', $eventResource->get('type'), $eventResource->get('model')),
                    'uniqueId' => str_random(),
                    'canUpdate' => app('auth')->can('update', $eventResource->get('model')),
                    'canDelete' => app('auth')->can('delete', $eventResource->get('model')),
                                ), $this->getAdditionalViewParam()))->render();
            }
        }

        return [
            'tabKey' => $this->getTabKey() . '-edit-' . implode('', $ids),
            'tabLabel' => $type->translate('title'),
            'tabContent' => implode('<div class="hr hr-double hr-dotted hr18"></div>', $content)
        ];
    }

    public function deleteList($id = null, $ids = [])
    {
        $id = $this->getRequest()->input('id');
        $ids = empty($ids) ? (array) $this->getRequest()->input('tableCheckAll') : $ids;

        if (empty($id) || empty($ids))
        {
            return \Response::json(['message' => 'Expectation Failed'], 417 /* Expectation Failed */);
        }

        $type = $this->getTypeByModelId($id);

        if (!app('auth')->can('delete', "object_type.{$type->code}"))
        {
            throw new \LogicException($this->LL('error.access'));
        }

        $error = false;

        app('db')->transaction(function() use ($id, $ids, &$error)
        {
            try
            {
                $model = $this->getModelByTypeId($id);

                foreach ($ids as $id_)
                {
                    $model::withTrashed()->findOrFail($id_)->delete();
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

    public function store($id = null)
    {
        $input = $this->getRequestCollected();

        $type = $this->getType($id);

        if ($type->classController() && ($controllerProcessing = $this->typeForm($type)) instanceof \Telenok\Core\Contract\Presentation\Presentation)
        {
            return $controllerProcessing->setDisplayType($this->displayType)->store();
        }

        $model = $this->save($input, $type);

        $fields = $model->getFieldForm();

        $eventResource = collect(['model' => $model, 'type' => $type, 'fields' => $fields]);

        //\Event::fire('workflow.form.edit', (new \Telenok\Core\Workflow\Event())->setResource($eventResource)->setInput($input));

        $return = [];

        $return['tabContent'] = view($this->getPresentationModelView(), array_merge(array(
            'controller' => $this,
            'model' => $eventResource->get('model'),
            'type' => $eventResource->get('type'),
            'fields' => $eventResource->get('fields'),
            'uniqueId' => str_random(),
            'success' => true,
            'warning' => \Session::get('warning'),
            'routerParam' => $this->getRouterParam('store', $eventResource->get('type'), $eventResource->get('model')),
            'canUpdate' => app('auth')->can('update', $eventResource->get('model')),
            'canDelete' => app('auth')->can('delete', $eventResource->get('model')),
                        ), $this->getAdditionalViewParam()))->render();

        return $return;
    }

    public function update($id = null)
    {
        try
        {
            $input = $this->getRequestCollected();

            $type = $this->getType($id);

            if ($type->classController() && ($controllerProcessing = $this->typeForm($type)) instanceof \Telenok\Core\Contract\Presentation\Presentation)
            {
                return $controllerProcessing->setDisplayType($this->displayType)->update();
            }

            $model = $this->save($input, $type);
        }
        catch (\Exception $e)
        {
            throw $e;
        }

        $fields = $model->getFieldForm();

        $eventResource = collect(['model' => $model, 'type' => $type, 'fields' => $fields]);

        //\Event::fire('workflow.form.edit', (new \Telenok\Core\Workflow\Event())->setResource($eventResource)->setInput($input));

        $return = [];

        $return['tabContent'] = view($this->getPresentationModelView(), array_merge(array(
            'controller' => $this,
            'model' => $eventResource->get('model'),
            'type' => $eventResource->get('type'),
            'fields' => $eventResource->get('fields'),
            'uniqueId' => str_random(),
            'success' => true,
            'warning' => \Session::get('warning'),
            'routerParam' => $this->getRouterParam('update', $eventResource->get('type'), $eventResource->get('model')),
            'canUpdate' => app('auth')->can('update', $eventResource->get('model')),
            'canDelete' => app('auth')->can('delete', $eventResource->get('model')),
                        ), $this->getAdditionalViewParam()))->render();

        return $return;
    }

    public function getRouterParam($action = '', $type = null, $model = null)
    {
        switch ($action)
        {
            case 'create':
                return [ $this->getRouterStore(['id' => $type->getKey(), 'saveBtn' => $this->getRequest()->input('saveBtn', true), 'chooseBtn' => $this->getRequest()->input('chooseBtn', false), 'chooseSequence' => $this->getRequest()->input('chooseSequence', false)])];
                break;

            case 'edit':
                return [ $this->getRouterUpdate(['id' => $type->getKey(), 'saveBtn' => $this->getRequest()->input('saveBtn', true), 'chooseBtn' => $this->getRequest()->input('chooseBtn', true), 'chooseSequence' => $this->getRequest()->input('chooseSequence', false)])];
                break;

            case 'store':
                return [ $this->getRouterUpdate(['id' => $type->getKey(), 'saveBtn' => $this->getRequest()->input('saveBtn', true), 'chooseBtn' => $this->getRequest()->input('chooseBtn', true), 'chooseSequence' => $this->getRequest()->input('chooseSequence', false)])];
                break;

            case 'update':
                return [ $this->getRouterUpdate(['id' => $type->getKey(), 'saveBtn' => $this->getRequest()->input('saveBtn', true), 'chooseBtn' => $this->getRequest()->input('chooseBtn', true), 'chooseSequence' => $this->getRequest()->input('chooseSequence', false)])];
                break;

            default:
                return [];
                break;
        }
    }

    public function save($input = [], $type = null)
    {
        $input = collect($input);

        if (!($type instanceof \Telenok\Core\Model\Object\Type))
        {
            try
            {
                $type = $this->getType($type);
            }
            catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e)
            {
                try
                {
                    $type = \App\Vendor\Telenok\Core\Model\Object\Sequence::findOrFail($input->get('id'))->sequencesObjectType()->firstOrFail();
                }
                catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e)
                {
                    throw new \Exception("\App\Vendor\Telenok\Core\Module\Objects\Lists\Controller::save() - Error: 'type of object not found, please, define it'");
                }
            }
        }

        $model = $this->getModelByTypeId($type->getKey());

        $this->preProcess($model, $type, $input);

        $this->validate($model, $input->all());

        $model_ = $model->storeOrUpdate($input, true);

        $this->postProcess($model_, $type, $input);

        return $model_;
    }

}
