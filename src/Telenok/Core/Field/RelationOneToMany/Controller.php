<?php 

namespace Telenok\Core\Field\RelationOneToMany;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Controller extends \Telenok\Core\Interfaces\Field\Relation\Controller {

    protected $key = 'relation-one-to-many'; 
    protected $specialField = ['relation_one_to_many_has', 'relation_one_to_many_belong_to'];
    protected $allowMultilanguage = false;

	public function getChooseTypeId($field, $linkedField)
	{
		return $field->relation_one_to_many_belong_to ? $field->{$linkedField} : $field->relation_one_to_many_has;
	}
	
	public function getLinkedModelType($field)
	{
		return \App\Model\Telenok\Object\Type::whereIn('id', [$field->relation_one_to_many_has, $field->relation_one_to_many_belong_to])->first();
	}
	
    public function getFormModelContent($controller = null, $model = null, $field = null, $uniqueId = null)
    { 		
		if ($field->relation_one_to_many_has || $field->relation_one_to_many_belong_to)
		{
			return parent::getFormModelContent($controller, $model, $field, $uniqueId);
		}
	} 

    public function getModelField($model, $field)
    {
		return $field->relation_one_to_many_belong_to ? [$field->code] : [];
    } 

    public function getFilterQuery($field = null, $model = null, $query = null, $name = null, $value = null) 
    {
		if (!empty($value))
		{
			if ($field->relation_one_to_many_belong_to)
			{
				$query->whereIn($name, (array)$value);
			}
			else
			{
				$method = camel_case($field->code);

				$relatedQuery = $model->$method();

				$linkedTable = $relatedQuery->getRelated()->getTable();

				$alias = $linkedTable . '_O2O_' . $field->code;
				
				$query->join($linkedTable . ' as ' . $alias, function($join) use ($linkedTable, $relatedQuery, $model, $alias)
				{
					$join->on($model->getTable() . '.id', '=', $alias . '.' . $relatedQuery->getPlainForeignKey());
				});

				$query->whereIn($alias . '.id', (array)$value);
			}
		}
    }

