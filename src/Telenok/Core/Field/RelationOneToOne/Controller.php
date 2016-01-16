<?php namespace Telenok\Core\Field\RelationOneToOne;

use Illuminate\Database\Schema\Blueprint;

/**
 * @class Telenok.Core.Field.RelationOneToOne.Controller
 * Class of field "relation-one-to-one". Field allow to link objects.
 * 
 * @extends Telenok.Core.Interfaces.Field.Relation.Controller
 */
class Controller extends \Telenok\Core\Interfaces\Field\Relation\Controller {

    /**
     * @protected
     * @property {String} $key
     * Field key.
     * @member Telenok.Core.Field.RelationOneToOne.Controller
     */
    protected $key = 'relation-one-to-one';

    /**
     * @protected
     * @property {Array} $specialField
     * Define list of field's names to process saving and filling {@link Telenok.Core.Model.Object.Field Telenok.Core.Model.Object.Field}.
     * @member Telenok.Core.Field.RelationOneToOne.Controller
     */
    protected $specialField = ['relation_one_to_one_has', 'relation_one_to_one_belong_to', 'relation_one_to_one_default'];

    /**
     * @protected
     * @property {Boolean} $allowMultilanguage
     * Field doesn't support multilanguage
     * @member Telenok.Core.Field.RelationOneToOne.Controller
     */
    protected $allowMultilanguage = false;

    /**
     * @method getLinkedModelType
     * Return Object Type of field
     * 
     * @param {Telenok.Core.Model.Object.Field} $field
     * @return {Telenok.Core.Model.Object.Type}
     * @member Telenok.Core.Field.RelationOneToOne.Controller
     */
    public function getLinkedModelType($field)
    {
        return \App\Telenok\Core\Model\Object\Type::whereIn('id', [$field->relation_one_to_one_has, $field->relation_one_to_one_belong_to])->first();
    }

