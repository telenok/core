<?php namespace Telenok\Core\Widget\Model\Form;

class Controller extends \Telenok\Core\Abstraction\Controller\Controller {

	protected $config;
	protected $id;
	protected $model;
	protected $modelType;
	protected $eventResource;
	protected $uniqueId;
	protected $fields;
	protected $fieldOnly = [];
	protected $fieldExcept = [];

	protected $modelFieldView = [];
	protected $modelFieldViewKey;
	protected $modelFieldViewVariable = [];

	protected $formClass = 'form-horizontal';

	protected $modelView = 'core::widget.form.model';
	protected $formView = 'core::widget.form.form';
	protected $fieldView = 'core::widget.form.field';
	
	protected $mainFieldViewKey = 'frontend';

	protected $routerStore;
	protected $routerUpdate;
	protected $routerDelete;
	protected $redirectAfterCreate;
	protected $redirectAfterUpdate;
	protected $redirectAfterDelete;

	/*

	echo (new \App\Vendor\Telenok\Core\Html\Form\Controller())->form([	
		'uniqueId' => 'adadad94820sdvbxjkh',
		'model' => (\App\...\Object\Type::find(10) || 299 ),
		'modelType' => for security reason (\App\...\Object\Type::find(10) || type code like 'user' || 299 || '\App\Model\Package'),
		'showTabs' => true,
		'fieldOnly' => [
			'id',
			'title', 
			'created_by_user', 
		],
		'fieldExcept' => [
			'some_other_field',
		],
		'modelFieldViewKey' => 'frontend',
		'modelFieldView' => [
			'code' => 'core::some.special.view',
			'key' => 'core::some.other.view',
		],
		'modelFieldViewVariable' => [
			'code' => function($fieldController, $model, $field, $uniqueId)
						{
							return [ 'urlListTitle' => route('some.other.router') ];
						}
		],
		'modelView' => 'core::widget.form.model',
		'formView' => 'core::widget.form.form',
		'fieldView' => 'core::widget.form.field',
		'routerStore' => 'some.router.store', // or FALSE
		'routerUpdate' => 'some.router.update', // or FALSE
		'routerDelete' => 'some.router.delete', // or FALSE
		'redirectAfterCreate' => 'some.router.afterProcessingCreate' or 'http://some.url/to/page,
		'redirectAfterUpdate' => 'some.router.afterProcessingUpdate' or 'http://some.url/to/page,
		'redirectAfterDelete' => 'some.router.afterProcessingDelete' or 'http://some.url/to/page,
	]);

	*/
	
	public function form($config = [])
	{
		$this->setConfig($config);

		$this->setModelType();
		$this->setModel();
		$this->setFields();

		if ($this->getModel()->exists)
		{
			return $this->edit();
		}
		else
		{
			return $this->create();
		}
	}

	public function create()
	{
        if (!app('auth')->can('create', "object_type.{$this->getModelType()->code}"))
        {
            throw new \LogicException($this->LL('error.access.create'));
        }

		$fields = $this->getFields();

        $eventResource = collect(['model' => $this->getModel(), 'type' => $this->getModelType(), 'fields' => $fields]);

        //\Event::fire('workflow.form.create', (new \Telenok\Core\Workflow\Event())->setResource($eventResource)->setInput($input));

		$this->setEventResource($eventResource);

		try
		{
			return view($this->getModelView(), [
				'urlParam' => $this->getConfig('routerStore') === FALSE ? '' : $this->getUrlStore(['typeId' => $this->getEventResource()->get('type')->getKey()]),
				'controller' => $this,
				'canCreate' => $this->getConfig('routerStore') !== FALSE,
			])->render();
		}
		catch (\Exception $ex) 
		{
			return [
				'exception' => $ex->getMessage(),
			];
		}
	}
	
