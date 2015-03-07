<?php 

namespace Telenok\Core\Field\MorphOneToMany;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Controller extends \Telenok\Core\Interfaces\Field\Relation\Controller {

    protected $key = 'morph-one-to-many'; 
    protected $specialField = ['morph_one_to_many_has', 'morph_one_to_many_belong_to', 'morph_one_to_many_belong_to_type_list'];
    protected $allowMultilanguage = false;

	public function getChooseTypeId($field, $linkedField)
	{
		return $field->morph_one_to_many_has ? $field->morph_one_to_many_has : $field->morph_one_to_many_belong_to_type_list->all();
	}

	public function getLinkedModelType($field)
	{
		return \App\Model\Telenok\Object\Type::whereIn('id', [$field->morph_one_to_many_has, $field->morph_one_to_many_belong_to])->first();
	}
	
    public function getFormModelContent($controller = null, $model = null, $field = null, $uniqueId = null)
    { 		
		if ($field->morph_one_to_many_has || $field->morph_one_to_many_belong_to)
		{
			return parent::getFormModelContent($controller, $model, $field, $uniqueId);
		}
	} 

    public function getModelField($model, $field)
    {
		return $field->morph_one_to_many_belong_to ? [$field->code . '_type', $field->code . '_id'] : [];
    }
	
