<?php

namespace Telenok\Core\Module\Objects\Type;

class Controller extends \Telenok\Core\Interfaces\Presentation\TreeTabObject\Controller {

    protected $key = 'objects-type';
    protected $parent = 'objects';
    protected $modelListClass = '\App\Model\Telenok\Object\Type';

    protected $presentation = 'tree-tab-object';
    protected $presentationFormFieldListView = 'core::module.objects-type.form-field-list';

	public function createResource($model, $type = null, $input = [])
	{
		$resCode = 'object_type.'.$model->code;

		$title = $model->title->all();
		$toAdd = ['ru' => 'Тип объекта', 'en' => 'Type of object'];

		foreach($title as $language => $value)
		{
			$title[$language] = array_get($toAdd, $language, 'Type of object') . ': ' . $value;
		}

		try
		{
            \Telenok\Core\Security\Acl::addResource($resCode, $title);
		} 
		catch (\Exception $ex) {}

		$resCodeOwn = 'object_type.'.$model->code.'.own';

		$title = $model->title->all();
		$toAdd = ['ru' => 'Тип объекта', 'en' => 'Type of object'];
		$toAddAfter = ['ru' => 'Собственные записи', 'en' => 'Own records'];

		foreach($title as $language => $value)
		{
			$title[$language] = array_get($toAdd, $language, 'Type of object') . ': ' . $value . '. ' . array_get($toAddAfter, $language, 'Own records');
		}

		try
		{
			(new \App\Model\Telenok\Security\Resource())->storeOrUpdate([
				'title' => $title,
				'code' => $resCodeOwn,
				'active' => 1
			]);
		}
		catch (\Exception $ex) {}
	}

    public function validateClassModel($model, $type = null, $input = [])
    { 
        if ($model->exists && $model->class_model)
        {
			\Session::flash('warning.class_model_exists', $this->LL('error.class_model_exists'));

            $input->forget('class_model');

            return;
        }

		$input->put('class_model', strtolower(trim($input->get('class_model'), '\\ ')));

		$classNameCollection = \Illuminate\Support\Collection::make(explode('\\', $input->get('class_model')))
            ->filter(function($i) { return trim($i); })->each(function($item)
		{
			if (!preg_match('/^[a-z][\w]*$/i', $item))
			{
				throw new \Exception($this->LL('error.class_model.name'));
			}
		})
		->transform(function($item) { return ucfirst($item); });

		$input->put('class_model', '\\' . implode($classNameCollection->all(), '\\'));

        $classModel = $input->get('class_model');

        if (preg_match('/^\\\\App\\\\Model\\\\.+/', $classModel) !== 1 && !class_exists($classModel))
        {
            throw new \Exception($this->LL('error.class_model.store'));
        }
	}

    public function validateClassController($model, $type = null, $input = [])
    {
        if (!$input->get('class_controller'))
        {
            return;
        }

		$input->put('class_controller', strtolower(trim($input->get('class_controller'), '\\ ')));

		$classNameCollection = \Illuminate\Support\Collection::make(explode('\\', $input->get('class_controller')))
                ->filter(function($i) { return trim($i); })->each(function($item)
		{
			if (!preg_match('/^[a-z][\w]*$/i', $item))
			{
				throw new \Exception($this->LL('error.class_controller.name'));
			}
		})
		->transform(function($item) { return ucfirst($item); });

		$input->put('class_controller', '\\' . implode($classNameCollection->all(), '\\'));

        $classModel = $input->get('class_controller');
        
		if (class_exists($classModel))
		{
			\Session::flash('warning.class_controller_exists', $this->LL($this->LL('error.class_controller_exists')));
		}
        else if (preg_match('/^\\\\App\\\\Http\\\\Controllers\\\\.+/', $classModel) !== 1)
        {
            throw new \Exception($this->LL('error.class_controller.store'));
        }
	}

    public function preProcess($model, $type = null, $input = [])
    { 
		$input->put('code', trim($input->get('code')));

		$this->validateClassModel($model, $type, $input);
		$this->validateClassController($model, $type, $input);

        return parent::preProcess($model, $type, $input); 
	}

    public function postProcess($model, $type, $input)
    {
        parent::postProcess($model, $type, $input); 

		$this->createResource($model, $type, $input);
		$this->createModelFile($model, $type, $input); 
		$this->createModelTable($model, $type, $input);
		$this->createControllerFile($model, $type, $input);
		$this->createControllerLocalizationFile($model, $type, $input);
        $this->createObjectField($model, $type, $input);

		return $this;
	}

