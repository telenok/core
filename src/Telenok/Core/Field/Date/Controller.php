<?php namespace Telenok\Core\Field\Date;

use Illuminate\Database\Schema\Blueprint;

/**
 * @class Telenok.Core.Field.Date.Controller
 * Class of field "date". Field can store dates.
 * 
 * @extends Telenok.Core.Abstraction.Field.Controller
 */
class Controller extends \Telenok\Core\Abstraction\Field\Controller {

    /**
     * @protected
     * @property {String} $key
     * Field key.
     * @member Telenok.Core.Field.Date.Controller
     */
    protected $key = 'date';

    /**
     * @protected
     * @property {Boolean} $allowMultilanguage
     * Field doesn't support multilanguage
     * @member Telenok.Core.Field.Date.Controller
     */
    protected $allowMultilanguage = false;
    
    /**
     * @protected
     * @property {Array} $specialDateField
     * Define list of date field's names to process saving and filling {@link Telenok.Core.Model.Object.Field Telenok.Core.Model.Object.Field}.
     * @member Telenok.Core.Field.Date.Controller
     */
    protected $specialDateField = ['date_default'];

    /**
     * @method getDateField
     * Define list of date fields in Eloquent object to process it saving and filling.
     * 
     * @param {Telenok.Core.Abstraction.Eloquent.Object.Model} $model
     * Eloquent object.
     * @param {Telenok.Core.Model.Object.Field} $field
     * Object with data of field's configuration.
     * @return {Array}
     * @member Telenok.Core.Field.Date.Controller
     */
    public function getDateField($model, $field)
    { 
        return [$field->code];
    }

    /**
     * @method getListFieldContent
     * Return value of fields for show in list cell like Javascript Datatables().
     * 
     * @param {Telenok.Core.Model.Object.Field} $field
     * Object with data of field's configuration.
     * @param {Object} $item
     * Eloquent object with data of list's row.
     * @param {Telenok.Core.Model.Object.Type} $type
     * Type of eloquent object $item.
     * @return {String}
     * @member Telenok.Core.Field.Date.Controller
     */
    public function getListFieldContent($field, $item, $type = null)
    {  
        return e((string)$item->{$field->code});
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
     * @member Telenok.Core.Field.Date.Controller
     */
    public function setModelAttribute($model, $key, $value, $field)
    {
        if ($value === null)
        {
            $value = $field->date_default ?: null;
        }

        return parent::setModelAttribute($model, $key, $value, $field);
    }

    /**
     * @method getModelAttribute
     * Return value of field's attributes.
     *
     * @param {Telenok.Core.Abstraction.Eloquent.Object.Model} $model
     * Eloquent object.
     * @param {String} $key
     * Code of field in $model.
     * @param {mixed} $value
     * @param {Telenok.Core.Model.Object.Field} $field
     * Object with data of field's configuration.
     *
     * @return {mixed}
     * @member Telenok.Core.Field.Date.Controller
     */
    public function getModelAttribute($model, $key, $value, $field)
    {
        try
        {
            return \Carbon\Carbon::createFromFormat('Y-m-d', $value === null ? $field->date_default : $value);
        }
        catch (\Exception $e)
        {
            return null;
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
     * @member Telenok.Core.Field.Date.Controller
     */
    public function getModelSpecialAttribute($model, $key, $value)
    {
        try
        {
            if ($key == 'date_default')
            { 
                return $value === null ? \Carbon\Carbon::now() : $value;
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
     * @return {Telenok.Core.Field.Date.Controller}
     * @member Telenok.Core.Field.Date.Controller
     */
    public function setModelSpecialAttribute($model, $key, $value)
    {  
        if ($key == 'date_default' && $value === null)
        {
            $value = \Carbon\Carbon::now();
        }

        return parent::setModelSpecialAttribute($model, $key, $value);
    }

    /**
     * @method getFilterContent
     * Return HTML of filter field in search form.
     * 
     * @param {Telenok.Core.Model.Object.Field} $field
     * Object with data of field's configuration.
     * @return {String}
     * @member Telenok.Core.Field.Date.Controller
     */
    public function getFilterContent($field = null)
    {
        return view($this->getViewFilter(), [
            'controller' => $this,
            'field' => $field,
        ])->render();
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
     * @member Telenok.Core.Field.Date.Controller
     */
    public function getFilterQuery($field = null, $model = null, $query = null, $name = null, $value = null) 
    {
        if ($value !== null)
        {
            $query->where(function($query) use ($value, $name, $model)
            {
                if ($v = trim(array_get($value, 'start')))
                {
                    $query->where($model->getTable() . '.' . $name, '>=', $v);
                }

                if ($v = trim(array_get($value, 'end')))
                {
                    $query->where($model->getTable() . '.' . $name, '<=', $v);
                }
            });
        }
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
     * @return {Telenok.Core.Field.Date.Controller}
     * @member Telenok.Core.Field.Date.Controller
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
     * Post prosess saving {@link Telenok.Core.Model.Object.Field $model}.
     * 
     * @param {Telenok.Core.Model.Object.Field} $model
     * Object to save.
     * @param {Telenok.Core.Model.Object.Type} $type
     * Object with data of field's configuration.
     * @param {Illuminate.Http.Request} $input
     * Laravel request object.
     * @return {Telenok.Core.Field.Date.Controller}
     * @member Telenok.Core.Field.Date.Controller
     */
    public function postProcess($model, $type, $input)
    {
        $table = $model->fieldObjectType()->first()->code;
        $fieldName = $model->code;

        if (!\Schema::hasColumn($table, $fieldName))
        {
            \Schema::table($table, function(Blueprint $table) use ($fieldName)
            {
                $table->date($fieldName)->nullable();
            });
        }
        
        return parent::postProcess($model, $type, $input);
    }
}