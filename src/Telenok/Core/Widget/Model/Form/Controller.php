<?php namespace Telenok\Core\Widget\Model\Form;

class Controller extends \Telenok\Core\Interfaces\Controller\Controller {

	protected $config;
	protected $id;
	protected $model;
	protected $modelType;
	protected $eventResource;
	protected $uniqueId;
	protected $fields;
	protected $fieldOnly = [];
	protected $fieldExcept = [];

	protected $fieldTemplateView = [];
	protected $fieldTemplateKey;

	protected $formClass = 'form-horizontal';

	protected $modelView = 'core::widget.form.model';
	protected $formView = 'core::widget.form.form';
	protected $fieldView = 'core::widget.form.field';
	
	protected $mainFieldViewKey = 'frontend';

	protected $routerStore;
	protected $routerUpdate;
	protected $routerDelete;
	protected $redirectAfterProcessing;

	/*

	echo (new \App\Telenok\Core\Html\Form\Controller())->form([
		'id' => 22, //for editinng
		'model' => (\App\...\Object\Type::find(10) for creating || 'type_299' for creating || '\App\Model\Package' for creating),
		'fieldOnly' => [
			'id',
			'title', 
			'created_by_user', 
			'updated_by_user', 
			'locked_by_user',
			'active',
			'active_at',
			'permission',
			'package_license',
			'key',
			'description',
			'version',
			'image',
		],
		'fieldTemplateKey' => 'frontend',
		'modelView' => 'frontend',
		'fieldTemplateKey' => 'frontend',
		'routerStore' => 'some.router.store',
		'routerUpdate' => 'some.router.update',
		'routerDelete' => 'some.router.delete',
		'redirectAfterProcessing' => 'some.router.afterProcessing',
	]);

	*/
	public function form($config = [])
	{
		$this->setConfig($config);

		$this->uniqueId = array_get($this->getConfig(), 'uniqueId', str_random());

		$this->id = intval(array_get($this->getConfig(), 'id'));
		$this->formClass = array_get($this->getConfig(), 'formClass', $this->formClass);
		$this->modelView = array_get($this->getConfig(), 'modelView', $this->modelView);
		$this->formView = array_get($this->getConfig(), 'formView', $this->formView);
		$this->fieldView = array_get($this->getConfig(), 'fieldView', $this->fieldView);
		$this->fieldOnly = array_get($this->getConfig(), 'fieldOnly', []);
		$this->fieldExcept = array_get($this->getConfig(), 'fieldExcept', []);
		$this->fieldExcept = array_get($this->getConfig(), 'fieldExcept', []);
		$this->fieldTemplateView = array_get($this->getConfig(), 'fieldTemplateView', []);
		$this->fieldTemplateKey = array_get($this->getConfig(), 'fieldTemplateKey');
		$this->routerStore = array_get($this->getConfig(), 'routerStore');
		$this->routerUpdate = array_get($this->getConfig(), 'routerUpdate');
		$this->routerDelete = array_get($this->getConfig(), 'routerDelete');
		$this->redirectAfterProcessing = array_get($this->getConfig(), 'redirectAfterProcessing');

		$this->setModel();
		$this->setModelType();
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

        $eventResource = \Illuminate\Support\Collection::make(['model' => $this->getModel(), 'type' => $this->getModelType(), 'fields' => $fields]);

        //\Event::fire('workflow.form.create', (new \Telenok\Core\Workflow\Event())->setResource($eventResource)->setInput($input));

		$this->setEventResource($eventResource);

		try
		{
			return view($this->getModelView(), [
				'routerParam' => $this->getRouterStore(['typeId' => $this->getEventResource()->get('type')->getKey()]),
				'controller' => $this,
				'canCreate' => true,
			]);
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

        $eventResource = \Illuminate\Support\Collection::make(['model' => $this->getModel(), 'type' => $this->getModelType(), 'fields' => $fields]);

        //\Event::fire('workflow.form.edit', (new \Telenok\Core\Workflow\Event())->setResource($eventResource)->setInput($input));

		$this->setEventResource($eventResource);

		try
		{
			return view($this->getModelView(), [
				'routerParam' => $this->getRouterUpdate(['id' => $this->getEventResource()->get('model')->getKey()]),
				'controller' => $this,
				'canUpdate' => app('auth')->can('update', $eventResource->get('model')),
				'canDelete' => app('auth')->can('delete', $eventResource->get('model')),
			]);
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
        try 
        {
            $input = \Illuminate\Support\Collection::make($this->getRequest()->input());  

			$type = $this->getTypeById($typeId);

			$model = $this->getModelByTypeId($type->getKey());

			$model_ = $model->storeOrUpdate($input, true);
        } 
        catch (\Exception $e) 
        {   
			throw $e;
        }

		return ['redirect' => route('account.package.view', ['id' => $model_->getKey()], false)];
	}

	public function update()
	{
		dd('updateProcess');
	}

    public function setRouterStore($param)
    {
		$this->routerStore = $param;
		
		return $this;
    }       

    public function getRouterStore($param = [])
    {
        return route($this->routerStore ?: "cmf.html.form.store", $param);
    }

    public function setRouterUpdate($param)
    {
		$this->routerUpdate = $param;
		
		return $this;
    }       

    public function getRouterUpdate($param = [])
    {
        return route($this->routerUpdate ?: "cmf.html.form.update", $param);
    }

    public function setRouterDelete($param)
    {
		$this->routerDelete = $param;
		
		return $this;
    }       

    public function getRouterDelete($param = [])
    {
        return route($this->routerDelete ?: "cmf.html.form.delete", $param);
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
		$model_ = null;

		if ($model instanceof \Telenok\Core\Interfaces\Eloquent\Object\Model)
		{
			$model_ = $model;
		}
		else if (strpos($model, 'type_') !== FALSE && ($id = intval(str_replace('type_', '', $model))))
		{
			$model_ = $this->getModelByTypeId($id);
		}
		else if (strpos($model, 'type_') !== FALSE && ($id = intval(str_replace('type_', '', $model))))
		{
			$model_ = $this->getModelByTypeId($id);
		}
		else if (is_string($model) && class_exists($model))
		{
			$model_ = app($model);
		}
		
		if ($this->id)
		{
			if ($model_)
			{
				$this->model = $model_->findOrFail($this->id);
			}
			else
			{
				$this->model = $this->getModelById($id);
			}
		}
		else if ($model_)
		{
			$this->model = $model_;
		}
		
		if (!($this->model instanceof \Telenok\Core\Interfaces\Eloquent\Object\Model))
		{
			throw new \Exception('Please, set model class or ID');
		}

		return $this;
	}

    public function getModelById($id)
    {
        return \App\Telenok\Core\Model\Object\Sequence::getModel($id);
    }

    public function getTypeById($id)
    {
        return \App\Telenok\Core\Model\Object\Type::where('id', $id)->orWhere('code', $id)->active()->firstOrFail();
    } 

    public function getTypeByModelId($id)
    {
        return \App\Telenok\Core\Model\Object\Sequence::findOrFail($id)->sequencesObjectType;
    }

    public function getModelByTypeId($id)
    {
        return app($this->getTypeById($id)->class_model);
    }

	public function typeForm($type)
    {
        return app($type->classController())
					->setTabKey($this->key)
					->setAdditionalViewParam($this->getAdditionalViewParam());
    } 
	
	public function setModelType($type = null)
	{
		$this->modelType = $type?:$this->getModel()->type();

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
				if (in_array($item->code, $this->fieldExcept))
				{
					return true;
				}
			})->filter(function($item)
			{
				if (empty($this->fieldOnly) || in_array($item->code, $this->fieldOnly))
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
		$this->config = $config;
		
		return $this;
	}
	
	public function getConfig($key = '')
	{
		if (empty($key))
		{
			return $this->config;
		}
		else
		{
			return array_get($this->config, $key);
		}
	}
	
	public function getFieldTemplateView($field)
	{
		if ($t = array_get($this->fieldTemplateView, $field->code))
		{
			return $t;
		}
	}
	
	public function getFieldTemplateKey($field)
	{
		return $this->fieldTemplateKey;
	}
	
	public function setRedirectAfterProcessing($param)
	{
		$this->redirectAfterProcessing = $param;
		
		return $this;
	}
	
	public function getRedirectAfterProcessing()
	{
		return $this->redirectAfterProcessing;
	}
}