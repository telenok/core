<?php namespace Telenok\Core\Widget\Model\Grid;

class Controller extends \Telenok\Core\Abstraction\Controller\Controller {

	protected $config;
	protected $id;
	protected $model;
	protected $modelType;
	protected $uniqueId;
	protected $buttonTop;
	protected $buttonTopOrder;
	protected $fieldOnly = [];
	protected $fieldExcept = [];
	protected $routerList = 'telenok.widget.grid.list';
	protected $routerCreate = 'telenok.widget.form.create';
	protected $routerEdit = 'telenok.widget.form.edit';
	protected $routerDelete = 'telenok.widget.form.delete';
	protected $pageLength = 10;
	protected $enableColumnSelect = true;
	protected $enableColumnAction = true;

	protected $viewGrid = 'core::widget.grid.table';
	protected $viewRow = 'core::widget.grid.row';
	protected $viewButtonTop = 'core::widget.grid.buttonTop';
	
	protected $queryFilter;

	/*

	echo (new \App\Vendor\Telenok\Core\Html\Grid\Controller())->form([
		'modelType' => (model object \App\...\Object\Type::find(10) || 299 || '\App\Model\Package' or type code eg 'user'),
		'buttonTop' => [
			'create' => function($controller)
			{
				return '
						"sExtends": "text",
						"sButtonText": "<i class=\'fa fa-plus smaller-90\'></i> ' . e($controller->LL('list.btn.create')) . '",
						"sButtonClass": "btn-success btn-sm",
						"fnClick": function(nButton, oConfig, oFlash) { window.location = "' . $controller->getUrlCreate() . '"; }
					';
			},
			'refresh' => function($controller)
			{
				return '
						"sExtends": "text",
						"sButtonText": "<i class=\'fa fa-refresh smaller-90\'></i> ' . e($controller->LL('list.btn.refresh')) . '",
						"sButtonClass": "btn-success btn-sm",
                        action : function (e, dt, button, config)
						{
							dt.ajax.reload();
						}					
					';
			}
		],
		'buttonTopOrder' => ['create', 'refresh'],
		'routerList' => 'telenok.widget.grid.list',
		'routerCreate' => 'telenok.widget.form.create',
		'routerEdit' => 'telenok.widget.form.edit',
		'routerDelete' => 'telenok.widget.form.delete',
		'enableColumnSelect' => true,
		'enableColumnAction' => false,
	]);

	*/
	public function grid($config = [])
	{
		$this->setConfig($config);

		$this->setModelType();

        if (!app('auth')->can('read', "object_type.{$this->getModelType()->code}"))
        {
            throw new \LogicException($this->LL('error.access.read'));
        } 
		
		$this->setModel();
		$this->setFields();

		return view($this->getViewGrid(), [
			'controller' => $this,
		])->render();
	}

	public function setViewGrid($param)
	{
		$this->viewGrid = $param;
		
		return $this;
	}
	
	public function getViewGrid()
	{
		return $this->viewGrid;
	}

	public function setViewRow($param)
	{
		$this->viewRow = $param;
		
		return $this;
	}
	
	public function getViewRow()
	{
		return $this->viewRow;
	}

	public function setViewButtonTop($param)
	{
		$this->viewButtonTop = $param;
		
		return $this;
	}
	
	public function getViewButtonTop()
	{
		return $this->viewButtonTop;
	}

	public function enableColumnSelect()
	{
		return $this->enableColumnSelect;
	}

	public function enableColumnAction()
	{
		return $this->enableColumnAction;
	}

	public function getFieldTitleList($id = null, $closure = null)
	{
		
		
		return app('\Telenok\Core\Abstraction\Field\Relation\Controller')->getTitleList($id, $closure);
	}

