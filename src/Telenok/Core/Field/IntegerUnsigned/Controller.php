<?php namespace Telenok\Core\Field\IntegerUnsigned;

use Illuminate\Database\Schema\Blueprint;

/**
 * @class Telenok.Core.Field.IntegerUnsigned.Controller
 * Class of field "integer-unsigned". Field can store integers.
 * 
 * @extends Telenok.Core.Interfaces.Field.Controller
 */
class Controller extends \Telenok\Core\Interfaces\Field\Controller {

    /**
     * @protected
     * @property {String} $key
     * Field key.
     * @member Telenok.Core.Field.IntegerUnsigned.Controller
     */
    protected $key = 'integer-unsigned';
    
    /**
     * @protected
     * @property {Array} $specialField
     * Define list of field's names to process saving and filling {@link Telenok.Core.Model.Object.Field Telenok.Core.Model.Object.Field}.
     * @member Telenok.Core.Field.IntegerUnsigned.Controller
     */
    protected $specialField = ['integer_unsigned_default', 'integer_unsigned_min', 'integer_unsigned_max'];

    /**
     * @protected
     * @property {Array} $ruleList
     * Define list of rules for special fields.
     * @member Telenok.Core.Field.IntegerUnsigned.Controller
     */
    protected $ruleList = [
                'integer_unsigned_default' => ['integer', 'between:0,4294967295'], 
                'integer_unsigned_min' => ['integer', 'between:0,4294967295'], 
                'integer_unsigned_max' => ['integer', 'between:0,4294967295']
            ];

    /**
     * @protected
     * @property {Boolean} $allowMultilanguage
     * Field doesn't support multilanguage
     * @member Telenok.Core.Field.IntegerUnsigned.Controller
     */
    protected $allowMultilanguage = false;

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
     * @member Telenok.Core.Field.IntegerUnsigned.Controller
     */
    public function getListFieldContent($field, $item, $type = null)
    {
        return $item->{$field->code};
    }

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
     * @member Telenok.Core.Field.IntegerUnsigned.Controller
     */
    public function getFilterQuery($field = null, $model = null, $query = null, $name = null, $value = null)
    {
        if (($value = trim($value)) !== "")
        {
            $query->whereIn($model->getTable() . '.' . $name, explode(',', $value));
        }
    }

    /**
     * @method getModelAttribute
     * Return processed value of field.
     * 
     * @param {Telenok.Core.Interfaces.Eloquent.Object} $model
     * Eloquent object.
     * @param {String} $key
     * Field's name.
     * @param {mixed} $value
     * Value of field from database for processing in this method.
     * @param {Telenok.Core.Model.Object.Field} $field
     * Object with data of field's configuration.
     * @return {String}
     * @member Telenok.Core.Field.IntegerUnsigned.Controller
     */
    public function getModelAttribute($model, $key, $value, $field)
    {
        if ($value === null)
        {
            $value = $field->integer_unsigned_default;
        }

        return $value;
    }

    /**
     * @method setModelAttribute
     * Return processed value of field.
     * 
     * @param {Telenok.Core.Interfaces.Eloquent.Object} $model
     * Eloquent object.
     * @param {String} $key
     * Field's name.
     * @param {mixed} $value
     * Value of field from php code for processing in this method.
     * @param {Telenok.Core.Model.Object.Field} $field
     * Object with data of field's configuration.
     * @return {void}
     * @member Telenok.Core.Field.IntegerUnsigned.Controller
     */
    public function setModelAttribute($model, $key, $value, $field)
    {
        if ($value === null)
        {
            $default = $field->integer_unsigned_default;
            
            $model->setAttribute($key, $default);
        }
        else
        {
            $model->setAttribute($key, (int)$value);
        }
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
     * @member Telenok.Core.Field.IntegerUnsigned.Controller
     */
    public function getModelSpecialAttribute($model, $key, $value)
    {
        if (in_array($key, ['integer_unsigned_default', 'integer_unsigned_min', 'integer_unsigned_max'], true) && $value === null)
        { 
            if ($key == 'integer_unsigned_default')
            {
                return 0;
            }
            else if ($key == 'integer_unsigned_min')
            {
                return 0;
            }
            else if ($key == 'integer_unsigned_max')
            {
                return 4294967295;
            }
        }

        return parent::getModelSpecialAttribute($model, $key, $value);
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
     * @return {Telenok.Core.Field.IntegerUnsigned.Controller}
     * @member Telenok.Core.Field.IntegerUnsigned.Controller
     */
    public function setModelSpecialAttribute($model, $key, $value)
    {
        if (in_array($key, ['integer_unsigned_default', 'integer_unsigned_min', 'integer_unsigned_max'], true))
        {
            if ($value === null)
            {
                if ($key == 'integer_unsigned_default')
                {
                    $value = 0;
                }
                else if ($key == 'integer_unsigned_min')
                {
                    $value = 0;
                }
                else if ($key == 'integer_unsigned_max')
                {
                    $value = 4294967295;
                }
            }
            else
            {
                $value = (int)$value;
            }
        }

        return parent::setModelSpecialAttribute($model, $key, $value);
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
     * @return {Telenok.Core.Field.IntegerUnsigned.Controller}
     * @member Telenok.Core.Field.IntegerUnsigned.Controller
     */
    public function preProcess($model, $type, $input)
    {
        $rule = ['integer'];

        if ($input->get('required'))
        {
            $rule[] = 'required';
        }

        if ($input->get('integer_unsigned_min'))
        {
            $rule[] = "min:" . (int)$input->get('integer_unsigned_min');
        }

        if ($input->get('integer_unsigned_max'))
        {
            $rule[] = "max:" . (int)$input->get('integer_unsigned_max');
        }

        $input->put('rule', $rule);
        $input->put('multilanguage', 0);
        $input->put('integer_unsigned_default', $input->get('integer_unsigned_default', null));
        
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
     * @return {Telenok.Core.Field.IntegerUnsigned.Controller}
     * @member Telenok.Core.Field.IntegerUnsigned.Controller
     */
    public function postProcess($model, $type, $input)
    {
        $table = $model->fieldObjectType()->first()->code;
        $fieldName = $model->getAttribute('code');

        if (!\Schema::hasColumn($table, $fieldName))
        {
            \Schema::table($table, function(Blueprint $table) use ($fieldName)
            {
                $table->integer($fieldName)->unsigned()->nullable();
            });
        }

        return parent::postProcess($model, $type, $input);
    }
}