    public function createControllerLocalizationFile($model, $type = null, $input = [])
    {    
        if ($model->code)
        {
            $locales = \Config::get('app.locales');

            foreach ($locales as $locale)
            {
                $dir = base_path('resources' . DIRECTORY_SEPARATOR . 'lang' . DIRECTORY_SEPARATOR . $locale . DIRECTORY_SEPARATOR . 'module');
                $file = base_path('resources' . DIRECTORY_SEPARATOR . 'lang' . DIRECTORY_SEPARATOR . $locale . DIRECTORY_SEPARATOR . 'module' . DIRECTORY_SEPARATOR . 'objects-' . $model->code . '.php');
            
                if (!\File::exists($file)) 
                {
                    try 
                    {
                        \File::makeDirectory($dir, 0775, true, true);

                        $param = [
                            'name' => $model->translate('title', $locale),
                            'title' => $model->translate('title', $locale),
                        ];

                        $stub = \File::get(__DIR__.'/stubs/locale.stub');

                        foreach($param as $k => $v)
                        {
                            $stub = str_replace('{{'.$k.'}}', $v, $stub);
                        }

                        \File::put($file, $stub);
                    } 
                    catch (\Exception $e) 
                    {
                        \Exception($this->LL('error.file.create', array('path' => $file)));
                    }
                } 
            }
        }
    }
    
    public function createModelFile($model, $type = null, $input = [])
    {
        $class = class_basename($model->class_model);

		$ns = trim(preg_replace('/\\\\'.$class.'$/', '', $model->class_model), '\\');

        $path = preg_replace('/^(App)(.+)$/', '${2}', $ns);
        
		$dir = str_replace('\\', DIRECTORY_SEPARATOR, app_path() . $path);
		$file = $dir . DIRECTORY_SEPARATOR . $class . '.php';

        if (!\File::exists($file)) 
        {
            try 
            {
				\File::makeDirectory($dir, 0775, true, true);

                $param = [
                    'namespace' => ($ns ? "namespace $ns;" : ""),
                    'class' => $class,
                    'table' => $model->code,
                ];

                $stub = \File::get(__DIR__.'/stubs/model.stub');

                foreach($param as $k => $v)
                {
                    $stub = str_replace('{{'.$k.'}}', $v, $stub);
                }
 
                \File::put($file, $stub);
            } 
            catch (\Exception $e) 
            {
				\Exception($this->LL('error.file.create', array('path' => $file)));
            }
        } 
    }

    public function createControllerFile($model, $type = null, $input = [])
    {
        $class = class_basename($model->class_controller);

		$ns = trim(preg_replace('/\\\\'.$class.'$/', '', $model->class_controller), '\\');

        $path = preg_replace('/^(App)(.+)$/', '${2}', $ns);
        
		$dir = str_replace('\\', DIRECTORY_SEPARATOR, app_path() . $path);
		$file = $dir . DIRECTORY_SEPARATOR . $class . '.php';

        if (!\File::exists($file)) 
        {
            try 
            {
				\File::makeDirectory($dir, 0775, true, true);

                $param = [
                    'namespace' => ($ns ? "namespace $ns;" : ''),
                    'class' => $class,
                    'key' => "objects-{$model->code}",
                    'parent' => 'objects',
                    'presentation' => 'tree-tab-object',
                    'classList' => "{$model->class_model}",
                    'classTree' => "",
                ];

                $stub = \File::get(__DIR__.'/stubs/controller.stub');

                foreach($param as $k => $v)
                {
                    $stub = str_replace('{{'.$k.'}}', $v, $stub);
                }
 
                \File::put($file, $stub);
            } 
            catch (\Exception $e) 
            {
				\Exception($this->LL('error.file.create', array('path' => $file)));
            }
        } 
    }

    public function createModelTable($model, $type = null, $input = [])
    {  
        $table = $model->code;

        try
        {
            if (!\Schema::hasTable($table)) 
            {
                \Schema::create($table, function(\Illuminate\Database\Schema\Blueprint $table) use ($model)
                {
                    $table->increments('id');
                    $table->timestamps();
                    $table->softDeletes();
                    $table->text('title')->nullable();
                });
            }
        }
        catch (\Exception $e)
        {
            throw $e;
        }
    }

