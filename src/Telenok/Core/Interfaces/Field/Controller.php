<?php namespace Telenok\Core\Interfaces\Field;

/**
 * @class Telenok.Core.Interfaces.Field.Controller
 * Base class for fields.
 * 
 * @uses Telenok.Core.Interfaces.Field.IField
 * @extends Telenok.Core.Interfaces.Controller.Controller
 */
class Controller extends \Telenok\Core\Interfaces\Controller\Controller implements \Telenok\Core\Interfaces\Field\IField {

    /**
     * @protected
     * @property {Array} $ruleList
     * List of rules for special field's attributes.
     * @member Telenok.Core.Interfaces.Field.Controller
     */
    protected $ruleList = [];
    
    /**
     * @protected
     * @property {Array} $specialField
     * List of names for special field's attributes.
     * @member Telenok.Core.Interfaces.Field.Controller
     */
    protected $specialField = [];
    
    /**
     * @protected
     * @property {Array} $specialDateField
     * List of names for special date field's attributes.
     * @member Telenok.Core.Interfaces.Field.Controller
     */
    protected $specialDateField = [];
    
    /**
     * @protected
     * @property {Boolean} $allowMultilanguage
     * Can be field multilanguage.
     * @member Telenok.Core.Interfaces.Field.Controller
     */
    protected $allowMultilanguage = true;
    
    /**
     * @protected
     * @static
     * @property {Integer} $pageLength
     * Amount of rows to show in table for relation's fields.
     * @member Telenok.Core.Interfaces.Field.Controller
     */
    protected $pageLength = 5;
    
    /**
     * @protected
     * @property {String} $viewModel
     * View name for show model's form.
     * @member Telenok.Core.Interfaces.Field.Controller
     */
    protected $viewModel;
    
    /**
     * @protected
     * @property {String} $viewField
     * View name for show field's form.
     * @member Telenok.Core.Interfaces.Field.Controller
     */
    protected $viewField;
    
    /**
     * @protected
     * @property {String} $viewFilter
     * View name for show field's filter.
     * @member Telenok.Core.Interfaces.Field.Controller
     */
    protected $viewFilter;
    
    /**
     * @protected
     * @property {String} $routeListTable
     * Name of router to list data.
     * @member Telenok.Core.Interfaces.Field.Controller
     */
    protected $routeListTable;
    
    /**
     * @protected
     * @property {String} $routeListTitle
     * Name of router to list title.
     * @member Telenok.Core.Interfaces.Field.Controller
     */
    protected $routeListTitle;
    
    /**
     * @protected
     * @property {String} $routeWizardCreate
     * Name of router to creating wizard (modal window).
     * @member Telenok.Core.Interfaces.Field.Controller
     */
    protected $routeWizardCreate;
    
    /**
     * @protected
     * @property {String} $routeWizardEdit
     * Name of router to editing wizard (modal window).
     * @member Telenok.Core.Interfaces.Field.Controller
     */
    protected $routeWizardEdit;
    
    /**
     * @protected
     * @property {String} $routeWizardChoose
     * Name of router to choosing wizard (modal window).
     * @member Telenok.Core.Interfaces.Field.Controller
     */
    protected $routeWizardChoose;
    
    /**
     * @protected
     * @property {String} $languageDirectory
     * Default language directory for fields.
     * @member Telenok.Core.Interfaces.Field.Controller
     */
    protected $languageDirectory = 'field';

    /**
     * @method getViewModel
     * Return view name for model's form.
     * 
     * @return {String}
     * @member Telenok.Core.Interfaces.Field.Controller
     */
    public function getViewModel()
    {
        return $this->viewModel ? : "{$this->getPackage()}::field.{$this->getKey()}.model";
    }

