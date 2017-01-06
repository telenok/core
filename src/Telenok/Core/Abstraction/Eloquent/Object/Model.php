<?php namespace Telenok\Core\Abstraction\Eloquent\Object;

use App\Vendor\Telenok\Core\Support\DateTime\Processing;
use Illuminate\Support\Arr;
use \Telenok\Core\Contract\Eloquent\EloquentProcessController;
use \Illuminate\Database\Eloquent\SoftDeletes;
use \App\Vendor\Telenok\Core\Abstraction\Eloquent\Cache\QueryCache;
use Telenok\Core\Support\Exception\ModelProcessAccessDenied;

/**
 * @class Telenok.Core.Abstraction.Eloquent.Object.Model
 * Base class for all Telenok's object eloquent models.
 *
 * @uses Telenok.Core.Contract.Eloquent.EloquentProcessController
 * @uses Illuminate.Database.Eloquent.SoftDeletes
 * @uses Telenok.Core.Abstraction.Eloquent.Cache.QueryCache
 * @extends Illuminate.Database.Eloquent.Model
 */
class Model extends \Illuminate\Database\Eloquent\Model {

    use QueryCache;
    use SoftDeletes {
        SoftDeletes::restore as softDeletesRestore;
    }

    /**
     * @property {Boolean} $incrementing
     * Allow primary key to be autoincremental.
     * @member Telenok.Core.Abstraction.Eloquent.Object.Model
     */
    public $incrementing = false;

    /**
     * @property {Boolean} $timestamps
     * Allow set time when create and update.
     * @member Telenok.Core.Abstraction.Eloquent.Object.Model
     */
    public $timestamps = true;

    /**
     * @protected
     * @property {Boolean} $hasVersioning
     * Allow create version for every call storeOrUpdate.
     * @member Telenok.Core.Abstraction.Eloquent.Object.Model
     */
    protected $hasVersioning = true;

    /**
     * @protected
     * @property {Array} $ruleList
     * List of rules for validation data before storing.
     * @member Telenok.Core.Abstraction.Eloquent.Object.Model
     */
    protected $ruleList = [];

    /**
     * @protected
     * @property {Array} $multilanguageList
     * List of multilanguages fields.
     * @member Telenok.Core.Abstraction.Eloquent.Object.Model
     */
    protected $multilanguageList = [];

    /**
     * @protected
     * @property {Array} $translatedList
     * List of translated (for multilanguages) fields.
     * @member Telenok.Core.Abstraction.Eloquent.Object.Model
     */
    protected $translatedList = [];

    /**
     * @protected
     * @property {Array} $dates
     * List of date fields.
     * @member Telenok.Core.Abstraction.Eloquent.Object.Model
     */
    protected $dates = [];

    /**
     * @protected
     * @static
     * @property {Array} $listField
     * List of cached model fields.
     * @member Telenok.Core.Abstraction.Eloquent.Object.Model
     */
    protected static $listField = [];

    /**
     * @protected
     * @static
     * @property {Array} $listRule
     * List of cached field's rules.
     * @member Telenok.Core.Abstraction.Eloquent.Object.Model
     */
    protected static $listRule = [];

    /**
     * @protected
     * @static
     * @property {Array} $listAllFieldController
     * List of cached links to field's controllers.
     * @member Telenok.Core.Abstraction.Eloquent.Object.Model
     */
    protected static $listAllFieldController = [];

    /**
     * @protected
     * @static
     * @property {Array} $listFillableFieldController
     * List of cached links to field's controllers.
     * @member Telenok.Core.Abstraction.Eloquent.Object.Model
     */
    protected static $listFillableFieldController = [];

    /**
     * @protected
     * @static
     * @property {Array} $listTranslated
     * List of cached translated fields.
     * @member Telenok.Core.Abstraction.Eloquent.Object.Model
     */
    protected static $listTranslated = [];

    /**
     * @protected
     * @static
     * @property {Array} $listFieldDate
     * List of cached date fields.
     * @member Telenok.Core.Abstraction.Eloquent.Object.Model
     */
    protected static $listFieldDate = [];

    /**
     * @protected
     * @static
     * @property {Array} $macros
     * List of cached macros aka callabled closures.
     * @member Telenok.Core.Abstraction.Eloquent.Object.Model
     */
    protected static $macros = [];

    /**
     * @method macro
     * Register a custom macro.
     *
     * @param {String} $name
     * @param {Closure} $macro
     * @return {void}
     * @member Telenok.Core.Abstraction.Eloquent.Object.Model
     */
    public static function macro($name, callable $macro)
    {
        static::$macros[$name] = $macro;
    }

    /**
     * @method hasMacro
     * Checks if macro is registered.
     *
     * @param {String} $name
     * @param {Closure} $macro
     * @return {void}
     * @member Telenok.Core.Abstraction.Eloquent.Object.Model
     */
    public static function hasMacro($name)
    {
        return isset(static::$macros[$name]);
    }

    /**
     * @method __callStatic
     * Dynamically handle calls to the class.
     *
     * @param {String} $method
     * @param {Array} $parameters
     * @return {mixed}
     * @throws \BadMethodCallException
     * @member Telenok.Core.Abstraction.Eloquent.Object.Model
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
     * @method __call
     * Dynamically handle calls to the class.
     *
     * @param {String} $method
     * @param {Array} $parameters
     * @return {mixed}
     * @throws \BadMethodCallException
     * @member Telenok.Core.Abstraction.Eloquent.Object.Model
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

    /**
     * @method boot
     * Booting events.
     *
     * @return {void}
     * @member Telenok.Core.Abstraction.Eloquent.Object.Model
     */
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
                    \App\Vendor\Telenok\Core\Model\Object\Version::add($model);
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

    /**
     * @protected
     * @method generateKeyId
     * Create new model ID.
     *
     * @return {void}
     * @member Telenok.Core.Abstraction.Eloquent.Object.Model
     */
    protected function generateKeyId()
    {
        if (!($this instanceof \Telenok\Core\Model\Object\Sequence))
        {
            if ($this->getKey())
            {
                $sequence = new \App\Vendor\Telenok\Core\Model\Object\Sequence();
                $sequence->id = $this->getKey();
                $sequence->model_class = get_class($this);
                $sequence->save();
            }
            else
            {
                $sequence = \App\Vendor\Telenok\Core\Model\Object\Sequence::create(['model_class' => get_class($this)]);
            }

            $this->id = $sequence->id;
        }
    }

