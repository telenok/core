<?php namespace Telenok\Core\Field\String;

use Illuminate\Database\Schema\Blueprint;

/**
 * @class Telenok.Core.Field.String.Controller
 * Class of field "string". Field allow to process html select or checkboxes.
 * 
 * @extends Telenok.Core.Interfaces.Field.Controller
 */
class Controller extends \Telenok\Core\Interfaces\Field\Controller {

    /**
     * @protected
     * @property {String} $key
     * Field key.
     * @member Telenok.Core.Field.String.Controller
     */
    protected $key = 'string';

    /**
     * @protected
     * @property {Array} $specialField
     * Define list of field's names to process saving and filling {@link Telenok.Core.Model.Object.Field Telenok.Core.Model.Object.Field}.
     * @member Telenok.Core.Field.String.Controller
     */
    protected $specialField = ['string_default', 'string_regex', 'string_password', 'string_unique', 'string_max', 'string_min', 'string_list_size'];

    /**
     * @protected
     * @property {Array} $ruleList
     * Define list of rules for special fields.
     * @member Telenok.Core.Field.String.Controller
     */
    protected $ruleList = ['string_regex' => ['valid_regex']];

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
     * @member Telenok.Core.Field.String.Controller
     */
    public function getFilterQuery($field = null, $model = null, $query = null, $name = null, $value = null)
    {
        if ($value !== null && trim($value))
        {
            $fieldCode = $field->code;
            $translate = new \App\Telenok\Core\Model\Object\Translation();

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
     * @param {Telenok.Core.Interfaces.Eloquent.Object.Model} $model
     * Eloquent object.
     * @param {String} $key
     * Field's name.
     * @param {mixed} $value
     * Value of field from database for processing in this method.
     * @param {Telenok.Core.Model.Object.Field} $field
     * Object with data of field's configuration.
     * @return {String}
     * @member Telenok.Core.Field.String.Controller
     */
    public function getModelAttribute($model, $key, $value, $field)
    {
        if ($field->multilanguage)
        {
            $value = collect(json_decode($value ? : '[]', true));
        }

        return $value;
    }

    /**
     * @method setModelAttribute
     * Return processed value of field.
     * 
     * @param {Telenok.Core.Interfaces.Eloquent.Object.Model} $model
     * Eloquent object.
     * @param {String} $key
     * Field's name.
     * @param {mixed} $value
     * Value of field from php code for processing in this method.
     * @param {Telenok.Core.Model.Object.Field} $field
     * Object with data of field's configuration.
     * @return {void}
     * @member Telenok.Core.Field.String.Controller
     */
    public function setModelAttribute($model, $key, $value, $field)
    {
        if ($field->multilanguage)
        {
            $current = $model->{$key};
            $defaultLanguage = config('app.localeDefault', "en");

            if (is_string($value))
            {
                $current[$defaultLanguage] = $value;
            }
            else
            {
                foreach ($value as $language => $v)
                {
                    $current[$language] = $v;
                }
            }

            $default = (array) json_decode($field->string_default ? : "[]", true);

            foreach ($default as $language => $v)
            {
                if (!isset($value[$language]) && !$current->get($language))
                {
                    $current->put($language, $v);
                }
            }

            $value = json_encode($current->all(), JSON_UNESCAPED_UNICODE);
        }
        else
        {
            if ($value === null || !strlen($value))
            {
                $value = $field->string_default ? : null;
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
     * @member Telenok.Core.Field.String.Controller
     */
    public function getModelSpecialAttribute($model, $key, $value)
    {
        try
        {
            if (in_array($key, ['string_default'], true) && $model->multilanguage)
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
     * @return {Telenok.Core.Field.String.Controller}
     * @member Telenok.Core.Field.String.Controller
     */
    public function setModelSpecialAttribute($model, $key, $value)
    {
        if (in_array($key, ['string_default'], true) && ($model->multilanguage || is_array($value)))
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
        else if ($key === 'string_min' && !$value)
        {
            $model->setAttribute($key, 0);
        }
        else if ($key === 'string_max' && !$value)
        {
            $model->setAttribute($key, 255);
        }
        else if ($key === 'string_password' && !$value)
        {
            $model->setAttribute($key, 0);
        }
        else if ($key === 'string_list_size' && !$value)
        {
            $model->setAttribute($key, 50);
        }
        else
        {
            parent::setModelSpecialAttribute($model, $key, $value);
        }

        return $this;
    }

    /**
     * @method getListFieldContent
     * Return value of field for show in list cell like Javascript Datatables().
     * 
     * @param {Telenok.Core.Model.Object.Field} $field
     * Object with data of field's configuration.
     * @param {Object} $item
     * Eloquent object with data of list's row.
     * @param {Telenok.Core.Model.Object.Type} $type
     * Type of eloquent object $item.
     * @return {String}
     * @member Telenok.Core.Field.String.Controller
     */
    public function getListFieldContent($field, $item, $type = null)
    {
        return e(\Str::limit($item->translate((string) $field->code), $field->string_list_size ? : 20));
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
     * @return {Telenok.Core.Field.String.Controller}
     * @member Telenok.Core.Field.String.Controller
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

        $fields = ['rule' => []];

        if ($input->get('required'))
        {
            $fields['rule'][] = 'required';
        }

        if ($input->get('string_unique'))
        {
            $fields['rule'][] = "unique:{$table},{$fieldName},:id:,id";
        }

        if ($string_regex = trim($input->get('string_regex')))
        {
            $fields['rule'][] = "regex:{$string_regex}";
        }

        if ($string_max = intval($input->get('string_max')))
        {
            $fields['rule'][] = "max:{$string_max}";
        }

        if ($string_min = intval($input->get('string_min')))
        {
            $fields['rule'][] = "min:{$string_min}";
        }

        if ($string_list_size = intval($input->get('string_list_size')))
        {
            $fields['string_list_size'] = $string_list_size;
        }
        else
        {
            $fields['string_list_size'] = 20;
        }

        $model->fill($fields)->save();

        return parent::postProcess($model, $type, $input);
    }
}