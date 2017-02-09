<?php namespace Telenok\Core\Field\RelationManyToMany;

use Illuminate\Database\Schema\Blueprint;

/**
 * @class Telenok.Core.Field.RelationManyToMany.Controller
 * Class of field "relation-many-to-many". Field allow to link objects.
 * 
 * @extends Telenok.Core.Abstraction.Field.Relation.Controller
 */
class Controller extends \Telenok\Core\Abstraction\Field\Relation\Controller {

    /**
     * @protected
     * @property {String} $key
     * Field key.
     * @member Telenok.Core.Field.RelationManyToMany.Controller
     */
    protected $key = 'relation-many-to-many';

    /**
     * @protected
     * @property {Array} $specialField
     * Define list of field's names to process saving and filling {@link Telenok.Core.Model.Object.Field Telenok.Core.Model.Object.Field}.
     * @member Telenok.Core.Field.RelationManyToMany.Controller
     */
    protected $specialField = ['relation_many_to_many_has', 'relation_many_to_many_belong_to', 'relation_many_to_many_default'];

    /**
     * @protected
     * @property {Boolean} $allowMultilanguage
     * Field doesn't support multilanguage
     * @member Telenok.Core.Field.RelationManyToMany.Controller
     */
    protected $allowMultilanguage = false;

    /**
     * @method getModelFillableField
     * Define list of fields in Eloquent object which can be filled by user.
     * 
     * @param {Telenok.Core.Abstraction.Eloquent.Object.Model} $model
     * Eloquent object.
     * @param {Telenok.Core.Model.Object.Field} $field
     * Object with data of field's configuration.
     * @return {Array}
     * @member Telenok.Core.Field.RelationManyToMany.Controller
     */
    public function getModelFillableField($model, $field)
    {
        return [];
    }

    /**
     * @method getLinkedField
     * Define name of special field.
     * 
     * @param {Telenok.Core.Model.Object.Field} $field
     * Object with data of field's configuration.
     * @return {String}
     * @member Telenok.Core.Field.RelationManyToMany.Controller
     */
    public function getLinkedField($field)
    {
        return $field->relation_many_to_many_has ? 'relation_many_to_many_has' : 'relation_many_to_many_belong_to';
    }

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
     * @member Telenok.Core.Field.RelationManyToMany.Controller
     */
    public function getFormModelViewVariable($controller = null, $model = null, $field = null, $uniqueId = null)
    {
        $linkedField = $this->getLinkedField($field);

        return [
            'urlListTitle' => route($this->getRouteListTitle(), ['id' => $field->{$linkedField}]),
            'urlListTable' => route($this->getRouteListTable(), ["id" => (int) $model->getKey(), "fieldId" => $field->getKey(), "uniqueId" => $uniqueId]),
            'urlWizardCreate' => route($this->getRouteWizardCreate(), [ 'id' => $this->getChooseTypeId($field), 'saveBtn' => 1, 'chooseBtn' => 1]),
            'urlWizardChoose' => route($this->getRouteWizardChoose(), ['typeId' => $this->getChooseTypeId($field)]),
            'urlWizardEdit' => route($this->getRouteWizardEdit(), ['id' => '--id--', 'saveBtn' => 1]),
        ];
    }

    /**
     * @method getFormModelContent
     * Return HTML content of form element for the field
     * 
     * @param {Telenok.Core.Field.RelationOneToMany.Controller} $controller
     * @param {Telenok.Core.Abstraction.Eloquent.Object.Model} $model
     * @param {Telenok.Core.Model.Object.Field} $field
     * @param {String} $uniqueId
     * @return {String}
     * @member Telenok.Core.Field.RelationManyToMany.Controller
     */
    public function getFormModelContent($controller = null, $model = null, $field = null, $uniqueId = null)
    {
        if ($field->relation_many_to_many_has || $field->relation_many_to_many_belong_to)
        {
            return parent::getFormModelContent($controller, $model, $field, $uniqueId);
        }
    }