    /**
     * @protected
     * @method restoreSequence
     * Restore linked sequence when restore model.
     *
     * @return {void}
     * @member Telenok.Core.Abstraction.Eloquent.Object.Model
     */
    protected function restoreSequence()
    {
        if (!($this instanceof \Telenok\Core\Model\Object\Sequence))
        {
            \App\Vendor\Telenok\Core\Model\Object\Sequence::withTrashed()->find($this->getKey())->restore();
        }
    }

    /**
     * @protected
     * @method deleteSequence
     * Delete linked sequence when restore model.
     *
     * @return {void}
     * @member Telenok.Core.Abstraction.Eloquent.Object.Model
     */
    protected function deleteSequence()
    {
        $sequence = \App\Vendor\Telenok\Core\Model\Object\Sequence::withTrashed()->find($this->getKey());

        if ($this->forceDeleting)
        {
            $sequence->forceDelete();
        }
        else
        {
            $sequence->delete();
        }
    }

    /**
     * @protected
     * @method deleteModelFieldController
     * Delete data via linked controllers to model.
     *
     * @return {void}
     * @member Telenok.Core.Abstraction.Eloquent.Object.Model
     */
    protected function deleteModelFieldController()
    {
        $controllers = app('telenok.repository')->getObjectFieldController();

        foreach ($this->getObjectField()->all() as $field)
        {
            if ($controller = $controllers->get($field->key))
            {
                $controller->processModelDelete($this, $this->forceDeleting);
            }
        }
    }

    /**
     * @method restore
     * Restore model.
     *
     * @return {Boolean}
     * @member Telenok.Core.Abstraction.Eloquent.Object.Model
     * @throws {Telenok.Cor.Support.Exception.ModelProcessAccessDenied}
     */
    public function restore()
    {
        if (app('auth')->can('delete', $this->getKey()))
        {
            app('db')->transaction(function()
            {
                return parent::restore();
            });
        }
        else
        {
            throw new \Telenok\Core\Support\Exception\ModelProcessAccessDenied();
        }
    }

    /**
     * @method delete
     * Delete model.
     *
     * @return {Boolean}
     * @member Telenok.Core.Abstraction.Eloquent.Object.Model
     * @throws {Telenok.Core.Support.Exception.ModelProcessAccessDenied}
     */
    public function delete()
    {
        if (app('auth')->can('delete', $this->getKey()))
        {
            app('db')->transaction(function()
            {
                if ($this->trashed())
                {
                    $this->forceDeleting = true;
                }
                else if (app('auth')->check())
                {
                    $this->deleted_by_user = app('auth')->user()->id;
                    $this->save();
                }

                return parent::delete();
            });
        }
        else
        {
            throw new \Telenok\Core\Support\Exception\ModelProcessAccessDenied();
        }
    }

