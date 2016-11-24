<?php

namespace Telenok\Core\Module\Objects\Type;

/**
 * @class Telenok.Core.Module.Objects.Type.Controller
 * @extends Telenok.Core.Abstraction.Presentation.TreeTabObject.Controller
 */
class Controller extends \Telenok\Core\Abstraction\Presentation\TreeTabObject\Controller {

    protected $key = 'objects-type';
    protected $parent = 'objects';
    protected $modelListClass = '\App\Vendor\Telenok\Core\Model\Object\Type';
    protected $presentation = 'tree-tab-object';
    protected $presentationFormFieldListView = 'core::module.objects-type.form-field-list';

    public function createResource($model, $type = null, $input = [])
    {
        $resCode = 'object_type.' . $model->code;

        $title = $model->title->all();
        $toAdd = ['ru' => 'Тип объекта', 'en' => 'Type of object'];

        foreach ($title as $language => $value)
        {
            $title[$language] = array_get($toAdd, $language, 'Type of object') . ': ' . $value;
        }

        try
        {
            \App\Vendor\Telenok\Core\Security\Acl::addResource($resCode, $title);
        }
        catch (\Exception $e)
        {
            
        }

        $resCodeOwn = 'object_type.' . $model->code . '.own';

        $title = $model->title->all();
        $toAdd = ['ru' => 'Тип объекта', 'en' => 'Type of object'];
        $toAddAfter = ['ru' => 'Собственные записи', 'en' => 'Own records'];

        foreach ($title as $language => $value)
        {
            $title[$language] = array_get($toAdd, $language, 'Type of object') . ': ' . $value . '. ' . array_get($toAddAfter, $language, 'Own records');
        }

        try
        {
            (new \App\Vendor\Telenok\Core\Model\Security\Resource())->storeOrUpdate([
                'title' => $title,
                'code' => $resCodeOwn,
                'active' => 1
            ]);
        }
        catch (\Exception $e)
        {
            
        }
    }

    public function validateClassModel($model, $type = null, $input = [])
    {
        if ($model->exists && $model->model_class)
        {
            \Session::flash('warning.model_class_exists', $this->LL('error.model_class_exists'));

            $input->forget('model_class');

            return;
        }

        $input->put('model_class', trim($input->get('model_class'), '\\ '));

        $classNameCollection = collect(explode('\\', $input->get('model_class')))
                ->filter(function($i)
                {
                    return trim($i);
                })
                ->each(function($item)
                {
                    if (!preg_match('/^[a-z][\w]*$/i', $item))
                    {
                        throw new \Exception($this->LL('error.model_class.name'));
                    }
                })
                ->transform(function($item)
        {
            return ucfirst($item);
        });

        if ($classNameCollection->isEmpty())
        {
            throw new \Exception($this->LL('error.model_class.name'));
        }

        $input->put('model_class', '\\' . implode('\\', $classNameCollection->all()));

        $classModel = $input->get('model_class');

        if (preg_match('/^\\\\App\\\\Model\\\\.+/', $classModel) !== 1 && !class_exists($classModel))
        {
            throw new \Exception($this->LL('error.model_class.store', ['class' => $classModel]));
        }
    }

    public function validateClassController($model, $type = null, $input = [])
    {
        if (!$input->get('controller_class'))
        {
            return;
        }

        $input->put('controller_class', trim($input->get('controller_class'), '\\ '));

        $classNameCollection = collect(explode('\\', $input->get('controller_class')))
                ->filter(function($i)
                {
                    return trim($i);
                })->each(function($item)
                {
                    if (!preg_match('/^[a-z][\w]*$/i', $item))
                    {
                        throw new \Exception($this->LL('error.controller_class.name'));
                    }
                })
                ->transform(function($item)
        {
            return ucfirst($item);
        });

        $input->put('controller_class', '\\' . implode('\\', $classNameCollection->all()));

        $classController = $input->get('controller_class');

        if (class_exists($classController))
        {
            \Session::flash('warning.controller_class_exists', $this->LL($this->LL('error.controller_class_exists')));
        }
        else if (preg_match('/^\\\\App\\\\Http\\\\Controllers\\\\.+/', $classController) !== 1)
        {
            throw new \Exception($this->LL('error.controller_class.store', ['class' => $classController]));
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
            $locales = config('app.locales');

            foreach ($locales as $locale)
            {
                $dir = base_path('resources/lang/' . $locale . '/module');
                $file = base_path('resources/lang/' . $locale . '/module/objects-' . $model->code . '.php');

                if (!\File::exists($file))
                {
                    try
                    {
                        \File::makeDirectory($dir, 0775, true, true);

                        $param = [
                            'name' => $model->translate('title', $locale),
                            'title' => $model->translate('title', $locale),
                        ];

                        $stub = file_get_contents(__DIR__ . '/stubs/locale.stub');

                        foreach ($param as $k => $v)
                        {
                            $stub = str_replace('{{' . $k . '}}', addcslashes($v, "'"), $stub);
                        }

                        file_put_contents($file, $stub, LOCK_EX);
                    }
                    catch (\Exception $e)
                    {
                        throw new \Exception($this->LL('error.file.create', array('path' => $file)));
                    }
                }
            }
        }
    }

