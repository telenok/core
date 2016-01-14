<?php namespace Telenok\Core\Field\Decimal;

use Illuminate\Database\Schema\Blueprint;

/**
 * @class Telenok.Core.Field.Decimal.Controller
 * Class of field "decimal". Field allow to manipulate big numbers.
 * 
 * @extends Telenok.Core.Interfaces.Field.Controller
 */
class Controller extends \Telenok\Core\Interfaces\Field\Controller {

    /**
     * @protected
     * @property {String} $key
     * Field key.
     * @member Telenok.Core.Field.Decimal.Controller
     */
    protected $key = 'decimal';
    
    /**
     * @protected
     * @property {Array} $specialDateField
     * Define list of field's names to process saving and filling {@link Telenok.Core.Model.Object.Field Telenok.Core.Model.Object.Field}.
     * @member Telenok.Core.Field.Decimal.Controller
     */
    protected $specialField = ['decimal_default', 'decimal_min', 'decimal_max', 'decimal_precision', 'decimal_scale'];

    /**
     * @protected
     * @property {Array} $ruleList
     * Define list of rules for special fields.
     * @member Telenok.Core.Field.Decimal.Controller
     */
    protected $ruleList = [
                'decimal_default' => ['string', 'max:37'], 
                'decimal_min' => ['string', 'max:37'], 
                'decimal_precision' => ['integer', 'max:37'],
                'decimal_scale' => ['integer', 'max:37'],
            ];
    
    /**
     * @protected
     * @property {Boolean} $allowMultilanguage
     * Field doesn't support multilanguage
     * @member Telenok.Core.Field.Decimal.Controller
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
     * @member Telenok.Core.Field.Decimal.Controller
     */
    public function getListFieldContent($field, $item, $type = null)
    {
        return $item->{$field->code}->value();
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
     * @member Telenok.Core.Field.Decimal.Controller
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
     * @member Telenok.Core.Field.Decimal.Controller
     */
    public function getModelAttribute($model, $key, $value, $field)
    {
        if ($value === null)
        {
            $value = $field->decimal_default;
        }
        
        return \App\Telenok\Core\Field\Decimal\BigDecimal::create($value, $field->decimal_scale);
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
     * @member Telenok.Core.Field.Decimal.Controller
     */
    public function setModelAttribute($model, $key, $value, $field)
    {
        if ($value instanceof \Telenok\Core\Field\Decimal\BigDecimal)
        {
            $value_ = $value->value();
        }
        else if ($value !== null)
        {
            $value_ = $value;
        }
        else
        {
            $value_ = $field->decimal_default;
        }

        $model->setAttribute($key, $value_);
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
     * @member Telenok.Core.Field.Decimal.Controller
     */
    public function getModelSpecialAttribute($model, $key, $value)
    {
        if (in_array($key, ['decimal_default', 'decimal_min', 'decimal_max', 'decimal_precision', 'decimal_scale'], true) && $value === null)
        { 
            if ($key == 'decimal_default')
            {
                return \App\Telenok\Core\Field\Decimal\BigDecimal::create(0, 2);
            }
            else if ($key == 'decimal_min')
            {
                return \App\Telenok\Core\Field\Decimal\BigDecimal::create('-9999999999999999999999999999', 2);
            }
            else if ($key == 'decimal_max')
            {
                return \App\Telenok\Core\Field\Decimal\BigDecimal::create('9999999999999999999999999999', 2);
            }
            else if ($key == 'decimal_precision')
            {
                return 30;
            }
            else if ($key == 'decimal_scale')
            {
                return 2;
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
     * @return {Telenok.Core.Field.Decimal.Controller}
     * @member Telenok.Core.Field.Decimal.Controller
     */
    public function setModelSpecialAttribute($model, $key, $value)
    {
        if (in_array($key, ['decimal_default', 'decimal_min', 'decimal_max', true]) && $value instanceof \App\Telenok\Core\Field\Decimal\BigDecimal)
        {
            $value = $value->value();
        }
        else if (in_array($key, ['decimal_default', 'decimal_min', 'decimal_max', 'decimal_precision', 'decimal_scale'], true) && $value === null)
        {            
            if ($key == 'decimal_default')
            {
                $value = 0;
            }
            else if ($key == 'decimal_min')
            {
                $value = '-9999999999999999999999999999.00';
            }
            else if ($key == 'decimal_max')
            {
                $value = '9999999999999999999999999999.00';
            }
        }

        return parent::setModelSpecialAttribute($model, $key, $value);
    }

    /**
     * @method validate
     * Validate values of special field's attributes.
     * 
     * @param {Telenok.Core.Model.Object.Field} $model
     * Eloquent Field object.
     * @param {Illuminate.Support.Collection} $input
     * Values of request.
     * @param {Array} $messages
     * Array of custom messsages attributes.
     * @return {Telenok.Core.Field.Decimal.Controller}
     * @member Telenok.Core.Field.Decimal.Controller
     * @throws Telenok.Core.Support.Exception.Validator
     */
    public function validate($model = null, $input = [], $messages = [])
	{
		if ($input->get('decimal_precision') < $input->get('decimal_scale'))
        {
			throw $this->validateException()->setMessageError($this->LL('error.precision_scale'));
        }

		return parent::validate($model, $input, $messages);
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
     * @return {Telenok.Core.Field.Decimal.Controller}
     * @member Telenok.Core.Field.Decimal.Controller
     */
    public function preProcess($model, $type, $input)
    {
        $rule = ['numeric'];

        if ($input->get('required'))
        {
            $rule[] = 'required';
        }

        $input->put('rule', $rule);
        $input->put('multilanguage', 0);
        $input->put('decimal_default', $input->get('decimal_default', null));
        
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
     * @return {Telenok.Core.Field.Decimal.Controller}
     * @member Telenok.Core.Field.Decimal.Controller
     */
    public function postProcess($model, $type, $input)
    {
        $table = $model->fieldObjectType()->first()->getAttribute('code');
        $fieldName = $model->getAttribute('code');

        if (!\Schema::hasColumn($table, $fieldName))
        {
            \Schema::table($table, function(Blueprint $table) use ($fieldName)
            {
                $table->decimal($fieldName)->nullable();
            });
        }

        return parent::postProcess($model, $type, $input);
    }
}