<?php namespace Telenok\Core\Field\MorphOneToOne;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * @class Telenok.Core.Field.MorphOneToOne.Controller
 * Class of field "morph-one-to-one". Field allow to link objects.
 *
 * @extends Telenok.Core.Interfaces.Field.Relation.Controller
 */
class Controller extends \Telenok\Core\Interfaces\Field\Relation\Controller {

    /**
     * @protected
     * @property {String} $key
     * Field key.
     * @member Telenok.Core.Field.MorphOneToOne.Controller
     */
    protected $key = 'morph-one-to-one';

    /**
     * @protected
     * @property {Array} $specialField
     * Define list of field's names to process saving and filling {@link Telenok.Core.Model.Object.Field Telenok.Core.Model.Object.Field}.
     * @member Telenok.Core.Field.MorphOneToOne.Controller
     */
    protected $specialField = ['morph_one_to_one_has', 'morph_one_to_one_belong_to', 'morph_one_to_one_belong_to_type_list'];

    /**
     * @protected
     * @property {Boolean} $allowMultilanguageMorphOneToOne
     * Field doesn't support multilanguage
     * @member Telenok.Core.Field.MorphOneToOne.Controller
     */
    protected $allowMultilanguage = false;

    /**
     * @method getLinkedField
     * Define name of special field.
     *
     * @param {Telenok.Core.Model.Object.Field} $field
     * Object with data of field's configuration.
     * @return {String}
     * @member Telenok.Core.Field.MorphOneToOne.Controller
     */
    public function getLinkedField($field)
    {
        return $field->morph_one_to_one_has ? 'morph_one_to_one_has' : 'morph_one_to_one_belong_to';
    }

    /**
     * @method getChooseTypeId
     * Return ID of linked Type Object.
     *
     * @param {Telenok.Core.Model.Object.Field} $field
     * Object with data of field's configuration.
     * @return {Integer}
     * @member Telenok.Core.Field.MorphOneToOne.Controller
     */
    public function getChooseTypeId($field)
    {
        return $field->morph_one_to_one_has ? $field->{$this->getLinkedField()} : $field->morph_one_to_one_belong_to_type_list->all();
    }

    /**
     * @method getModelFieldViewVariable
     * Return array with URL for variables in $viewModel view.
     *
     * @param {Telenok.Core.Field.RelationOneToMany.Controller} $controller
     * @param {Telenok.Core.Interfaces.Eloquent.Object.Model} $model
     * @param {Telenok.Core.Model.Object.Field} $field
     * @param {String} $uniqueId
     *
     * @return {Array}
     * @member Telenok.Core.Field.MorphOneToOne.Controller
     */
    public function getModelFieldViewVariable($controller = null, $model = null, $field = null, $uniqueId = null)
    {
        $linkedField = $this->getLinkedField($field);
        
        return
        [
            'urlListTable' => route($this->getRouteListTable(), ['id' => (int)$model->getKey(), 'fieldId' => $field->getKey(), "uniqueId" => $uniqueId]),
            'urlWizardCreate' => route($this->getRouteWizardCreate(), [ 'id' => $field->{$linkedField}, 'saveBtn' => 1, 'chooseBtn' => 1]),
            'urlWizardChoose' => route($this->getRouteWizardChoose(), ['id' => $this->getChooseTypeId($field)]),
            'urlListTitle' => route($this->getRouteListTitle(), ['id' => (int)$field->{$linkedField}]),
            'urlWizardEdit' => route($this->getRouteWizardEdit(), ['id' => '--id--', 'saveBtn' => 1]),
        ];
    }

    /**
     * @method getLinkedModelType
     * Return Object Type of field
     *
     * @param {Telenok.Core.Model.Object.Field} $field
     * @return {Telenok.Core.Model.Object.Type}
     * @member Telenok.Core.Field.MorphOneToOne.Controller
     */
    public function getLinkedModelType($field)
    {
        return \App\Telenok\Core\Model\Object\Type::whereIn('id', [$field->morph_one_to_one_has, $field->morph_one_to_one_belong_to])->first();
    }

    /**
     * @method getFormModelContent
     * Return HTML content of form element for the field
     *
     * @param {Telenok.Core.Field.RelationOneToMany.Controller} $controller
     * @param {Telenok.Core.Interfaces.Eloquent.Object.Model} $model
     * @param {Telenok.Core.Model.Object.Field} $field
     * @param {String} $uniqueId
     * @return {String}
     * @member Telenok.Core.Field.MorphOneToOne.Controller
     */
    public function getFormModelContent($controller = null, $model = null, $field = null, $uniqueId = null)
    {         
        if ($field->morph_one_to_one_has || $field->morph_one_to_one_belong_to)
        {
            return parent::getFormModelContent($controller, $model, $field, $uniqueId);
        }
    }