    public function createObjectField($model, $type = null, $input = [])
    { 
		$multilanguage = $input->get('multilanguage');

		$tabMain = \App\Model\Telenok\Object\Tab::where('tab_object_type', $model->getKey())->where('code', 'main')->first();
		$tabVisible = \App\Model\Telenok\Object\Tab::where('tab_object_type', $model->getKey())->where('code', 'visibility')->first();
		$tabAdditionally = \App\Model\Telenok\Object\Tab::where('tab_object_type', $model->getKey())->where('code', 'additionally')->first();

		$translationSeed = $this->translationSeed();

        $now = \Carbon\Carbon::now()->toDateTimeString();
        $plus15Year = \Carbon\Carbon::now()->addYears(15)->toDateTimeString();

		if (!$tabMain)
		{
			$tabMain = (new \App\Model\Telenok\Object\Tab())->storeOrUpdate(
					[
						'title' => array_get($translationSeed, 'tab.main'),
						'code' => 'main',
						'active' => 1,
						'tab_object_type' => $model->getKey(),
						'tab_order' => 1
					]
			);
		}

		if (!$tabVisible)
		{
			$tabVisible = (new \App\Model\Telenok\Object\Tab())->storeOrUpdate(
					[
						'title' => array_get($translationSeed, 'tab.visibility'),
						'code' => 'visibility',
						'active' => 1,
						'tab_object_type' => $model->getKey(),
						'tab_order' => 2
					]
			);
		}

		if (!$tabAdditionally)
		{
			$tabAdditionally = (new \App\Model\Telenok\Object\Tab())->storeOrUpdate(
					[
						'title' => array_get($translationSeed, 'tab.additionally'),
						'code' => 'additionally',
						'active' => 1,
						'tab_object_type' => $model->getKey(),
						'tab_order' => 3
					]
			);
		}

		if (!\App\Model\Telenok\Object\Field::where('field_object_type', $model->getKey())->where('code', 'id')->count())
		{
			(new \App\Model\Telenok\Object\Field())->storeOrUpdate([
				'title' => array_get($translationSeed, 'model.№'),
				'title_list' => array_get($translationSeed, 'model.№'),
				'key' => 'integer-unsigned',
				'code' => 'id',
				'active' => 1,
				'field_object_type' => $model->getKey(),
				'field_object_tab' => $tabMain->getKey(),
				'show_in_list' => 1,
				'show_in_form' => 1,
				'allow_search' => 1,
				'multilanguage' => 0,
				'allow_create' => 0,
				'allow_update' => 0,
				'field_order' => 1,
			]);
		}

		if (!\App\Model\Telenok\Object\Field::where('field_object_type', $model->getKey())->where('code', 'title')->count())
		{
			(new \App\Model\Telenok\Object\Field())->storeOrUpdate([
				'title' => array_get($translationSeed, 'model.title'),
				'title_list' => array_get($translationSeed, 'model.title'),
				'key' => 'string',
				'code' => 'title',
				'active' => 1,
				'field_object_type' => $model->getKey(),
				'field_object_tab' => $tabMain->getKey(),
				'multilanguage' => $multilanguage,
				'show_in_list' => 1,
				'show_in_form' => 1,
				'allow_search' => 1,
				'allow_create' => 1,
				'allow_update' => 1,
				'field_order' => 2,
				'string_list_size' => 50,
			]);
		}

		if (!\App\Model\Telenok\Object\Field::where('field_object_type', $model->getKey())->where('key', 'created-by')->count())
		{
			(new \App\Model\Telenok\Object\Field())->storeOrUpdate([
				'key' => 'created-by',
				'field_object_type' => $model->getKey(),
				'field_object_tab' => $tabAdditionally->getKey(),
				'field_order' => 1,
			]);
		}
		
		if (!\App\Model\Telenok\Object\Field::where('field_object_type', $model->getKey())->where('key', 'updated-by')->count())
		{
			(new \App\Model\Telenok\Object\Field())->storeOrUpdate([
				'key' => 'updated-by',
				'field_object_type' => $model->getKey(),
				'field_object_tab' => $tabAdditionally->getKey(),
				'field_order' => 2,
			]);
		}
		
		if (!\App\Model\Telenok\Object\Field::where('field_object_type', $model->getKey())->where('key', 'locked-by')->count())
		{
			(new \App\Model\Telenok\Object\Field())->storeOrUpdate([
				'key' => 'locked-by',
				'field_object_type' => $model->getKey(),
				'field_object_tab' => $tabAdditionally->getKey(),
				'field_order' => 3,
			]);
		}
		
		if ($model->treeable)
		{
			if (!\App\Model\Telenok\Object\Field::where('field_object_type', $model->getKey())->where('key', 'tree')->count())
			{
				(new \App\Model\Telenok\Object\Field())->storeOrUpdate([
					'key' => 'tree',
					'field_object_type' => $model->getKey(),
					'field_object_tab' => $tabMain->getKey(),
					'field_order' => 10,
				]);
                
                \App\Model\Telenok\Object\Sequence::where('sequences_object_type', $model->getKey())
                        ->update(['treeable' => 1]);
			}
		}
		else
		{
			\App\Model\Telenok\Object\Field::where('field_object_type', $model->getKey())->where('key', 'tree')->forceDelete();
 
            \App\Model\Telenok\Object\Sequence::where('sequences_object_type', $model->getKey())
                        ->update(['treeable' => 0]);
		}

		if (!\App\Model\Telenok\Object\Field::where('field_object_type', $model->getKey())->where('code', 'active')->count())
		{
			(new \App\Model\Telenok\Object\Field())->storeOrUpdate([
                'title' => array_get($translationSeed, 'model.active'),
                'title_list' => array_get($translationSeed, 'model.active'),
                'key' => 'select-one',
                'code' => 'active',
                'select_one_data' => [
                    'title' => \SeedCommonFields::llYesNo(),
                    'key' => [0, 1],
                    'default' => 0,
                ],
                'active' => 1,
                'field_view' => 'core::field.select-one.model-toggle-button',
                'field_object_type' => $model->getKey(),
                'field_object_tab' => $tabVisible->getKey(),
                'multilanguage' => 0,
                'show_in_form' => 1,
                'show_in_list' => 0,
                'allow_search' => 1,
                'allow_create' => 1,
                'allow_update' => 1,
                'field_order' => 3,
            ]);
        }
        
		if (!\App\Model\Telenok\Object\Field::where('field_object_type', $model->getKey())->where('code', 'active_at')->count())
        {
			(new \App\Model\Telenok\Object\Field())->storeOrUpdate([
                'title' => array_get($translationSeed, 'model.active_at'),
                'title_list' => array_get($translationSeed, 'model.active_at'),
                'key' => 'datetime-range',
                'code' => 'active_at',
                'datetime_range_default_start' => $now,
                'datetime_range_default_end' => $plus15Year,
                'active' => 1,
                'field_object_type' => $model->getKey(),
                'field_object_tab' => $tabVisible->getKey(),
                'multilanguage' => 0,
                'show_in_form' => 1,
                'show_in_list' => 0,
                'allow_search' => 1,
                'allow_create' => 1,
                'allow_update' => 1,
                'field_order' => 10,
            ]);
  
		}

		if (!\App\Model\Telenok\Object\Field::where('field_object_type', $model->getKey())->where('key', 'permission')->count())
		{
			(new \App\Model\Telenok\Object\Field())->storeOrUpdate([
				'key' => 'permission',
				'field_object_type' => $model->getKey(),
			]); 
		}
    }    
 
	public function translationSeed()
	{
		return [
			'field' => [
				'id' => [
					'ru' => "№",
					'en' => "№",
				],
				'title' => [
					'ru' => "Заголовок",
					'en' => "Title",
				],
				'title_list' => [
					'ru' => "Заголовок списка",
					'en' => "Title of list",
				],
			],
			'tab' => [
				'main' => ['en' => 'Main', 'ru' => 'Основное'],
				'visibility' => ['en' => 'Visibility', 'ru' => 'Видимость'],
				'additionally' => ['en' => 'Additionally', 'ru' => 'Дополнительно'],
			],
			'model' => [
				'№' => ['en' => '№', 'ru' => '№'],
				'title' => ['en' => 'Title', 'ru' => 'Заголовок'],
				'active' => ['en' => 'Active', 'ru' => 'Активно'],
				'active_at' => ['en' => 'Active time', 'ru' => 'Период активности'],
			],
		];
	}

}

