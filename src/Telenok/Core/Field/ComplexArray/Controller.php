<?php namespace Telenok\Core\Field\ComplexArray;

use Illuminate\Database\Schema\Blueprint;

/**
 * @class Telenok.Core.Field.ComplexArray.Controller
 * Class of field "complex-array". Field can store any plain types 
 * (String, Number, etc) and objects with JsonSerializable interface.
 * 
 * @extends Telenok.Core.Abstraction.Field.Controller
 */
class Controller extends \Telenok\Core\Abstraction\Field\Controller {

    /**
     * @protected
     * @property {String} $key
     * Field key.
     * @member Telenok.Core.Field.ComplexArray.Controller
     */
    protected $key = 'complex-array';

    /**
     * @protected
     * @property {Boolean} $allowMultilanguage
     * Field doesn't support multilanguage
     * @member Telenok.Core.Field.ComplexArray.Controller
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
     * @member Telenok.Core.Field.ComplexArray.Controller
     */
    public function getListFieldContent($field, $item, $type = null)
    {
        if ($item instanceof \Illuminate\Support\Collection)
        {
            return 'Complex array';
        } 
        else
        {
            return e(\Str::limit($item->{$field->code}, 20));
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
     * @member Telenok.Core.Field.ComplexArray.Controller
     */
    public function getModelAttribute($model, $key, $value, $field)
    {
        $value = $value === null || $value === "" ? '[]' : $value;

        $v = json_decode($value, true);

        if (is_array($v))
        {
            return collect($v);
        } 
        else
        {
            return $v;
        }
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
     * @member Telenok.Core.Field.ComplexArray.Controller
     */
    public function setModelAttribute($model, $key, $value, $field)
    {
        if ($value instanceof \Illuminate\Support\Collection)
        {
            $value_ = $value->toArray();
        } 
        else
        {
            $value_ = $value === null ? [] : $value;
        }

        $model->setAttribute($key, json_encode($value_, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
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
     * @member Telenok.Core.Field.ComplexArray.Controller
     */
    public function getFilterQuery($field = null, $model = null, $query = null, $name = null, $value = null)
    {
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
     * @return {Telenok.Core.Field.ComplexArray.Controller}
     * @member Telenok.Core.Field.ComplexArray.Controller
     */
    public function preProcess($model, $type, $input)
    {
        $input->put('multilanguage', 0);
        $input->put('allow_sort', 0);

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
     * @return {Telenok.Core.Field.ComplexArray.Controller}
     * @member Telenok.Core.Field.ComplexArray.Controller
     */
    public function postProcess($model, $type, $input)
    {
        $table = $model->fieldObjectType()->first()->getAttribute('code');
        $fieldName = $model->getAttribute('code');

        if (!\Schema::hasColumn($table, $fieldName))
        {
            \Schema::table($table, function(Blueprint $table) use ($fieldName)
            {
                $table->longText($fieldName)->nullable();
            });
        }

        return parent::postProcess($model, $type, $input);
    }
}