	public function getList($typeId = 0, $closure = null, $btnActionView = 'core::widget.grid.buttonAction')
	{
		$input = $this->getRequest();  
		
		$this->setModelType($typeId);
		$this->setModel();
		$this->setFields();

		$this->setRouterEdit($input->input('routerEdit'));
		
        if (!app('auth')->can('read', "object_type.{$this->getModelType()->code}"))
        {
            throw new \LogicException($this->LL('error.access.read'));
        } 
 
        $total = $input->input('pageLength', $this->getpageLength());
        $draw = $input->input('draw');
        $pageStart = $input->input('pageStart', 0); 
		
        $query = $this->getModel()->withTrashed()->select($this->getModel()->getTable() . '.*')->withPermission();

        if ($str = trim($input->input('search.value')))
        {
			$query->where(function($query) use ($str, $query)
			{
				$f = $this->getModel()->getObjectField()->get('title');

				app('telenok.config.repository')
						->getObjectFieldController($f->key)
						->getFilterQuery($f, $this->getModel(), $query, $f->code, $str);
			});       
		} 

		if ($closure instanceof \Closure)
		{
			$closure($query);
		}
		
		if ($input->input('multifield_search', false))
		{
			$controller = app('telenok.config.repository')->getObjectFieldController();

			if (!$input->input('filter', []) instanceof \Illuminate\Support\Collection)
			{
				$input_ = collect($input);
			}

			$this->getModel()->getFieldForm()->each(function($field) use ($input_, $query, $controller)
			{
				if ($field->allow_search)
				{
					if ($input_->has($field->code))
					{
						$controller->get($field->key)->getFilterQuery($field, $this->getModel(), $query, $field->code, $input_->get($field->code));
					}
					else
					{
						$controller->get($field->key)->getFilterQuery($field, $this->getModel(), $query, $field->code, null);
					}
				}
			});
		}

        $orderByField = $input->get('mDataProp_' . $input->get('iSortCol_0'));
        
        if ($input->get('iSortCol_0', 0))
        {
            $query->orderBy($this->getModel()->getTable() . '.' . $orderByField, $input->get('sSortDir_0'));
        }

        $items = $query->groupBy($this->getModel()->getTable() . '.id')
				->orderBy($this->getModel()->getTable() . '.updated_at', 'desc')
				->skip($this->getRequest()->input('pageStart', 0))
				->take($this->getpageLength() + 1)->get();
		
		$config = app('telenok.config.repository')->getObjectFieldController();

		$content = [];
		
		foreach ($items->slice(0, $this->getpageLength(), true) as $item)
		{
            $put = ['tableCheckAll' => '<input type="checkbox" class="ace ace-checkbox-2" name="tableCheckAll[]" value="'.$item->getKey().'"><span class="lbl"></span>'];

			foreach ($this->getModel()->getFieldList() as $field)
			{ 
				$put[$field->code] = $config->get($field->key)->getListFieldContent($field, $item, $this->getModelType());
			}

			$put['tableManageItem'] = $btnActionView ? view($btnActionView, ['controller' => $this, 'item' => $item])->render() : '';

			$content[] = $put;
		}

        return [
            'draw' => $draw,
            'iTotalRecords' => ($pageStart + $items->count()),
            'iTotalDisplayRecords' => ($pageStart + $items->count()),
            'data' => $content
        ];
	}
	
	public function getLinkedFieldList($typeId, $term = '')
	{
		$return = [];

		$this->setModelType($typeId);

		$this->getModelType()->withPermission()
			->join('object_translation', function($join)
			{
				$join->on($this->getModelType()->getTable() . '.id', '=', 'object_translation.translation_object_model_id');
			})
			->where('created_by_user', app('auth')->user()->getKey())
			->where(function($query) use ($term)
			{
				collect(explode(' ', $term))
				->reject(function($i)
				{
					return !trim($i);
				})
				->each(function($i) use ($query)
				{
					$query->orWhere('object_translation.translation_object_string', 'like', "%{$i}%");
				});

				$query->orWhere($this->getModelType()->getTable() . '.id', (int) $term);
			})
			->take(20)->groupBy($this->getModelType()->getTable() . '.id')->get()->each(function($item) use (&$return)
		{
			$return[] = ['value' => $item->id, 'text' => "[{$item->id}] " . $item->translate('title')];
		});

		return $return;
	}
	
	public function queryFilter($closure)
	{
		$this->queryFilter = $closure;
		
		return $this;
	}
	
	public function getUrlList()
	{
		if (app('router')->has($this->getRouterList()))
		{
			return route($this->getRouterList(), ['typeId' => $this->getModelType()->getKey(), 'routerEdit' => $this->getRouterEdit()]);
		}
		else
		{
			return $this->getRouterList();
		}
	}
	
	public function getUrlCreate()
	{
		if (app('router')->has($this->getRouterCreate()))
		{
			return route($this->getRouterCreate(), ['typeId' => $this->getModelType()->getKey()]);
		}
		else
		{
			return $this->getRouterCreate();
		}
	}
	
	public function getUrlEdit($item)
	{
		if (app('router')->has($this->getRouterEdit()))
		{
			return route($this->getRouterEdit(), ['id' => $item->getKey()]);
		}
		else
		{
			return $this->getRouterEdit();
		}
	}

	public function setRouterList($param)
	{
		$this->routerList = $param;
		
		return $this;
	}

	public function getRouterList()
	{
		return $this->routerList;
	}

	public function setRouterCreate($param)
	{
		$this->routerCreate = $param;
		
		return $this;
	}

	public function getRouterCreate()
	{
		return $this->routerCreate;
	}

	public function setRouterEdit($param)
	{
		$this->routerEdit = $param;
		
		return $this;
	}

	public function getRouterEdit()
	{
		return $this->routerEdit;
	}

	public function setRouterDelete($param)
	{
		$this->routerDelete = $param;
		
		return $this;
	}

	public function getRouterDelete()
	{
		return $this->routerDelete;
	}
	
