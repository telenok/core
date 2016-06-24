<?php namespace Telenok\Core\Field\Text;

use Illuminate\Database\Schema\Blueprint;

/**
 * @class Telenok.Core.Field.Text.Controller
 * Class of field "text". Field allow to store text and process text in form with
 * CKEditor.
 * 
 * @extends Telenok.Core.Abstraction.Field.Controller
 */
class Controller extends \Telenok\Core\Abstraction\Field\Controller {

    /**
     * @protected
     * @property {String} $key
     * Field key.
     * @member Telenok.Core.Field.Text.Controller
     */
    protected $key = 'text';
    
    /**
     * @protected
     * @property {Array} $specialField
     * Define list of field's names to process saving and filling {@link Telenok.Core.Model.Object.Field Telenok.Core.Model.Object.Field}.
     * @member Telenok.Core.Field.Text.Controller
     */
    protected $specialField = ['text_width', 'text_height', 'text_default', 'text_rte'];

    /**
     * @method getFilterQuery
     * Add restrictions to search query.
     * 
     * @param {Telenok.Core.Model.Object.Field} $field
     * Object with data of field's configuration.
     * @param {Object} $model
     * Eloquent object.
     * @param {Illuminate.Database.Query.Builder} $query
     * Laravel query builder object.
     * @param {String} $name
     * Name of field to search for.
     * @param {String} $value
     * Value to search for.
     * @return {void}
     * @member Telenok.Core.Field.Text.Controller
     */
    public function getFilterQuery($field = null, $model = null, $query = null, $name = null, $value = null)
    {
        if ($value !== null && trim($value))
        {
            $fieldCode = $field->code;
            $translate = new \App\Vendor\Telenok\Core\Model\Object\Translation();

            if (in_array($fieldCode, $model->getMultilanguage(), true))
            {
                $query->leftJoin($translate->getTable(), function($join) use ($model, $translate, $fieldCode)
                {
                    $join->on($model->getTable() . '.id', '=', $translate->getTable() . '.translation_object_model_id')
                            ->on($translate->getTable() . '.translation_object_field_code', '=', app('db')->raw("'" . $fieldCode . "'"))
                            ->on($translate->getTable() . '.translation_object_language', '=', app('db')->raw("'" . config('app.locale') . "'"));
                });

                $query->where(function($query) use ($value, $model, $translate)
                {
                    collect(explode(' ', $value))
                            ->filter(function($i)
                            {
                                return trim($i);
                            })
                            ->each(function($i) use ($query, $translate)
                            {
                                $query->orWhere($translate->getTable() . '.translation_object_string', 'like', '%' . trim($i) . '%');
                            });

                    $query->orWhere($model->getTable() . '.id', intval($value));
                });
            }
            else
            {
                parent::getFilterQuery($field, $model, $query, $name, $value);
            }
        }
    }

    /**
     * @method getModelAttribute
     * Return processed value of field.
     * 
     * @param {Telenok.Core.Abstraction.Eloquent.Object.Model} $model
     * Eloquent object.
     * @param {String} $key
     * Field's name.
     * @param {mixed} $value
     * Value of field from database for processing in this method.
     * @param {Telenok.Core.Model.Object.Field} $field
     * Object with data of field's configuration.
     * @return {String}
     * @member Telenok.Core.Field.Text.Controller
     */
    public function getModelAttribute($model, $key, $value, $field)
    {
        if ($field->multilanguage)
        {
            $value = collect(json_decode($value ? : '[]', true));

            foreach ($value->all() as $k => $v)
            {
                $value->put($k, app('\App\Vendor\Telenok\Core\Field\Text\Processing')->setRawValue($v));
            }
        }
        else
        {
            $value = app('\App\Vendor\Telenok\Core\Field\Text\Processing')->setRawValue($value);
        }

        return $value;
    }

