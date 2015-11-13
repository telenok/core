<?php namespace Telenok\Core\Interfaces\Presentation\TreeTabObject;

class Controller extends \Telenok\Core\Interfaces\Presentation\TreeTab\Controller {

    protected $key = '';
    protected $parent = '';

    protected $presentationTreeView = 'core::presentation.tree-tab-object.tree';
    protected $presentationContentView = 'core::presentation.tree-tab-object.content';
    protected $presentationModelView = 'core::presentation.tree-tab-object.model';
    protected $presentationFormModelView = 'core::presentation.tree-tab-object.form';
    protected $presentationFormFieldListView = 'core::presentation.tree-tab-object.form-field-list'; 

    public function getModelFieldView($field) {}

	public function getModelFieldViewKey($field) {}
	
	public function getModelFieldViewVariable($fieldController = null, $model = null, $field = null, $uniqueId = null) {}

    public function getGridId($key = 'gridId')
    {
        return "{$this->getPresentation()}-{$this->getTabKey()}-{$this->getTypeList()->code}";
    }  

    public function getActionParam()
    { 
        try
        {
            return [
                'presentation' => $this->getPresentation(),
                'presentationModuleKey' => $this->getPresentationModuleKey(),
                'presentationContent' => $this->getPresentationContent(),
                'key' => $this->getKey(),
                'treeContent' => $this->getTreeContent(),
                'url' => $this->getRouterContent(['typeId' => $this->getTypeList()->getKey(), 'treeId' => 0]),
                'breadcrumbs' => $this->getBreadcrumbs(),
                'pageHeader' => $this->getPageHeader(),
            ];
        }
        catch (\Exception $e)
        {
            return [
                'error' => $e->getMessage(),
            ];
        } 
    }