    public function getModelSpecialAttribute($model, $key, $value)
    {
        try
        {
			if (in_array($key, ['morph_one_to_many_belong_to_type_list'], true))
			{
				$value = $value ? : '[]';

				$v = json_decode($value, true);

				if (is_array($v))
				{
					return \Illuminate\Support\Collection::make($v);
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

    public function setModelSpecialAttribute($model, $key, $value)
    {
		if (in_array($key, ['morph_one_to_many_belong_to_type_list'], true))
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

    public function getListFieldContentItems($field, $item, $type = null)
    {
        $method = camel_case($field->code);

        return $item->$method ? $item->$method()->take(8)->get() : [];
    }

    public function getFilterQuery($field = null, $model = null, $query = null, $name = null, $value = null) 
    {
		if (!empty($value))
		{
			if ($field->morph_one_to_many_has)
			{
				$modelTable = $model->getTable();

				$linkedTable = \App\Model\Telenok\Object\Sequence::getModel($field->morph_one_to_many_has)->code;

				$alias = $linkedTable . str_random();
				
				$query->join($linkedTable . ' as ' . $alias, function($join) use ($modelTable, $linkedTable, $name, $field, $alias)
				{
					$join->on($modelTable . '.id', '=', $alias . '.' . $field->code . 'able_id');
				});

				$query->whereIn($alias.'.id', (array)$value);
			}
			else if ($field->morph_one_to_many_belong_to)
			{
				$modelTable = $model->getTable();

				$linkedTable = 'object_sequence';

				$alias = $linkedTable . str_random();

				$query->join($linkedTable . ' as ' . $alias, function($join) use ($modelTable, $linkedTable, $name, $field, $alias)
				{
					$join->on($modelTable . '.' . $field->code . '_id', '=', $alias . '.id');
				});

				$query->whereIn($alias.'.id', (array)$value);
				$query->whereIn($alias.'.sequences_object_type', $field->morph_one_to_many_belong_to_type_list->all());
			}
		}
    }

    public function getFilterContent($field = null)
    {
        $uniqueId = str_random();
        $option = [];
        
        $id = $field->morph_one_to_many_has ?: $field->morph_one_to_many_belong_to;
        
        $class = \App\Model\Telenok\Object\Sequence::getModel($id)->class_model;
        
		$model = new $class;
		
        $query = $model::withPermission()->take(20)->groupBy($model->getTable() . '.id');
		
		if ($field->morph_one_to_many_belong_to)
		{
			$query->whereIn($model->getTable() . '.sequences_object_type', $field->morph_one_to_many_belong_to_type_list->all());
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

		$id = $input->get("{$field->code}", 0);
		
		if ($field->morph_one_to_many_belong_to)
		{ 
			if ($id)
			{
				$objectModel = \App\Model\Telenok\Object\Sequence::find($id)->model()->first();

				if (in_array($objectModel->type()->getKey(), $field->morph_one_to_many_belong_to_type_list->all(), true))
				{
					$model->fill([$field->code . '_type' => get_class($objectModel), $field->code . '_id' => $objectModel->getKey()])->save();
				}
			}
			else
			{
				$model->fill([$field->code . '_type' => null, $field->code . '_id' => null])->save();
			}
		}
		else if ($field->morph_one_to_many_has)
		{ 
			$idsAdd = array_unique((array)$input->get("{$field->code}_add", []));
			$idsDelete = array_unique((array)$input->get("{$field->code}_delete", []));

			if (!empty($idsAdd) || !empty($idsDelete))
			{ 
				$method = camel_case($field->code);

				$relatedField = $field->code . 'able';

				if (in_array('*', $idsDelete, true))
				{
					$model->$method()->get()->each(function($item) use ($relatedField) 
					{
						$item->fill([$relatedField . '_id' => 0, $relatedField . '_type' => null])->save();
					});
				}
				else if (!empty($idsDelete))
				{
					$model->$method()->whereIn('id', $idsDelete)->get()->each(function($item) use ($relatedField) 
					{
						$item->fill([$relatedField . '_id' => 0, $relatedField . '_type' => null])->save();
					});
				}
				
				$relatedModel = app(\App\Model\Telenok\Object\Type::findOrFail($field->morph_one_to_many_has)->class_model);

				\Illuminate\Support\Collection::make($idsAdd)->each(function($id) use ($model, $method, $relatedModel) 
				{
					if (intval($id)) 
					{
						try
						{
							$linked = $relatedModel::findOrFail($id);
							$model->$method()->save( $linked );
						} 
						catch(\Exception $e) {}
					}
				});
			}
		}
		
        return $model;
    }
	
    public function preProcess($model, $type, $input)
    {
		if (!$input->get('morph_one_to_many_belong_to'))
		{
			$this->validateExistsInputField($input, ['field_has', 'morph_one_to_many_has']);
		}
		
		if (!$input->get('morph_one_to_many_has') && $input->get('field_has'))
		{
			$input->put('morph_one_to_many_has', $input->get('field_has'));
		}

		$input->put('morph_one_to_many_has', intval(\App\Model\Telenok\Object\Type::where('code', $input->get('morph_one_to_many_has'))->orWhere('id', $input->get('morph_one_to_many_has'))->pluck('id')));
		$input->put('multilanguage', 0);
		$input->put('allow_sort', 0); 
		
        return parent::preProcess($model, $type, $input);
    } 

    public function processDeleting($model)
    {  
		if ($model->morph_one_to_many_has)
		{
			$f = \App\Model\Telenok\Object\Field::where(function($query) use ($model)
					{
						$query->where('code', $model->code . 'able');
						$query->where('field_object_type', $model->morph_one_to_many_has);
					})
					->first();

			if ($f)
			{
				$tList = $f->morph_one_to_many_belong_to_type_list;

				$tNewList = $tList->reject(function($item) use ($model) 
				{
					return $item == $model->fieldObjectType->getKey();
				});

				$f->morph_one_to_many_belong_to_type_list = $tNewList;

				$f->update();
			}
		}

        return parent::processDeleting($model);
    } 
	
    public function postProcess($model, $type, $input)
    {
        try 
        {
			$model->fill(['morph_one_to_many_has' => $input->get('morph_one_to_many_has')])->save();
			
			if (!$input->get('morph_one_to_many_has'))
			{
				return parent::postProcess($model, $type, $input);
			} 

            $relatedTypeOfModelField = $model->fieldObjectType()->first();   // eg object \App\Model\Telenok\Object\Type which DB-field "code" is "author"

            $classModelHasMany = $relatedTypeOfModelField->class_model;
            $codeFieldHasMany = $model->code; 
            $codeTypeHasMany = $relatedTypeOfModelField->code; 

            $typeBelongTo = \App\Model\Telenok\Object\Type::findOrFail($input->get('morph_one_to_many_has')); 
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
					'morph_one_to_many_belong_to' => \App\Model\Telenok\Object\Type::where('code', 'object_sequence')->pluck('id'),
					'morph_one_to_many_belong_to_type_list' => [$relatedTypeOfModelField->getKey()],
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


				$f = \App\Model\Telenok\Object\Field::where(function($query) use ($relatedSQLField, $model)
						{
							$query->where('code', $relatedSQLField);
							$query->where('field_object_type', $model->morph_one_to_many_has);
						})
						->first();

				if ($f)
				{
					$tList = $f->morph_one_to_many_belong_to_type_list;

					$tList->push($relatedTypeOfModelField->getKey());

					$f->morph_one_to_many_belong_to_type_list = $tList;

					$f->update();
				}
				else
				{
					$validator = $this->validator(new \App\Model\Telenok\Object\Field(), $toSave, []);
					
					if ($validator->passes()) 
					{
						\App\Model\Telenok\Object\Field::create($toSave);
					}
				}

				try
				{
					\Schema::table($tableBelongTo, function(Blueprint $table) use ($relatedSQLField)
					{
						$table->unsignedInteger("{$relatedSQLField}_id")->nullable();

						$table->string("{$relatedSQLField}_type")->nullable();

						$table->index(array("{$relatedSQLField}_id", "{$relatedSQLField}_type"));					
					});
				} 
				catch (\Exception $ex) {}

				if (!$this->validateMethodExists($belongToObject, $belongTo['method']))
				{
					$this->updateModelFile($belongToObject, $belongTo, 'morphTo', __DIR__);
				}
				else
				{
					\Session::flash('warning.morphOneTo', $this->LL('error.method.defined', ['method'=>$belongTo['method'], 'class'=>$classBelongTo]));
				} 
			}

            if (!$this->validateMethodExists($hasManyObject, $hasMany['method']))
            {
                $this->updateModelFile($hasManyObject, $hasMany, 'morphMany', __DIR__);
            } 
            else
            {
                \Session::flash('warning.morphManyHas', $this->LL('error.method.defined', ['method'=>$hasMany['method'], 'class'=>$classModelHasMany]));
            }
        }
        catch (\Exception $e) 
        {
            throw $e;
        }

        return parent::postProcess($model, $type, $input);
    } 

}