    public function createModelFile($model, $type = null, $input = [])
    {
        $class = class_basename($model->model_class);

        $ns = trim(preg_replace('/\\\\' . $class . '$/', '', $model->model_class), '\\');

        $path = trim(str_replace('App', '', $ns), '\\');

        $dir = str_replace('\\', '/', app_path($path));
        $file = $dir . '/' . $class . '.php';

        if (!\File::exists($file))
        {
            try
            {
                \File::makeDirectory($dir, 0775, true, true);

                $param = [
                    'namespace' => $ns,
                    'class' => $class,
                    'table' => $model->code,
                ];

                $stub = file_get_contents(__DIR__ . '/stubs/model.stub');

                foreach ($param as $k => $v)
                {
                    $stub = str_replace('{{' . $k . '}}', $v, $stub);
                }

                file_put_contents($file, $stub, LOCK_EX);
            }
            catch (\Exception $e)
            {
                throw new \Exception($this->LL('error.file.create', array('path' => $file)));
            }
        }
    }

    public function createControllerFile($model, $type = null, $input = [])
    {
        if (!$model->controller_class)
        {
            return;
        }

        $class = class_basename($model->controller_class);
        $ns = trim(preg_replace('/\\\\' . $class . '$/', '', $model->controller_class), '\\');

        $path = preg_replace('/^(App)(.+)$/', '${2}', $ns);

        $dir = str_replace('\\', '/', app_path($path));
        $file = $dir . '/' . $class . '.php';

        if (!\File::exists($file))
        {
            try
            {
                \File::makeDirectory($dir, 0775, true, true);

                $param = [
                    'namespace' => $ns,
                    'class' => $class,
                    'key' => "objects-{$model->code}",
                    'parent' => 'objects',
                    'presentation' => 'tree-tab-object',
                    'classList' => "{$model->model_class}",
                    'classTree' => "",
                ];

                $stub = file_get_contents(__DIR__ . '/stubs/controller.stub');

                foreach ($param as $k => $v)
                {
                    $stub = str_replace('{{' . $k . '}}', $v, $stub);
                }

                file_put_contents($file, $stub, LOCK_EX);
            }
            catch (\Exception $e)
            {
                throw new \Exception($this->LL('error.file.create', array('path' => $file)));
            }
        }
    }

    public function createModelTable($model, $type = null, $input = [])
    {
        $table = $model->code;

        if (!\Schema::hasTable($table))
        {
            \Schema::create($table, function(\Illuminate\Database\Schema\Blueprint $table) use ($model)
            {
                $table->increments('id');
                $table->timestamps();
                $table->softDeletes();
                $table->mediumText('title')->nullable();
            });
        }
    }