    public function getContent()
    { 
        try 
        {
            $model = $this->getModelList();
            $type = $this->getTypeList(); 
            $fields = $model->getFieldList();
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
                'fieldsFilter' => $this->getModelFieldFilter(),
                'gridId' => $this->getGridId(),
                'uniqueId' => str_random(),
            ], $this->getAdditionalViewParam()))->render()
        ];
    }

    public function getTreeContent()
    {
        if (!$this->getModelTreeClass()) 
		{
			return;
		}

        return view($this->getPresentationTreeView(), array(
                'controller' => $this, 
                'treeChoose' => $this->LL('title.tree'),
                'typeId' => $this->getTypeList()->getKey(),
                'id' => str_random()
            ))->render();
    } 

    public function getFormContent($model, $type, $fields, $uniqueId)
    {
        return view($this->getPresentationFormModelView(), array_merge(array( 
                'controller' => $this,
                'model' => $model, 
                'type' => $type, 
                'fields' => $fields, 
                'uniqueId' => $uniqueId, 
            ), $this->getAdditionalViewParam()))->render();
    }

    public function getModelFieldFilter($model = null)
    {
        return $this->getModelList()->getFieldForm()->filter(function($field)
		{
            return $field->allow_search;
        }); 
    }

    public function getFilterSubQuery($input, $model, $query)
    {
        $fieldConfig = app('telenok.config.repository')->getObjectFieldController();

		if (!$input instanceof \Illuminate\Support\Collection)
		{
			$input = \Illuminate\Support\Collection::make($input);
		}

        $model->getFieldForm()->each(function($field) use ($input, $query, $fieldConfig, $model)
        {
			if ($field->allow_search && $input->has($field->code))
			{
                $fieldConfig->get($field->key)->getFilterQuery($field, $model, $query, $field->code, $input->get($field->code));
			}
            else
            {
                $fieldConfig->get($field->key)->getFilterQuery($field, $model, $query, $field->code, null);
            }
        }); 
    }

    public function getList()
    {
        $content = [];

        $input = \Illuminate\Support\Collection::make($this->getRequest()->input()); 
        
        $total = $input->get('iDisplayLength', $this->displayLength);
        $sEcho = $input->get('sEcho');
        $iDisplayStart = $input->get('iDisplayStart', 0); 

        try
        {
            $model = $this->getModelList();
            $type = $this->getTypeList();

            $items = $this->getListItem($model)->get();

            $config = app('telenok.config.repository')->getObjectFieldController();

			$fields = $model->getFieldList();
			
            foreach ($items->slice(0, $this->displayLength, true) as $k => $item)
            {
                $put = ['tableCheckAll' => '<input type="checkbox" class="ace ace-checkbox-2" name="tableCheckAll[]" value="'.$item->getKey().'"><span class="lbl"></span>'];

                foreach ($fields as $field)
                {
					$put[$field->code] = $config->get($field->key)->getListFieldContent($field, $item, $type);
                }

                $put['tableManageItem'] = $this->getListButton($item);
                        
                $content[] = $put;
            }
        }
        catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) 
        {
            return [
                'gridId' => $this->getGridId(), 
                'sEcho' => $sEcho,
                'iTotalRecords' => 0,
                'iTotalDisplayRecords' => 0,
                'aaData' => []
            ];
        }

        return [
            'gridId' => $this->getGridId(), 
            'sEcho' => $sEcho,
            'iTotalRecords' => ($iDisplayStart + $items->count()),
            'iTotalDisplayRecords' => ($iDisplayStart + $items->count()),
            'aaData' => $content
        ];
    }

    public function getListItem($model)
    {  
        $query = $model::withTrashed()->select($model->getTable() . '.*')->withPermission();

        $this->getFilterQuery($model, $query); 

        return $query->groupBy($model->getTable() . '.id')->orderBy($model->getTable() . '.updated_at', 'desc')->skip($this->getRequest()->input('iDisplayStart', 0))->take($this->displayLength + 1);
    } 
    
    public function getTreeListTypes()
    { 
        $types = [];

        if ($this->getModelTreeClass())
        {
            $types[] = $this->getTypeTree()->getKey();
        }
        
        return $types;
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
    
    public function getFilterQuery($model, $query)
    {
        $translate = new \App\Telenok\Core\Model\Object\Translation();
        
        if ($title = trim($this->getRequest()->input('sSearch')))
        {
            $this->getFilterQueryLike($title, $query, $model, 'title');
        } 

		if ($this->getRequest()->input('multifield_search', false))
		{
			$this->getFilterSubQuery($this->getRequest()->input('filter', []), $model, $query);
		}
        else
        {
			$this->getFilterSubQuery(null, $model, $query);
        }

        $orderByField = $this->getRequest()->input('mDataProp_' . $this->getRequest()->input('iSortCol_0'));
        
        if ($this->getRequest()->input('iSortCol_0', 0))
        {
            if (in_array($orderByField, $model->getMultilanguage(), true))
            { 
                $query->leftJoin($translate->getTable(), function($join) use ($model, $translate, $orderByField)
                {
                    $join   ->on($model->getTable().'.id', '=', $translate->getTable().'.translation_object_model_id')
                            ->on($translate->getTable().'.translation_object_field_code', '=', \DB::raw("'{$orderByField}'"))
                            ->on($translate->getTable().'.translation_object_language', '=', \DB::raw("'".config('app.locale')."'"));
                });

                $query->orderBy($translate->getTable().'.translation_object_string', $this->getRequest()->input('sSortDir_0'));
            }
            else
            {
                $query->orderBy($model->getTable() . '.' . $orderByField, $this->getRequest()->input('sSortDir_0'));
            }
        }
    }

    public function create()
    { 
        $input = \Illuminate\Support\Collection::make($this->getRequest()->input()); 

		$model = $this->getModelList();
        $type = $this->getTypeList();
        $fields = $model->getFieldForm();

        $eventResource = \Illuminate\Support\Collection::make(['model' => $model, 'type' => $type, 'fields' => $fields]);

        //\Event::fire('workflow.form.create', (new \Telenok\Core\Workflow\Event())->setResource($eventResource)->setInput($input));

        return [
            'tabKey' => $this->getTabKey() . '-new-' . str_random(),
            'tabLabel' => $type->translate('title'),
            'tabContent' => view($this->getPresentationModelView(), array_merge([
                'controller' => $this,
                'model' => $eventResource->get('model'), 
                'type' => $eventResource->get('type'), 
                'fields' => $eventResource->get('fields'), 
                'uniqueId' => str_random(), 
				'routerParam' => $this->getRouterParam('create', $eventResource->get('type'), $eventResource->get('model')),
				'canCreate' => app('auth')->can('create', $eventResource->get('model')), 
            ], $this->getAdditionalViewParam()))->render()
        ];
    }

    public function edit($id = 0)
    { 
        $input = \Illuminate\Support\Collection::make($this->getRequest()->input());
		$id = $id ?: $input->get('id');
		
        $model = $this->getModelList()->findOrFail($id);
        $type = $this->getTypeList();
        $fields = $model->getFieldForm();
            
        $eventResource = \Illuminate\Support\Collection::make(['model' => $model, 'type' => $type, 'fields' => $fields]);

        //\Event::fire('workflow.form.edit', (new \Telenok\Core\Workflow\Event())->setResource($eventResource)->setInput($input));  

		$model->lock();
        
        return [
            'tabKey' => $this->getTabKey() . '-edit-' . $id,
            'tabLabel' => $type->translate('title'),
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

    public function store($id = null)
    {
        $input = \Illuminate\Support\Collection::make($this->getRequest()->input());  

        $type = $this->getTypeList();

        $model = $this->save($input, $type);

		$fields = $model->getFieldForm();
            
        $eventResource = \Illuminate\Support\Collection::make(['model' => $model, 'type' => $type, 'fields' => $fields]);

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
        $input = \Illuminate\Support\Collection::make($this->getRequest()->input()); 

        $type = $this->getTypeList();

        $model = $this->save($input, $type);
	
		$fields = $model->getFieldForm();
            
        $eventResource = \Illuminate\Support\Collection::make(['model' => $model, 'type' => $type, 'fields' => $fields]);

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
	
    public function save($input = [], $type = null)
    {   
        $input = \Illuminate\Support\Collection::make($input);
        $model = $this->getModelList();
        
        if (!($type instanceof \Telenok\Core\Model\Object\Type))
        {
            $type = $this->getTypeList();
        }

		return $model->storeOrUpdate($input, true);
    }

    public function editList()
    {
        $content = [];
        $input = \Illuminate\Support\Collection::make($this->getRequest()->input());
        
        $ids = $input->get('tableCheckAll', []);
        
        if (empty($ids))
		{
            return \Response::json(['message' => 'Expectation Failed'], 417 /* Expectation Failed */);
		}
        
        $type = $this->getTypeList();
        $model = $this->getModelList();
        $fields = $type->field()->get();

        foreach ($ids as $id)
        { 
            $eventResource = \Illuminate\Support\Collection::make(['model' => $model::find($id), 'type' => $type, 'fields' => $fields]);

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

        return [
            'tabKey' => $this->getTabKey() . '-edit-' . implode('', $ids),
            'tabLabel' => $type->translate('title'),
            'tabContent' => implode('<div class="hr hr-double hr-dotted hr18"></div>', $content)
        ];
    }

	public function getRouterParam($action = '', $type = null, $model = null)
	{
		switch ($action)
		{
			case 'create':
				return [ $this->getRouterStore(['id' => $type->getKey(), 'files' => true,  'saveBtn' => $this->getRequest()->input('saveBtn', true), 'chooseBtn' => $this->getRequest()->input('chooseBtn', false)]) ];
				break;

			case 'edit':
				return [ $this->getRouterUpdate(['id' => $type->getKey(), 'files' => true, 'saveBtn' => $this->getRequest()->input('saveBtn', true), 'chooseBtn' => $this->getRequest()->input('chooseBtn', true)]) ];
				break;

			case 'store':
				return [ $this->getRouterUpdate(['id' => $type->getKey(), 'files' => true, 'saveBtn' => $this->getRequest()->input('saveBtn', true), 'chooseBtn' => $this->getRequest()->input('chooseBtn', true)]) ];
				break;

			case 'update':
				return [ $this->getRouterUpdate(['id' => $type->getKey(), 'files' => true, 'saveBtn' => $this->getRequest()->input('saveBtn', true), 'chooseBtn' => $this->getRequest()->input('chooseBtn', true)]) ];
				break;

			default:
				return [];
				break;
		}
	} 
	
    public function getRouterActionParam($param = [])
    {
		try
		{
			return route($this->routerActionParam ?: $this->getVendorName() . ".module.{$this->getKey()}.action.param", $param);
		} 
		catch (\InvalidArgumentException $ex) 
		{
            $param['typeId'] = array_get($param, 'typeId', $this->getTypeList()->getKey());
            $param['treeId'] = array_get($param, 'treeId', 0);

			return route($this->getVendorName() . ".module.objects-lists.action.param", $param);
		}
    } 
    
    public function getRouterList($param = [])
    {
		try
		{
			return route($this->routerList ?: $this->getVendorName() . ".module.{$this->getKey()}.list", $param);
		} 
		catch (\InvalidArgumentException $ex) 
		{
			return route($this->getVendorName() . ".module.objects-lists.list", $param);
		}
    }	

    public function getRouterContent($param = [])
    {
		try
		{
			return route($this->routerContent ?:$this->getVendorName() . ".module.{$this->getKey()}", $param);
		} 
		catch (\InvalidArgumentException $ex) 
		{
            $param['typeId'] = array_get($param, 'typeId', $this->getTypeList()->getKey());

			return route($this->getVendorName() . ".module.objects-lists", $param);
		}
    }
    
    public function getRouterCreate($param = [])
    {
		try
		{
			return route($this->routerCreate ?: $this->getVendorName() . ".module.{$this->getKey()}.create", $param);
		} 
		catch (\InvalidArgumentException $ex) 
		{
			return route($this->getVendorName() . ".module.objects-lists." . ($this->isDisplayTypeWizard() ? "wizard." : "") . "create", $param);
		} 
    }
	
    public function getRouterEdit($param = [])
    {
		try
		{
			return route($this->routerEdit ?: $this->getVendorName() . ".module.{$this->getKey()}.edit", $param);
		} 
		catch (\InvalidArgumentException $ex) 
		{
			return route("telenok.module.objects-lists." . ($this->isDisplayTypeWizard() ? "wizard." : "") . "edit", $param);
		} 
    }
    
    public function getRouterDelete($param = [])
    {
		try
		{
			return route($this->routerDelete ?: "telenok.module.{$this->getKey()}.delete", $param);
		} 
		catch (\InvalidArgumentException $ex) 
		{
			return route("telenok.module.objects-lists." . ($this->isDisplayTypeWizard() ? "wizard." : "") . "delete", $param);
		} 
    }
    
    public function getRouterStore($param = [])
    {		
		try
		{
			return route($this->routerStore ?: "telenok.module.{$this->getKey()}.store", $param);
		} 
		catch (\InvalidArgumentException $ex) 
		{
			return route("telenok.module.objects-lists." . ($this->isDisplayTypeWizard() ? "wizard." : "") . "store", $param);
		} 
    }
    
    public function getRouterUpdate($param = [])
    {
		try
		{
			return route($this->routerUpdate ?: "telenok.module.{$this->getKey()}.update", $param);
		} 
		catch (\InvalidArgumentException $ex) 
		{
			return route("telenok.module.objects-lists." . ($this->isDisplayTypeWizard() ? "wizard." : "") . "update", $param);
		} 
    }
	
    public function getRouterListEdit($param = [])
    {
		try
		{
			return route($this->routerListEdit ?: "telenok.module.{$this->getKey()}.list.edit", $param);
		} 
		catch (\InvalidArgumentException $ex) 
		{
			return route("telenok.module.objects-lists.list.edit", $param);
		} 
    }
	
    public function getRouterListDelete($param = [])
    {
		try
		{
			return route($this->routerListDelete ?: "telenok.module.{$this->getKey()}.list.delete", $param);
		} 
		catch (\InvalidArgumentException $ex) 
		{
			return route("telenok.module.objects-lists.list.delete", $param);
		} 
    }
	
    public function getRouterLock($param = [])
    {
		try
		{
			return route($this->routerLock ?: "telenok.module.{$this->getKey()}.lock", $param);
		} 
		catch (\InvalidArgumentException $ex) 
		{
			return route($this->getVendorName() . ".module.objects-lists.lock", $param);
		} 
    }
	
    public function getRouterListLock($param = [])
    {
		try
		{
			return route($this->routerListLock ?: $this->getVendorName() . ".module.{$this->getKey()}.list.lock", $param);
		} 
		catch (\InvalidArgumentException $ex) 
		{
			return route($this->getVendorName() . ".module.objects-lists.list.lock", $param);
		} 
    }
	
    public function getRouterListUnlock($param = [])
    {
		try
		{
			return route($this->routerListUnlock ?: $this->getVendorName() . ".module.{$this->getKey()}.list.unlock", $param);
		} 
		catch (\InvalidArgumentException $ex) 
		{
			return route($this->getVendorName() . ".module.objects-lists.list.unlock", $param);
		} 
    }
	
    public function setRouterListTree($param)
    {
		$this->routerListTree = $param;
		
		return $this;
    }       

    public function getRouterListTree($param = [])
    {
		try
		{
			return route($this->routerListTree ?: $this->getVendorName() . ".module.{$this->getKey()}.list.tree", $param);
		} 
		catch (\InvalidArgumentException $ex) 
		{
            $param['typeId'] = array_get($param, 'typeId', $this->getTypeList()->getKey());
            $param['treeId'] = array_get($param, 'treeId', 0);

			return route($this->getVendorName() . ".module.objects-lists.list.tree", $param);
		} 
    }

    public function getName()
    {
        return $this->LL('name', [], $this->getTypeList()->translate('title'));
    }

    public function getHeader()
    {
        return $this->LL('header.title', [], $this->getTypeList()->translate('title'));
    }    

    public function getHeaderDescription()
    {
        return $this->LL('header.description', [], trans('core::default.header.description'));
    }
}