	public function edit()
	{
        if (!app('auth')->can('read', "object_type.{$this->getModelType()->code}"))
        {
            throw new \LogicException($this->LL('error.access.read'));
        } 

		$fields = $this->getFields();

        $eventResource = collect(['model' => $this->getModel(), 'type' => $this->getModelType(), 'fields' => $fields]);

        //\Event::fire('workflow.form.edit', (new \Telenok\Core\Workflow\Event())->setResource($eventResource)->setInput($input));

		$this->setEventResource($eventResource);

		try
		{
			return view($this->getModelView(), [
				'urlParam' => $this->getConfig('routerUpdate') ===  FALSE ? '' : $this->getUrlUpdate(['id' => $this->getEventResource()->get('model')->getKey()]),
				'controller' => $this,
				'success' => $this->getRequest()->input('success'),
				'canUpdate' => $this->getConfig('routerUpdate') ===  FALSE ? FALSE : app('auth')->can('update', $eventResource->get('model')),
				'canDelete' => $this->getConfig('routerDelete') === FALSE ? FALSE : app('auth')->can('delete', $eventResource->get('model')),
			])->render();
		}
		catch (\Exception $ex) 
		{
			return [
				'exception' => $ex->getMessage(),
			];
		}
	}

	public function store($typeId = 0)
	{
        $input = $this->getRequestCollected();  

        $type = $this->getTypeById($typeId);

        $model = $this->getModelByTypeId($type->getKey());

        $model_ = $model->storeOrUpdate($input, true);
		
		$v = $input->get('redirect_after_store');
		
		if (app('router')->has($v))
		{
			$redirect = route($v, ['id' => $model_->getKey()]);
		}
		else
		{
			$redirect = $v;
		}

		return ['redirect' => $redirect];
	}

	public function update($id)
	{
        $input = $this->getRequestCollected();  

        $model = $this->getModelById($id);

        $model_ = $model->storeOrUpdate($input, true);
		
		$v = $input->get('redirect_after_update');
		
		return ['redirect' => app('router')->has($v) ? route($v, ['id' => $model_->getKey()]) : $v];
	}

	public function delete($id = 0)
	{
        try
        {
			$this->getModelById($id)->delete();
        } 
        catch (\Exception $e) 
        {   
			return ['success' => 0];
        }

		$v = $this->getRequest()->input('redirect_after_delete');

		return ['success' => 1, 'redirect' => app('router')->has($v) ? route($v) : $v];
	}

    public function setRouterStore($param)
    {
		$this->routerStore = $param;
		
		return $this;
    }       

    public function getRouterStore()
    {
        return $this->routerStore ?: $this->getVendorName() . ".widget.form.store";
    }

    public function getUrlStore($param = [])
    {
		if (app('router')->has($this->getRouterStore()))
		{
			return route($this->getRouterStore(), $param);
		}
		else
		{
			return $this->getRouterStore();
		}
    }

    public function setRouterUpdate($param)
    {
		$this->routerUpdate = $param;
		
		return $this;
    }       

    public function getRouterUpdate()
    {
        return $this->routerUpdate ?: $this->getVendorName() . ".widget.form.update";
    }

