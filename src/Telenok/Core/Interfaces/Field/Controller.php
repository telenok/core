<?php namespace Telenok\Core\Interfaces\Field;

abstract class Controller extends \Telenok\Core\Interfaces\Controller\Controller implements \Telenok\Core\Interfaces\Field\IField {

	protected $ruleList = [];
	protected $specialField = [];
	protected $specialDateField = [];
	protected $allowMultilanguage = true;
	protected $languageDirectory = 'field';
	protected $displayLength = 5;
	protected $viewModel;
	protected $viewField;
	protected $viewFilter;
	protected $routeListTable;
	protected $routeListTitle;
	protected $routeWizardCreate;
	protected $routeWizardEdit;
	protected $routeWizardChoose;

	public function getViewModel()
	{
		return $this->viewModel ? : "{$this->getPackage()}::field.{$this->getKey()}.model";
	}

	public function setViewModel($field = null, $templateView = null, $templateKey = null)
	{
		$viewObj = app('view');  

		$fieldView = '';
		$defaultTemplate = $this->viewModel?:"{$this->getPackage()}::field.{$this->getKey()}.model";

		if ($field instanceof \Telenok\Core\Model\Object\Field && $viewObj->exists($field->field_view))
		{
			$fieldView = $field->field_view;
		}
		
		if ($templateView && $viewObj->exists($templateView))
		{
			$this->viewModel = $templateView;
		}
		else if ($templateKey && $viewObj->exists($t = ($fieldView ?: $defaultTemplate) . '-' . $templateKey))
		{
			$this->viewModel = $t;
		}
		else if ($fieldView)
		{
			$this->viewModel = $fieldView;
		}
		else if ($viewObj->exists($this->viewModel))
		{
		}
		else if ($viewObj->exists($defaultTemplate))
		{
			$this->viewModel = $defaultTemplate;
		}
		else
		{
			throw new \Exception('Please set view for field "' . $this->getKey() . '"');
		}
		
		return $this;
	}

	public function getViewField()
	{
		return $this->viewField ? : "core::field.{$this->getKey()}.field";
	}

	public function getViewFilter()
	{
		return $this->viewFilter ? : "core::field.{$this->getKey()}.filter";
	}

	public function getRouteListTable()
	{
		return $this->routeListTable ? : "cmf.field.{$this->getKey()}.list.table";
	}

	public function getRouteListTitle()
	{
		return $this->routeListTitle ? : "cmf.field.{$this->getKey()}.list.title";
	}

	public function getRouteWizardCreate()
	{
		return $this->routeWizardCreate ? : 'cmf.module.objects-lists.wizard.create';
	}

	public function getRouteWizardEdit()
	{
		return $this->routeWizardEdit ? : 'cmf.module.objects-lists.wizard.edit';
	}

	public function getRouteWizardChoose()
	{
		return $this->routeWizardChoose ? : 'cmf.module.objects-lists.wizard.choose';
	}

	public function getSpecialField($model)
	{
		return $this->specialField;
	}

	public function getModelField($model, $field)
	{
		return [$field->code];
	}

	public function getDateField($model, $field)
	{
		return [];
	}

	public function getSpecialDateField($model)
	{
		return $this->specialDateField;
	}

	public function getRule($field = null)
	{
		return $this->ruleList;
	}

	public function getModelAttribute($model, $key, $value, $field)
	{
		try
		{
			return $model->getAttribute($key);
		}
		catch (\Exception $e)
		{
			return null;
		}
	}

	public function setModelAttribute($model, $key, $value, $field)
	{
		$model->setAttribute($key, $value);

		return $this;
	}

	public function getModelSpecialAttribute($model, $key, $value)
	{
		try
		{
			return $model->getAttribute($key);
		}
		catch (\Exception $e)
		{
			return null;
		}
	}

	public function setModelSpecialAttribute($model, $key, $value)
	{
		$model->setAttribute($key, $value);

		return $this;
	}

	public function getFormModelContent($controller = null, $model = null, $field = null, $uniqueId = null)
	{
		$this->setViewModel($field, $controller->getModelFieldView($field), $controller->getModelFieldViewKey($field));
		
		return view($this->getViewModel(), array_merge([
					'controllerParent' => $controller,
					'controller' => $this,
					'model' => $model,
					'field' => $field,
					'permissionCreate' => app('auth')->can('create', 'object_field.' . $model->getTable() . '.' . $field->code),
					'permissionUpdate' => app('auth')->can('update', 'object_field.' . $model->getTable() . '.' . $field->code),
					'permissionDelete' => app('auth')->can('delete', 'object_field.' . $model->getTable() . '.' . $field->code),
					'displayLength' => $this->displayLength,
					'uniqueId' => $uniqueId,
				],
				(array)$this->getModelFieldViewVariable($controller, $model, $field, $uniqueId),
				(array)$controller->getModelFieldViewVariable($this, $model, $field, $uniqueId)
			))->render();
	}

