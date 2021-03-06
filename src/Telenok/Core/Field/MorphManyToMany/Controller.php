<?php namespace Telenok\Core\Field\MorphManyToMany;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Controller extends \Telenok\Core\Abstraction\Field\Relation\Controller {

    protected $key = 'morph-many-to-many';
    protected $specialField = ['morph_many_to_many_has', 'morph_many_to_many_belong_to'];
    protected $allowMultilanguage = false;

    public function getModelFillableField($model, $field)
    {
        return [];
    }

    /**
     * Return Object Type linked to the field
     *
     * @param \App\Vendor\Telenok\Core\Model\Object\Field $field
     * @return \App\Vendor\Telenok\Core\Model\Object\Type
     *
     */
    public function getLinkedModelType($field)
    {
        return \App\Vendor\Telenok\Core\Model\Object\Type::findMany([$field->morph_many_to_many_has, $field->morph_many_to_many_belong_to])->first();
    }

    public function getLinkedField($field)
    {
        return $field->morph_many_to_many_has ? 'morph_many_to_many_has' : 'morph_many_to_many_belong_to';
    }

    public function getFormModelViewVariable($controller = null, $model = null, $field = null, $uniqueId = null)
    {
        $linkedField = $this->getLinkedField($field);

        return
        [
            'urlListTitle' => route($this->getRouteListTitle(), ['id' => (int)$field->{$linkedField}]),
            'urlListTable' => route($this->getRouteListTable(), ['id' => (int)$model->getKey(), "fieldId" => $field->getKey(), "uniqueId" => $uniqueId]),
            'urlWizardChoose' => route($this->getRouteWizardChoose(), ['typeId' => $this->getChooseTypeId($field)]),
            'urlWizardCreate' => route($this->getRouteWizardCreate(), ['id' => $field->{$linkedField}, 'saveBtn' => 1, 'chooseBtn' => 1]),
            'urlWizardEdit' => route($this->getRouteWizardEdit(), ['id' => '--id--', 'saveBtn' => 1]),
        ];
    }

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

            $query->whereIn($relatedTable . '.id', (array)$value);
        }
    }

    public function getFilterContent($field = null)
    {
        $uniqueId = str_random();
        $option = [];

        $id = $field->morph_many_to_many_has ?: $field->morph_many_to_many_belong_to;

        $class = \App\Vendor\Telenok\Core\Model\Object\Sequence::getModel($id)->model_class;

        ($model = new $class)->withPermission()->take(20)->distinct()->get()->each(function($item) use (&$option)
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

    public function getFormModelContent($controller = null, $model = null, $field = null, $uniqueId = null)
    {
        if ($field->morph_many_to_many_has || $field->morph_many_to_many_belong_to)
        {
            return parent::getFormModelContent($controller, $model, $field, $uniqueId);
        }
    }

    public function saveModelField($field, $model, $input)
    {
        // if creating field
        if ($model instanceof \Telenok\Core\Model\Object\Field && !$input->get('id'))
        {
            return $model;
        }

        if (app('auth')->can('update', 'object_field.' . $model->getTable() . '.' . $field->code))
        {
            $idsAdd = array_unique((array)$input->get("{$field->code}_add", []));
            $idsDelete = array_unique((array)$input->get("{$field->code}_delete", []));

            if ( (!empty($idsAdd) || !empty($idsDelete)))
            {
                $method = camel_case($field->code);

                if (in_array('*', $idsDelete, true))
                {
                    $model->{$method}()->detach();
                }
                else if (!empty($idsDelete))
                {
                    $model->{$method}()->detach($idsDelete);
                }

                foreach($idsAdd as $id)
                {
                    try
                    {
                        $model->{$method}()->attach($id);
                    }
                    catch (\Exception $e) {}
                }
            }
        }

        return $model;
    }

    public function preProcess($model, $type, $input)
    {
        if (!$input->get('morph_many_to_many_belong_to'))
        {
            $this->validateExistsInputField($input, ['field_has', 'morph_many_to_many_has']);
        }

        if (!$input->get('morph_many_to_many_has') && $input->get('field_has'))
        {
            $input->put('morph_many_to_many_has', $input->get('field_has'));
        }

        // can be zero if process field belong_to
		if ($input->get('morph_many_to_many_has'))
		{
			$input->put('morph_many_to_many_belong_to', 0);
            $input->put('morph_many_to_many_has', intval(\App\Vendor\Telenok\Core\Model\Object\Type::where('code', (string)$input->get('morph_many_to_many_has'))->orWhere('id', $input->get('morph_many_to_many_has'))->value('id')));
        }
        else
        {
			$input->put('morph_many_to_many_has', 0);
        }

        $input->put('multilanguage', 0);
        $input->put('allow_sort', 0);

        return parent::preProcess($model, $type, $input);
    }

    public function postProcess($model, $type, $input)
    {
        if (!$input->get('morph_many_to_many_has'))
        {
            return parent::postProcess($model, $type, $input);
        }

        $typeHas = $model->fieldObjectType()->first();
        $typeBelongTo = \App\Vendor\Telenok\Core\Model\Object\Type::findOrFail($input->get('morph_many_to_many_has'));

        $hasCode = $model->code;
        $belongToCode = $hasCode . '_' . $typeBelongTo->code;

        $pivotTable = 'pivot_morph_m2m_' . $hasCode . '_' . $typeBelongTo->code;

        $classModelHas = $typeHas->model_class;
        $classModelBelongTo = $typeBelongTo->model_class;

        $hasObject = new $classModelHas;
        $belongToObject = new $classModelBelongTo;



        $has = [
            'method' => camel_case($hasCode),
            'name' => $hasCode,
            'class' => $classModelBelongTo,
            'table' => $pivotTable,
            'foreignKey' => $hasCode . '_id',
            'otherKey' => 'morphable_id',
        ];

        $belongTo = [
            'method' => camel_case($belongToCode),
            'name' => $hasCode,
            'class' => $classModelHas,
            'table' => $pivotTable,
            'foreignKey' => 'morphable_id',
            'otherKey' => $hasCode . '_id',
        ];


        if (!\Schema::hasTable($pivotTable))
        {
            \Schema::create($pivotTable, function(Blueprint $table) use ($hasCode, $belongToCode, $typeHas, $typeBelongTo)
            {
                $table->increments('id');
                $table->timestamps();
                $table->integer('morphable_id')->unsigned()->nullable();
                $table->integer($hasCode . '_id')->unsigned()->nullable();
                $table->string($hasCode . '_type')->nullable();

                $table->unique(['morphable_id', $hasCode . '_id', $hasCode . '_type'], 'uniq_key');

                $table->foreign($hasCode . '_id')->references('id')->on($typeHas->code)->onUpdate('cascade')->onDelete('cascade');
                $table->foreign('morphable_id')->references('id')->on($typeBelongTo->code)->onUpdate('cascade')->onDelete('cascade');

                $this->schemeCreateExtraField($table, $hasCode, $belongToCode, $typeHas, $typeBelongTo);
            });
        }

        if ($input->get('create_belong') !== false)
        {
            $title = collect($typeHas->title)->merge($input->get('title_belong', []))->transform(function ($item, $key) {
                return $item . ' [morphBelongTo]';
            })->all();

            $title_list = collect($typeHas->title_list)->merge($input->get('title_list_belong', []))->transform(function ($item, $key) {
                return $item . ' [morphBelongTo]';
            })->all();

            $tabTo = $this->getFieldTabBelongTo($typeBelongTo->getKey(), $input->get('field_object_tab_belong'), $input->get('field_object_tab'));

            $toSave = [
                'title' => $title,
                'title_list' => $title_list,
                'key' => $this->getKey(),
                'code' => $belongToCode,
                'field_object_type' => $typeBelongTo->getKey(),
                'field_object_tab' => $tabTo->getKey(),
                'morph_many_to_many_belong_to' => $typeHas->getKey(),
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


            $validator = $this->validator(new \App\Vendor\Telenok\Core\Model\Object\Field(), $toSave, []);

            if ($validator->passes())
            {
                \App\Vendor\Telenok\Core\Model\Object\Field::create($toSave);
            }

            if (!$this->validateMethodExists($belongToObject, $belongTo['method']))
            {
                $this->updateModelFile($belongToObject, $belongTo, 'balongToMany');
            }
            else
            {
                \Session::flash('warning.morphHasMany', $this->LL('error.method.defined', ['method' => $belongTo['method'], 'class' => $belongToObject]));
            }
        }

        if (!$this->validateMethodExists($hasObject, $has['method']))
        {
            $this->updateModelFile($hasObject, $has, 'hasMany');
        }
        else
        {
            \Session::flash('warning.morphManyHas', $this->LL('error.method.defined', ['method' => $has['method'], 'class' => $hasObject]));
        }

        $belongToObject->eraseCachedFields();

        return parent::postProcess($model, $type, $input);
    }

    public function getStubFileDirectory()
    {
        return __DIR__;
    }
}