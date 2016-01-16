<?php namespace Telenok\Core\Field\System\DeletedBy;

use Illuminate\Database\Schema\Blueprint;

/**
 * @class Telenok.Core.Field.System.DeletedBy.Controller
 * Class of field "deleted-by". Field allow to store data about date
 * and deleter.
 *  
 * @extends Telenok.Core.Field.RelationOneToMany.Controller
 */
class Controller extends \Telenok\Core\Field\RelationOneToMany\Controller {

    /**
     * @protected
     * @property {String} $key
     * Field key.
     * @member Telenok.Core.Field.System.DeletedBy.Controller
     */
    protected $key = 'deleted-by';

    /**
     * @protected
     * @property {String} $routeListTitle
     * Router's name to show list.
     * @member Telenok.Core.Field.System.DeletedBy.Controller
     */  
    protected $routeListTitle = "telenok.field.relation-one-to-many.list.title";

    /**
     * @method getModelFieldViewVariable
     * Return array with URL for variables in $viewModel view.
     * 
     * @param {Telenok.Core.Field.RelationOneToMany.Controller} $controller
     * @param {Telenok.Core.Interfaces.Eloquent.Object} $model
     * @param {Telenok.Core.Model.Object.Field} $field
     * @param {String} $uniqueId
     * 
     * @return {Array}
     * @member Telenok.Core.Field.System.DeletedBy.Controller
     */
    public function getModelFieldViewVariable($controller = null, $model = null, $field = null, $uniqueId = null)
    {
    }

    /**
     * @method getDateField
     * Define list of date fields in Eloquent object to process it saving and filling.
     * 
     * @param {Telenok.Core.Interfaces.Eloquent.Object} $model
     * Eloquent object.
     * @param {Telenok.Core.Model.Object.Field} $field
     * Object with data of field's configuration.
     * @return {Array}
     * @member Telenok.Core.Field.System.DeletedBy.Controller
     */
    public function getDateField($model, $field)
    {
        return ['deleted_at'];
    }

    /**
     * @method getModelFillableField
     * Define list of fields in Eloquent object which can be filled by user.
     * 
     * @param {Telenok.Core.Interfaces.Eloquent.Object} $model
     * Eloquent object.
     * @param {Telenok.Core.Model.Object.Field} $field
     * Object with data of field's configuration.
     * @return {Array}
     * @member Telenok.Core.Field.System.DeletedBy.Controller
     */
    public function getModelFillableField($model, $field)
    {
        return $field->relation_one_to_many_belong_to ? [$field->code, 'deleted_at'] : [];
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
     * @member Telenok.Core.Field.System.DeletedBy.Controller
     */
    public function setModelAttribute($model, $key, $value, $field)
    {
        if ($key == 'deleted_by_user' && $value === null && $model->deleted_at !== null)
        {
            $value = app('auth')->check() ? app('auth')->user()->id : 0;
        }

        $model->setAttribute($key, $value);
    }

    /**
     * @method getFilterContentOption
     * Add options to html select for filtering.
     * 
     * @param {Telenok.Core.Model.Object.Field} $field
     * Object with data of field's configuration.
     * @return {Array}
     * @member Telenok.Core.Field.System.DeletedBy.Controller
     */
    public function getFilterContentOption($field = null)
    {
        return ["<option value='*'>" . $this->LL('title.value.any') . "</option>"];
    }

    /**
     * @method getTitleList
     * Return array with titles of model's records
     * 
     * @param {Integer} $id
     * ID of Telenok.Core.Model.Object.Type 
     * @param {Function} $closure
     * Closure to adding eloquent builder's query filter
     * 
     * @return {Array}
     * @member Telenok.Core.Field.System.DeletedBy.Controller
     */
    public function getTitleList($id = null, $closure = null)
    {
        $list = ['value' => "*", 'text' => $this->LL('title.value.any')];

        if ($list_ = parent::getTitleList($id, $closure))
        {
            $list = $list + $list_;
        }

        return $list;
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
     * @member Telenok.Core.Field.System.DeletedBy.Controller
     */
    public function getFilterQuery($field = null, $model = null, $query = null, $name = null, $value = null)
    {
        if (empty($value))
        {
            $query->whereNull($model->getTable() . '.deleted_at');
        }
        else
        {
            $query->whereNotNull($model->getTable() . '.deleted_at');

            if (!in_array("*", $value))
            {
                parent::getFilterQuery($field, $model, $query, $name, $value);
            }
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
     * @return {Telenok.Core.Field.System.DeletedBy.Controller}
     * @member Telenok.Core.Field.System.DeletedBy.Controller
     */
    public function preProcess($model, $type, $input)
    {
        $translationSeed = $this->translationSeed();

        $input->put('title', array_get($translationSeed, 'model.deleted_by'));
        $input->put('title_list', array_get($translationSeed, 'model.deleted_by'));
        $input->put('code', 'deleted_by_user');
        $input->put('active', 1);
        $input->put('multilanguage', 0);
        $input->put('allow_create', 0);
        $input->put('allow_update', 0);
        $input->put('relation_one_to_many_belong_to', app('db')->table('object_type')->where('code', 'user')->pluck('id'));
        $input->put('multilanguage', 0);
        $input->put('allow_sort', 0);
        $input->put('allow_search', $input->get('allow_search', 1));

        if (!$input->get('field_object_tab'))
        {
            $input->put('field_object_tab', 'additionally');
        }

        $tab = $this->getFieldTab($input->get('field_object_type'), $input->get('field_object_tab', 'additionally'));

        $input->put('field_object_tab', $tab->getKey());

        $table = \App\Telenok\Core\Model\Object\Type::find($input->get('field_object_type'))->code;

        $fieldName = 'deleted_by_user';

        if (!\Schema::hasColumn($table, $fieldName))
        {
            \Schema::table($table, function(Blueprint $table) use ($fieldName)
            {
                $table->integer($fieldName)->unsigned()->nullable();
            });
        }

        return $this;
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
     * @return {Telenok.Core.Field.System.DeletedBy.Controller}
     * @member Telenok.Core.Field.System.DeletedBy.Controller
     */
    public function postProcess($model, $type, $input)
    {
        return $this;
    }

    /**
     * @method translationSeed
     * Return multilanguage array
     * 
     * @return {Array}
     * @member Telenok.Core.Field.System.DeletedBy.Controller
     */
    public function translationSeed()
    {
        return [
            'model' => [
                'deleted_by' => ['en' => 'Deleted by', 'ru' => 'Создано'],
            ],
        ];
    }
}