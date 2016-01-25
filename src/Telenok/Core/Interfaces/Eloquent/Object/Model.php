<?php

namespace Telenok\Core\Interfaces\Eloquent\Object;

use \Telenok\Core\Interfaces\Controller\IEloquentProcessController;

class Model extends \Illuminate\Database\Eloquent\Model {

    use \Illuminate\Database\Eloquent\SoftDeletes;

use \Telenok\Core\Interfaces\Eloquent\Cache\QueryCache;

    public $incrementing = false;
    public $timestamps = true;
    protected $hasVersioning = true;
    protected $ruleList = [];
    protected $multilanguageList = [];
    protected $dates = [];
    protected static $listField = [];
    protected static $listRule = [];
    protected static $listAllFieldController = [];
    protected static $listFillableFieldController = [];
    protected static $listMultilanguage = [];
    protected static $listFieldDate = [];
    protected static $macros = [];

    /**
     * Register a custom macro.
     *
     * @param  string    $name
     * @param  callable  $macro
     * @return void
     */
    public static function macro($name, callable $macro)
    {
        static::$macros[$name] = $macro;
    }

    /**
     * Checks if macro is registered.
     *
     * @param  string  $name
     * @return bool
     */
    public static function hasMacro($name)
    {
        return isset(static::$macros[$name]);
    }

    /**
     * Dynamically handle calls to the class.
     *
     * @param  string  $method
     * @param  array   $parameters
     * @return mixed
     *
     * @throws \BadMethodCallException
     */
    public static function __callStatic($method, $parameters)
    {
        if (static::hasMacro($method))
        {
            if (static::$macros[$method] instanceof Closure)
            {
                return call_user_func_array(\Closure::bind(static::$macros[$method], null, get_called_class()), $parameters);
            }
            else
            {
                return call_user_func_array(static::$macros[$method], $parameters);
            }
        }

        return parent::__callStatic($method, $parameters);
    }

    /**
     * Dynamically handle calls to the class.
     *
     * @param  string  $method
     * @param  array   $parameters
     * @return mixed
     *
     * @throws \BadMethodCallException
     */
    public function __call($method, $parameters)
    {
        if (static::hasMacro($method))
        {
            if (static::$macros[$method] instanceof \Closure)
            {
                return static::$macros[$method]->call($this, $parameters);
            }
            else
            {
                return call_user_func_array(static::$macros[$method], $parameters);
            }
        }

        return parent::__call($method, $parameters);
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function($model)
        {
            $model->generateKeyId();
        });

        static::saved(function($model)
        {
            $model->translateSync();
        });

        static::deleting(function($model)
        {
            if (!($model instanceof \Telenok\Core\Model\Object\Sequence))
            {
                if ($model->hasVersioning())
                {
                    \App\Telenok\Core\Model\Object\Version::add($model);
                }

                $model->deleteModelFieldController();
            }
        });

        static::deleted(function($model)
        {
            if (!($model instanceof \Telenok\Core\Model\Object\Sequence))
            {
                $model->deleteSequence();
            }
        });