    /**
     * @method getModelFillableField
     * Define list of fields in Eloquent object which can be filled by user.
     *
     * @param {Telenok.Core.Interfaces.Eloquent.Object.Model} $model
     * Eloquent object.
     * @param {Telenok.Core.Model.Object.Field} $field
     * Object with data of field's configuration.
     * @return {Array}
     * @member Telenok.Core.Field.MorphOneToOne.Controller
     */
    public function getModelFillableField($model, $field)
    {
        return $field->morph_one_to_one_belong_to ? [$field->code . '_type', $field->code . '_id'] : [];
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
     * @member Telenok.Core.Field.MorphOneToOne.Controller
     */
    public function getModelSpecialAttribute($model, $key, $value)
    {
        try
        {
            if (in_array($key, ['morph_one_to_one_belong_to_type_list'], true))
            {
                $value = $value ? : '[]';

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
     * @return {Telenok.Core.Field.MorphOneToOne.Controller}
     * @member Telenok.Core.Field.MorphOneToOne.Controller
     */
    public function setModelSpecialAttribute($model, $key, $value)
    {
        if (in_array($key, ['morph_one_to_one_belong_to_type_list'], true))
        {
            $default = [];

            if ($value instanceof \Illuminate\Support\Collection) 
            {
                if ($value->count())
                {
                    $value = $value->toArray();
                }
                else
                {
                    $value = $default;
                }
            }
            else
            {
                $value = $value ? : $default;
            } 

            $model->setAttribute($key, json_encode($value, JSON_UNESCAPED_UNICODE));
        }
        else
        {
            parent::setModelSpecialAttribute($model, $key, $value);
        }
        
        return $this;
    }

    /**
     * @method getListFieldContentItems
     * Return initial list of linked field values.
     *
     * @param {Telenok.Core.Model.Object.Field} $field
     * @param {mixed} $item
     * @param {Telenok.Core.Model.Object.Type} $type
     * @return {Illuminate.Support.Collection}
     * @member Telenok.Core.Field.MorphOneToOne.Controller
     */
    public function getListFieldContentItems($field, $item, $type = null)
    {
        $method = camel_case($field->code);

        return $item->{$method} ? $item->{$method}()->take(8)->get() : [];
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
     * @member Telenok.Core.Field.MorphOneToOne.Controller
     */
    public function getFilterQuery($field = null, $model = null, $query = null, $name = null, $value = null) 
    {
        if (!empty($value))
        {
            if ($field->morph_one_to_one_has)
            {
                $modelTable = $model->getTable();

                $linkedTable = \App\Telenok\Core\Model\Object\Sequence::getModel($field->morph_one_to_one_has)->code;
                
                $alias = $linkedTable . str_random();

                $query->join($linkedTable . ' as ' . $alias, function($join) use ($modelTable, $field, $alias)
                {
                    $join->on($modelTable . '.id', '=', $alias . '.' . $field->code . 'able_id');
                });

                $query->whereIn($linkedTable.'.id', (array)$value);
            }
            else if ($field->morph_one_to_one_belong_to)
            {
                $modelTable = $model->getTable();

                $linkedTable = 'object_sequence';

                $alias = $linkedTable . str_random();

                $query->join($linkedTable . ' as ' . $alias, function($join) use ($modelTable, $field, $alias)
                {
                    $join->on($modelTable . '.' . $field->code . '_id', '=', $alias . '.id');
                });

                $query->whereIn($alias.'.id', (array)$value);
                $query->whereIn($alias.'.sequences_object_type', $field->morph_one_to_one_belong_to_type_list->all());
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
     * @member Telenok.Core.Field.MorphOneToOne.Controller
     */
    public function getFilterContent($field = null)
    {
        $uniqueId = str_random();
        $option = [];
        
        $id = $field->morph_one_to_one_has ?: $field->morph_one_to_one_belong_to;
        
        $class = \App\Telenok\Core\Model\Object\Sequence::getModel($id)->class_model;
        
        $model = app($class);
        
        $query = $model::withPermission()->take(20)->groupBy($model->getTable() . '.id');
        
        if ($field->morph_one_to_one_belong_to)
        {
            $query->whereIn($model->getTable() . '.sequences_object_type', $field->morph_one_to_one_belong_to_type_list->all());
        }
        
        $query->get()->each(function($item) use (&$option)
        {
            $option[] = "<option value='{$item->id}'>[{$item->id}] {$item->translate('title')}</option>";
        });
        
        $option[] = "<option value='0' disabled='disabled'>...</option>";
        
        return '
            <select class="chosen" multiple data-placeholder="'.$this->LL('notice.choose').'" id="input'.$uniqueId.'" name="filter['.$field->code.'][]">
            ' . implode('', $option) . ' 
            </select>
            <script type="text/javascript">
                jQuery("#input'.$uniqueId.'").on("chosen:showing_dropdown", function()
                {
                    telenok.maxZ("*", jQuery(this).parent().find("div.chosen-drop"));
                })
                .ajaxChosen({ 
                    keepTypingMsg: "'.$this->LL('notice.typing').'",
                    lookingForMsg: "'.$this->LL('notice.looking-for').'",
                    type: "GET",
                    url: "'.route($this->getRouteListTitle(), ['id' => (int)$id]).'", 
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
                    no_results_text: "'.$this->LL('notice.not-found').'" 
                });
            </script>';
    }

    /**
     * @method saveModelField
     * Save eloquent model with field's data.
     *
     * @param {Telenok.Core.Model.Object.Field} $field
     * Eloquent object Field.
     * @param {Telenok.Core.Interfaces.Eloquent.Object.Model} $model
     * Eloquent object.
     * @param {Illuminate.Support.Collection} $input
     * Values of request.
     * @return {Telenok.Core.Interfaces.Eloquent.Object.Model}
     * @member Telenok.Core.Field.MorphOneToOne.Controller
     * @throws {Exception}
     */
    public function saveModelField($field, $model, $input)
    { 
        // if created field
        if ($model instanceof \Telenok\Core\Model\Object\Field && !$input->get('id'))
        {
            return $model;
        }

        $id = $input->get("{$field->code}", 0);

        $canUpdate = app('auth')->can('update', 'object_field.' . $model->getTable() . '.' . $field->code);
        
        if ($field->morph_one_to_one_belong_to)
        { 
            if ($id && $canUpdate)
            {
                $objectModel = \App\Telenok\Core\Model\Object\Sequence::find($id)->model()->first();

                if (in_array($objectModel->type()->getKey(), $field->morph_one_to_one_belong_to_type_list->all(), true))
                {
                    $model->fill([$field->code . '_type' => get_class($objectModel), $field->code . '_id' => $objectModel->getKey()])->save();
                }
            }
            else
            {
                $model->fill([$field->code . '_type' => null, $field->code . '_id' => null])->save();
            }
        }
        else if ($field->morph_one_to_one_has && $canUpdate)
        {  
            $method = camel_case($field->code);

            $relatedField = $field->code . 'able';
 
            $model->{$method}()->get()->each(function($item) use ($relatedField) 
            {
                $item->fill([$relatedField . '_id' => 0, $relatedField . '_type' => null])->save();
            });

            $relatedModel = app(\App\Telenok\Core\Model\Object\Type::findOrFail($field->morph_one_to_one_has)->class_model);

            if (intval($id)) 
            {
                try
                {
                    $linked = $relatedModel::findOrFail($id);
                    $model->{$method}()->save( $linked );
                } 
                catch (\Exception $e) {}
            }
        }
        
        return $model;
    }

    /**
     * @method processFieldDelete
     * @member Telenok.Core.Field.MorphOneToOne.Controller
     */
    public function processFieldDelete($model, $type)
    {  
        if ($model->morph_one_to_one_has)
        {
            $f = \App\Telenok\Core\Model\Object\Field::where(function($query) use ($model)
                    {
                        $query->where('code', $model->code . 'able');
                        $query->where('field_object_type', $model->morph_one_to_one_has);
                    })
                    ->first();
            if ($f)
            {
                $tList = $f->morph_one_to_one_belong_to_type_list;

                $tNewList = $tList->reject(function($item) use ($model) 
                {
                    return $item == $model->fieldObjectType->getKey();
                });

                $f->morph_one_to_one_belong_to_type_list = $tNewList;

                $f->update();
            }
        }

        return true;
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
     * @return {Telenok.Core.Field.MorphOneToOne.Controller}
     * @member Telenok.Core.Field.MorphOneToOne.Controller
     */
    public function preProcess($model, $type, $input)
    {
        if (!$input->get('morph_one_to_one_belong_to'))
        {
            $this->validateExistsInputField($input, ['field_has', 'morph_one_to_one_has']);
        }

        if (!$input->get('morph_one_to_one_has') && $input->get('field_has'))
        {
            $input->put('morph_one_to_one_has', $input->get('field_has'));
        }

        // can be zero if process field belong_to
		if ($input->get('morph_one_to_one_has'))
		{
			$input->put('morph_one_to_one_belong_to', 0);
            $input->put('morph_one_to_one_has', intval(\App\Telenok\Core\Model\Object\Type::where('code', $input->get('morph_one_to_one_has'))->orWhere('id', $input->get('morph_one_to_one_has'))->value('id')));
        }
        else
        {
			$input->put('morph_one_to_one_has', 0);
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
     * @return {Telenok.Core.Field.MorphOneToOne.Controller}
     * @member Telenok.Core.Field.MorphOneToOne.Controller
     */
    public function postProcess($model, $type, $input)
    {
        if (!$input->get('morph_one_to_one_has'))
        {
            return parent::postProcess($model, $type, $input);
        } 

        $relatedTypeOfModelField = $model->fieldObjectType()->first();

        $classModelHasMany = $relatedTypeOfModelField->class_model;
        $codeFieldHasMany = $model->code; 
        $codeTypeHasMany = $relatedTypeOfModelField->code; 

        $typeBelongTo = \App\Telenok\Core\Model\Object\Type::findOrFail($input->get('morph_one_to_one_has')); 
        $tableBelongTo = $typeBelongTo->code;
        $classBelongTo = $typeBelongTo->class_model;

        $relatedSQLField = $codeFieldHasMany . 'able';

        $hasMany = [
                'method' => camel_case($codeFieldHasMany),
                'name' => $relatedSQLField,
                'class' => $classBelongTo,
                'type' => $relatedSQLField . '_type',
                'foreignKey' => $relatedSQLField . '_id',
                'otherKey' => 'id',
            ];

        $belongTo = [
                'method' => camel_case($relatedSQLField),
                'name' => $relatedSQLField,
                'type' => $relatedSQLField . '_type',
                'id' => $relatedSQLField . '_id',
            ];

        $hasManyObject = app($classModelHasMany);
        $belongToObject = app($classBelongTo);

        if ($input->get('create_belong') !== false) 
        {
            $title = $input->get('title_belong', []);
            $title_list = $input->get('title_list_belong', []);

            foreach($relatedTypeOfModelField->title->all() as $language => $val)
            {
                $title[$language] = array_get($title, $language, $model->translate('title', $language) . ' [morphTo]');
            }

            foreach($relatedTypeOfModelField->title_list->all() as $language => $val)
            {
                $title_list[$language] = array_get($title_list, $language, $model->translate('title_list', $language) . ' [morphTo]');
            }

            $tabTo = $this->getFieldTabBelongTo($typeBelongTo->getKey(), $input->get('field_object_tab_belong'), $input->get('field_object_tab'));

            $toSave = [
                'title' => $title,
                'title_list' => $title_list,
                'key' => $this->getKey(),
                'code' => $relatedSQLField,
                'field_object_type' => $typeBelongTo->getKey(),
                'field_object_tab' => $tabTo->getKey(),
                'morph_one_to_one_belong_to' => \App\Telenok\Core\Model\Object\Type::where('code', 'object_sequence')->value('id'),
                'morph_one_to_one_belong_to_type_list' => [$relatedTypeOfModelField->getKey()],
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


            $f = \App\Telenok\Core\Model\Object\Field::where(function($query) use ($relatedSQLField, $model)
                    {
                        $query->where('code', $relatedSQLField);
                        $query->where('field_object_type', $model->morph_one_to_one_has);
                    })
                    ->first();

            if ($f)
            {
                $tList = $f->morph_one_to_one_belong_to_type_list;

                $tList->push($relatedTypeOfModelField->getKey());

                $f->morph_one_to_one_belong_to_type_list = $tList;

                $f->update();
            }
            else
            {
                $validator = $this->validator(app('\App\Telenok\Core\Model\Object\Field'), $toSave, []);

                if ($validator->passes()) 
                {
                    \App\Telenok\Core\Model\Object\Field::create($toSave);
                }
            }

            try
            {
                \Schema::table($tableBelongTo, function(Blueprint $table) use ($relatedSQLField)
                {
                    $table->unsignedInteger("{$relatedSQLField}_id")->nullable();

                    $table->string("{$relatedSQLField}_type")->nullable();

                    $table->index(array("{$relatedSQLField}_id", "{$relatedSQLField}_type"));           

                    $this->schemeCreateExtraField($table, $relatedSQLField);
                });
            } 
            catch (\Exception $e) {}

            if (!$this->validateMethodExists($belongToObject, $belongTo['method']))
            {
                $this->updateModelFile($belongToObject, $belongTo, 'morphTo');
            }
            else
            {
                \Session::flash('warning.morphOneTo', $this->LL('error.method.defined', ['method'=>$belongTo['method'], 'class'=>$classBelongTo]));
            } 
        }

        if (!$this->validateMethodExists($hasManyObject, $hasMany['method']))
        {
            $this->updateModelFile($hasManyObject, $hasMany, 'morphOne');
        } 
        else
        {
            \Session::flash('warning.morphOneHas', $this->LL('error.method.defined', ['method'=>$hasMany['method'], 'class'=>$classModelHasMany]));
        }

        return parent::postProcess($model, $type, $input);
    }

    /**
     * @method getStubFileDirectory
     * Path to directory of stub (class template) files
     *
     * @return {String}
     * @member Telenok.Core.Field.MorphOneToOne.Controller
     */
    public function getStubFileDirectory()
    {
        return __DIR__;
    }
}