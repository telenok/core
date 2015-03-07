<?php

namespace Telenok\Core\Field\MorphManyToMany;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Controller extends \Telenok\Core\Interfaces\Field\Relation\Controller {

    protected $key = 'morph-many-to-many';
    protected $specialField = ['morph_many_to_many_has', 'morph_many_to_many_belong_to'];
    protected $allowMultilanguage = false;

    public function getModelField($model, $field)
    {
		return [];
    } 

	public function getLinkedModelType($field)
	{
		return \App\Model\Telenok\Object\Type::whereIn('id', [$field->morph_many_to_many_has, $field->morph_many_to_many_belong_to])->first();
	} 
	
    public function getFilterQuery($field = null, $model = null, $query = null, $name = null, $value = null)
    {
		if (!empty($value))
		{
			$method = camel_case($field->code);
			$relatedQuery = $model->$method();

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

        $class = \App\Model\Telenok\Object\Sequence::getModel($id)->class_model;

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
                    url: "'.\URL::route($this->getRouteListTitle(), ['id' => (int)$id]).'", 
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
		// if created field
		if ($model instanceof \Telenok\Core\Model\Object\Field && !$input->get('id'))
		{
			return $model;
		}

		$idsAdd = array_unique((array)$input->get("{$field->code}_add", []));
        $idsDelete = array_unique((array)$input->get("{$field->code}_delete", []));
         
        if ( (!empty($idsAdd) || !empty($idsDelete)))
        { 
            $method = camel_case($field->code);
             
            if (in_array('*', $idsDelete, true))
            {
                $model->$method()->detach();
            }
            else if (!empty($idsDelete))
            {
                $model->$method()->detach($idsDelete);
            }

            foreach($idsAdd as $id)
            {
                try
                {
                    $model->$method()->attach($id);
                }
                catch(\Exception $e) {}
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

		$input->put('morph_many_to_many_has', intval(\App\Model\Telenok\Object\Type::where('code', $input->get('morph_many_to_many_has'))->orWhere('id', $input->get('morph_many_to_many_has'))->pluck('id')));
		$input->put('multilanguage', 0);
		$input->put('allow_sort', 0); 

        return parent::preProcess($model, $type, $input);
    } 
	
    public function postProcess($model, $type, $input)
    { 
        try
        {
			$model->fill(['morph_many_to_many_has' => $input->get('morph_many_to_many_has')])->save();
			
			if (!$input->get('morph_many_to_many_has'))
			{
				return parent::postProcess($model, $type, $input);
			} 

            $typeMorphMany = $model->fieldObjectType()->first();
            $typeBelongTo = \App\Model\Telenok\Object\Type::findOrFail($input->get('morph_many_to_many_has')); 

            $morphManyCode = $model->code;
            $morphToCode = $morphManyCode . '_' . $typeMorphMany->code;

            $classModelMorphMany = $typeMorphMany->class_model;
            $classModelMorphTo = $typeBelongTo->class_model;
 

            $morphManyObject = app($classModelMorphMany);
            $morphToObject = app($classModelMorphTo);

            $pivotTable = 'pivot_morph_m2m_' . $morphManyCode . '_' . $typeBelongTo->code;

            $morphMany = [
                'method' => camel_case($morphManyCode),
                'name' => $morphManyCode,
                'class' => $classModelMorphTo,
                'table' => $pivotTable,
                'foreignKey' => $morphManyCode . '_linked_id',
                'otherKey' => 'morph_id',
            ];

            $morphTo = [
                'method' => camel_case($morphToCode),
                'name' => $morphManyCode,
                'class' => $classModelMorphMany,
                'table' => $pivotTable,
                'foreignKey' => 'morph_id',
                'otherKey' => $morphManyCode . '_linked_id',
            ];

            if (!\Schema::hasTable($pivotTable)) 
			{
                \Schema::create($pivotTable, function(Blueprint $table) use ($morphManyCode, $typeMorphMany, $typeBelongTo)
                {
                    $table->increments('id');
                    $table->timestamps();
                    $table->integer('morph_id')->unsigned()->nullable();
                    $table->integer($morphManyCode . '_linked_id')->unsigned()->nullable();
                    $table->string($morphManyCode . '_type')->nullable();

                    $table->unique(['morph_id', $morphManyCode . '_linked_id', $morphManyCode . '_type'], 'uniq_key');
                });
            }

            if ($input->get('create_belong') !== false) 
            {
				$title = $input->get('title_belong', []);
				$title_list = $input->get('title_list_belong', []);

				foreach($typeMorphMany->title->all() as $language => $val)
				{
					$title[$language] = array_get($title, $language, $model->translate('title', $language) . ' [morphMany]');
				}

				foreach($typeMorphMany->title_list->all() as $language => $val)
				{
					$title_list[$language] = array_get($title_list, $language, $model->translate('title_list', $language) . ' [morphMany]');
				}

				$tabTo = $this->getFieldTabBelongTo($typeBelongTo->getKey(), $input->get('field_object_tab_belong'), $input->get('field_object_tab'));

				$toSave = [
					'title' => $title,
					'title_list' => $title_list,
					'key' => $this->getKey(),
					'code' => $morphToCode,
					'field_object_type' => $typeBelongTo->getKey(),
					'field_object_tab' => $tabTo->getKey(),
					'morph_many_to_many_belong_to' => $typeMorphMany->getKey(),
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


				$validator = $this->validator(app('\App\Model\Telenok\Object\Field'), $toSave, []);

				if ($validator->passes()) 
				{
					\App\Model\Telenok\Object\Field::create($toSave);
				}

				if (!$this->validateMethodExists($morphToObject, $morphTo['method']))
				{
					$this->updateModelFile($morphToObject, $morphTo, 'morphTo', __DIR__);
				} 
				else
				{
					\Session::flash('warning.morphManyTo', $this->LL('error.method.defined', ['method' => $morphTo['method'], 'class' => $classModelMorphTo]));
				} 
			}

            if (!$this->validateMethodExists($morphManyObject, $morphMany['method']))
            {
                $this->updateModelFile($morphManyObject, $morphMany, 'morphMany', __DIR__);
            } 
            else
            {
                \Session::flash('warning.morphManyHas', $this->LL('error.method.defined', ['method' => $morphMany['method'], 'class' => $classModelMorphMany]));
            }
        }
        catch (\Exception $e) 
        {
            throw $e;
        }

        return parent::postProcess($model, $type, $input);
    } 
}

