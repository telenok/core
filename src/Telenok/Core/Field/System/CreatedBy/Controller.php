<?php namespace Telenok\Core\Field\System\CreatedBy;

use Illuminate\Database\Schema\Blueprint;

/**
 * @class Telenok.Core.Field.System.CreatedBy.Controller
 * Class of field "created-by". Field allow to store data about date
 * and creator.
 * 
 * @extends Telenok.Core.Field.RelationOneToMany.Controller
 */
class Controller extends \Telenok\Core\Field\RelationOneToMany\Controller {

    /**
     * @protected
     * @property {String} $key
     * Field key.
     * @member Telenok.Core.Field.System.CreatedBy.Controller
     */
    protected $key = 'created-by';
    
    /**
     * @protected
     * @property {String} $routeListTitle
     * Router's name to show list.
     * @member Telenok.Core.Field.System.CreatedBy.Controller
     */
    protected $routeListTitle = "telenok.field.relation-one-to-many.list.title";

    /**
     * @method getModelFieldViewVariable
     * Return array with URL for variables in $viewModel view.
     * 
     * @param {Telenok.Core.Field.RelationOneToMany.Controller} $controller
     * @param {Telenok.Core.Abstraction.Eloquent.Object.Model} $model
     * @param {Telenok.Core.Model.Object.Field} $field
     * @param {String} $uniqueId
     * 
     * @return {Array}
     * @member Telenok.Core.Field.System.CreatedBy.Controller
     */
    public function getModelFieldViewVariable($controller = null, $model = null, $field = null, $uniqueId = null)
    {
    }

    /**
     * @method getDateField
     * Define list of date fields in Eloquent object to process it saving and filling.
     * 
     * @param {Telenok.Core.Abstraction.Eloquent.Object.Model} $model
     * Eloquent object.
     * @param {Telenok.Core.Model.Object.Field} $field
     * Object with data of field's configuration.
     * @return {Array}
     * @member Telenok.Core.Field.System.CreatedBy.Controller
     */
    public function getDateField($model, $field)
    {
        return ['created_at'];
    }

    /**
     * @method getModelFillableField
     * Define list of fields in Eloquent object which can be filled by user.
     * 
     * @param {Telenok.Core.Abstraction.Eloquent.Object.Model} $model
     * Eloquent object.
     * @param {Telenok.Core.Model.Object.Field} $field
     * Object with data of field's configuration.
     * @return {Array}
     * @member Telenok.Core.Field.System.CreatedBy.Controller
     */
    public function getModelFillableField($model, $field)
    {
        return $field->relation_one_to_many_belong_to ? [$field->code, 'created_at'] : [];
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
     * @member Telenok.Core.Field.System.CreatedBy.Controller
     */
    public function getModelAttribute($model, $key, $value, $field)
    {
        if ($key == 'created_at' && $value === null)
        {
            $value = \Carbon\Carbon::now();
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
     * @member Telenok.Core.Field.System.CreatedBy.Controller
     */
    public function setModelAttribute($model, $key, $value, $field)
    {
        if ($key == 'created_by_user' && $value === null)
        {
            $value = app('auth')->check() ? app('auth')->user()->id : 0;
        }
        else if ($key == 'created_at' && $value === null)
        {
            $value = \Carbon\Carbon::now();
        }

        $model->setAttribute($key, $value);
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
     * @return {Telenok.Core.Field.System.CreatedBy.Controller}
     * @member Telenok.Core.Field.System.CreatedBy.Controller
     */
    public function preProcess($model, $type, $input)
    {
        $translationSeed = $this->translationSeed();

        $input->put('title', array_get($translationSeed, 'model.created_by'));
        $input->put('title_list', array_get($translationSeed, 'model.created_by'));
        $input->put('code', 'created_by_user');
        $input->put('active', 1);
        $input->put('multilanguage', 0);
        $input->put('allow_create', 0);
        $input->put('allow_update', 0);
        $input->put('relation_one_to_many_belong_to', app('db')->table('object_type')->where('code', 'user')->value('id'));
        $input->put('multilanguage', 0);
        $input->put('allow_sort', 0);
        $input->put('allow_search', $input->get('allow_search', 1));

        if (!$input->get('field_object_tab'))
        {
            $input->put('field_object_tab', 'additionally');
        }

        $tab = $this->getFieldTab($input->get('field_object_type'), $input->get('field_object_tab', 'additionally'));

        $input->put('field_object_tab', $tab->getKey());

        $table = \App\Vendor\Telenok\Core\Model\Object\Type::find($input->get('field_object_type'))->code;

        $fieldName = 'created_by_user';

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
     * @return {Telenok.Core.Field.System.CreatedBy.Controller}
     * @member Telenok.Core.Field.System.CreatedBy.Controller
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
     * @member Telenok.Core.Field.System.CreatedBy.Controller
     */
    public function translationSeed()
    {
        return [
            'model' => [
                'created_by' => ['en' => 'Created by', 'ru' => 'Создано'],
            ],
        ];
    }
}