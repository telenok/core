<?php namespace Telenok\Core\Field\System\LockedBy;

use Illuminate\Database\Schema\Blueprint;

/**
 * @class Telenok.Core.Field.System.LockedBy.Controller
 * Class of field "locked-by". Field allow to store data about date
 * and locker.
 *  
 * @extends Telenok.Core.Field.RelationOneToMany.Controller
 */
class Controller extends \Telenok\Core\Field\RelationOneToMany\Controller {

    /**
     * @protected
     * @property {String} $key
     * Field key.
     * @member Telenok.Core.Field.System.LockedBy.Controller
     */
    protected $key = 'locked-by';
    
    /**
     * @protected
     * @property {String} $routeListTitle
     * Router's name to show list.
     * @member Telenok.Core.Field.System.LockedBy.Controller
     */  
    protected $routeListTitle = "telenok.field.relation-one-to-many.list.title";

    /**
     * @method getFormModelViewVariable
     * Return array with URL for variables in $viewModel view.
     * 
     * @param {Telenok.Core.Field.RelationOneToMany.Controller} $controller
     * @param {Telenok.Core.Abstraction.Eloquent.Object.Model} $model
     * @param {Telenok.Core.Model.Object.Field} $field
     * @param {String} $uniqueId
     * 
     * @return {Array}
     * @member Telenok.Core.Field.System.LockedBy.Controller
     */
    public function getFormModelViewVariable($controller = null, $model = null, $field = null, $uniqueId = null)
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
     * @member Telenok.Core.Field.System.LockedBy.Controller
     */
    public function getDateField($model, $field)
    {
        return ['locked_at'];
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
     * @member Telenok.Core.Field.System.LockedBy.Controller
     */
    public function getModelFillableField($model, $field)
    {
        return $field->relation_one_to_many_belong_to ? [$field->code, 'locked_at'] : [];
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
     * @member Telenok.Core.Field.System.LockedBy.Controller
     */
    public function getModelAttribute($model, $key, $value, $field)
    {
        if ($key == 'locked_at' && $value === null)
        {
            $value = \Carbon\Carbon::now();
        }

        return $value;
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
     * @return {Telenok.Core.Field.System.LockedBy.Controller}
     * @member Telenok.Core.Field.System.LockedBy.Controller
     */
    public function preProcess($model, $type, $input)
    {
        $translationSeed = $this->translationSeed();

        $input->put('title', array_get($translationSeed, 'model.locked_by'));
        $input->put('title_list', array_get($translationSeed, 'model.locked_by'));
        $input->put('code', 'locked_by_user');
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

        $fieldName = 'locked_by_user';

        if (!\Schema::hasColumn($table, $fieldName))
        {
            \Schema::table($table, function(Blueprint $table) use ($fieldName)
            {
                $table->integer($fieldName)->unsigned()->nullable();
            });
        }

        $fieldName = 'locked_at';

        if (!\Schema::hasColumn($table, $fieldName))
        {
            \Schema::table($table, function(Blueprint $table) use ($fieldName)
            {
                $table->timestamp($fieldName)->nullable();
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
     * @return {Telenok.Core.Field.System.LockedBy.Controller}
     * @member Telenok.Core.Field.System.LockedBy.Controller
     */
    public function postProcess($model, $type, $input)
    {
        $classBelongTo = app('db')->table('object_type')->where('code', 'user')->value('model_class');

        (new $classBelongTo)->eraseCachedFields();

        return $this;
    }

    /**
     * @method translationSeed
     * Return multilanguage array
     * 
     * @return {Array}
     * @member Telenok.Core.Field.System.LockedBy.Controller
     */
    public function translationSeed()
    {
        return [
            'model' => [
                'locked_by' => ['en' => 'Locked by', 'ru' => 'Занято'],
            ],
        ];
    }
}