	/**
	 * Return Object Type linked to the field
	 * 
	 * @param \App\Telenok\Core\Model\Object\Field $field
	 * @return \App\Telenok\Core\Model\Object\Type
	 * 
	 */
	public function getLinkedModelType($field) {}
	
	public function getModelFieldViewVariable($controller = null, $model = null, $field = null, $uniqueId = null) {}

	public function getTableList($id = null, $fieldId = null, $uniqueId = null)
	{
		$term = trim($this->getRequest()->input('sSearch'));
		$iDisplayStart = intval($this->getRequest()->input('iDisplayStart', 0));
		$iDisplayLength = intval($this->getRequest()->input('iDisplayLength', 10));
		$sEcho = $this->getRequest()->input('sEcho');
		$content = [];

		try
		{
			$model = \App\Telenok\Core\Model\Object\Sequence::getModel($id);
			$field = \App\Telenok\Core\Model\Object\Sequence::getModel($fieldId);
			$type = $this->getLinkedModelType($field);

			$query = $model->{camel_case($field->code)}();

			if ($term)
			{
				$query->where(function($query) use ($term)
				{
					\Illuminate\Support\Collection::make(explode(' ', $term))
							->reject(function($i)
							{
								return !trim($i);
							})
							->each(function($i) use ($query)
							{
								$query->where('title', 'like', "%{$i}%");
							});
				});
			}

			$query->skip($iDisplayStart)->take($this->displayLength + 1);

			$items = $query->get();

			$objectField = $type->field()->active()->get()->filter(function($item) use ($type)
			{
				return $item->show_in_list == 1 && app('auth')->can('read', 'object_field.' . $type->code . '.' . $item->code);
			});

			$config = app('telenok.config.repository')->getObjectFieldController();

			$canUpdate = app('auth')->can('update', 'object_field.' . $model->getTable() . '.' . $field->code);

			foreach ($items->slice(0, $this->displayLength, true) as $k => $item)
			{
				$c = [];

				foreach ($objectField as $f)
				{
					$c[$f->code] = $config->get($f->key)->getListFieldContent($f, $item, $type);
				}

				$c['tableManageItem'] = $this->getListButtonExtended($item, $field, $type, $uniqueId, $canUpdate);

				$content[] = $c;
			}

			return [
				'sEcho' => $sEcho,
				'iTotalRecords' => ($iDisplayStart + $items->count()),
				'iTotalDisplayRecords' => ($iDisplayStart + $items->count()),
				'aaData' => $content
			];
		}
		catch (\Exception $e)
		{
			return [
				'sEcho' => $sEcho,
				'iTotalRecords' => 0,
				'iTotalDisplayRecords' => 0,
				'aaData' => [],
				'exception' => $e->getMessage(),
			];
		}
	}

	public function getFormModelTableColumn($field, $model, $jsUnique)
	{
		$fields = [];
		$type = $this->getLinkedModelType($field);

		$objectField = $type->field()->active()->get()->filter(function($item) use ($type)
		{
			return $item->show_in_list == 1 && app('auth')->can('read', 'object_field.' . $type->code . '.' . $item->code);
		});

		foreach ($objectField as $key => $field)
		{
			$fields[$field->code] = [
				"mData" => $field->code,
				"sTitle" => e($field->translate('title_list')),
				"mDataProp" => null,
				"bSortable" => $field->allow_sort ? true : false,
			];

			if (($key == 1 && $objectField->count() > 1) || $objectField->count() == 1)
			{
				$fields['tableManageItem'] = [
					"mData" => 'tableManageItem',
					"sTitle" => e($this->LL('action')),
					"mDataProp" => null,
					"bSortable" => false,
				];
			}
		}

		return $fields;
	}

	public function getFormFieldContent($model = null, $uniqueId = null)
	{
		return view($this->getViewField(), array(
					'controller' => $this,
					'model' => $model,
					'uniqueId' => $uniqueId,
				))->render();
	}

	public function getFilterQuery($field = null, $model = null, $query = null, $name = null, $value = null)
	{
		if ($value !== null && trim($value))
		{
			$query->where(function($query) use ($value, $name, $model)
			{
				\Illuminate\Support\Collection::make(explode(' ', $value))
						->reject(function($i)
						{
							return !trim($i);
						})
						->each(function($i) use ($query, $name, $model)
						{
							$query->orWhere($model->getTable() . '.' . $name, 'like', '%' . trim($i) . '%');
						});
			});
		}
	}

