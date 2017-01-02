<?php namespace Telenok\Core\Field\IntegerSigned;

use Illuminate\Database\Schema\Blueprint;

/**
 * @class Telenok.Core.Field.IntegerSigned.Controller
 * Class of field "integer-signed". Field can store integers.
 * 
 * @extends Telenok.Core.Abstraction.Field.Controller
 */
class Controller extends \Telenok\Core\Abstraction\Field\Controller {

    /**
     * @protected
     * @property {String} $key
     * Field key.
     * @member Telenok.Core.Field.IntegerSigned.Controller
     */
    protected $key = 'integer-signed';
    
    /**
     * @protected
     * @property {Array} $specialField
     * Define list of field's names to process saving and filling {@link Telenok.Core.Model.Object.Field Telenok.Core.Model.Object.Field}.
     * @member Telenok.Core.Field.IntegerSigned.Controller
     */
    protected $specialField = ['integer_signed_default', 'integer_signed_min', 'integer_signed_max'];
    
    /**
     * @protected
     * @property {Array} $ruleList
     * Define list of rules for special fields.
     * @member Telenok.Core.Field.IntegerSigned.Controller
     */
    protected $ruleList = [
                'integer_signed_default' => ['integer', 'between:-2147483648,2147483647'],
                'integer_signed_min' => ['integer', 'between:-2147483648,2147483647'],
                'integer_signed_max' => ['integer', 'between:-2147483648,2147483647']];
    
    /**
     * @protected
     * @property {Boolean} $allowMultilanguage
     * Field doesn't support multilanguage
     * @member Telenok.Core.Field.IntegerSigned.Controller
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
     * @member Telenok.Core.Field.IntegerSigned.Controller
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
     * @member Telenok.Core.Field.IntegerSigned.Controller
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
     * @param {Telenok.Core.Abstraction.Eloquent.Object.Model} $model
     * Eloquent object.
     * @param {String} $key
     * Field's name.
     * @param {mixed} $value
     * Value of field from database for processing in this method.
     * @param {Telenok.Core.Model.Object.Field} $field
     * Object with data of field's configuration.
     * @return {String}
     * @member Telenok.Core.Field.IntegerSigned.Controller
     */
    public function getModelAttribute($model, $key, $value, $field)
    {
        if ($value === null)
        {
            $value = $field->integer_signed_default?:0;
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
     * @member Telenok.Core.Field.IntegerSigned.Controller
     */
    public function setModelAttribute($model, $key, $value, $field)
    {
        if ($value === null)
        {
            $default = $field->integer_signed_default?:null;

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
     * @member Telenok.Core.Field.IntegerSigned.Controller
     */
    public function getModelSpecialAttribute($model, $key, $value)
    {
        if (in_array($key, ['integer_signed_default', 'integer_signed_min', 'integer_signed_max'], true) && $value === null)
        { 
            if ($key == 'integer_signed_default')
            {
                return 0;
            }
            else if ($key == 'integer_signed_min')
            {
                return -2147483648;
            }
            else if ($key == 'integer_signed_max')
            {
                return 2147483647;
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
     * @return {Telenok.Core.Field.IntegerSigned.Controller}
     * @member Telenok.Core.Field.IntegerSigned.Controller
     */
    public function setModelSpecialAttribute($model, $key, $value)
    {
        if (in_array($key, ['integer_signed_default', 'integer_signed_min', 'integer_signed_max'], true))
        {            
            if ($value === null)
            {
                if ($key == 'integer_signed_default')
                {
                    $value = 0;
                }
                else if ($key == 'integer_signed_min')
                {
                    $value = -2147483648;
                }
                else if ($key == 'integer_signed_max')
                {
                    $value = 2147483647;
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
     * @return {Telenok.Core.Field.IntegerSigned.Controller}
     * @member Telenok.Core.Field.IntegerSigned.Controller
     */
    public function preProcess($model, $type, $input)
    {
        $rule = ['integer'];

        if ($input->get('required'))
        {
            $rule[] = 'required';
        }

        if ($input->get('integer_signed_min'))
        {
            $rule[] = "min:" . (int)$input->get('integer_signed_min');
        }

        if ($input->get('integer_signed_max'))
        {
            $rule[] = "max:" . (int)$input->get('integer_signed_max');
        }

        $input->put('rule', $rule);
        $input->put('multilanguage', 0);
        $input->put('integer_signed_default', $input->get('integer_signed_default', null));
        
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
     * @return {Telenok.Core.Field.IntegerSigned.Controller}
     * @member Telenok.Core.Field.IntegerSigned.Controller
     */
    public function postProcess($model, $type, $input)
    {
        $table = $model->fieldObjectType()->first()->getAttribute('code');
        $fieldName = $model->getAttribute('code');

        if (!\Schema::hasColumn($table, $fieldName))
        {
            \Schema::table($table, function(Blueprint $table) use ($fieldName)
            {
                $table->integer($fieldName)->nullable();
            });
        }

        return parent::postProcess($model, $type, $input);
    }
}