    /**
     * @method getLinkedModelType
     * Return Object Type of field
     * 
     * @param {Telenok.Core.Model.Object.Field} $field
     * @return {Telenok.Core.Model.Object.Type}
     * @member Telenok.Core.Field.RelationManyToMany.Controller
     */
    public function getLinkedModelType($field)
    {
        return \App\Vendor\Telenok\Core\Model\Object\Type::whereIn('id', [$field->relation_many_to_many_has, $field->relation_many_to_many_belong_to])->first();
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
     * @member Telenok.Core.Field.RelationManyToMany.Controller
     */
    public function getFilterQuery($field = null, $model = null, $query = null, $name = null, $value = null)
    {
        if (!empty($value))
        {
            $method = camel_case($field->code);
            $relatedQuery = $model->{$method}();

            $relatedTable = $relatedQuery->getRelated()->getTable();

            $query->join($relatedQuery->getTable(), function($join) use ($model, $relatedQuery)
            {
                $join->on($relatedQuery->getForeignKey(), '=', $model->getTable() . '.id');
            });

            $query->join($relatedTable, function($join) use ($relatedTable, $relatedQuery)
            {
                $join->on($relatedQuery->getOtherKey(), '=', $relatedTable . '.id');
            });

            $query->whereIn($relatedTable . '.id', (array) $value);
        }
    }

    /**
     * @method getFilterContent
     * Return HTML of filter field in search form.
     * 
     * @param {Telenok.Core.Model.Object.Field} $field
     * Object with data of field's configuration.
     * @return {String}
     * @member Telenok.Core.Field.RelationManyToMany.Controller
     */
    public function getFilterContent($field = null)
    {
        $uniqueId = str_random();
        $option = [];

        $id = $field->relation_many_to_many_has ? : $field->relation_many_to_many_belong_to;

        $class = \App\Vendor\Telenok\Core\Model\Object\Sequence::getModel($id)->model_class;

        (new $class)->withPermission()->take(20)->distinct()->get()->each(function($item) use (&$option)
        {
            $option[] = "<option value='{$item->id}'>[{$item->id}] {$item->translate('title')}</option>";
        });

        $option[] = "<option value='0' disabled='disabled'>...</option>";

        return '
            <select class="chosen" multiple data-placeholder="' . $this->LL('notice.choose') . '" id="input' . $uniqueId . '" name="filter[' . $field->code . '][]">
            ' . implode('', $option) . ' 
            </select>
            <script type="text/javascript">
                jQuery("#input' . $uniqueId . '").on("chosen:showing_dropdown", function()
                {
                    telenok.maxZ("*", jQuery(this).parent().find("div.chosen-drop"));
                })
                .ajaxChosen({ 
                    keepTypingMsg: "' . $this->LL('notice.typing') . '",
                    lookingForMsg: "' . $this->LL('notice.looking-for') . '",
                    type: "GET",
                    url: "' . route($this->getRouteListTitle(), ['id' => (int) $id]) . '", 
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
     * @member Telenok.Core.Field.RelationManyToMany.Controller
     */
    public function getModelSpecialAttribute($model, $key, $value)
    {
        if (in_array($key, ['relation_many_to_many_default'], true))
        {
            return collect((array) json_decode($value, true));
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
     * @return {Telenok.Core.Field.RelationManyToMany.Controller}
     * @member Telenok.Core.Field.RelationManyToMany.Controller
     */
    public function setModelSpecialAttribute($model, $key, $value)
    {
        if (in_array($key, ['relation_many_to_many_default'], true))
        {
            if ($value instanceof \Illuminate\Support\Collection)
            {
                $value = $value->toArray();
            }

            $model->setAttribute($key, json_encode((array) $value, JSON_UNESCAPED_UNICODE));
        }
        else
        {
            return parent::setModelSpecialAttribute($model, $key, $value);
        }

        return $this;
    }

    /**
     * @method saveModelField
     * Save eloquent model with field's data.
     * 
     * @param {Telenok.Core.Model.Object.Field} $field
     * Eloquent object Field.
     * @param {Telenok.Core.Abstraction.Eloquent.Object.Model} $model
     * Eloquent object.
     * @param {Illuminate.Support.Collection} $input
     * Values of request.
     * @return {Telenok.Core.Abstraction.Eloquent.Object.Model}
     * @member Telenok.Core.Field.RelationManyToMany.Controller
     * @throws {Exception}
     */
    public function saveModelField($field, $model, $input)
    {
        // if created field
        if ($model instanceof \Telenok\Core\Model\Object\Field && !$input->get('id'))
        {
            return $model;
        }

        $idsAdd = array_unique((array) $input->get("{$field->code}_add", []));
        $idsDelete = array_unique((array) $input->get("{$field->code}_delete", []));

        $method = camel_case($field->code);

        if (app('auth')->can('update', 'object_field.' . $model->getTable() . '.' . $field->code))
        {
            if (!empty($idsDelete))
            {
                if (in_array('*', $idsDelete, true))
                {
                    $model->{$method}()->detach();
                }
                else if (!empty($idsDelete))
                {
                    $model->{$method}()->detach($idsDelete);
                }
            }

            if (!$model->{$method}()->exists() && empty($idsAdd))
            {
                $idsAdd = $field->relation_many_to_many_default->all();
            }

            foreach ($idsAdd as $id)
            {
                try
                {
                    if (app('auth')->can('update', $id))
                    {
                        $model->{$method}()->attach($id);
                    }
                }
                catch (\Exception $e)
                {
                    
                }
            }
        }

        if ($field->required && !$model->{$method}()->exists())
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
     * @return {Telenok.Core.Field.RelationManyToMany.Controller}
     * @member Telenok.Core.Field.RelationManyToMany.Controller
     */
    public function preProcess($model, $type, $input)
    {
        if (!$input->get('relation_many_to_many_belong_to'))
        {
            $this->validateExistsInputField($input, ['field_has', 'relation_many_to_many_has']);
        }

        if (!$input->get('relation_many_to_many_has') && $input->get('field_has'))
        {
            $input->put('relation_many_to_many_has', $input->get('field_has'));
        }

        // can be zero if process field belong_to
        if ($input->get('relation_many_to_many_has'))
        {
            $input->put('relation_many_to_many_belong_to', 0);
            $input->put('relation_many_to_many_has', intval(\App\Vendor\Telenok\Core\Model\Object\Type::where('code', $input->get('relation_many_to_many_has'))->orWhere('id', $input->get('relation_many_to_many_has'))->value('id')));
        }
        else
        {
            $input->put('relation_many_to_many_has', 0);
        }

        $input->put('multilanguage', 0);
        $input->put('allow_sort', 0);

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
     * @return {Telenok.Core.Field.RelationOneToMany.Controller}
     * @member Telenok.Core.Field.RelationManyToMany.Controller
     */
    public function postProcess($model, $type, $input)
    {
        if (!$input->get('relation_many_to_many_has'))
        {
            return parent::postProcess($model, $type, $input);
        }

        $relatedTypeOfModelField = $model->fieldObjectType()->first();

        $classModelHasMany = $relatedTypeOfModelField->model_class;
        $tableHasMany = $relatedTypeOfModelField->code;
        $codeFieldHasMany = $model->code;
        $codeTypeHasMany = $relatedTypeOfModelField->code;

        $typeBelongTo = \App\Vendor\Telenok\Core\Model\Object\Type::findOrFail($input->get('relation_many_to_many_has'));
        $tableBelongTo = $typeBelongTo->code;
        $classBelongTo = $typeBelongTo->model_class;

        $pivotTable = 'pivot_relation_m2m_' . $codeFieldHasMany . '_' . $codeTypeHasMany;
        $pivotField = $codeFieldHasMany . '_' . $codeTypeHasMany;

        $hasMany = [
            'method' => camel_case($codeFieldHasMany),
            'class' => $classBelongTo,
            'table' => $pivotTable,
            'field_1' => $pivotField,
            'field_2' => $codeFieldHasMany,
        ];

        $belongTo = [
            'method' => camel_case($codeFieldHasMany . '_' . $codeTypeHasMany),
            'class' => $classModelHasMany,
            'table' => $pivotTable,
            'field_1' => $codeFieldHasMany,
            'field_2' => $pivotField,
        ];

        $hasManyObject = new $classModelHasMany;
        $belongToObject = new $classBelongTo;

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
                'code' => $pivotField,
                'field_object_type' => $typeBelongTo->getKey(),
                'field_object_tab' => $tabTo->getKey(),
                'relation_many_to_many_belong_to' => $relatedTypeOfModelField->getKey(),
                'show_in_list' => $input->get('show_in_list_belong', $model->show_in_list),
                'show_in_form' => $input->get('show_in_form_belong', $model->show_in_form),
                'allow_search' => $input->get('allow_search_belong', $model->allow_search),
                'multilanguage' => 0,
                'active' => $input->get('active_belong', $model->active),
                'active_at_start' => $input->get('start_at_belong', $model->active_at_start),
                'active_at_end' => $input->get('end_at_belong', $model->active_at_end),
                'allow_create' => $input->get('allow_create_belong', $model->allow_create),
                'allow_update' => $input->get('allow_update_belong', $model->allow_update),
                'field_order' => $input->get('field_order_belong', $model->field_order),
            ];

            $validator = $this->validator(new App\Vendor\Telenok\Core\Model\Object\Field(), $toSave, []);

            if ($validator->passes())
            {
                \App\Vendor\Telenok\Core\Model\Object\Field::create($toSave);
            }

            if ($this->validateMethodExists($belongToObject, $belongTo['method']))
            {
                \Session::flash('warning.hasMany', $this->LL('error.method.defined', ['method' => $belongTo['method'], 'class' => $classBelongTo]));
            }
            else
            {
                $this->updateModelFile($belongToObject, $belongTo, 'belongToMany');
            }
        }

        if (!\Schema::hasTable($pivotTable))
        {
            \Schema::create($pivotTable, function(Blueprint $table) use ($codeFieldHasMany, $pivotField, $tableHasMany, $tableBelongTo)
            {
                $table->increments('id');
                $table->timestamps();
                $table->integer($codeFieldHasMany)->unsigned()->default(0)->nullable();
                $table->integer($pivotField)->unsigned()->default(0)->nullable();

                $table->unique([$pivotField, $codeFieldHasMany], 'uniq_key');

                $this->schemeCreateExtraField($table, $codeFieldHasMany, $pivotField, $tableHasMany, $tableBelongTo);
            });
        }

        if ($this->validateMethodExists($hasManyObject, $hasMany['method']))
        {
            \Session::flash('warning.hasMany', $this->LL('error.method.defined', ['method' => $hasMany['method'], 'class' => $classModelHasMany]));
        }
        else
        {
            $this->updateModelFile($hasManyObject, $hasMany, 'hasMany');
        }

        $belongToObject->eraseCachedFields();

        return parent::postProcess($model, $type, $input);
    }

    /**
     * @method getStubFileDirectory
     * Path to directory of stub (class template) files
     * 
     * @return {String}
     * @member Telenok.Core.Field.RelationManyToMany.Controller
     */
    public function getStubFileDirectory()
    {
        return __DIR__;
    }
}