    /**
     * @method getLinkedField
     * Define name of special field.
     * 
     * @param {Telenok.Core.Model.Object.Field} $field
     * Object with data of field's configuration.
     * @return {String}
     * @member Telenok.Core.Field.RelationOneToOne.Controller
     */
    public function getLinkedField($field)
    {
        return $field->relation_one_to_one_has ? 'relation_one_to_one_has' : 'relation_one_to_one_belong_to';
    }

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
     * @member Telenok.Core.Field.RelationOneToOne.Controller
     */
    public function getModelFieldViewVariable($controller = null, $model = null, $field = null, $uniqueId = null)
    {
        $linkedField = $this->getLinkedField($field);

        return [
            'urlListTitle' => route($this->getRouteListTitle(), ['id' => (int) $field->{$linkedField}]),
            'urlListTable' => route($this->getRouteListTable(), ["id" => (int) $model->getKey(), "fieldId" => $field->getKey(), "uniqueId" => $uniqueId]),
            'urlWizardChoose' => route($this->getRouteWizardChoose(), ['typeId' => $this->getChooseTypeId($field)]),
            'urlWizardCreate' => route($this->getRouteWizardCreate(), [ 'id' => $field->{$linkedField}, 'saveBtn' => 1, 'chooseBtn' => 1]),
            'urlWizardEdit' => route($this->getRouteWizardEdit(), ['id' => '--id--', 'saveBtn' => 1]),
        ];
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
     * @member Telenok.Core.Field.RelationOneToOne.Controller
     */
    public function getModelFillableField($model, $field)
    {
        return $field->relation_one_to_one_belong_to ? [$field->code] : [];
    }

    /**
     * @method getFormModelContent
     * Return HTML content of form element for the field
     * 
     * @param {Telenok.Core.Field.RelationOneToMany.Controller} $controller
     * @param {Telenok.Core.Interfaces.Eloquent.Object} $model
     * @param {Telenok.Core.Model.Object.Field} $field
     * @param {String} $uniqueId
     * @return {String}
     * @member Telenok.Core.Field.RelationOneToMany.Controller
     */
    public function getFormModelContent($controller = null, $model = null, $field = null, $uniqueId = null)
    {
        if ($field->relation_one_to_one_has || $field->relation_one_to_one_belong_to)
        {
            return parent::getFormModelContent($controller, $model, $field, $uniqueId);
        }
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
     * @member Telenok.Core.Field.RelationOneToOne.Controller
     */
    public function getFilterQuery($field = null, $model = null, $query = null, $name = null, $value = null)
    {
        if (!empty($value))
        {
            if ($field->relation_one_to_one_belong_to)
            {
                $query->whereIn($name, (array) $value);
            }
            else
            {
                $method = camel_case($field->code);

                $relatedQuery = $model->{$method}();

                $linkedTable = $relatedQuery->getRelated()->getTable();

                $alias = $linkedTable . $field->code;

                $query->join($linkedTable . ' as ' . $alias, function($join) use ($linkedTable, $relatedQuery, $model, $alias)
                {
                    $join->on($model->getTable() . '.id', '=', $alias . '.' . $relatedQuery->getPlainForeignKey());
                });

                $query->whereIn($alias . '.id', (array) $value);
            }
        }
    }

    /**
     * @method getFilterContent
     * Return HTML of filter field in search form.
     * 
     * @param {Telenok.Core.Model.Object.Field} $field
     * Object with data of field's configuration.
     * @return {String}
     * @member Telenok.Core.Field.RelationOneToOne.Controller
     */
    public function getFilterContent($field = null)
    {
        $uniqueId = str_random();
        $option = [];

        $id = $field->relation_one_to_one_has ? : $field->relation_one_to_one_belong_to;

        $class = \App\Telenok\Core\Model\Object\Sequence::getModel($id)->class_model;

        $model = app($class);

        $model::withPermission()->groupBy($model->getTable() . '.id')->take(20)->get()->each(function($item) use (&$option)
        {
            $option[] = "<option value='{$item->id}'>[{$item->id}] {$item->translate('title')}</option>";
        });

        $option[] = "<option value='0' disabled='disabled'>...</option>";

        return '
            <select class="chosen-select" multiple data-placeholder="' . $this->LL('notice.choose') . '" id="input' . $uniqueId . '" name="filter[' . $field->code . '][]">
            ' . implode('', $option) . ' 
            </select>
            <script type="text/javascript">
                jQuery("#input' . $uniqueId . '").ajaxChosen({
                    keepTypingMsg: "' . $this->LL('notice.typing') . '",
                    lookingForMsg: "' . $this->LL('notice.looking-for') . '",
                    type: "GET",
                    url: "' . route($this->getRouteListTitle(), ['id' => $id]) . '",
                    dataType: "json",
                    minTermLength: 1
                }, 
                function (data) 
                {
                    var results = [];

                    jQuery.each(data, function (i, val) {
                        results.push({ value: val.value, text: val.text });
                    });

                    return results;
                },
                {
                    width: "200px",
                    no_results_text: "' . $this->LL('notice.not-found') . '" 
                });
            </script>';
    }

    /**
     * @method fill
     * Fill model attributes before calling saveModelField.
     * 
     * @param {Telenok.Core.Interfaces.Eloquent.Object} $model
     * Eloquent object.
     * @param {Telenok.Core.Model.Object.Field} $field
     * Object with data of field's configuration.
     * @param {Illuminate.Support.Collection} $input
     * Values of request.
     * @return {Telenok.Core.Field.RelationOneToOne.Controller}
     * @member Telenok.Core.Field.RelationOneToOne.Controller
     */
    public function fill($field, $model, $input)
    {
        if ($input->get($field->code) === null)
        {
            $input->put($field->code, $field->relation_one_to_one_default);
        }

        return parent::fill($field, $model, $input);
    }

    /**
     * @method saveModelField
     * Save eloquent model with field's data.
     * 
     * @param {Telenok.Core.Model.Object.Field} $field
     * Eloquent object Field.
     * @param {Telenok.Core.Interfaces.Eloquent.Object} $model
     * Eloquent object.
     * @param {Illuminate.Support.Collection} $input
     * Values of request.
     * @return {Telenok.Core.Interfaces.Eloquent.Object}
     * @member Telenok.Core.Field.RelationOneToOne.Controller
     */
    public function saveModelField($field, $model, $input)
    {
        if ($model instanceof \Telenok\Core\Model\Object\Field && !$input->get('id'))
        {
            return $model;
        }

        $relatedQuery = $model->{camel_case($field->code)}();

        if ($field->relation_one_to_one_has)
        {
            try
            {
                $relatedQuery->getRelated()->findOrFail((int) $input->get($field->code, 0))
                        ->storeOrUpdate([$relatedQuery->getPlainForeignKey() => $model->getKey()], true);
            }
            catch (\Exception $e)
            {
                
            }
        }
        else if ($field->relation_one_to_one_belong_to && ($v = (int) $input->get($field->code, 0)))
        {
            // just validation input value
            \App\Telenok\Core\Model\Object\Sequence::getModelByTypeId($field->relation_one_to_one_belong_to)
                    ->findOrFail($v);
        }

        if ($field->rule->get('required') && !$relatedQuery->count())
        {
            throw new \Exception($this->LL('error.field.required', ['attribute' => $field->translate('title')]));
        }

        return $model;
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
     * @return {Telenok.Core.Field.RelationOneToOne.Controller}
     * @member Telenok.Core.Field.RelationOneToOne.Controller
     */
    public function preProcess($model, $type, $input)
    {
        if (!$input->get('relation_one_to_one_belong_to'))
        {
            $this->validateExistsInputField($input, ['field_has', 'relation_one_to_one_has']);
        }

        if (!$input->get('relation_one_to_one_has') && $input->get('field_has'))
        {
            $input->put('relation_one_to_one_has', $input->get('field_has'));
        }

        // can be zero if process field belong_to
        if ($input->get('relation_one_to_one_has'))
        {
            $input->put('relation_one_to_one_belong_to', 0);
            $input->put('relation_one_to_one_has', intval(\App\Telenok\Core\Model\Object\Type::where('code', $input->get('relation_one_to_one_has'))->orWhere('id', $input->get('relation_one_to_one_has'))->pluck('id')));
        }
        else
        {
            $input->put('relation_one_to_one_has', 0);
        }

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
     * @return {Telenok.Core.Field.RelationOneToOne.Controller}
     * @member Telenok.Core.Field.RelationOneToOne.Controller
     */
    public function postProcess($model, $type, $input)
    {
        if (!$input->get('relation_one_to_one_has'))
        {
            return parent::postProcess($model, $type, $input);
        }

        $relatedTypeOfModelField = $model->fieldObjectType()->first();   // eg object \App\Telenok\Core\Model\Object\Type which DB-field "code" is "author"

        $classModelHasOne = $relatedTypeOfModelField->class_model;
        $codeFieldHasOne = $model->code;
        $codeTypeHasOne = $relatedTypeOfModelField->code;

        $typeBelongTo = \App\Telenok\Core\Model\Object\Type::findOrFail($input->get('relation_one_to_one_has'));
        $tableBelongTo = $typeBelongTo->code;
        $classBelongTo = $typeBelongTo->class_model;

        $relatedSQLField = $codeFieldHasOne . '_' . $codeTypeHasOne;

        $hasOne = [
            'method' => camel_case($codeFieldHasOne),
            'class' => $classBelongTo,
            'field' => $relatedSQLField,
        ];

        $belongTo = [
            'method' => camel_case($relatedSQLField),
            'class' => $classModelHasOne,
            'field' => $relatedSQLField,
        ];

        $hasOneObject = app($classModelHasOne);
        $belongToObject = app($classBelongTo);

        if ($input->get('create_belong') !== false)
        {
            $title = $input->get('title_belong', []);
            $title_list = $input->get('title_list_belong', []);

            foreach ($relatedTypeOfModelField->title->all() as $language => $val)
            {
                $title[$language] = array_get($title, $language, $val . '/' . $model->translate('title', $language));
            }

            foreach ($relatedTypeOfModelField->title_list->all() as $language => $val)
            {
                $title_list[$language] = array_get($title_list, $language, $val . '/' . $model->translate('title_list', $language));
            }

            $tabTo = $this->getFieldTabBelongTo($typeBelongTo->getKey(), $input->get('field_object_tab_belong'), $input->get('field_object_tab'));

            $toSave = [
                'title' => $title,
                'title_list' => $title_list,
                'key' => $this->getKey(),
                'code' => $relatedSQLField,
                'field_object_type' => $typeBelongTo->getKey(),
                'field_object_tab' => $tabTo->getKey(),
                'relation_one_to_one_belong_to' => $relatedTypeOfModelField->getKey(),
                'show_in_form' => $input->get('show_in_form_belong', $model->show_in_form),
                'show_in_list' => $input->get('show_in_list_belong', $model->show_in_list),
                'allow_search' => $input->get('allow_search_belong', $model->allow_search),
                'multilanguage' => 0,
                'active' => $input->get('active_belong', $model->active),
                'active_at_start' => $input->get('start_at_belong', $model->active_at_start),
                'active_at_end' => $input->get('end_at_belong', $model->active_at_end),
                'allow_create' => $input->get('allow_create_belong', $model->allow_create),
                'allow_update' => $input->get('allow_update_belong', $model->allow_update),
                'field_order' => $input->get('field_order_belong', $model->field_order),
            ];

            $validator = $this->validator(app('\App\Telenok\Core\Model\Object\Field'), $toSave, []);

            if ($validator->passes())
            {
                \App\Telenok\Core\Model\Object\Field::create($toSave);
            }

            if (!\Schema::hasColumn($tableBelongTo, $relatedSQLField))
            {
                \Schema::table($tableBelongTo, function(Blueprint $table) use ($relatedSQLField)
                {
                    $table->integer($relatedSQLField)->unsigned()->nullable();

                    $this->schemeCreateExtraField($table, $relatedSQLField);
                });
            }

            if (!$this->validateMethodExists($belongToObject, $belongTo['method']))
            {
                $this->updateModelFile($belongToObject, $belongTo, 'belongsTo');
            }
            else
            {
                \Session::flash('warning.hasOneBelongTo', $this->LL('error.method.defined', ['method' => $belongTo['method'], 'class' => $classBelongTo]));
            }
        }

        if (!$this->validateMethodExists($hasOneObject, $hasOne['method']))
        {
            $this->updateModelFile($hasOneObject, $hasOne, 'hasOne');
        }
        else
        {
            \Session::flash('warning.hasOne', $this->LL('error.method.defined', ['method' => $hasOne['method'], 'class' => $classModelHasOne]));
        }

        return parent::postProcess($model, $type, $input);
    }

    /**
     * @method getStubFileDirectory
     * Path to directory of stub (class template) files
     * 
     * @return {String}
     * @member Telenok.Core.Field.RelationOneToOne.Controller
     */
    public function getStubFileDirectory()
    {
        return __DIR__;
    }
}