    public function getFilterContent($field = null)
    {
        $uniqueId = str_random();
        $option = [];
        
        $id = $field->relation_one_to_many_has ?: $field->relation_one_to_many_belong_to;
        
        $class = \App\Model\Telenok\Object\Sequence::getModel($id)->class_model;
        
		$model = new $class;
		
        $model::withPermission()->take(20)->groupBy($model->getTable() . '.id')->get()->each(function($item) use (&$option)
        {
            $option[] = "<option value='{$item->id}'>[{$item->id}] {$item->translate('title')}</option>";
        });
        
        $option[] = "<option value='0' disabled='disabled'>...</option>";
        
        return '
            <select class="chosen-select" multiple data-placeholder="'.$this->LL('notice.choose').'" id="input'.$uniqueId.'" name="filter['.$field->code.'][]">
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

    public function saveModelField($field, $model, $input)
    {
		// if created field
		if ($model instanceof \Telenok\Core\Model\Object\Field && !$input->get('id'))
		{
			return $model;
		}

		$idsAdd = array_unique((array)$input->get("{$field->code}_add", []));
        $idsDelete = array_unique((array)$input->get("{$field->code}_delete", []));

        if ( (!empty($idsAdd) || !empty($idsDelete)) && $field->relation_one_to_many_has)
        { 
            $method = camel_case($field->code);

            $relatedField = $field->code . '_' . $model->sequence->sequencesObjectType->code;

            if (in_array('*', $idsDelete, true))
            {
                $model->$method()->get()->each(function($item) use ($relatedField) 
                {
                    $item->fill([$relatedField => 0])->save();
                });
            }
            else if (!empty($idsDelete))
            {
                $model->$method()->whereIn('id', $idsDelete)->get()->each(function($item) use ($relatedField) 
                {
                    $item->fill([$relatedField => 0])->save();
                });
            }

			$method = camel_case($field->code);

			$relatedQuery = $model->$method();

			try
			{
				foreach($idsAdd as $id)
				{
					$relatedQuery->getRelated()->findOrFail((int)$id)
						->storeOrUpdate([$relatedQuery->getPlainForeignKey() => $model->getKey()], true);
				}
			}
			catch (\Exception $e) {}
        }

        return $model;
    }
	
    public function preProcess($model, $type, $input)
    {
		if (!$input->get('relation_one_to_many_belong_to'))
		{
			$this->validateExistsInputField($input, ['field_has', 'relation_one_to_many_has']);
		}
		
		if (!$input->get('relation_one_to_many_has') && $input->get('field_has'))
		{
			$input->put('relation_one_to_many_has', $input->get('field_has'));
		}

		$input->put('relation_one_to_many_has', intval(\App\Model\Telenok\Object\Type::where('code', $input->get('relation_one_to_many_has'))->orWhere('id', $input->get('relation_one_to_many_has'))->pluck('id')));
		$input->put('multilanguage', 0);
		$input->put('allow_sort', 0);
		
        return parent::preProcess($model, $type, $input);
    } 

    public function postProcess($model, $type, $input)
    {
        try 
        {
			$model->fill(['relation_one_to_many_has' => $input->get('relation_one_to_many_has')])->save();
			 
			if (!$input->get('relation_one_to_many_has'))
			{
				return parent::postProcess($model, $type, $input);
			} 

            $relatedTypeOfModelField = $model->fieldObjectType()->first();   // eg object \App\Model\Telenok\Object\Type which DB-field "code" is "author"

            $classModelHasMany = $relatedTypeOfModelField->class_model;
            $codeFieldHasMany = $model->code; 
            $codeTypeHasMany = $relatedTypeOfModelField->code; 

            $typeBelongTo = \App\Model\Telenok\Object\Type::findOrFail($input->get('relation_one_to_many_has')); 
            $tableBelongTo = $typeBelongTo->code;
            $classBelongTo = $typeBelongTo->class_model;

            $relatedSQLField = $codeFieldHasMany . '_' . $codeTypeHasMany;

            $hasMany = [
                    'method' => camel_case($codeFieldHasMany),
                    'class' => $classBelongTo,
                    'field' => $relatedSQLField,
                ];

            $belongTo = [
                    'method' => camel_case($relatedSQLField),
                    'class' => $classModelHasMany,
                    'field' => $relatedSQLField,
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
					'code' => $relatedSQLField,
					'field_object_type' => $typeBelongTo->getKey(),
					'field_object_tab' => $tabTo->getKey(),
					'relation_one_to_many_belong_to' => $relatedTypeOfModelField->getKey(),
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
				
				$validator = $this->validator(new \App\Model\Telenok\Object\Field(), $toSave, []);

				if ($validator->passes()) 
				{
					\App\Model\Telenok\Object\Field::create($toSave);
				}

				if (!\Schema::hasColumn($tableBelongTo, $relatedSQLField) && !\Schema::hasColumn($tableBelongTo, "`{$relatedSQLField}`"))
				{
					\Schema::table($tableBelongTo, function(Blueprint $table) use ($relatedSQLField)
					{
						$table->integer($relatedSQLField)->unsigned()->nullable();
					});
				}

				if (!$this->validateMethodExists($belongToObject, $belongTo['method']))
				{
					$this->updateModelFile($belongToObject, $belongTo, 'belongsTo', __DIR__);
				}
				else
				{
					\Session::flash('warning.hasManyBelongTo', $this->LL('error.method.defined', ['method'=>$belongTo['method'], 'class'=>$classBelongTo]));
				} 
			}

            if (!$this->validateMethodExists($hasManyObject, $hasMany['method']))
            {
                $this->updateModelFile($hasManyObject, $hasMany, 'hasMany', __DIR__);
            } 
            else
            {
                \Session::flash('warning.hasMany', $this->LL('error.method.defined', ['method'=>$hasMany['method'], 'class'=>$classModelHasMany]));
            }
        }
        catch (\Exception $e) 
        {
            throw $e;
        }

        return parent::postProcess($model, $type, $input);
    } 

}