    public function getUrlUpdate($param = [])
    {
		if (app('router')->has($this->getRouterUpdate()))
		{
			return route($this->getRouterUpdate(), $param);
		}
		else
		{
			return $this->getRouterUpdate();
		}
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

    public function getUrlDelete($param = [])
    {
		if (app('router')->has($this->getRouterDelete()))
		{
			return route($this->getRouterDelete(), $param);
		}
		else
		{
			return $this->getRouterDelete();
		}
    }

	public function getUniqueId()
	{
		return $this->uniqueId;
	}

	public function setFormClass($param)
	{
		$this->formClass = $param;
		
		return $this;
	}

	public function getFormClass()
	{
		return $this->formClass;
	}

	public function getEventResource()
	{
		return $this->eventResource;
	}

	public function setEventResource($eventResource)
	{
		$this->eventResource = $eventResource;
		
		return $this;
	}

	public function getModel()
	{
		return $this->model;
	}

	public function setModel($model = null)
	{
		$model = $model?:$this->getConfig('model');

		$realModel = $this->getModelByTypeId($this->getModelType()->getKey());
		
		if ($model instanceof $realModel)
		{
			$this->model = $model;
		}
		else if ($v = intval($model))
		{
			$this->model = $realModel->findOrFail($v);
		}
		else
		{
			$this->model = $realModel;
		}

		return $this;
	}

    public function getModelById($id)
    {
        return \App\Vendor\Telenok\Core\Model\Object\Sequence::getModel($id);
    }

    public function getTypeById($id)
    {
        return \App\Vendor\Telenok\Core\Model\Object\Type::where('id', $id)->orWhere('code', $id)->active()->firstOrFail();
    } 

    public function getTypeByModelId($id)
    {
        return \App\Vendor\Telenok\Core\Model\Object\Sequence::findOrFail($id)->sequencesObjectType;
    }

    public function getModelByTypeId($id)
    {
        return app($this->getTypeById($id)->class_model);
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

	public function getModelType()
	{
		return $this->modelType;
	}

	public function getModelView()
	{
		return $this->modelView;
	}

	public function getFormView()
	{
		return $this->formView;
	}

	public function getFieldView()
	{
		return $this->fieldView;
	}

	public function setFields()
	{
		$this->fields = $this->getModel()->getFieldForm()->reject(function($item)
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
	
	public function setConfig($config)
	{
		$this->config = collect($config)->all();

		$this->uniqueId = $this->getConfig('uniqueId', str_random());
		$this->id = intval($this->getConfig('id'));
		$this->formClass = $this->getConfig('formClass', $this->formClass);
		$this->modelView = $this->getConfig('modelView', $this->modelView);
		$this->formView = $this->getConfig('formView', $this->formView);
		$this->fieldView = $this->getConfig('fieldView', $this->fieldView);
		$this->fieldOnly = $this->getConfig('fieldOnly', []);
		$this->fieldExcept = $this->getConfig('fieldExcept', []);
		$this->modelFieldView = $this->getConfig('modelFieldView', []);
		$this->modelFieldViewKey = $this->getConfig('modelFieldViewKey');
		$this->modelFieldViewVariable = $this->getConfig('modelFieldViewVariable', $this->modelFieldViewVariable);
		$this->routerStore = $this->getConfig('routerStore');
		$this->routerUpdate = $this->getConfig('routerUpdate');
		$this->routerDelete = $this->getConfig('routerDelete');
		$this->redirectAfterStore = $this->getConfig('redirectAfterStore');
		$this->redirectAfterUpdate = $this->getConfig('redirectAfterUpdate');
		$this->redirectAfterDelete = $this->getConfig('redirectAfterDelete');

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

	public function getModelFieldView($field)
	{
		if ($t = array_get($this->modelFieldView, $field->code))
		{
			return $t;
		}
	}
		
	public function getModelFieldViewKey($field)
	{
		return $this->modelFieldViewKey;
	}
	
	public function setRedirectAfterStore($param)
	{
		$this->redirectAfterStore = $param;
		
		return $this;
	}
	
	public function getRedirectAfterStore()
	{
		return $this->redirectAfterStore;
	}
	
	public function setRedirectAfterUpdate($param)
	{
		$this->redirectAfterUpdate = $param;
		
		return $this;
	}
	
	public function getRedirectAfterUpdate()
	{
		return $this->redirectAfterUpdate;
	}
	
	public function setRedirectAfterDelete($param)
	{
		$this->redirectAfterDelete = $param;
		
		return $this;
	}
	
	public function getRedirectAfterDelete()
	{
		return $this->redirectAfterDelete;
	}
	
	public function getModelFieldViewVariable($fieldController = null, $model = null, $field = null, $uniqueId = null)
	{
		$f = array_get($this->modelFieldViewVariable, $field->code);
		
		if ($f instanceof \Closure)
		{
			return $f($fieldController, $model, $field, $uniqueId);
		}
	}
}