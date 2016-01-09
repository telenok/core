<?php namespace Telenok\Core\Interfaces\Field;

class Controller extends \Telenok\Core\Interfaces\Controller\Controller implements \Telenok\Core\Interfaces\Field\IField {

	protected $ruleList = [];
	protected $specialField = [];
	protected $specialDateField = [];
	protected $allowMultilanguage = true;
	protected $pageLength = 5;
	protected $viewModel;
	protected $viewField;
	protected $viewFilter;
	protected $routeListTable;
	protected $routeListTitle;
	protected $routeWizardCreate;
	protected $routeWizardEdit;
	protected $routeWizardChoose;
    protected $languageDirectory = 'field';

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
		return $this->viewField ? : $this->getPackage() . "::field.{$this->getKey()}.field";
	}

	public function getViewFilter()
	{
		return $this->viewFilter ? : $this->getPackage() . "::field.{$this->getKey()}.filter";
	}

	public function getRouteListTable()
	{
		return $this->routeListTable ? : $this->getVendorName() . ".field.{$this->getKey()}.list.table";
	}

	public function getRouteListTitle()
	{
		return $this->routeListTitle ? : $this->getVendorName() . ".field.{$this->getKey()}.list.title";
	}

	public function getRouteWizardCreate()
	{
		return $this->routeWizardCreate ? : 'telenok.module.objects-lists.wizard.create';
	}

	public function getRouteWizardEdit()
	{
		return $this->routeWizardEdit ? : 'telenok.module.objects-lists.wizard.edit';
	}

	public function getRouteWizardChoose()
	{
		return $this->routeWizardChoose ? : 'telenok.module.objects-lists.wizard.choose';
	}

	public function getSpecialField($model)
	{
		return $this->specialField;
	}

	public function getModelField($model, $field)
	{
		return $this->getModelFillableField($model, $field);
	}

	public function getModelFillableField($model, $field)
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
					'pageLength' => $this->pageLength,
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
        $input = $this->getRequest();
        
		$term = trim($input->input('search.value'));
        
        $draw = $input->input('draw');
		$start = $input->input('start', 0);
        $length = $input->input('length', $this->pageLength);
        
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
					collect(explode(' ', $term))
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

			$query->skip($start)->take($length + 1);

			$items = $query->get();

			$objectField = $type->field()->active()->get()->filter(function($item) use ($type)
			{
				return $item->show_in_list == 1 && app('auth')->can('read', 'object_field.' . $type->code . '.' . $item->code);
			});

			$config = app('telenok.config.repository')->getObjectFieldController();

			$canUpdate = app('auth')->can('update', 'object_field.' . $model->getTable() . '.' . $field->code);

			foreach ($items->slice(0, $length, true) as $item)
			{
				$c = [];

				foreach ($objectField as $f)
				{
					$c[$f->code] = $config->get($f->key)->getListFieldContent($f, $item, $type);
				}

				$c['tableManageItem'] = $this->getListButton($item, $field, $type, $uniqueId, $canUpdate);

				$content[] = $c;
			}

			return [
				'draw' => $draw,
				'data' => $content,
				'recordsTotal' => ($start + $items->count()),
				'recordsFiltered' => ($start + $items->count()),
			];
		}
		catch (\Exception $e)
		{
			return [
				'draw' => $draw,
				'data' => [],
				'exception' => $e->getMessage(),
				'recordsTotal' => 0,
				'recordsFiltered' => 0,
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
			if (($key == 0 && $objectField->count() > 1) || $objectField->count() == 1)
			{
				$fields['tableManageItem'] = [
					"data" => 'tableManageItem',
					"title" => "",
					"orderable" => false,
				];
			}
            
			$fields[$field->code] = [
				"data" => $field->code,
				"title" => e($field->translate('title_list')),
				"orderable" => $field->allow_sort ? true : false,
			];
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
				collect(explode(' ', $value))
						->reject(function($i)
						{
							return !trim($i);
						})
						->each(function($i) use ($query, $name, $model)
						{
							$query->orWhere($model->getTable() . '.' . $name, 'like', '%' . trim($i) . '%');
						});
                        
                $query->orWhere($model->getTable() . '.id', intval($value));
			});
		}
	}

	public function getFilterContent($field = null)
	{
		return '<input type="text" name="filter[' . $field->code . ']" value="" />';
	}

	public function getListFieldContent($field, $item, $type = null)
	{
		return e(\Str::limit($item->translate((string) $field->code), 20));
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
			if (method_exists($object, $method) || preg_match("/function\s+{$method}\s*\(/", file_get_contents($file)))
			{
				return true;
			}
		}
        catch (\Exception $e)
        {
    		return false;
		}
	}

	public function fill($field, $model, $input)
	{
		return $this;
	}

	public function saveModelField($field, $model, $input)
	{
		return $model;
	}

	public function updateModelFile($model, $param, $stubFile)
	{
		$reflector = new \ReflectionClass($model);
		$file = $reflector->getFileName();
        $dir = $this->getStubFileDirectory();

		try
		{
			$param['class_name'] = get_class($model);

            // update /app/Model/macro.php
			$stub = file_get_contents($dir . DIRECTORY_SEPARATOR . 'stubs' . DIRECTORY_SEPARATOR . "$stubFile.macro.stub");

			foreach ($param as $k => $v)
			{
				$stub = str_replace('{{' . $k . '}}', $v, $stub);
			}
			
			file_put_contents(app_path(static::$macroFile), $stub, FILE_APPEND | LOCK_EX);


            // update class file
			$stub = file_get_contents($dir . DIRECTORY_SEPARATOR . 'stubs' . DIRECTORY_SEPARATOR . "$stubFile.stub");


			foreach ($param as $k => $v)
			{
				$stub = str_replace('{{' . $k . '}}', $v, $stub);
			}

			$res = preg_replace('/\}\s*(\?\>)?$/', $stub, file_get_contents($file)) . PHP_EOL . PHP_EOL . '}' . PHP_EOL . '?>';

			file_put_contents($file, $res, LOCK_EX);


            // reload /app/Model/macro.php
			\Telenok\Core\Interfaces\Field\Relation\Controller::readMacroFile();
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
		return new \Telenok\Core\Support\Exception\Validator;
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

	public function processFieldDelete($model, $type)
	{
        try
        {
            \Schema::table($type->code, function($table) use ($model)
            {
                $table->dropColumn($model->code);
            });
        }
        catch (\Exception $e) {}

		return true;
	}
	
	public function processModelDelete($model, $force)
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

	public function getTitleList($id = null, $closure = null)
	{
		$term = trim($this->getRequest()->input('term'));
		$return = [];

        if ($id)
        {
            $model = app(\App\Telenok\Core\Model\Object\Sequence::getModel($id)->class_model);
        }
        else
        {
            $model = app('\App\Telenok\Core\Model\Object\Sequence');
        }

		$query = $model::withPermission()->with('sequencesObjectType');

        if (in_array('title', $model->getMultilanguage(), true))
        {
			$query->join('object_translation', function($join) use ($model)
			{
				$join->on($model->getTable() . '.id', '=', 'object_translation.translation_object_model_id')
                    ->on('object_translation.translation_object_field_code', '=', app('db')->raw("'title'"))
                    ->on('object_translation.translation_object_language', '=', app('db')->raw("'".config('app.locale')."'"));
			});
        }

        $query->where(function($query) use ($term, $model)
        {
            if (trim($term))
            {
                collect(explode(' ', $term))
                ->reject(function($i)
                {
                    return !trim($i);
                })
                ->each(function($i) use ($query, $model)
                {
                    if (in_array('title', $model->getMultilanguage(), true))
                    {
                        $query->where('object_translation.translation_object_string', 'like', "%{$i}%");
                    }
                    else
                    {
                        $query->where($model->getTable() . '.title', 'like', "%{$i}%");
                    }
                });

                $query->orWhere($model->getTable() . '.id', (int) $term);
            }
        });

		if ($closure instanceof \Closure)
		{
			$closure($query);
		}

		$query->take(20)->groupBy($model->getTable() . '.id')->get()->each(function($item) use (&$return)
		{
			$return[] = ['value' => $item->id, 'text' => "[{$item->sequencesObjectType->translate('title')} #{$item->id}] " . $item->translate('title')];
		});

		return $return;
	}

    public function getStubFileDirectory()
    {
        return __DIR__;
    }
}