    /**
     * @method setModelAttribute
     * Return processed value of field.
     * 
     * @param {Telenok.Core.Abstraction.Eloquent.Object.Model} $model
     * Eloquent object.
     * @param {String} $key
     * Field's name.
     * @param {mixed} $value
     * Value of field from php code for processing in this method.
     * @param {Telenok.Core.Model.Object.Field} $field
     * Object with data of field's configuration.
     * @return {void}
     * @member Telenok.Core.Field.Text.Controller
     */
    public function setModelAttribute($model, $key, $value, $field)
    {
        if ($field->multilanguage)
        {
            $current = $model->{$key};
            $defaultLanguage = config('app.localeDefault', "en");

            if (is_string($value))
            {
                $current->put($defaultLanguage, $value);
            }
            else
            {
                foreach (collect($value)->all() as $language => $v)
                {
                    $current->put($language, $v);
                }
            }

            $default = (array) json_decode($field->text_default ? : "[]", true);

            foreach ($default as $language => $v)
            {
                if (!$current->get($language))
                {
                    $current->put($language, $v);
                }
            }

            $value = json_encode($current->toArray(), JSON_UNESCAPED_UNICODE);
        }
        else
        {
            if ($value === null || !strlen($value))
            {
                $value = $field->text_default ? : null;
            }
        }

        $model->setAttribute($key, $value);
    }

    /**
     * @method getModelSpecialAttribute
     * Return processed value of special fields.
     * 
     * @param {Telenok.Core.Model.Object.Field} $model
     * Eloquent object.
     * @param {String} $key
     * Field's name.
     * @param {mixed} $value
     * Value of field from database for processing in this method.
     * @return {mixed}
     * @member Telenok.Core.Field.Text.Controller
     */
    public function getModelSpecialAttribute($model, $key, $value)
    {
        try
        {
            if (in_array($key, ['text_default'], true) && $model->multilanguage)
            {
                return collect(json_decode($value, true));
            }
            else
            {
                return parent::getModelSpecialAttribute($model, $key, $value);
            }
        }
        catch (\Exception $e)
        {
            return null;
        }
    }

    /**
     * @method setModelSpecialAttribute
     * Set processed value of special fields.
     * 
     * @param {Telenok.Core.Model.Object.Field} $model
     * Eloquent object.
     * @param {String} $key
     * Field's name.
     * @param {mixed} $value
     * Value of field from database for processing in this method.
     * @return {Telenok.Core.Field.Text.Controller}
     * @member Telenok.Core.Field.Text.Controller
     */
    public function setModelSpecialAttribute($model, $key, $value)
    {
        if (in_array($key, ['text_default'], true) && ($model->multilanguage || is_array($value)))
        {
            $default = [];

            if ($value instanceof \Illuminate\Support\Collection)
            {
                if ($value->count())
                {
                    $value = $value->toArray();
                }
                else
                {
                    $value = $default;
                }
            }
            else
            {
                $value = $value ? : $default;
            }

            $model->setAttribute($key, json_encode($value, JSON_UNESCAPED_UNICODE));
        }
        else
        {
            parent::setModelSpecialAttribute($model, $key, $value);
        }

        return $this;
    }

    /**
     * @method preProcess
     * Preprocess save {@link Telenok.Core.Model.Object.Field $model}.
     * 
     * @param {Telenok.Core.Model.Object.Field} $model
     * Object to save.
     * @param {Telenok.Core.Model.Object.Type} $type
     * Object with data of field's configuration.
     * @param {Illuminate.Http.Request} $input
     * Laravel request object.
     * @return {Telenok.Core.Field.Text.Controller}
     * @member Telenok.Core.Field.Text.Controller
     */
    public function preProcess($model, $type, $input)
    {
        if ($input->get('required'))
        {
            $input->put('rule', ['required']);
        }
        else
        {
            $input->put('rule', []);
        }

        return parent::preProcess($model, $type, $input);
    }

    /**
     * @method postProcess
     * postProcess save {@link Telenok.Core.Model.Object.Field $model}.
     * 
     * @param {Telenok.Core.Model.Object.Field} $model
     * Object to save.
     * @param {Telenok.Core.Model.Object.Type} $type
     * Object with data of field's configuration.
     * @param {Illuminate.Http.Request} $input
     * Laravel request object.
     * @return {Telenok.Core.Field.Text.Controller}
     * @member Telenok.Core.Field.Text.Controller
     */
    public function postProcess($model, $type, $input)
    {
        $table = $model->fieldObjectType()->first()->code;
        $fieldName = $model->code;

        if (!\Schema::hasColumn($table, $fieldName))
        {
            \Schema::table($table, function(Blueprint $table) use ($fieldName)
            {
                $table->mediumText($fieldName)->nullable();
            });
        }

        return parent::postProcess($model, $type, $input);
    }
}