	public function getFilterContent($field = null)
	{
		return '<input type="text" name="filter[' . $field->code . ']" value="" />';
	}

	public function getListFieldContent($field, $item, $type = null)
	{
		return \Str::limit($item->translate((string) $field->code), 20);
	}

	public function validate($model = null, $input = [], $messages = [])
	{
		$validator = $this->validator($this, $input, array_merge($messages, $this->LL('error')));

		if ($validator->fails())
		{
			throw $this->validateException()->setMessageError($validator->messages());
		}

		return $this;
	}

	public function validateMethodExists($object, $method)
	{
		$reflector = new \ReflectionClass($object);
		$file = $reflector->getFileName();

		try
		{
			if (method_exists($object, $method) || preg_match("/function\s+{$method}\s*\(/", \File::get($file)))
			{
				return true;
			}
		}
		catch (\Exception $e)
		{
			
		}

		return false;
	}

	public function fill($field, $model, $input)
	{
		return $this;
	}

	public function saveModelField($field, $model, $input)
	{
		return $model;
	}

	public function updateModelFile($model, $param, $stubFile, $dir)
	{
		$reflector = new \ReflectionClass($model);
		$file = $reflector->getFileName();

		try
		{
			$stub = \File::get($dir . DIRECTORY_SEPARATOR . 'stubs' . DIRECTORY_SEPARATOR . "$stubFile.stub");

			foreach ($param as $k => $v)
			{
				$stub = str_replace('{{' . $k . '}}', $v, $stub);
			}

			$res = preg_replace('/\}\s*(\?\>)?$/', $stub, \File::get($file)) . PHP_EOL . PHP_EOL . '}' . PHP_EOL . '?>';

			\File::put($file, $res);
		}
		catch (\Exception $e)
		{
			throw new \Exception($this->LL('error.file.update', array('file' => $file)));
		}
	}

	public function validator($model = null, $input = [], $message = [], $customAttribute = [])
	{
		return app('\Telenok\Core\Interfaces\Validator\Model')
						->setModel($model)
						->setInput($input)
						->setMessage($message)
						->setCustomAttribute($customAttribute);
	}

	public function validateException()
	{
		return app('\Telenok\Core\Interfaces\Exception\Validate');
	}

	public function preProcess($model, $type, $input)
	{
		$tab = $this->getFieldTab($input->get('field_object_type'), $input->get('field_object_tab', 'main'));

		$input->put('field_object_tab', $tab->getKey());

		return $this;
	}

	public function postProcess($model, $type, $input)
	{
		return $this;
	}

	public function processDeleting($model)
	{
		return true;
	}

	public function allowMultilanguage()
	{
		return $this->allowMultilanguage;
	}

	public function getMultilanguage($model, $field)
	{
		if ($field->multilanguage)
		{
			return [$field->code];
		}
	}

	public function getFieldTab($typeId, $tabCode)
	{
		try
		{
			$tabTo = \App\Telenok\Core\Model\Object\Tab::where('tab_object_type', $typeId)
					->where(function($query) use ($tabCode)
					{
						$query->where('id', $tabCode);
						$query->orWhere('code', $tabCode);
					})
					->firstOrFail();
		}
		catch (\Exception $ex)
		{
			try
			{
				$tabTo = \App\Telenok\Core\Model\Object\Tab::where('tab_object_type', $typeId)->where('code', 'main')->firstOrFail();
			}
			catch (\Exception $ex)
			{
				throw new \Exception($this->LL('error.tab.field.key'));
			}
		}

		return $tabTo;
	}

	public function getFieldTabBelongTo($typeId, $tabBelongCode, $tabHasId)
	{
		try
		{
			$tabTo = $this->getFieldTab($typeId, $tabBelongCode);
		}
		catch (\Exception $ex)
		{
			try
			{
				$tabHas = \App\Telenok\Core\Model\Object\Tab::firstOrFail('id', $tabHasId);

				$tabTo = \App\Telenok\Core\Model\Object\Tab::where('tab_object_type', $typeId)->whereCode($tabHas->code);
			}
			catch (\Exception $ex)
			{
				try
				{
					$tabTo = \App\Telenok\Core\Model\Object\Tab::where('tab_object_type', $typeId)->where('code', 'main')->firstOrFail();
				}
				catch (\Exception $ex)
				{
					throw new \Exception($this->LL('error.tab.field.key'));
				}
			}
		}

		return $tabTo;
	}

}