        static::restoring(function($model)
        {
            if (!($model instanceof \Telenok\Core\Model\Object\Sequence))
            {
                if ($model->hasVersioning())
                {
                    $model->restoreSequence();
                }
            }
        });
    }

    protected function generateKeyId()
    {
        if (!($this instanceof \Telenok\Core\Model\Object\Sequence))
        {
            if ($this->getKey())
            {
                $sequence = new \App\Telenok\Core\Model\Object\Sequence();
                $sequence->id = $this->getKey();
                $sequence->class_model = get_class($this);
                $sequence->save();
            }
            else
            {
                $sequence = \App\Telenok\Core\Model\Object\Sequence::create(['class_model' => get_class($this)]);
            }

            $this->id = $sequence->id;
        }
    }

    protected function restoreSequence()
    {
        if (!($this instanceof \Telenok\Core\Model\Object\Sequence))
        {
            \App\Telenok\Core\Model\Object\Sequence::withTrashed()->find($this->getKey())->restore();
        }
    }

    protected function deleteSequence()
    {
        $sequence = \App\Telenok\Core\Model\Object\Sequence::withTrashed()->find($this->getKey());

        if ($this->forceDeleting)
        {
            $sequence->forceDelete();
        }
        else
        {
            $sequence->delete();
        }
    }

    protected function deleteModelFieldController()
    {
        $controllers = app('telenok.config.repository')->getObjectFieldController();

        foreach ($this->getObjectField()->all() as $field)
        {
            if ($controller = $controllers->get($field->key))
            {
                $controller->processModelDelete($this, $this->forceDeleting);
            }
        }
    }

    public function restore()
    {
        if (app('auth')->can('delete', $this->getKey()))
        {
            app('db')->transaction(function()
            {
                parent::restore();
            });
        }
        else
        {
            throw new \Telenok\Core\Support\Exception\ModelProcessAccessDenied();
        }
    }

    public function delete()
    {
        if (app('auth')->can('delete', $this->getKey()))
        {
            app('db')->transaction(function()
            {
                if ($this->trashed())
                {
                    parent::delete();
                }
                else if (app('auth')->check())
                {
                    $this->deleted_by_user = app('auth')->user()->id;
                    $this->save();
                }

                parent::delete();
            });
        }
        else
        {
            throw new \Telenok\Core\Support\Exception\ModelProcessAccessDenied();
        }
    }

    protected function translateSync()
    {
        if (!($this instanceof \Telenok\Core\Model\Object\Sequence))
        {
            \App\Telenok\Core\Model\Object\Translation::where('translation_object_model_id', $this->getKey())->forceDelete();

            foreach ($this->getMultilanguage() as $fieldCode)
            {
                $value = $this->{$fieldCode}->all();

                foreach ($value as $language => $string)
                {
                    \App\Telenok\Core\Model\Object\Translation::create([
                        'translation_object_model_id' => $this->getKey(),
                        'translation_object_field_code' => $fieldCode,
                        'translation_object_language' => $language,
                        'translation_object_string' => $string,
                    ]);
                }
            }

            $type = $this->type();

            $this->sequence()->first()->fill([
                'title' => ($this->title instanceof \Illuminate\Support\Collection ? $this->title->all() : $this->title),
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at,
                'deleted_at' => $this->deleted_at,
                'active' => $this->active,
                'active_at_start' => $this->active_at_start,
                'active_at_end' => $this->active_at_end,
                'created_by_user' => $this->created_by_user,
                'updated_by_user' => $this->updated_by_user,
                'sequences_object_type' => $type->getKey(),
                'treeable' => $type->treeable,
            ])->save();
        }
    }

    public function sequence()
    {
        return $this->hasOne('\App\Telenok\Core\Model\Object\Sequence', 'id');
    }

    public function type()
    {
        return \App\Telenok\Core\Model\Object\Type::whereCode($this->getTable())->first();
    }

    public function hasVersioning()
    {
        return $this->hasVersioning;
    }

    public function classController()
    {
        return $this->class_controller;
    }

    public function treeForming()
    {
        return $this->type()->treeable;
    }

    public static function eraseStatic($model)
    {
        $class = get_class($model);

        static::$listRule[$class] = null;
        static::$listField[$class] = null;
        static::$listAllFieldController[$class] = null;
        static::$listFillableFieldController[$class] = null;
        static::$listMultilanguage[$class] = null;

        $model->getObjectField();
        $model->getFillable();
        $model->getMultilanguage();
        $model->getDates();
        $model->getRule();
    }

    public function fill(array $attributes)
    {
        foreach ($this->fillableFromArray($attributes) as $key => $value)
        {
            $key = $this->removeTableFromKey($key);

            if ($this->isFillable($key))
            {
                $this->__set($key, $value);
            }
        }

        return $this;
    }

    public function addFillable($attributes)
    {
        $this->fillable = array_unique(array_merge($this->fillable, (array) $attributes));

        return $this;
    }

    protected function fillableFromArray(array $attributes)
    {
        $this->fillable = array_unique(array_merge($this->fillable, $this->getFillable()));

        return parent::fillableFromArray($attributes);
    }

    public function storeOrUpdate($input = [], $withPermission = false, $withEvent = true)
    {
        if ($this instanceof \App\Telenok\Core\Model\Object\Sequence)
        {
            throw new \Telenok\Core\Support\Exception\ModelProcessAccessDenied('Cant storeOrUpdate sequence model directly');
        }

        try
        {
            $type = $this->type();
        }
        catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e)
        {
            throw new \Telenok\Core\Support\Exception\ModelProcessAccessDenied("Telenok\Core\Interfaces\Eloquent\Object\Model::storeOrUpdate() - Error: 'type of object not found, please, define it'");
        }

        // merge attributes if model filled via $model->fill() and plus ->storeOrUpdate([some attributes])
        $t = [];

        foreach ($this->getAttributes() as $k => $v)
        {
            $t[$k] = $this->{$k};
        }

        $input = collect($t)->merge($input);

        try
        {
            if (!$this->exists)
            {
                $model = $this->findOrFail($input->get($this->getKeyName()));

                // if model exists - add its attributes to $input for process all attributes in events
                $t = [];

                foreach ($model->getAttributes() as $k => $v)
                {
                    $t[$k] = $model->{$k};
                }

                $input = collect($t)->merge($input);
            }
            else
            {
                $model = $this;
            }
        }
        catch (\Exception $ex)
        {
            $model = new static();
        }

        if ($withPermission)
        {
            $model->validateStoreOrUpdatePermission($type, $input);
        }

        foreach ($model->fillable as $fillable)
        {
            if ($input->has($fillable))
            {
                
            }
            else if (!$model->exists)
            {
                $model->{$fillable} = null;
                $input->put($fillable, null);
            }
            else
            {
                //$input->put($fillable, $model->{$fillable});
            }
        }

        try
        {
            app('db')->transaction(function() use ($type, $input, $model, $withEvent)
            {
                $controllerProcessing = null;

                $exists = $model->exists;

                if ($withEvent)
                {
                    //\Event::fire('workflow.' . ($exists ? 'update' : 'store') . '.before', (new \App\Telenok\Core\Workflow\Event())->setResource($model)->setInput($input));
                }

                if (($c = $type->classController()) && ($controllerProcessing = app($c)) && $controllerProcessing instanceof IEloquentProcessController)
                {
                    $controllerProcessing->preProcess($model, $type, $input);
                }

                $model->preProcess($type, $input);

                $validator = app('\App\Telenok\Core\Interfaces\Validator\Model')
                        ->setModel($model)
                        ->setInput($input)
                        ->setMessage($this->LL('error'))
                        ->setCustomAttribute($this->validatorCustomAttributes());

                if ($validator->fails())
                {
                    throw (new \Telenok\Core\Support\Exception\Validator())->setMessageError($validator->messages());
                }

                if ($controllerProcessing instanceof IEloquentProcessController)
                {
                    $controllerProcessing->validate($model, $input);
                }

                $model->fill($input->all())->push();

                if (!$exists && $type->treeable)
                {
                    $model->makeRoot();
                }

                $model->postProcess($type, $input);

                if ($controllerProcessing instanceof IEloquentProcessController)
                {
                    $controllerProcessing->postProcess($model, $type, $input);
                }

                if ($withEvent)
                {
                    //\Event::fire('workflow.' . ($exists ? 'update' : 'store') . '.after', (new \App\Telenok\Core\Workflow\Event())->setResource($model)->setInput($input));
                }
            });
        }
        catch (\Telenok\Core\Support\Exception\Validator $e)
        {
            throw $e;
        }
        catch (\Exception $e)
        {
            throw $e;
        }

        return $model;
    }

    protected function validatorCustomAttributes()
    {
        static $attr = null;

        if (!isset($attr))
        {
            $attr = [];

            $attr['table'] = $this->getTable();

            foreach ($this->getFieldForm() as $field)
            {
                $attr[$field->code] = $field->translate('title');
            }
        }

        return $attr;
    }

    protected function validateStoreOrUpdatePermission($type = null, $input = null)
    {
        if (!$type)
        {
            $type = $this->type();
        }

        if (!$this->exists && !app('auth')->can('create', "object_type.{$type->code}"))
        {
            throw new \LogicException('Cant create model with type "' . $type->code . '". Access denied.');
        }
        else if ($this->exists && !app('auth')->can('update', $this->getKey()))
        {
            throw new \LogicException('Cant update model with type "' . $type->code . '". Access denied.');
        }

        $objectField = $this->getObjectField();

        foreach ($input->all() as $key => $value)
        {
            $f = $objectField->get($key);
            $f_ = app('telenok.config.repository')->getObjectFieldController();

            if ($f)
            {
                if (
                        (!$this->exists && !app('auth')->can('create', 'object_field.' . $type->code . '.' . $key)) ||
                        ($this->exists && !app('auth')->can('update', 'object_field.' . $type->code . '.' . $key))
                )
                {
                    $input->forget($key);
                }
            }
            else
            {
                if ($this instanceof \Telenok\Core\Model\Object\Field && ($fieldController = $f_->get($this->key)) && (in_array($key, $fieldController->getSpecialField($this), true) || in_array($key, $fieldController->getSpecialDateField($this), true)) &&
                        (
                        (!$this->exists && !app('auth')->can('create', 'object_type.object_field')) ||
                        ($this->exists && !app('auth')->can('update', $this->getKey()))
                        )
                )
                {
                    $input->forget($key);
                }
                else
                {
                    foreach ($objectField->all() as $key_ => $field_)
                    {
                        $fieldController = $f_->get($field_->key);

                        if ($fieldController && (in_array($key, $fieldController->getModelFillableField($this, $field_), true) || in_array($key, $fieldController->getDateField($this, $field_), true)) &&
                                (
                                (!$this->exists && !app('auth')->can('create', 'object_field.' . $type->code . '.' . $key_)) ||
                                ($this->exists && !app('auth')->can('update', 'object_field.' . $type->code . '.' . $key_))
                                )
                        )
                        {
                            $input->forget($key);
                        }
                    }
                }
            }
        }
    }

    public function preProcess($type, $input)
    {
        $config = app('telenok.config.repository')->getObjectFieldController();

        foreach ($type->field()->get() as $field)
        {
            $config->get($field->key)->fill($field, $this, $input);
        }

        return $this;
    }

    public function postProcess($type, $input)
    {
        $config = app('telenok.config.repository')->getObjectFieldController();

        foreach ($type->field()->get() as $field)
        {
            $config->get($field->key)->saveModelField($field, $this, $input);
        }
        
        if ($this->hasVersioning())
        {
            \App\Telenok\Core\Model\Object\Version::add($this);
        }

        return $this;
    }

    public function __get($key)
    {
        try
        {
            $value = parent::__get($key);
        }
        catch (\Exception $e)
        {
            $value = null;
        }

        return $this->getModelAttributeController($key, $value);
    }

    public function __set($key, $value)
    {
        $class = get_class($this);

        if (isset(static::$listAllFieldController[$class][$key]))
        {
            $this->setModelAttributeController($key, $value);
        }
        else
        {
            parent::__set($key, $value);
        }
    }

    public function getModelAttributeController($key, $value)
    {
        $class = get_class($this);

        if (isset(static::$listAllFieldController[$class][$key]))
        {
            return static::$listAllFieldController[$class][$key]->getModelAttribute($this, $key, $value, $this->getObjectField()->get($key));
        }
        else
        {
            return $value;
        }
    }

    public function setModelAttributeController($key, $value)
    {
        $class = get_class($this);

        $f = static::$listAllFieldController[$class][$key];

        $f->setModelAttribute($this, $key, $value, $this->getObjectField()->get($key));
    }

    public function getObjectField()
    {
        $class = get_class($this);

        if (!isset(static::$listField[$class]))
        {
            $r = range_minutes($this->getCacheMinutes());

            $type = app('db')->table('object_type')->whereNull('deleted_at')->where('code', $this->getTable())->first();

            $f = app('db')->table('object_field')
                    ->where('field_object_type', $type->id)
                    ->whereNull('deleted_at')
                    ->where('active', '=', 1)
                    ->where('active_at_start', '<=', $r[1])
                    ->where('active_at_end', '>=', $r[0])
                    ->get();

            static::$listField[$class] = collect(array_combine(array_pluck($f, 'code'), $f));
        }

        return static::$listField[$class];
    }

    public function getFieldList()
    {
        $type = $this->type();

        return $type->field()->active()->get()->filter(function($item) use ($type)
                {
                    return $item->show_in_list == 1 && app('auth')->can('read', 'object_field.' . $type->code . '.' . $item->code);
                });
    }

    public function getFieldForm()
    {
        $type = $this->type();

        return $type->field()->active()->get()->filter(function($item) use ($type)
                {
                    return $item->show_in_form == 1 && app('auth')->can('read', 'object_field.' . $type->code . '.' . $item->code);
                });
    }

    public function getMultilanguage()
    {
        $class = get_class($this);

        if (!isset(static::$listMultilanguage[$class]))
        {
            static::$listMultilanguage[$class] = (array) $this->multilanguageList;

            $fields = app('telenok.config.repository')->getObjectFieldController();

            foreach ($this->getObjectField()->all() as $key => $field)
            {
                $controller = $fields->get($field->key);

                if ($controller)
                {
                    static::$listMultilanguage[$class] = array_merge(static::$listMultilanguage[$class], (array) $controller->getMultilanguage($this, $field));
                }
            }
        }

        return static::$listMultilanguage[$class];
    }

    public function addMultilanguage($fieldCode)
    {
        $class = get_class($this);

        static::$listMultilanguage[$class][] = $fieldCode;

        static::$listMultilanguage[$class] = array_unique(static::$listMultilanguage[$class]);

        return $this;
    }

    public function getDates()
    {
        return array_merge(parent::getDates(), $this->dates);
    }

    public function getFillable()
    {
        $class = get_class($this);

        if (!isset(static::$listFillableFieldController[$class]))
        {
            static::$listAllFieldController[$class] = [];
            static::$listFillableFieldController[$class] = [];
            static::$listFieldDate[$class] = [];

            $controllers = app('telenok.config.repository')->getObjectFieldController();

            foreach ($this->getObjectField()->all() as $key => $field)
            {
                if ($controller = $controllers->get($field->key))
                {
                    $dateField = (array) $controller->getDateField($this, $field);
                    static::$listFieldDate[$class] = array_merge(static::$listFieldDate[$class], $dateField);

                    foreach (array_merge((array) $controller->getModelFillableField($this, $field), $dateField) as $f_)
                    {
                        static::$listFillableFieldController[$class][$f_] = $controller;
                        static::$listField[$class][$f_] = $field;
                    }

                    foreach ((array) $controller->getModelField($this, $field) as $f_)
                    {
                        static::$listAllFieldController[$class][$f_] = $controller;
                    }
                }
            }
        }


        $this->dates = array_merge($this->getDates(), (array) static::$listFieldDate[$class]);

        return array_keys((array) static::$listFillableFieldController[$class]);
    }

    public function getRule()
    {
        $class = get_class($this);

        if (!isset(static::$listRule[$class]))
        {
            static::$listRule[$class] = [];

            foreach ($this->ruleList as $key => $value)
            {
                foreach ($value as $key_ => $value_)
                {
                    static::$listRule[$class][$key][head(explode(':', $value_))] = $value_;
                }
            }

            foreach ($this->type()->field()->active()->get() as $key => $field)
            {
                if ($field->rule instanceof \Illuminate\Support\Collection)
                {
                    foreach ($field->rule->all() as $key => $value)
                    {
                        static::$listRule[$class][$field->code][head(explode(':', $value))] = $value;
                    }
                }
            }
        }

        return static::$listRule[$class];
    }

    public function translate($field, $locale = '')
    {
        $locale = $locale ? : config('app.locale');

        if ($this->{$field} instanceof \Illuminate\Support\Collection)
        {
            $translated = $this->{$field}->get($locale);

            return $translated ? : $this->{$field}->get(app('config')->get('app.localeDefault'));
        }
        else if (($this->{$field} instanceof \ArrayAccess && ($v = $this->{$field})) || (($v = json_decode($this->{$field}, true)) && json_last_error() === JSON_ERROR_NONE))
        {
            if (isset($v[$locale]))
            {
                return $v[$locale];
            }
            else if (isset($v[config('app.localeDefault')]))
            {
                return $v[config('app.localeDefault')];
            }
            else
            {
                return $this->{$field};
            }
        }
        else
        {
            return $this->{$field};
        }
    }

    public function scopeActive($query, $table = null)
    {
        $table = $table ? : $this->getTable();
        $r = range_minutes($this->getCacheMinutes());

        return $query->where(function($query) use ($table, $r)
                {
                    $query->whereNull($table . '.deleted_at')
                            ->where($table . '.active', 1)
                            ->where($table . '.active_at_start', '<=', $r[1])
                            ->where($table . '.active_at_end', '>=', $r[0]);
                });
    }

    public function scopeNotActive($query, $table = null)
    {
        $table = $table ? : $this->getTable();
        $r = range_minutes($this->getCacheMinutes());

        return $query->where(function($query) use ($table, $r)
                {
                    $query->where($table . '.active', 0)
                            ->orWhere($table . '.active_at_start', '>=', $r[1])
                            ->orWhere($table . '.active_at_end', '<=', $r[0]);
                });
    }

    public function scopeTranslateField($query, $linkedTableAlias = '', $translateTableAlias = '', $translateField = '', $language = '')
    {
        $translateModel = app('\App\Telenok\Core\Model\Object\Translation');

        $translateTableAlias = $translateTableAlias ? : $translateModel->getTable();

        $query->leftJoin($translateModel->getTable() . ' as ' . $translateTableAlias, function($join)
                use ($linkedTableAlias, $translateTableAlias, $translateField, $language)
        {
            $join->on($linkedTableAlias . '.id', '=', $translateTableAlias . '.translation_object_model_id')
                    ->on($translateTableAlias . '.translation_object_field_code', '=', app('db')->raw("'" . $translateField . "'"))
                    ->on($translateTableAlias . '.translation_object_language', '=', app('db')->raw("'" . ($language ? : config('app.locale')) . "'"));
        });
    }

    // ->permission() - can current user read (read - by default)
    // ->permission('write', null) - can current user read
    // ->permission(null, $someObject) - can $someObject read 
    // ->permission(null, 'user_authorized') - can authorized user read 
    // ->permission('read', 'user_any') - can anybody read
    // ->permission('read', 'user_authorized', ['object-type', 'own'])
    public function scopeWithPermission($query, $permissionCode = 'read', $subjectCode = null, $filterCode = null)
    {
        if (!config('app.acl.enabled'))
        {
            return $query;
        }

        $subjectCollection = collect();

        try
        {
            if (empty($subjectCode))
            {
                $subjectCollection->push(\App\Telenok\Core\Model\Security\Resource::where('code', 'user_any')->active()->first());

                if (app('auth')->guest())
                {
                    $subjectCollection->push(\App\Telenok\Core\Model\Security\Resource::where('code', 'user_unauthorized')->active()->first());
                }
                else if (app('auth')->check())
                {
                    if (app('auth')->hasRole('super_administrator'))
                    {
                        return $query;
                    }
                    else
                    {
                        $subjectCollection->push(app('auth')->user());
                    }
                }
            }
            else if ($subjectCode instanceof \Illuminate\Database\Eloquent\Model)
            {
                $subjectCollection->push(\App\Telenok\Core\Model\Object\Sequence::where('id', $subjectCode->getKey())->active()->firstOrFail());
            }
            else
            {
                $subjectCollection->push(\App\Telenok\Core\Model\Object\Sequence::where('id', $subjectCode)->active()->firstOrFail());
            }
        }
        catch (\Exception $e)
        {
            
        }

        $permission = \App\Telenok\Core\Model\Security\Permission::where('id', $permissionCode)->orWhere('code', $permissionCode)->active()->first();

        if ($subjectCollection->isEmpty() || !$permission)
        {
            return $query->where($this->getTable() . '.id', 'Error: permission code');
        }

        $r = range_minutes($this->getCacheMinutes());
        $spr = new \App\Telenok\Core\Model\Security\SubjectPermissionResource();
        $sequence = new \App\Telenok\Core\Model\Object\Sequence();
        $type = new \App\Telenok\Core\Model\Object\Type();

        $query->addSelect($this->getTable() . '.*');

        $query->join($sequence->getTable() . ' as osequence', function($join) use ($spr, $permission)
        {
            $join->on($this->getTable() . '.id', '=', 'osequence.id');
        });

        $query->join($type->getTable() . ' as otype', function($join) use ($type, $r)
        {
            $join->on('osequence.sequences_object_type', '=', 'otype.id');
            $join->whereNull('otype.' . $type->getDeletedAtColumn());
            $join->where('otype.active', '=', 1);
            $join->where('otype.active_at_start', '<=', $r[1]);
            $join->where('otype.active_at_end', '>=', $r[0]);
        });

        $query->where(function($queryWhere) use ($query, $filterCode, $permission, $subjectCollection)
        {
            $queryWhere->where(app('db')->raw(1), 0);

            $filters = app('telenok.config.repository')->getAclResourceFilter();

            if (!empty($filterCode))
            {
                $filters = $filters->filter(function($i) use ($filterCode)
                {
                    return in_array($i->getKey(), (array) $filterCode, true);
                });
            }

            $filters->each(function($item) use ($query, $queryWhere, $permission, $subjectCollection)
            {
                $item->filter($query, $queryWhere, $this, $permission, $subjectCollection);
            });
        });

        return $query;
    }

    public function treeParent()
    {
        return $this->belongsToMany('\App\Telenok\Core\Model\Object\Sequence', 'pivot_relation_m2m_tree', 'tree_id', 'tree_pid');
    }

    public function treeChild()
    {
        return $this->belongsToMany('\App\Telenok\Core\Model\Object\Sequence', 'pivot_relation_m2m_tree', 'tree_pid', 'tree_id');
    }

    /* Treeable section */

    public function treeAttr()
    {
        return $this->withTreeAttr()->where($this->getTable() . '.id', $this->getKey())->firstOrFail();
    }

    public function scopeWithTreeAttr($query)
    {
        $query->join('pivot_relation_m2m_tree as pivot_tree_attr', $this->getTable() . '.id', '=', 'pivot_tree_attr.tree_id')
                ->addSelect(['*', $this->getTable() . '.id as id']);
    }

    public function children($depth = 0)
    {
        if ($depth == 1)
        {
            $query = \App\Telenok\Core\Model\Object\Sequence::withTreeAttr()->where('pivot_tree_attr.tree_pid', $this->getKey());
        }
        else
        {
            $model = $this->treeAttr();
            $query = \App\Telenok\Core\Model\Object\Sequence::withTreeAttr();

            if ($depth)
            {
                $query->where('pivot_tree_attr.tree_depth', '<=', $model->tree_depth + $depth);
            }

            $query->where('pivot_tree_attr.tree_path', 'like', $model->tree_path . $this->getKey() . '.%');
        }

        return $query;
    }

    public function scopeWithChildren($query, $depth = 0)
    {
        $query->join('object_sequence as o_tc', $this->getTable() . '.id', '=', 'o_tc.id');
        $query->join('pivot_relation_m2m_tree as pivot_tree_children', $this->getTable() . '.id', '=', 'pivot_tree_children.tree_id');
        $query->where('pivot_tree_children.tree_depth', '<=', $depth);
        $query->addSelect([$this->getTable() . '.*', 'pivot_tree_children.*', $this->getTable() . '.id as id']);

        return $query;
    }

    public function makeRoot()
    {
        app('db')->transaction(function()
        {
            try
            {
                // throw Exception if not attr in pivot_relation_m2m_tree
                $model = $this->treeAttr();

                $childs = app('db')->table('pivot_relation_m2m_tree')->where('tree_path', 'LIKE', '%.' . $this->getKey() . '.%')->get();

                foreach ($childs as $item)
                {
                    app('db')->table('pivot_relation_m2m_tree')->where('id', $item->id)->update([
                        'tree_path' => preg_replace('/.*\.' . $this->getKey() . '\./', '.0.' . $this->getKey() . '.', $item->tree_path),
                        'tree_depth' => app('db')->raw('(tree_depth - ' . $model->tree_depth . ')'),
                    ]);
                }

                app('db')->table('pivot_relation_m2m_tree')->where('tree_id', $this->getKey())->update([
                    'tree_path' => '.0.',
                    'tree_pid' => 0,
                    'tree_depth' => 0,
                    'tree_order' => (app('db')->table('pivot_relation_m2m_tree')->where('tree_pid', 0)->max('tree_order') + 1)
                ]);
            }
            catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e)
            {
                $this->insertTree();
            }
        });

        return $this;
    }

    public function insertTree()
    {
        if ($this->exists && \App\Telenok\Core\Model\Object\Sequence::findOrFail($this->getKey())->treeable)
        {
            app('db')->table('pivot_relation_m2m_tree')->insert([
                'tree_id' => $this->getKey(),
                'tree_path' => '.0.',
                'tree_pid' => 0,
                'tree_depth' => 0,
                'tree_order' => (app('db')->table('pivot_relation_m2m_tree')->where('tree_pid', 0)->max('tree_order') + 1)
            ]);
        }
        else
        {
            throw new \Telenok\Core\Support\Exception\ModelProcessAccessDenied('Model not exists or not treeable');
        }

        return $this;
    }

    public function makeLastChildOf($parent)
    {
        if (!$parent instanceof \Illuminate\Database\Eloquent\Model)
        {
            $parent = \App\Telenok\Core\Model\Object\Sequence::find($parent);
        }

        $this->makeRoot();

        $sequence = $this->treeAttr();
        $sequenceParent = $parent->treeAttr();

        if ($sequence->isAncestor($sequenceParent))
        {
            throw new \Telenok\Core\Support\Exception\ModelProcessAccessDenied('Cant move Ancestor to Descendant');
        }

        app('db')->transaction(function() use ($sequence, $sequenceParent)
        {
            $children = $sequence->children()->get();

            foreach ($children->all() as $child)
            {
                app('db')->table('pivot_relation_m2m_tree')->where('tree_id', $child->getKey())->update([
                    'tree_path' => str_replace($sequence->tree_path, $sequenceParent->tree_path . $sequenceParent->getKey() . '.', $child->tree_path),
                    'tree_depth' => ( $sequenceParent->tree_depth + 1 + ($child->tree_depth - $sequence->tree_depth) ),
                ]);
            }

            app('db')->table('pivot_relation_m2m_tree')->where('tree_id', $sequence->getKey())->update([
                'tree_path' => $sequenceParent->tree_path . $sequenceParent->getKey() . '.',
                'tree_pid' => $sequenceParent->getKey(),
                'tree_order' => ($sequenceParent->children(1)->where('tree_id', '<>', $sequence->getKey())->max('tree_order') + 1),
                'tree_depth' => ($sequenceParent->tree_depth + 1)
            ]);
        });

        return $this;
    }

    public function makeFirstChildOf($parent)
    {
        if (!$parent instanceof \Illuminate\Database\Eloquent\Model)
        {
            $parent = \App\Telenok\Core\Model\Object\Sequence::find($parent);
        }

        $this->makeRoot();

        $sequence = $this->treeAttr();
        $sequenceParent = $parent->treeAttr();

        if ($sequence->isAncestor($sequenceParent))
        {
            throw new \Telenok\Core\Support\Exception\ModelProcessAccessDenied('Cant move Ancestor to Descendant');
        }

        app('db')->transaction(function() use ($sequence, $sequenceParent)
        {
            $sequenceParent->children(1)->increment('tree_order');

            $children = $sequence->children()->get();

            foreach ($children->all() as $child)
            {
                app('db')->table('pivot_relation_m2m_tree')->where('tree_id', $child->getKey())->update(
                        [
                            'tree_path' => str_replace($sequence->tree_path, $sequenceParent->tree_path . $sequenceParent->getKey() . '.', $child->tree_path),
                            'tree_depth' => ( $sequenceParent->tree_depth + 1 + ($child->tree_depth - $sequence->tree_depth) ),
                ]);
            }

            app('db')->table('pivot_relation_m2m_tree')->where('tree_id', $sequence->getKey())->update([
                'tree_path' => $sequenceParent->tree_path . $sequenceParent->getKey() . '.',
                'tree_pid' => $sequenceParent->getKey(),
                'tree_order' => 0,
                'tree_depth' => ($sequenceParent->tree_depth + 1)
            ]);
        });

        return $this;
    }

    public function isAncestor($descendant)
    {
        if (!$descendant instanceof \Illuminate\Database\Eloquent\Model)
        {
            $descendant = \App\Telenok\Core\Model\Object\Sequence::find($descendant);
        }

        $sequence = $this->treeAttr();
        $sequenceDescendant = $descendant->treeAttr();

        return strpos($sequenceDescendant->tree_path, $sequence->tree_path . $sequence->getKey() . '.') !== false && $sequenceDescendant->tree_path !== $sequence->tree_path;
    }

    public function isDescendant($ancestor)
    {
        if (!$ancestor instanceof \Illuminate\Database\Eloquent\Model)
        {
            $ancestor = \App\Telenok\Core\Model\Object\Sequence::find($ancestor);
        }

        $sequence = $this->treeAttr();
        $sequenceAncestor = $ancestor->treeAttr();

        return strpos($sequence->tree_path, $sequenceAncestor->tree_path . $sequenceAncestor->getKey() . '.') !== false && $sequenceAncestor->tree_path !== $sequence->tree_path;
    }

    protected function processSiblingOf($sibling, $op)
    {
        if (!$sibling instanceof \Illuminate\Database\Eloquent\Model)
        {
            $sibling = \App\Telenok\Core\Model\Object\Sequence::find($sibling);
        }

        $this->makeRoot();

        $sequence = $this->treeAttr();
        $sequenceSibling = $sibling->treeAttr();

        if ($sequence->isAncestor($sequenceSibling))
        {
            throw new \Telenok\Core\Support\Exception\ModelProcessAccessDenied('Cant move Ancestor to Descendant');
        }

        app('db')->transaction(function() use ($sequence, $sequenceSibling, $op)
        {
            $sequenceSibling->sibling()->where('tree_order', $op, $sequenceSibling->tree_order)->increment('tree_order');

            $children = $sequence->children()->get();

            foreach ($children as $child)
            {
                $child->update([
                    'tree_path' => str_replace($sequence->tree_path, $sequenceSibling->tree_path, $child->tree_path),
                    'tree_depth' => ($sequenceSibling->tree_depth + ($child->tree_depth - $sequence->tree_depth)),
                ]);
            }

            app('db')->table('pivot_relation_m2m_tree')->where('tree_id', $sequence->getKey())->update(
                    [
                        'tree_path' => $sequenceSibling->tree_path,
                        'tree_pid' => $sequenceSibling->tree_pid,
                        'tree_order' => $sequenceSibling->tree_order + ($op == '>' ? 1 : 0),
                        'tree_depth' => $sequenceSibling->tree_depth,
            ]);
        });

        return $this;
    }

    public function makePreviousSiblingOf($sibling)
    {
        return $this->processSiblingOf($sibling, '>=');
    }

    public function makeNextSiblingOf($sibling)
    {
        return $this->processSiblingOf($sibling, '>');
    }

    public function sibling()
    {
        $sequence = $this->treeAttr();

        return \App\Telenok\Core\Model\Object\Sequence::withTreeAttr()->where('tree_pid', '=', $sequence->tree_pid);
    }

    public function parents()
    {
        $sequence = $this->treeAttr();

        return \App\Telenok\Core\Model\Object\Sequence::whereIn($this->getTable() . '.id', array_filter(explode('.', $sequence->tree_path), 'strlen'));
    }

    public function isLeaf()
    {
        $sequence = $this->treeAttr();

        return !$sequence->children(1)->count();
    }

    public function calculateRelativeDepth($object)
    {
        if (!$object instanceof \Illuminate\Database\Eloquent\Model)
        {
            $object = \App\Telenok\Core\Model\Object\Sequence::find($object);
        }

        $sequence = $this->treeAttr();
        $sequenceObject = $object->treeAttr();

        return abs($sequence->tree_depth - $sequenceObject->tree_depth);
    }

    public static function allRoot()
    {
        $query = \App\Telenok\Core\Model\Object\Sequence::withTreeAttr()->where('tree_pid', 0);

        return $query;
    }

    public static function allDepth($depth = 0)
    {
        $query = \App\Telenok\Core\Model\Object\Sequence::withTreeAttr()->whereIn('tree_depth', (array) $depth);

        return $query;
    }

    public static function allLeaf()
    {
        $model = new static;

        $query = \App\Telenok\Core\Model\Object\Sequence::withTreeAttr()->leftJoin('pivot_relation_m2m_tree as tree_leaf', function($join) use ($model)
                {
                    $join->on($model->getTable() . '.id', '=', 'tree_leaf.tree_pid');
                })
                ->whereNull('tree_leaf.tree_id');

        return $query;
    }

    /* ~Treeable section */

    public function lock($period = 300)
    {
        app('db')->transaction(function() use ($period)
        {
            $user = app('auth')->user();

            if ($this->exists && app('auth')->check() && (!$this->locked() || $this->locked_by_user == $user->id))
            {
                $this->locked_by_user = $user->id;
                $this->locked_at = \Carbon\Carbon::now()->addSeconds($period);
                $this->save();
            }
        });
    }

    public function unLock()
    {
        app('db')->transaction(function()
        {
            $this->locked_by_user = 0;
            $this->save();
        });
    }

    public function locked()
    {
        return $this->exists && $this->locked_by_user && $this->locked_at->diffInSeconds(null, false) <= 0;
    }

    public function LL($key = '', $param = [])
    {
        return trans("core::default.$key", $param);
    }

    public function createdByUser()
    {
        return $this->belongsTo('\App\Telenok\Core\Model\User\User', 'created_by_user');
    }

    public function updatedByUser()
    {
        return $this->belongsTo('\App\Telenok\Core\Model\User\User', 'updated_by_user');
    }

    public function deletedByUser()
    {
        return $this->belongsTo('\App\Telenok\Core\Model\User\User', 'deleted_by_user');
    }

    public function lockedByUser()
    {
        return $this->belongsTo('\App\Telenok\Core\Model\User\User', 'locked_by_user');
    }

    public function aclSubject()
    {
        return $this->hasMany('\App\Telenok\Core\Model\Security\SubjectPermissionResource', 'acl_subject_object_sequence');
    }

    public function __wakeup()
    {
        parent::__wakeup();

        foreach ($this->getFillable() as $f)
        {
            if (isset($this->attributes[$f]))
            {
                $this->{$f} = json_decode($this->attributes[$f], JSON_PRETTY_PRINT);
            }
        }
    }

}
