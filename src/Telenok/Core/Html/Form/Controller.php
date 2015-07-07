<?php namespace Telenok\Core\Html\Form;

class Controller extends \Telenok\Core\Interfaces\Controller\Controller {

	protected $config;
	protected $model;
	protected $modelType;
	protected $uniqueId;
	protected $fields;
	protected $fieldOnly = [];
	protected $fieldExcept = [];

	protected $fieldTemplateView = [];
	protected $fieldTemplateKey;

	protected $formClass = 'form-horizontal';

	protected $modelView = 'core::html.form.model';
	protected $formView = 'core::html.form.form';
	protected $fieldView = 'core::html.form.field';
	protected $mainFieldViewKey = 'frontend';
	
	public function form($config = [])
	{
		$this->setConfig($config);
		
		$this->uniqueId = array_get($this->getConfig(), 'uniqueId', str_random());

		$this->modelView = array_get($this->getConfig(), 'modelView', $this->modelView);
		$this->formView = array_get($this->getConfig(), 'formView', $this->formView);
		$this->formClass = array_get($this->getConfig(), 'formClass', $this->formClass);
		$this->fieldView = array_get($this->getConfig(), 'fieldView', $this->fieldView);
		$this->fieldOnly = array_get($this->getConfig(), 'fieldOnly', []);
		$this->fieldExcept = array_get($this->getConfig(), 'fieldExcept', []);
		$this->fieldExcept = array_get($this->getConfig(), 'fieldExcept', []);
		$this->fieldTemplateView = array_get($this->getConfig(), 'fieldTemplateView', []);
		$this->fieldTemplateKey = array_get($this->getConfig(), 'fieldTemplateKey');
 
		
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
		return view($this->getModelView(), [
			'routerParam' => route('cmf.html.form.create'),
			'controller' => $this,
			'canCreate' => true,
			'canUpdate' => true,
		]);
	}

	public function edit()
	{
		return view($this->getModelView(), [
			'routerParam' => route('cmf.html.form.update'),
			'controller' => $this,
		]);
	}

	public function createProcess()
	{
		dd('createProcess');
	}

	public function updateProcess()
	{
		dd('updateProcess');
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

	public function setModel()
	{
		$model = $this->getConfig('model');
		
		if (is_string($model))
		{
			$this->model = app($model);
		}
		else if (is_object($model))
		{
			$this->model = $model;
		}
		else
		{
			throw new \Exception('Please, set model value');
		}
		
		return $this;
	}
	
	public function getModel()
	{
		return $this->model;
	}
	
	public function setModelType()
	{
		$this->modelType = $this->getModel()->type();
		
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
		$this->fields = $this->getModelType()->field()->get()->reject(function($item)
			{
				if (in_array($item->code, $this->fieldExcept))
				{
					return true;
				}
			})->filter(function($item)
			{
				if (in_array($item->code, $this->fieldOnly) || empty($this->fieldOnly))
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
}