	public function setModelType($model = null)
	{
		$model = $model?:$this->getConfig('modelType');

		if ($model instanceof \Telenok\Core\Model\Object\Type)
		{
			$this->modelType = $model;
		}
		else if (is_string($model) && class_exists($model))
		{
			$this->modelType = app($model);
		}
		else
		{
			$this->modelType = $this->getTypeById($model);
		}
		
		if (!($this->modelType instanceof \Telenok\Core\Model\Object\Type))
		{
			throw new \Exception('Please, set value for key "modelType"');
		}

		return $this;
	}

	public function delete($id = 0)
	{
        try
        {
			$this->getModelById($id)->delete();
			
			return ['success' => 1];
        } 
        catch (\Exception $e) 
        {   
			return ['success' => 0];
        }
	}

	public function getModelType()
	{
		return $this->modelType;
	}

	public function setModel()
	{
		$this->model = $this->getModelByType();
		
		return $this;
	}

	public function getModel()
	{
		return $this->model;
	}

	public function setFields()
	{
		$this->fields = $this->getModel()->getFieldList()->reject(function($item)
			{
				if (in_array($item->code, $this->fieldExcept, true))
				{
					return true;
				}
			})->filter(function($item)
			{
				if (empty($this->fieldOnly) || in_array($item->code, $this->fieldOnly, true))
				{
					return true;
				}
			});
			
		return $this;
	}

	public function getFields()
	{
		return $this->fields;
	}
	
	public function setButtonTop($param)
	{
		$this->buttonTop = $param;

		return $this;
	}
	
	public function getButtonTop()
	{
		return array_merge($this->getDefaultButtonTop(), $this->buttonTop);
	}
	
	public function getDefaultButtonTop()
	{
		return
		[
			'create' => function($controller)
			{
				return '
						"sExtends": "text",
						"sButtonText": "<i class=\'fa fa-plus smaller-90\'></i> ' . e($controller->LL('list.btn.create')) . '",
						"sButtonClass": "btn-success btn-sm",
						"fnClick": function(nButton, oConfig, oFlash) { window.location = "' . $controller->getUrlCreate() . '"; }
					';
			},
			'refresh' => function($controller)
			{
				return '
						"sExtends": "text",
						"sButtonText": "<i class=\'fa fa-refresh smaller-90\'></i> ' . e($controller->LL('list.btn.refresh')) . '",
						"sButtonClass": "btn-success btn-sm",
                        action : function (e, dt, button, config)
						{
							dt.ajax.reload();
						}					
					';
			}
		];
	}

	public function setButtonTopOrder($param)
	{
		$this->buttonTopOrder = $param;

		return $this;
	}

	public function getButtonTopOrder()
	{
		return $this->buttonTopOrder;
	}

	public function setConfig($config)
	{
		$this->config = collect($config)->all();
		
		$this->uniqueId = $this->getConfig('uniqueId', str_random());
		$this->buttonTop = $this->getConfig('buttonTop', []);
		$this->buttonTopOrder = $this->getConfig('buttonTopOrder', ['create', 'refresh']);
		$this->fieldOnly = $this->getConfig('fieldOnly', $this->fieldOnly);
		$this->fieldExcept = $this->getConfig('fieldExcept', $this->fieldExcept);
		$this->routerList = $this->getConfig('routerList', $this->routerList);
		$this->routerCreate = $this->getConfig('routerCreate', $this->routerCreate);
		$this->routerEdit = $this->getConfig('routerEdit', $this->routerEdit);
		$this->routerDelete = $this->getConfig('routerDelete', $this->routerDelete);
		$this->pageLength = $this->getConfig('pageLength', $this->pageLength);
		$this->enableColumnSelect = $this->getConfig('enableColumnSelect', $this->enableColumnSelect);
		$this->enableColumnAction = $this->getConfig('enableColumnAction', $this->enableColumnAction);

		$this->viewButtonTop = $this->getConfig('viewButtonTop', $this->viewButtonTop);
		$this->viewGrid = $this->getConfig('viewGrid', $this->viewGrid);
		$this->viewRow = $this->getConfig('viewRow', $this->viewRow);
		
		return $this;
	}
	
	public function getConfig($key = null, $default = null)
	{
		if (empty($key))
		{
			return $this->config;
		}
		else
		{
			return array_get($this->config, $key, $default);
		}
	}

	public function getUniqueId()
	{
		return $this->uniqueId;
	}

	public function setUniqueId($param)
	{
		$this->uniqueId = $param;

		return $this;
	}

    public function getTypeById($id)
    {
        return \App\Vendor\Telenok\Core\Model\Object\Type::where('id', $id)->orWhere('code', $id)->active()->firstOrFail();
    } 

    public function getModelByType()
    {
        return app($this->getModelType()->class_model);
    }
	
	public function getpageLength()
	{
		return $this->pageLength;
	}
}