    /**
     * @method setViewModel
     * Set view name for model's form.
     * 
     * @param {Telenok.Core.Model.Object.Field} $field
     * Object with data of field's configuration.
     * @param {String} $templateView
     * Optional view.
     * @param {String} $templateKey
     * Optional view key. Used for fields like select box etc, which can be presents in many ways.
     * @return {Telenok.Core.Interfaces.Field.Relation.Controller}
     * @member Telenok.Core.Interfaces.Field.Controller
     */
    public function setViewModel($field = null, $templateView = null, $templateKey = null)
    {
        $viewObj = app('view');

        $fieldView = '';
        $defaultTemplate = $this->viewModel? : "{$this->getPackage()}::field.{$this->getKey()}.model";

        if ($field instanceof \Telenok\Core\Model\Object\Field && $viewObj->exists($field->field_view))
        {
            $fieldView = $field->field_view;
        }

        if ($templateView && $viewObj->exists($templateView))
        {
            $this->viewModel = $templateView;
        }
        else if ($templateKey && $viewObj->exists($t = ($fieldView ? : $defaultTemplate) . '-' . $templateKey))
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

    /**
     * @method getViewField
     * Return view name for field's form.
     * 
     * @return {String}
     * @member Telenok.Core.Interfaces.Field.Controller
     */
    public function getViewField()
    {
        return $this->viewField ? : $this->getPackage() . "::field.{$this->getKey()}.field";
    }

    /**
     * @method getViewFilter
     * Return view name for filter's form.
     * 
     * @return {String}
     * @member Telenok.Core.Interfaces.Field.Controller
     */
    public function getViewFilter()
    {
        return $this->viewFilter ? : $this->getPackage() . "::field.{$this->getKey()}.filter";
    }

    /**
     * @method getRouteListTable
     * Return router's name for list in table.
     * 
     * @return {String}
     * @member Telenok.Core.Interfaces.Field.Controller
     */
    public function getRouteListTable()
    {
        return $this->routeListTable ? : $this->getVendorName() . ".field.{$this->getKey()}.list.table";
    }

    /**
     * @method getRouteListTitle
     * Return router's name for title's list.
     * 
     * @return {String}
     * @member Telenok.Core.Interfaces.Field.Controller
     */
    public function getRouteListTitle()
    {
        return $this->routeListTitle ? : $this->getVendorName() . ".field.{$this->getKey()}.list.title";
    }

    /**
     * @method getRouteWizardCreate
     * Return router's name for modal's creating wizard.
     * 
     * @return {String}
     * @member Telenok.Core.Interfaces.Field.Controller
     */
    public function getRouteWizardCreate()
    {
        return $this->routeWizardCreate ? : 'telenok.module.objects-lists.wizard.create';
    }

    /**
     * @method getRouteWizardEdit
     * Return router's name for modal's editing wizard.
     * 
     * @return {String}
     * @member Telenok.Core.Interfaces.Field.Controller
     */
    public function getRouteWizardEdit()
    {
        return $this->routeWizardEdit ? : 'telenok.module.objects-lists.wizard.edit';
    }

    /**
     * @method getRouteWizardChoose
     * Return router's name for modal's choosing wizard.
     * 
     * @return {String}
     * @member Telenok.Core.Interfaces.Field.Controller
     */
    public function getRouteWizardChoose()
    {
        return $this->routeWizardChoose ? : 'telenok.module.objects-lists.wizard.choose';
    }

    /**
     * @method getSpecialField
     * Return list with names of special fields.
     * 
     * @param {Telenok.Core.Model.Object.Field} $model
     * Object with data of field's configuration.
     * @return {Array}
     * @member Telenok.Core.Interfaces.Field.Controller
     */
    public function getSpecialField($model)
    {
        return $this->specialField;
    }

    /**
     * @method getModelField
     * Return names of field for $model.
     * 
     * @param {Telenok.Core.Interfaces.Eloquent.Object.Model} $model
     * Eloquent object.
     * @param {Telenok.Core.Model.Object.Field} $field
     * Object with data of field's configuration.
     * @return {Array}
     * @member Telenok.Core.Interfaces.Field.Controller
     */
    public function getModelField($model, $field)
    {
        return $this->getModelFillableField($model, $field);
    }

    /**
     * @method getModelFillableField
     * Return fillable names of field for $model.
     * 
     * @param {Telenok.Core.Interfaces.Eloquent.Object.Model} $model
     * Eloquent object.
     * @param {Telenok.Core.Model.Object.Field} $field
     * Object with data of field's configuration.
     * @return {Array}
     * @member Telenok.Core.Interfaces.Field.Controller
     */
    public function getModelFillableField($model, $field)
    {
        return [$field->code];
    }

    /**
     * @method getDateField
     * Return list names of date field for $model.
     * 
     * @param {Telenok.Core.Interfaces.Eloquent.Object.Model} $model
     * Eloquent object.
     * @param {Telenok.Core.Model.Object.Field} $field
     * Object with data of field's configuration.
     * @return {Array}
     * @member Telenok.Core.Interfaces.Field.Controller
     */
    public function getDateField($model, $field)
    {
        return [];
    }

    /**
     * @method getSpecialDateField
     * Return list names of date field for special field's attributes.
     * 
     * @param {Telenok.Core.Model.Object.Field} $model
     * Object with data of field's configuration.
     * @return {Array}
     * @member Telenok.Core.Interfaces.Field.Controller
     */
    public function getSpecialDateField($model)
    {
        return $this->specialDateField;
    }

    /**
     * @method getRule
     * Return list of rules for field.
     * 
     * @param {Telenok.Core.Model.Object.Field} $field
     * Object with data of field's configuration.
     * @return {Array}
     * @member Telenok.Core.Interfaces.Field.Controller
     */
    public function getRule($field = null)
    {
        return $this->ruleList;
    }

    /**
     * @method getModelAttribute
     * Return value of field's attributes.
     * 
     * @param {Telenok.Core.Interfaces.Eloquent.Object.Model} $model
     * Eloquent object.
     * @param {String} $key
     * Code of field in $model.
     * @param {mixed} $value
     * @param {Telenok.Core.Model.Object.Field} $field
     * Object with data of field's configuration.
     * @return {mixed}
     * @member Telenok.Core.Interfaces.Field.Controller
     */
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

    /**
     * @method setModelAttribute
     * Set value for field.
     * 
     * @param {Telenok.Core.Interfaces.Eloquent.Object.Model} $model
     * Eloquent object.
     * @param {String} $key
     * Code of field in $model.
     * @param {mixed} $value
     * @param {Telenok.Core.Model.Object.Field} $field
     * Object with data of field's configuration.
     * @return {Telenok.Core.Interfaces.Field.Relation.Controller}
     * @member Telenok.Core.Interfaces.Field.Controller
     */
    public function setModelAttribute($model, $key, $value, $field)
    {
        $model->setAttribute($key, $value);

        return $this;
    }

    /**
     * @method getModelSpecialAttribute
     * Return value of field's attributes.
     * 
     * @param {Telenok.Core.Model.Object.Field} $model
     * Object with data of field's configuration.
     * @param {String} $key
     * Code of field in $model.
     * @param {mixed} $value
     * @return {mixed}
     * @member Telenok.Core.Interfaces.Field.Controller
     */
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

    /**
     * @method getLinkedField
     * @member Telenok.Core.Interfaces.Field.Controller
     */
    public function setModelSpecialAttribute($model, $key, $value)
    {
        $model->setAttribute($key, $value);

        return $this;
    }

    /**
     * @method getFormModelContent
     * @member Telenok.Core.Interfaces.Field.Controller
     */
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
                                ], (array) $this->getModelFieldViewVariable($controller, $model, $field, $uniqueId), (array) $controller->getModelFieldViewVariable($this, $model, $field, $uniqueId)
                ))->render();
    }

    /**
     * @method getLinkedModelType
     * Return Object Type linked to the field
     * 
     * @param {Telenok.Core.Model.Object.Field} $field
     * Object with data of field's configuration.
     * @return {Telenok.Core.Model.Object.Type}
     * @member Telenok.Core.Interfaces.Field.Controller
     */
    public function getLinkedModelType($field)
    {
    }

    /**
     * @method getModelFieldViewVariable
     * @member Telenok.Core.Interfaces.Field.Controller
     */
    public function getModelFieldViewVariable($controller = null, $model = null, $field = null, $uniqueId = null)
    {
        
    }

    /**
     * @method getTableList
     * @member Telenok.Core.Interfaces.Field.Controller
     */
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

    /**
     * @method getFormModelTableColumn
     * @member Telenok.Core.Interfaces.Field.Controller
     */
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

    /**
     * @method getFormFieldContent
     * @member Telenok.Core.Interfaces.Field.Controller
     */
    public function getFormFieldContent($model = null, $uniqueId = null)
    {
        return view($this->getViewField(), array(
                    'controller' => $this,
                    'model' => $model,
                    'uniqueId' => $uniqueId,
                ))->render();
    }

    /**
     * @method getFilterQuery
     * @member Telenok.Core.Interfaces.Field.Controller
     */
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

    /**
     * @method getFilterContent
     * @member Telenok.Core.Interfaces.Field.Controller
     */
    public function getFilterContent($field = null)
    {
        return '<input type="text" name="filter[' . $field->code . ']" value="" />';
    }

    /**
     * @method getListFieldContent
     * @member Telenok.Core.Interfaces.Field.Controller
     */
    public function getListFieldContent($field, $item, $type = null)
    {
        return e(\Str::limit($item->translate((string) $field->code), 20));
    }

    /**
     * @method validate
     * @member Telenok.Core.Interfaces.Field.Controller
     */
    public function validate($model = null, $input = [], $messages = [])
    {
        $validator = $this->validator($this, $input, array_merge($messages, $this->LL('error')));

        if ($validator->fails())
        {
            throw $this->validateException()->setMessageError($validator->messages());
        }

        return $this;
    }

    /**
     * @method validateMethodExists
     * @member Telenok.Core.Interfaces.Field.Controller
     */
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

    /**
     * @method fill
     * @member Telenok.Core.Interfaces.Field.Controller
     */
    public function fill($field, $model, $input)
    {
        return $this;
    }

    /**
     * @method saveModelField
     * @member Telenok.Core.Interfaces.Field.Controller
     */
    public function saveModelField($field, $model, $input)
    {
        return $model;
    }

    /**
     * @method updateModelFile
     * @member Telenok.Core.Interfaces.Field.Controller
     */
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

    /**
     * @method validator
     * @member Telenok.Core.Interfaces.Field.Controller
     */
    public function validator($model = null, $input = [], $message = [], $customAttribute = [])
    {
        return app('\Telenok\Core\Support\Validator\Model')
                        ->setModel($model)
                        ->setInput($input)
                        ->setMessage($message)
                        ->setCustomAttribute($customAttribute);
    }

    /**
     * @method validateException
     * @member Telenok.Core.Interfaces.Field.Controller
     */
    public function validateException()
    {
        return new \Telenok\Core\Support\Exception\Validator;
    }

    /**
     * @method preProcess
     * @member Telenok.Core.Interfaces.Field.Controller
     */
    public function preProcess($model, $type, $input)
    {
        $tab = $this->getFieldTab($input->get('field_object_type'), $input->get('field_object_tab', 'main'));

        $input->put('field_object_tab', $tab->getKey());

        return $this;
    }

    /**
     * @method postProcess
     * @member Telenok.Core.Interfaces.Field.Controller
     */
    public function postProcess($model, $type, $input)
    {
        return $this;
    }

    /**
     * @method processFieldDelete
     * @member Telenok.Core.Interfaces.Field.Controller
     */
    public function processFieldDelete($model, $type)
    {
        try
        {
            \Schema::table($type->code, function($table) use ($model)
            {
                $table->dropColumn($model->code);
            });
        }
        catch (\Exception $e)
        {
            
        }

        return true;
    }

    /**
     * @method processModelDelete
     * @member Telenok.Core.Interfaces.Field.Controller
     */
    public function processModelDelete($model, $force)
    {
        return true;
    }

    /**
     * @method allowMultilanguage
     * @member Telenok.Core.Interfaces.Field.Controller
     */
    public function allowMultilanguage()
    {
        return $this->allowMultilanguage;
    }

    /**
     * @method getMultilanguage
     * @member Telenok.Core.Interfaces.Field.Controller
     */
    public function getMultilanguage($model, $field)
    {
        if ($field->multilanguage)
        {
            return [$field->code];
        }
    }

    /**
     * @method getFieldTab
     * @member Telenok.Core.Interfaces.Field.Controller
     */
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

    /**
     * @method getFieldTabBelongTo
     * @member Telenok.Core.Interfaces.Field.Controller
     */
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

    /**
     * @method getTitleList
     * @member Telenok.Core.Interfaces.Field.Controller
     */
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
                        ->on('object_translation.translation_object_language', '=', app('db')->raw("'" . config('app.locale') . "'"));
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

    /**
     * @method getStubFileDirectory
     * @member Telenok.Core.Interfaces.Field.Controller
     */
    public function getStubFileDirectory()
    {
        return __DIR__;
    }
}