    /**
     * @protected
     * @method translateSync
     * Save multilanguage data via Telenok.Core.Model.Object.Translation class.
     *
     * @return {void}
     * @member Telenok.Core.Abstraction.Eloquent.Object.Model
     */
    protected function translateSync()
    {
        if (!($this instanceof \Telenok\Core\Model\Object\Sequence))
        {
            \App\Vendor\Telenok\Core\Model\Object\Translation::where('translation_object_model_id', $this->getKey())->forceDelete();

            foreach ($this->getTranslatedField() as $fieldCode)
            {
                $value = $this->{$fieldCode}->all();

                foreach ($value as $language => $string)
                {
                    \App\Vendor\Telenok\Core\Model\Object\Translation::create([
                        'translation_object_model_id' => $this->getKey(),
                        'translation_object_field_code' => $fieldCode,
                        'translation_object_language' => $language,
                        'translation_object_string' => $string,
                    ]);
                }
            }

            $type = $this->type();


            $this->sequence()->withTrashed()->first()->fill([
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

    /**
     * @method sequence
     * Return sequense Eloquent model linked to model.
     *
     * @return {Telenok.Core.Model.Object.Sequence}
     * @member Telenok.Core.Abstraction.Eloquent.Object.Model
     *
     *     @example
     *     \App\Model\Article::find(104)->sequence()->translate('title');
     */
    public function sequence()
    {
        return $this->hasOne('\App\Vendor\Telenok\Core\Model\Object\Sequence', 'id');
    }

    /**
     * @method type
     * Return Object Type Eloquent model linked to model.
     *
     * @return {Telenok.Core.Model.Object.Type}
     * @member Telenok.Core.Abstraction.Eloquent.Object.Model
     *
     *     @example
     *     \App\Model\Article::find(104)->type()->code;
     */
    public function type()
    {
        return \App\Vendor\Telenok\Core\Model\Object\Type::where('code', $this->getTable())->first();
    }

    /**
     * @method hasVersioning
     * Checks if version allowed.
     *
     * @return {Boolean}
     * @member Telenok.Core.Abstraction.Eloquent.Object.Model
     */
    public function hasVersioning()
    {
        return ($this->hasVersioning === false || !$this->type()->has_versioning) ? false : true;
    }

    /**
     * @method treeForming
     * Checks if model support tree.
     *
     * @return {Boolean}
     * @member Telenok.Core.Abstraction.Eloquent.Object.Model
     */
    public function treeForming()
    {
        return $this->type()->treeable;
    }

    /**
     * @method eraseCachedFields
     * Erase all cached variables.
     *
     * @return {void}
     * @member Telenok.Core.Abstraction.Eloquent.Object.Model
     */
    public function eraseCachedFields()
    {
        $class = get_class($this);

        static::$listRule[$class] = null;
        static::$listField[$class] = null;
        static::$listAllFieldController[$class] = null;
        static::$listFillableFieldController[$class] = null;
        static::$listTranslated[$class] = null;

        //$model->getObjectField();
        $this->getFillable();
        $this->getTranslatedField();
        $this->getDates();
        $this->getRule();
    }

    /**
     * @method fill
     * Fill the model with an array of attributes.
     *
     * @param {Array} $attributes
     * @return {Telenok.Core.Abstraction.Eloquent.Object.Model}
     * @member Telenok.Core.Abstraction.Eloquent.Object.Model
     * @throws \Illuminate\Database\Eloquent\MassAssignmentException
     */
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

    /**
     * @method addFillable
     * Add additional attributes.
     *
     * @param {Array} $attributes
     * @return {Telenok.Core.Abstraction.Eloquent.Object.Model}
     * @member Telenok.Core.Abstraction.Eloquent.Object.Model
     */
    public function addFillable($attributes)
    {
        $this->fillable = array_unique(array_merge($this->fillable, (array) $attributes));

        return $this;
    }

    /**
     * @protected
     * @method fillableFromArray
     * Get the fillable attributes of a given array.
     *
     * @param {Array} $attributes
     * @return {Array}
     * @member Telenok.Core.Abstraction.Eloquent.Object.Model
     */
    protected function fillableFromArray(array $attributes)
    {
        $this->fillable = array_unique(array_merge($this->fillable, $this->getFillable($attributes)));

        return parent::fillableFromArray($attributes);
    }

    /**
     * @method storeOrUpdate
     * Create or update model.
     *
     * @param {Array} $input
     * @param {Boolean} $withPermission
     * Check permissions for model and model's fields.
     * @param {Boolean} $withEvent
     * Call events for external processing.
     * @return {Telenok.Core.Abstraction.Eloquent.Object.Model}
     * @member {Telenok.Core.Abstraction.Eloquent.Object.Model}
     * @throws {Telenok.Core.Support.Exception.ModelProcessAccessDenied}
     */
    public function storeOrUpdate($input = [], $withPermission = false, $withEvent = true)
    {
        if ($this instanceof \App\Vendor\Telenok\Core\Model\Object\Sequence)
        {
            throw new \Telenok\Core\Support\Exception\ModelProcessAccessDenied('Cant storeOrUpdate sequence model directly');
        }

        try
        {
            $type = $this->type();
        }
        catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e)
        {
            throw new \Telenok\Core\Support\Exception\ModelProcessAccessDenied('Telenok\Core\Abstraction\Eloquent\Object\Model::storeOrUpdate() - Error: "type of object not found, please, define it"');
        }


        if ($input instanceof \Telenok\Core\Abstraction\Eloquent\Object\Model)
        {
            $input = collect($input->getAttributes());
        }
        else
        {
            $input = collect($input);
        }

        try
        {
            if (!$this->exists)
            {
                $model = $this->findOrFail($input->get($this->getKeyName()));
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


        foreach ($model->fillable as $k)
        {
            // set the default value
            if (!$model->exists)
            {
                $model->{$k} = null;
            }

            if (!$input->has($k))
            {
                $input->put($k, $model->{$k});
            }
            else
            {
                $model->{$k} = $input->get($k);
            }
        }

        if ($withPermission)
        {
            $model->validateStoreOrUpdatePermission($type, $input);
        }

        app('db')->transaction(function() use ($type, $input, $model, $withEvent)
        {
            $controllerProcessing = null;

            $exists = $model->exists;

            if ($withEvent)
            {
                //\Event::fire('workflow.' . ($exists ? 'update' : 'store') . '.before', (new \App\Vendor\Telenok\Core\Workflow\Event())->setResource($model)->setInput($input));
            }

            if (($c = $type->classController()) && ($controllerProcessing = app($c)) && $controllerProcessing instanceof EloquentProcessController)
            {
                $controllerProcessing->preProcess($model, $type, $input);
            }

            $model->preProcess($type, $input);


            $model = $model->fill($input->all());

            $validator = app('\App\Vendor\Telenok\Core\Support\Validator\Model')
                ->setModel($model)
                ->setInput($input)
                ->setMessage($this->LL('error'))
                ->setCustomAttribute($this->validatorCustomAttributes());

            if ($validator->fails())
            {





                throw new \Exception(json_encode($validator->messages(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));






                throw new \Telenok\Core\Support\Exception\Validator($validator->messages());
            }

            if ($controllerProcessing instanceof EloquentProcessController)
            {
                $controllerProcessing->validate($model, $input);
            }

            $model->push();

            if (!$exists && $type->treeable)
            {
                $model->makeRoot();
            }

            $model->postProcess($type, $input);

            if ($controllerProcessing instanceof EloquentProcessController)
            {
                $controllerProcessing->postProcess($model, $type, $input);
            }

            if ($withEvent)
            {
                //\Event::fire('workflow.' . ($exists ? 'update' : 'store') . '.after', (new \App\Vendor\Telenok\Core\Workflow\Event())->setResource($model)->setInput($input));
            }
        });

        return $model;
    }

    /**
     * @protected
     * @method validatorCustomAttributes
     * Before valiating field's value try to add field's name to array and pass it
     * to validator.
     *
     * @return {Array}
     * @member Telenok.Core.Abstraction.Eloquent.Object.Model
     */
    protected function validatorCustomAttributes()
    {
        static $attr = null;

        if (!isset($attr))
        {
            $attr = [];

            $attr['custom_table'] = $this->getTable();

            foreach ($this->getFieldForm() as $field)
            {
                $attr['custom_' . $field->code] = $field->translate('title');
            }
        }

        return $attr;
    }

    /**
     * @protected
     * @method validateStoreOrUpdatePermission
     * Before storing model try to validate rights on fields in $input.
     *
     * @param {Telenok.Core.Model.Object.Type} $type
     * @param {Illuminate.Support.Collection} $input
     * @return {void}
     * @member Telenok.Core.Abstraction.Eloquent.Object.Model
     */
    protected function validateStoreOrUpdatePermission($type = null, $input = null)
    {
        if (!$type)
        {
            $type = $this->type();
        }

        if (!$this->exists && !app('auth')->can('create', "object_type.{$type->code}"))
        {
            throw new ModelProcessAccessDenied('Cant create model with type "' . $type->code . '". Access denied.');
        }
        else if ($this->exists && !app('auth')->can('update', $this->getKey()))
        {
            throw new ModelProcessAccessDenied('Cant update model with type "' . $type->code . '". Access denied.');
        }

        $objectField = static::$listField[get_class($this)];

        foreach ($input->all() as $key => $value)
        {
            $fc = app('telenok.repository')->getObjectFieldController();

            if (Arr::get($objectField, $key))
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
                if ($this instanceof \Telenok\Core\Model\Object\Field && ($fieldController = $fc->get($this->key)) && (in_array($key, $fieldController->getSpecialField($this), true) || in_array($key, $fieldController->getSpecialDateField($this), true)) &&
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
                    foreach ($this->getObjectField()->all() as $key_ => $field_)
                    {
                        $fieldController = $fc->get($field_->key);

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

    /**
     * @method preProcess
     * Before storing model called this hook.
     * @param {Telenok.Core.Model.Object.Type} $type
     * @param {Illuminate.Support.Collection} $input
     * @return {Telenok.Core.Abstraction.Eloquent.Object.Model}
     * @member Telenok.Core.Abstraction.Eloquent.Object.Model
     */
    public function preProcess($type, $input)
    {
        $config = app('telenok.repository')->getObjectFieldController();

        foreach ($type->field()->active()->get() as $field)
        {
            $config->get($field->key)->fill($field, $this, $input);
        }

        return $this;
    }

    /**
     * @method postProcess
     * After storing model called this hook.
     * @param {Telenok.Core.Model.Object.Type} $type
     * @param {Illuminate.Support.Collection} $input
     * @return {Telenok.Core.Abstraction.Eloquent.Object.Model}
     * @member Telenok.Core.Abstraction.Eloquent.Object.Model
     */
    public function postProcess($type, $input)
    {
        $config = app('telenok.repository')->getObjectFieldController();

        foreach ($type->field()->active()->get() as $field)
        {
            $config->get($field->key)->saveModelField($field, $this, $input);
        }

        if ($this->hasVersioning())
        {
            \App\Vendor\Telenok\Core\Model\Object\Version::add($this);
        }

        return $this;
    }

    /**
     * @method __get
     * Magic method
     * @param {String} $key
     * @return {mixed}
     * @member Telenok.Core.Abstraction.Eloquent.Object.Model
     */
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

    /**
     * @method __set
     * Magic method
     * @param {String} $key
     * @param {mixed} $value
     * @return {mixed}
     * @member Telenok.Core.Abstraction.Eloquent.Object.Model
     */
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

    /**
     * @method getModelAttributeController
     * Return value of field processed by field's controller for specific attribute.
     * @param {String} $key
     * @param {mixed} $value
     * @return {mixed}
     * @member Telenok.Core.Abstraction.Eloquent.Object.Model
     */
    public function getModelAttributeController($key, $value)
    {
        $class = get_class($this);

        if (isset(static::$listAllFieldController[$class][$key]))
        {
            return static::$listAllFieldController[$class][$key]->getModelAttribute($this, $key, $value, static::$listField[$class][$key]);
        }
        else
        {
            return $value;
        }
    }

    /**
     * @method setModelAttributeController
     * Set value of field processed by field's controller for specific attribute.
     * @param {String} $key
     * @param {mixed} $value
     * @return {void}
     * @member Telenok.Core.Abstraction.Eloquent.Object.Model
     */
    public function setModelAttributeController($key, $value)
    {
        $class = get_class($this);

        $f = static::$listAllFieldController[$class][$key];

        $f->setModelAttribute($this, $key, $value, static::$listField[$class][$key]);
    }

    /**
     * @method getObjectField
     * Return list with Object Field values for current model's class.
     * @return {Array}
     * @member Telenok.Core.Abstraction.Eloquent.Object.Model
     */
    public function getObjectField()
    {
        $class = get_class($this);

        if (!isset(static::$listField[$class]))
        {
            static::$listField[$class] = collect($this->type()->field()->active()->get()->keyBy('code'));
        }

        return static::$listField[$class];
    }

    /**
     * @method getFieldList
     * Return list of fields which can be showed in tables.
     * @return {Array}
     * @member Telenok.Core.Abstraction.Eloquent.Object.Model
     */
    public function getFieldList()
    {
        $type = $this->type();

        return $type->field()->active()->get()->filter(function($item) use ($type)
        {
            return $item->show_in_list == 1 && app('auth')->can('read', 'object_field.' . $type->code . '.' . $item->code);
        });
    }

    /**
     * @method getFieldForm
     * Return list of fields which can be showed in form.
     * @return {Array}
     * @member Telenok.Core.Abstraction.Eloquent.Object.Model
     */
    public function getFieldForm()
    {
        $type = $this->type();

        return $type->field()->active()->get()->filter(function($item) use ($type)
        {
            return $item->show_in_form == 1 && app('auth')->can('read', 'object_field.' . $type->code . '.' . $item->code);
        });
    }

    /**
     * @method getTranslatedField
     * Return list of multilanguage fields.
     * @return {Array}
     * @member Telenok.Core.Abstraction.Eloquent.Object.Model
     */
    public function getTranslatedField()
    {
        $class = get_class($this);

        if (!isset(static::$listTranslated[$class]))
        {
            static::$listTranslated[$class] = (array) $this->translatedList;

            $fields = app('telenok.repository')->getObjectFieldController();

            foreach ($this->getObjectField()->all() as $key => $field)
            {
                $controller = $fields->get($field->key);

                if ($controller)
                {
                    static::$listTranslated[$class] = array_merge(static::$listTranslated[$class], (array) $controller->getTranslatedField($this, $field));
                }
            }
        }

        return static::$listTranslated[$class];
    }

    /**
     * @method addTranslatedField
     * Add translated field's code.
     * @param {String} $fieldCode
     * Code of translated field.
     * @return {Telenok.Core.Abstraction.Eloquent.Object.Model}
     * @member Telenok.Core.Abstraction.Eloquent.Object.Model
     */
    public function addTranslatedField($fieldCode)
    {
        $class = get_class($this);

        static::$listTranslated[$class][] = $fieldCode;

        static::$listTranslated[$class] = array_unique(static::$listTranslated[$class]);

        return $this;
    }

    /**
     * @method getDates
     * Return dates fields.
     *
     * @return {Array}
     * @member Telenok.Core.Abstraction.Eloquent.Object.Model
     */
    public function getDates()
    {
        return array_unique(array_merge(parent::getDates(), $this->dates));
    }

    /**
     * @method getFillable
     * Return fillabled fields.
     *
     * @return {Array}
     * @member Telenok.Core.Abstraction.Eloquent.Object.Model
     */
    public function  getFillable()
    {
        $class = get_class($this);

        if (!isset(static::$listFillableFieldController[$class]))
        {
            static::$listAllFieldController[$class] = [];
            static::$listFillableFieldController[$class] = [];
            static::$listFieldDate[$class] = [];

            $controllers = app('telenok.repository')->getObjectFieldController();

            foreach ($this->getObjectField()->all() as $key => $field)
            {
                if ($controller = $controllers->get($field->key))
                {
                    $dateField = (array) $controller->getDateField($this, $field);
                    static::$listFieldDate[$class] = array_merge(static::$listFieldDate[$class], $dateField);

                    foreach (array_merge((array) $controller->getModelFillableField($this, $field), $dateField) as $f)
                    {
                        static::$listFillableFieldController[$class][$f] = $controller;
                        static::$listField[$class][$f] = $field;
                    }

                    foreach ((array) $controller->getModelField($this, $field) as $f)
                    {
                        static::$listAllFieldController[$class][$f] = $controller;
                    }
                }
            }
        }

        $this->dates = array_merge($this->getDates(), (array) static::$listFieldDate[$class]);

        return array_keys((array) static::$listFillableFieldController[$class]);
    }

    /**
     * @method getRule
     * Return field's rules for model.
     *
     * @return {Array}
     * @member Telenok.Core.Abstraction.Eloquent.Object.Model
     */
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
                    if (in_array($key, $this->getTranslatedField()))
                    {
                        static::$listRule[$class][$key . '.*'][head(explode(':', $value_))] = $value_;
                    }
                    else
                    {
                        static::$listRule[$class][$key][head(explode(':', $value_))] = $value_;
                    }
                }
            }

            foreach (static::$listField[$class] as $key => $field)
            {
                if ($field->rule instanceof \Illuminate\Support\Collection)
                {
                    foreach ($field->rule->all() as $key => $value)
                    {
                        if (in_array($field->code, $this->getTranslatedField()))
                        {
                            static::$listRule[$class][$field->code . '.*'][head(explode(':', $value))] = $value;
                        }
                        else
                        {
                            static::$listRule[$class][$field->code][head(explode(':', $value))] = $value;
                        }
                    }
                }
            }
        }

        return static::$listRule[$class];
    }

    /**
     * @method translate
     * Return translated value of field.
     *
     * @param {String} $field
     * Field's code.
     * @param {String} $locale
     * Locale. Can be null then used default site locale.
     * @return {mixed}
     * @member Telenok.Core.Abstraction.Eloquent.Object.Model
     */
    public function translate($field, $locale = '')
    {
        $val = $this->{$field};
        $localeDefault = config('app.localeDefault');
        $locale = $locale ? : config('app.locale');

        if ($val instanceof \Illuminate\Support\Collection)
        {
            return $val->get($locale) ? : ($val->get($localeDefault) ? : $val->get('en'));
        }
        else if ((($v = $val) instanceof \ArrayAccess && $v || is_array($v) && $v) || (($v = json_decode($val, true)) && json_last_error() === JSON_ERROR_NONE))
        {
            if (isset($v[$locale]))
            {
                return $v[$locale];
            }
            else if (isset($v[$localeDefault]))
            {
                return $v[$localeDefault];
            }
            else
            {
                return $v;
            }
        }
        else
        {
            return $val;
        }
    }

    /**
     * @method scopeActive
     * Apply additional filter to query to select only active rows.
     *
     * @param {Illuminate.Database.Query.Builder} $query
     * @param {String} $table
     * Name of field.
     * @return {Illuminate.Database.Query.Builder}
     * @member Telenok.Core.Abstraction.Eloquent.Object.Model
     *
     *     @example
     *     \App\Model\Article::active()->take(10)->get();
     */
    public function scopeActive($query, $table = null)
    {
        $table = $table ? : $this->getTable();
        $r = Processing::range_minutes($this->getCacheMinutes());

        return $query->where(function($query) use ($table, $r)
        {
            $query->whereNull($table . '.deleted_at')
                ->where($table . '.active', 1)
                ->where($table . '.active_at_start', '<=', $r[1])
                ->where($table . '.active_at_end', '>=', $r[0]);
        });
    }

    /**
     * @method scopeNotActive
     * Apply additional filter to query to select only not active rows.
     *
     * @param {Illuminate.Database.Query.Builder} $query
     * @param {String} $table
     * Name of field.
     * @return {Illuminate.Database.Query.Builder}
     * @member Telenok.Core.Abstraction.Eloquent.Object.Model
     *
     *     @example
     *     \App\Model\Article::notActive()->take(10)->get();
     */
    public function scopeNotActive($query, $table = null)
    {
        $table = $table ? : $this->getTable();
        $r = Processing::range_minutes($this->getCacheMinutes());

        return $query->where(function($query) use ($table, $r)
        {
            $query->where($table . '.active', 0)
                ->orWhere($table . '.active_at_start', '>=', $r[1])
                ->orWhere($table . '.active_at_end', '<=', $r[0]);
        });
    }

    /**
     * @method scopeTranslateField
     * Add translated field to query.
     *
     * @param {Illuminate.Database.Query.Builder} $query
     * @param {String} $linkedTableAlias
     * @param {String} $translateTableAlias
     * @param {String} $translateField
     * @param {String} $locale
     * @return {void}
     * @member Telenok.Core.Abstraction.Eloquent.Object.Model
     *
     *      @example
     *      $query = \App\Model\Article::notActive()->take(10);
     *      $query->translateField($query, $productModel->getTable(), 'translate_table', 'title', config('app.locale'));
     *      $query->orderBy('translate_table.title')->get();
     */
    public function scopeTranslateField($query, $linkedTableAlias = '', $translateTableAlias = '', $translateField = '', $locale = '')
    {
        $translateModel = app('\App\Vendor\Telenok\Core\Model\Object\Translation');

        $translateTableAlias = $translateTableAlias ? : $translateModel->getTable();

        $query->leftJoin($translateModel->getTable() . ' as ' . $translateTableAlias, function($join)
        use ($linkedTableAlias, $translateTableAlias, $translateField, $locale)
        {
            $join->on($linkedTableAlias . '.id', '=', $translateTableAlias . '.translation_object_model_id')
                ->on($translateTableAlias . '.translation_object_field_code', '=', app('db')->raw("'" . $translateField . "'"))
                ->on($translateTableAlias . '.translation_object_language', '=', app('db')->raw("'" . ($locale ? : config('app.locale')) . "'"));
        });
    }

    /**
     * @method scopeWithPermission
     * Validate right on query.
     *
     * @return {Array}
     * @member Telenok.Core.Abstraction.Eloquent.Object.Model
     *
     *      @example
     *      // can current user read (read - by default)
     *      \App\Vendor\Telenok\Core\Model\Object\Sequence::withPermission()->take(10)->get();
     *      // can current user write
     *      \App\Vendor\Telenok\Core\Model\Object\Sequence::withPermission('write', null)->take(10)->get();
     *      // can $someObject read
     *      \App\Vendor\Telenok\Core\Model\Object\Sequence::withPermission(null, $someObject)->take(10)->get();
     *      // can authorized user read
     *      \App\Vendor\Telenok\Core\Model\Object\Sequence::withPermission(null, 'user_authorized')->take(10)->get();
     *      // can anybody read
     *      \App\Vendor\Telenok\Core\Model\Object\Sequence::withPermission('read', 'user_any')->take(10)->get();
     *      // can user_authorized read with AND condition ['object-type', 'own']
     *      \App\Vendor\Telenok\Core\Model\Object\Sequence::withPermission('read', 'user_authorized', ['object-type', 'own'])->take(10)->get();
     */
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
                $subjectCollection->push(\App\Vendor\Telenok\Core\Model\Security\Resource::where('code', 'user_any')->active()->first());

                if (app('auth')->guest())
                {
                    $subjectCollection->push(\App\Vendor\Telenok\Core\Model\Security\Resource::where('code', 'user_unauthorized')->active()->first());
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
                $subjectCollection->push(\App\Vendor\Telenok\Core\Model\Object\Sequence::where('id', $subjectCode->getKey())->active()->firstOrFail());
            }
            else
            {
                $subjectCollection->push(\App\Vendor\Telenok\Core\Model\Object\Sequence::where('id', $subjectCode)->active()->firstOrFail());
            }
        }
        catch (\Exception $e)
        {

        }

        $permission = \App\Vendor\Telenok\Core\Model\Security\Permission::where('id', $permissionCode)->orWhere('code', $permissionCode)->active()->first();

        if ($subjectCollection->isEmpty() || !$permission)
        {
            return $query->where($this->getTable() . '.id', 'Error: permission code');
        }

        $r = Processing::range_minutes($this->getCacheMinutes());
        $spr = new \App\Vendor\Telenok\Core\Model\Security\SubjectPermissionResource();
        $sequence = new \App\Vendor\Telenok\Core\Model\Object\Sequence();
        $type = new \App\Vendor\Telenok\Core\Model\Object\Type();

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

            $filters = app('telenok.repository')->getAclResourceFilter();

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

    /**
     * @method treeParent
     * Return tree's parent of model.
     *
     * @return {Telenok.Core.Model.Object.Sequence}
     * @member Telenok.Core.Abstraction.Eloquent.Object.Model
     */
    public function treeParent()
    {
        return $this->belongsToMany('\App\Vendor\Telenok\Core\Model\Object\Sequence', 'pivot_relation_m2m_tree', 'tree_id', 'tree_pid');
    }

    /**
     * @method treeChild
     * Return tree's children of model.
     *
     * @return {Telenok.Core.Model.Object.Sequence}
     * @member Telenok.Core.Abstraction.Eloquent.Object.Model
     */
    public function treeChild()
    {
        return $this->belongsToMany('\App\Vendor\Telenok\Core\Model\Object\Sequence', 'pivot_relation_m2m_tree', 'tree_pid', 'tree_id');
    }

    /* Treeable section */

    /**
     * @method treeAttr
     * Return model with tree attributes.
     *
     * @return {Telenok.Core.Abstraction.Eloquent.Object.Model}
     * @member Telenok.Core.Abstraction.Eloquent.Object.Model
     */
    public function treeAttr()
    {
        return $this->withTreeAttr()->where($this->getTable() . '.id', $this->getKey())->firstOrFail();
    }

    /**
     * @method scopeWithTreeAttr
     * Add additional filter to query.
     *
     * @param {Illuminate.Database.Query.Builder} $query
     * @return {Illuminate.Database.Query.Builder}
     * @member Telenok.Core.Abstraction.Eloquent.Object.Model
     */
    public function scopeWithTreeAttr($query)
    {
        $query->join('pivot_relation_m2m_tree as pivot_tree_attr', $this->getTable() . '.id', '=', 'pivot_tree_attr.tree_id')
            ->addSelect(['*', $this->getTable() . '.id as id']);
    }

    /**
     * @method children
     * Add filter to query for getting children for model with depth.
     * @param {Integer} $depth
     * How many level of children select.
     * @return {Illuminate.Database.Query.Builder}
     * @member Telenok.Core.Abstraction.Eloquent.Object.Model
     */
    public function children($depth = 0)
    {
        if ($depth == 1)
        {
            $query = \App\Vendor\Telenok\Core\Model\Object\Sequence::withTreeAttr()->where('pivot_tree_attr.tree_pid', $this->getKey());
        }
        else
        {
            $model = $this->treeAttr();
            $query = \App\Vendor\Telenok\Core\Model\Object\Sequence::withTreeAttr();

            if ($depth)
            {
                $query->where('pivot_tree_attr.tree_depth', '<=', $model->tree_depth + $depth);
            }

            $query->where('pivot_tree_attr.tree_path', 'like', $model->tree_path . $this->getKey() . '.%');
        }

        return $query;
    }

    /**
     * @method scopeWithChildren
     * Add filter to query to choose children.
     * @param {Illuminate.Database.Query.Builder} $query
     * @param {Integer} $depth
     * How many level of children select.
     * @return {Illuminate.Database.Query.Builder}
     * @member Telenok.Core.Abstraction.Eloquent.Object.Model
     */
    public function scopeWithChildren($query, $depth = 0)
    {
        $query->join('object_sequence as o_tc', $this->getTable() . '.id', '=', 'o_tc.id');
        $query->join('pivot_relation_m2m_tree as pivot_tree_children', $this->getTable() . '.id', '=', 'pivot_tree_children.tree_id');
        $query->where('pivot_tree_children.tree_depth', '<=', $depth);
        $query->addSelect([$this->getTable() . '.*', 'pivot_tree_children.*', $this->getTable() . '.id as id']);

        return $query;
    }

    /**
     * @method makeRoot
     * Move model in top of tree.
     *
     * @return {Telenok.Core.Abstraction.Eloquent.Object.Model}
     * @member Telenok.Core.Abstraction.Eloquent.Object.Model
     */
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

    /**
     * @method insertTree
     * Insert model to tree for first time.
     *
     * @return {Telenok.Core.Abstraction.Eloquent.Object.Model}
     * @member Telenok.Core.Abstraction.Eloquent.Object.Model
     */
    public function insertTree()
    {
        if ($this->exists && \App\Vendor\Telenok\Core\Model\Object\Sequence::findOrFail($this->getKey())->treeable)
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

    /**
     * @method makeLastChildOf
     * Move model in the end of children's list.
     * @param {Telenok.Core.Abstraction.Eloquent.Object.Model} $parent
     * @return {Telenok.Core.Abstraction.Eloquent.Object.Model}
     * @member Telenok.Core.Abstraction.Eloquent.Object.Model
     */
    public function makeLastChildOf($parent)
    {
        if (!$parent instanceof \Illuminate\Database\Eloquent\Model)
        {
            $parent = \App\Vendor\Telenok\Core\Model\Object\Sequence::find($parent);
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

    /**
     * @method makeFirstChildOf
     * Move model in the top of children's list.
     * @param {Telenok.Core.Abstraction.Eloquent.Object.Model} $parent
     * @return {Telenok.Core.Abstraction.Eloquent.Object.Model}
     * @member Telenok.Core.Abstraction.Eloquent.Object.Model
     */
    public function makeFirstChildOf($parent)
    {
        if (!$parent instanceof \Illuminate\Database\Eloquent\Model)
        {
            $parent = \App\Vendor\Telenok\Core\Model\Object\Sequence::find($parent);
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

    /**
     * @method isAncestor
     * Whether model is ancestor of $descendant.
     * @param {Telenok.Core.Abstraction.Eloquent.Object.Model} $descendant
     * @return {Boolean}
     * @member Telenok.Core.Abstraction.Eloquent.Object.Model
     */
    public function isAncestor($descendant)
    {
        if (!$descendant instanceof \Illuminate\Database\Eloquent\Model)
        {
            $descendant = \App\Vendor\Telenok\Core\Model\Object\Sequence::find($descendant);
        }

        $sequence = $this->treeAttr();
        $sequenceDescendant = $descendant->treeAttr();

        return strpos($sequenceDescendant->tree_path, $sequence->tree_path . $sequence->getKey() . '.') !== false && $sequenceDescendant->tree_path !== $sequence->tree_path;
    }

    /**
     * @method isDescendant
     * Whether model is descendant of $ancestor.
     * @param {Telenok.Core.Abstraction.Eloquent.Object.Model} $ancestor
     * @return {Boolean}
     * @member Telenok.Core.Abstraction.Eloquent.Object.Model
     */
    public function isDescendant($ancestor)
    {
        if (!$ancestor instanceof \Illuminate\Database\Eloquent\Model)
        {
            $ancestor = \App\Vendor\Telenok\Core\Model\Object\Sequence::find($ancestor);
        }

        $sequence = $this->treeAttr();
        $sequenceAncestor = $ancestor->treeAttr();

        return strpos($sequence->tree_path, $sequenceAncestor->tree_path . $sequenceAncestor->getKey() . '.') !== false && $sequenceAncestor->tree_path !== $sequence->tree_path;
    }

    /**
     * @protected
     * @method processSiblingOf
     * Move model up or down on the same depth.
     * @param {Telenok.Core.Abstraction.Eloquent.Object.Model} $sibling
     * @param {String} $op
     * Can be "<" or ">"
     * @return {Telenok.Core.Abstraction.Eloquent.Object.Model}
     * @member Telenok.Core.Abstraction.Eloquent.Object.Model
     */
    protected function processSiblingOf($sibling, $op)
    {
        if (!$sibling instanceof \Illuminate\Database\Eloquent\Model)
        {
            $sibling = \App\Vendor\Telenok\Core\Model\Object\Sequence::find($sibling);
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

    /**
     * @method makePreviousSiblingOf
     * Move model to before of $sibling.
     *
     * @return {Telenok.Core.Abstraction.Eloquent.Object.Model}
     * @member Telenok.Core.Abstraction.Eloquent.Object.Model
     */
    public function makePreviousSiblingOf($sibling)
    {
        return $this->processSiblingOf($sibling, '>=');
    }

    /**
     * @method makeNextSiblingOf
     * Move model to next of $sibling.
     *
     * @return {Telenok.Core.Abstraction.Eloquent.Object.Model}
     * @member Telenok.Core.Abstraction.Eloquent.Object.Model
     */
    public function makeNextSiblingOf($sibling)
    {
        return $this->processSiblingOf($sibling, '>');
    }

    /**
     * @method sibling
     * Return initial query to select sibling models.
     *
     * @return {Illuminate.Database.Query.Builder}
     * @member Telenok.Core.Abstraction.Eloquent.Object.Model
     */
    public function sibling()
    {
        $sequence = $this->treeAttr();

        return \App\Vendor\Telenok\Core\Model\Object\Sequence::withTreeAttr()->where('tree_pid', '=', $sequence->tree_pid);
    }

    /**
     * @method parents
     * Return initial query to select parents models.
     *
     * @return {Illuminate.Database.Query.Builder}
     * @member Telenok.Core.Abstraction.Eloquent.Object.Model
     */
    public function parents()
    {
        $sequence = $this->treeAttr();

        return \App\Vendor\Telenok\Core\Model\Object\Sequence::whereIn($this->getTable() . '.id', array_filter(explode('.', $sequence->tree_path), 'strlen'));
    }

    /**
     * @method isLeaf
     * Whether model is leaf of tree's branch.
     *
     * @return {Boolean}
     * @member Telenok.Core.Abstraction.Eloquent.Object.Model
     */
    public function isLeaf()
    {
        $sequence = $this->treeAttr();

        return !$sequence->children(1)->exists();
    }

    /**
     * @method calculateRelativeDepth
     * Calculate relative depth between two models.
     *
     * @return {Integer}
     * @member Telenok.Core.Abstraction.Eloquent.Object.Model
     */
    public function calculateRelativeDepth($object)
    {
        if (!$object instanceof \Illuminate\Database\Eloquent\Model)
        {
            $object = \App\Vendor\Telenok\Core\Model\Object\Sequence::find($object);
        }

        $sequence = $this->treeAttr();
        $sequenceObject = $object->treeAttr();

        return abs($sequence->tree_depth - $sequenceObject->tree_depth);
    }

    /**
     * @method allRoot
     * Return initial query to select all root's models.
     *
     * @return {Illuminate.Database.Query.Builder}
     * @member Telenok.Core.Abstraction.Eloquent.Object.Model
     */
    public static function allRoot()
    {
        $query = \App\Vendor\Telenok\Core\Model\Object\Sequence::withTreeAttr()->where('tree_pid', 0);

        return $query;
    }

    /**
     * @method allDepth
     * Return initial query to select all models in tree in $depth.
     *
     * @param {Integer} $depth
     * @return {Illuminate.Database.Query.Builder}
     * @member Telenok.Core.Abstraction.Eloquent.Object.Model
     */
    public static function allDepth($depth = 0)
    {
        $query = \App\Vendor\Telenok\Core\Model\Object\Sequence::withTreeAttr()->whereIn('tree_depth', (array) $depth);

        return $query;
    }

    /**
     * @method allLeaf
     * Return initial query to select all models in tree which hasnt children.
     *
     * @return {Illuminate.Database.Query.Builder}
     * @member Telenok.Core.Abstraction.Eloquent.Object.Model
     */
    public static function allLeaf()
    {
        $model = new static;

        $query = \App\Vendor\Telenok\Core\Model\Object\Sequence::withTreeAttr()->leftJoin('pivot_relation_m2m_tree as tree_leaf', function($join) use ($model)
        {
            $join->on($model->getTable() . '.id', '=', 'tree_leaf.tree_pid');
        })
            ->whereNull('tree_leaf.tree_id');

        return $query;
    }

    /* ~Treeable section */

    /**
     * @method lock
     * Lock model to notify that it is eg. editing.
     *
     * @param {Integer} $period
     * Lock time in seconds.
     * @return {void}
     * @member Telenok.Core.Abstraction.Eloquent.Object.Model
     */
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

    /**
     * @method unLock
     * UnLock model to notify that it is free to eg. edit.
     *
     * @return {void}
     * @member Telenok.Core.Abstraction.Eloquent.Object.Model
     */
    public function unLock()
    {
        if ($this->exists)
        {
            app('db')->transaction(function()
            {
                $this->locked_by_user = 0;
                $this->save();
            });
        }
    }

    /**
     * @method locked
     * Whether model is locked.
     *
     * @return {Boolean}
     * @member Telenok.Core.Abstraction.Eloquent.Object.Model
     */
    public function locked()
    {
        return $this->exists && $this->locked_by_user && $this->locked_at->diffInSeconds(null, false) <= 0;
    }

    /**
     * @method createdByUser
     * Define an inverse one-to-many relationship.
     *
     * @return {Illuminate.Database.Query.Builder}
     * @member Telenok.Core.Abstraction.Eloquent.Object.Model
     */
    public function createdByUser()
    {
        return $this->belongsTo('\App\Vendor\Telenok\Core\Model\User\User', 'created_by_user');
    }

    /**
     * @method updatedByUser
     * Define an inverse one-to-many relationship.
     *
     * @return {Illuminate.Database.Query.Builder}
     * @member Telenok.Core.Abstraction.Eloquent.Object.Model
     */
    public function updatedByUser()
    {
        return $this->belongsTo('\App\Vendor\Telenok\Core\Model\User\User', 'updated_by_user');
    }

    /**
     * @method deletedByUser
     * Define an inverse one-to-many relationship.
     *
     * @return {Illuminate.Database.Query.Builder}
     * @member Telenok.Core.Abstraction.Eloquent.Object.Model
     */
    public function deletedByUser()
    {
        return $this->belongsTo('\App\Vendor\Telenok\Core\Model\User\User', 'deleted_by_user');
    }

    /**
     * @method lockedByUser
     * Define an inverse one-to-many relationship.
     *
     * @return {Illuminate.Database.Query.Builder}
     * @member Telenok.Core.Abstraction.Eloquent.Object.Model
     */
    public function lockedByUser()
    {
        return $this->belongsTo('\App\Vendor\Telenok\Core\Model\User\User', 'locked_by_user');
    }

    /**
     * @method aclSubject
     * Define a one-to-many relationship to select query permissions.
     *
     * @return {Illuminate.Database.Query.Builder}
     * @member Telenok.Core.Abstraction.Eloquent.Object.Model
     */
    public function aclSubject()
    {
        return $this->hasMany('\App\Vendor\Telenok\Core\Model\Security\SubjectPermissionResource', 'acl_subject_object_sequence');
    }

    /**
     * @method __wakeup
     * Magick method called when model deserialized.
     *
     * @return {void}
     * @member Telenok.Core.Abstraction.Eloquent.Object.Model
     */
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

    /**
     * @method LL
     * Translate by key.
     *
     * @return {String}
     * @member Telenok.Core.Abstraction.Eloquent.Object.Model
     */
    public function LL($key = '', $param = [])
    {
        return trans("core::default.$key", $param);
    }
}