    public function createObjectField($model, $type = null, $input = [])
    {
        $multilanguage = $input->get('multilanguage');

        $tabMain = \App\Vendor\Telenok\Core\Model\Object\Tab::where('tab_object_type', $model->getKey())->where('code', 'main')->first();
        $tabVisible = \App\Vendor\Telenok\Core\Model\Object\Tab::where('tab_object_type', $model->getKey())->where('code', 'visibility')->first();
        $tabAdditionally = \App\Vendor\Telenok\Core\Model\Object\Tab::where('tab_object_type', $model->getKey())->where('code', 'additionally')->first();

        $translationSeed = $this->translationSeed();

        $now = \Carbon\Carbon::now()->toDateTimeString();
        $plus15Year = \Carbon\Carbon::now()->addYears(15)->toDateTimeString();

        if (!$tabMain)
        {
            $tabMain = (new \App\Vendor\Telenok\Core\Model\Object\Tab())->storeOrUpdate(
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
            $tabVisible = (new \App\Vendor\Telenok\Core\Model\Object\Tab())->storeOrUpdate(
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
            $tabAdditionally = (new \App\Vendor\Telenok\Core\Model\Object\Tab())->storeOrUpdate(
                    [
                        'title' => array_get($translationSeed, 'tab.additionally'),
                        'code' => 'additionally',
                        'active' => 1,
                        'tab_object_type' => $model->getKey(),
                        'tab_order' => 3
                    ]
            );
        }

        if (!\App\Vendor\Telenok\Core\Model\Object\Field::where('field_object_type', $model->getKey())->where('code', 'id')->exists())
        {
            (new \App\Vendor\Telenok\Core\Model\Object\Field())->storeOrUpdate([
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

        if (!\App\Vendor\Telenok\Core\Model\Object\Field::where('field_object_type', $model->getKey())->where('code', 'title')->exists())
        {
            (new \App\Vendor\Telenok\Core\Model\Object\Field())->storeOrUpdate([
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

        if (!\App\Vendor\Telenok\Core\Model\Object\Field::where('field_object_type', $model->getKey())->where('key', 'created-by')->exists())
        {
            (new \App\Vendor\Telenok\Core\Model\Object\Field())->storeOrUpdate([
                'key' => 'created-by',
                'field_object_type' => $model->getKey(),
                'field_object_tab' => $tabAdditionally->getKey(),
                'field_order' => 1,
                'show_in_list' => 0,
                'show_in_form' => 1,
            ]);
        }

        if (!\App\Vendor\Telenok\Core\Model\Object\Field::where('field_object_type', $model->getKey())->where('key', 'updated-by')->exists())
        {
            (new \App\Vendor\Telenok\Core\Model\Object\Field())->storeOrUpdate([
                'key' => 'updated-by',
                'field_object_type' => $model->getKey(),
                'field_object_tab' => $tabAdditionally->getKey(),
                'field_order' => 2,
                'show_in_list' => 0,
                'show_in_form' => 1,
            ]);
        }

        if (!\App\Vendor\Telenok\Core\Model\Object\Field::where('field_object_type', $model->getKey())->where('key', 'locked-by')->exists())
        {
            (new \App\Vendor\Telenok\Core\Model\Object\Field())->storeOrUpdate([
                'key' => 'locked-by',
                'field_object_type' => $model->getKey(),
                'field_object_tab' => $tabAdditionally->getKey(),
                'field_order' => 3,
                'show_in_list' => 0,
                'show_in_form' => 1,
            ]);
        }

        if (!\App\Vendor\Telenok\Core\Model\Object\Field::where('field_object_type', $model->getKey())->where('key', 'deleted-by')->exists())
        {
            (new \App\Vendor\Telenok\Core\Model\Object\Field())->storeOrUpdate([
                'key' => 'deleted-by',
                'field_object_type' => $model->getKey(),
                'field_object_tab' => $tabAdditionally->getKey(),
                'field_order' => 3,
                'show_in_list' => 0,
                'show_in_form' => 1,
            ]);
        }

        if ($model->treeable)
        {
            if (!\App\Vendor\Telenok\Core\Model\Object\Field::where('field_object_type', $model->getKey())->where('key', 'tree')->exists())
            {
                (new \App\Vendor\Telenok\Core\Model\Object\Field())->storeOrUpdate([
                    'key' => 'tree',
                    'field_object_type' => $model->getKey(),
                    'field_object_tab' => $tabMain->getKey(),
                    'show_in_list' => 0,
                    'show_in_form' => 1,
                    'allow_create' => 1,
                    'allow_update' => 1,
                    'field_order' => 20,
                ]);

                \App\Vendor\Telenok\Core\Model\Object\Sequence::where('sequences_object_type', $model->getKey())
                        ->update(['treeable' => 1]);
            }
        }
        else
        {
            \App\Vendor\Telenok\Core\Model\Object\Field::where('field_object_type', $model->getKey())->where('key', 'tree')->forceDelete();

            \App\Vendor\Telenok\Core\Model\Object\Sequence::where('sequences_object_type', $model->getKey())
                    ->update(['treeable' => 0]);
        }

        if (!\App\Vendor\Telenok\Core\Model\Object\Field::where('field_object_type', $model->getKey())->where('code', 'active')->exists())
        {
            (new \App\Vendor\Telenok\Core\Model\Object\Field())->storeOrUpdate([
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
                'multilanguage' => 1,
                'show_in_form' => 1,
                'show_in_list' => 0,
                'allow_search' => 1,
                'allow_create' => 1,
                'allow_update' => 1,
                'field_order' => 3,
            ]);
        }

        if (!\App\Vendor\Telenok\Core\Model\Object\Field::where('field_object_type', $model->getKey())->where('code', 'active_at')->exists())
        {
            (new \App\Vendor\Telenok\Core\Model\Object\Field())->storeOrUpdate([
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

        if (!\App\Vendor\Telenok\Core\Model\Object\Field::where('field_object_type', $model->getKey())->where('key', 'permission')->exists())
        {
            (new \App\Vendor\Telenok\Core\Model\Object\Field())->storeOrUpdate([
                'key' => 'permission',
                'field_object_type' => $model->getKey(),
                'show_in_list' => 0,
                'show_in_form' => 1,
                'field_order' => 10,
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
