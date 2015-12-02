<?php namespace Telenok\Core\Field\RelationManyToMany;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Controller extends \Telenok\Core\Interfaces\Field\Relation\Controller {

    protected $key = 'relation-many-to-many'; 
    protected $specialField = array('relation_many_to_many_has', 'relation_many_to_many_belong_to', 'relation_many_to_many_default');
    protected $allowMultilanguage = false;

    public function getModelFillableField($model, $field)
    {
		return [];
    } 

	public function getLinkedField($field)
	{
		return $field->relation_many_to_many_has ? 'relation_many_to_many_has' : 'relation_many_to_many_belong_to';
	}

	public function getModelFieldViewVariable($controller = null, $model = null, $field = null, $uniqueId = null)
	{
	    $linkedField = $this->getLinkedField($field);

		return [
			'urlListTitle' => route($this->getRouteListTitle(), ['id' => $field->{$linkedField}]),
			'urlListTable' => route($this->getRouteListTable(), ["id" => (int)$model->getKey(), "fieldId" => $field->getKey(), "uniqueId" => $uniqueId]),
			'urlWizardCreate' => route($this->getRouteWizardCreate(), [ 'id' => $this->getChooseTypeId($field), 'saveBtn' => 1, 'chooseBtn' => 1]),
			'urlWizardChoose' => route($this->getRouteWizardChoose(), ['id' => $this->getChooseTypeId($field)]),
			'urlWizardEdit' => route($this->getRouteWizardEdit(), ['id' => '--id--', 'saveBtn' => 1]),
		];
	}
	
    public function getFormModelContent($controller = null, $model = null, $field = null, $uniqueId = null)
    { 		
		if ($field->relation_many_to_many_has || $field->relation_many_to_many_belong_to)
		{
			return parent::getFormModelContent($controller, $model, $field, $uniqueId);
		}
	}

	/**
	 * Return Object Type linked to the field
	 * 
	 * @param \App\Telenok\Core\Model\Object\Field $field
	 * @return \App\Telenok\Core\Model\Object\Type
	 * 
	 */
	public function getLinkedModelType($field)
	{
		return \App\Telenok\Core\Model\Object\Type::whereIn('id', [$field->relation_many_to_many_has, $field->relation_many_to_many_belong_to])->first();
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

        $id = $field->relation_many_to_many_has ?: $field->relation_many_to_many_belong_to;

        $class = \App\Telenok\Core\Model\Object\Sequence::getModel($id)->class_model;

		$model = app($class);

        $model::withPermission()->take(20)->groupBy($model->getTable() . '.id')->get()->each(function($item) use (&$option)
        {
            $option[] = "<option value='{$item->id}'>[{$item->id}] {$item->translate('title')}</option>";
        });
        
        $option[] = "<option value='0' disabled='disabled'>...</option>";
        
        return '
            <select class="chosen" multiple data-placeholder="'.$this->LL('notice.choose').'" id="input'.$uniqueId.'" name="filter['.$field->code.'][]">
            ' . implode('', $option) . ' 
            </select>
            <script type="text/javascript">
                jQuery("#input'.$uniqueId.'").ajaxChosen({ 
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

    public function getModelSpecialAttribute($model, $key, $value)
    {
        if (in_array($key, ['relation_many_to_many_default'], true))
        {
            return \Illuminate\Support\Collection::make((array)json_decode($value, true));
        }

        return parent::getModelSpecialAttribute($model, $key, $value);
    }

    public function setModelSpecialAttribute($model, $key, $value)
    {
        if (in_array($key, ['relation_many_to_many_default'], true))
        {
			if ($value instanceof \Illuminate\Support\Collection) 
			{
				$value = $value->toArray();
			}

			$model->setAttribute($key, json_encode((array)$value, JSON_UNESCAPED_UNICODE));
        }
        else
        {
            return parent::setModelSpecialAttribute($model, $key, $value);
        }

        return $this;
    }

    public function saveModelField($field, $model, $input)
    {
		// if created field
		if ($model instanceof \Telenok\Core\Model\Object\Field && !$input->get('id'))
		{
			return $model;
		}

		$idsAdd = array_unique((array)$input->get("{$field->code}_add", []));
        $idsDelete = array_unique((array)$input->get("{$field->code}_delete", []));

        $method = camel_case($field->code);

		if (app('auth')->can('update', 'object_field.' . $model->getTable() . '.' . $field->code))
		{
			if ( (!empty($idsAdd) || !empty($idsDelete)))
			{ 

				if (in_array('*', $idsDelete, true))
				{
					$model->{$method}()->detach();
				}
				else if (!empty($idsDelete))
				{
					$model->{$method}()->detach($idsDelete);
				}

                if (!$model->{$method}()->count() && empty($idsAdd))
                {
                    $idsAdd = $field->relation_many_to_many_default->all();
                }   
                
				foreach($idsAdd as $id)
				{
					try
					{
						if (app('auth')->can('update', $id))
						{
							$model->{$method}()->attach($id);
						}
					}
                    catch (\Exception $e) {}
				}
			}
		}

        if ($field->required && !$model->{$method}()->count())
        {
            throw new \Exception($this->LL('error.field.required', ['attribute' => $field->translate('title')]));
        }

        return $model;
    }

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
    		$input->put('relation_many_to_many_has', intval(\App\Telenok\Core\Model\Object\Type::where('code', $input->get('relation_many_to_many_has'))->orWhere('id', $input->get('relation_many_to_many_has'))->pluck('id')));
        }
        else
        {
            $input->put('relation_many_to_many_has', 0);
        }
        
        $input->put('multilanguage', 0);
		$input->put('allow_sort', 0);

        return parent::preProcess($model, $type, $input);
    } 

    public function postProcess($model, $type, $input)
    {
        if (!$input->get('relation_many_to_many_has'))
        {
            return parent::postProcess($model, $type, $input);
        }

        $relatedTypeOfModelField = $model->fieldObjectType()->first();

        $classModelHasMany = $relatedTypeOfModelField->class_model;
        $tableHasMany = $relatedTypeOfModelField->code;
        $codeFieldHasMany = $model->code; 
        $codeTypeHasMany = $relatedTypeOfModelField->code; 

        $typeBelongTo = \App\Telenok\Core\Model\Object\Type::findOrFail($input->get('relation_many_to_many_has')); 
        $tableBelongTo = $typeBelongTo->code;
        $classBelongTo = $typeBelongTo->class_model;

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

        $hasManyObject = app($classModelHasMany);
        $belongToObject = app($classBelongTo);

        if ($input->get('create_belong') !== false) 
        {
            $title = $input->get('title_belong', []);
            $title_list = $input->get('title_list_belong', []);

            foreach($relatedTypeOfModelField->title->all() as $language => $val)
            {
                $title[$language] = array_get($title, $language, $val . '/' . $model->translate('title', $language));
            }

            foreach($relatedTypeOfModelField->title_list->all() as $language => $val)
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

            $validator = $this->validator(new \App\Telenok\Core\Model\Object\Field(), $toSave, []);

            if ($validator->passes()) 
            {
                \App\Telenok\Core\Model\Object\Field::create($toSave);
            }

            if ($this->validateMethodExists($belongToObject, $belongTo['method']))
            {
                \Session::flash('warning.hasMany', $this->LL('error.method.defined', ['method'=>$belongTo['method'], 'class'=>$classBelongTo]));
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
            \Session::flash('warning.hasMany', $this->LL('error.method.defined', ['method'=>$hasMany['method'], 'class'=>$classModelHasMany]));
        } 
        else
        {
            $this->updateModelFile($hasManyObject, $hasMany, 'hasMany');
        }

        return parent::postProcess($model, $type, $input);
    }
    
    public function getStubFileDirectory()
    {
        return __DIR__;
    }
}