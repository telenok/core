<?php namespace Telenok\Core\Field\RelationOneToOne;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Controller extends \Telenok\Core\Interfaces\Field\Relation\Controller {

	protected $key = 'relation-one-to-one';
	protected $specialField = ['relation_one_to_one_has', 'relation_one_to_one_belong_to'];
	protected $allowMultilanguage = false;

	/**
	 * Return Object Type linked to the field
	 * 
	 * @param \App\Telenok\Core\Model\Object\Field $field
	 * @return \App\Telenok\Core\Model\Object\Type
	 * 
	 */
	public function getLinkedModelType($field)
	{
		return \App\Telenok\Core\Model\Object\Type::whereIn('id', [$field->relation_one_to_one_has, $field->relation_one_to_one_belong_to])->first();
	}

	public function getLinkedField($field)
	{
		return $field->relation_one_to_one_has ? 'relation_one_to_one_has' : 'relation_one_to_one_belong_to';
	}
	
	public function getModelFieldViewVariable($controller = null, $model = null, $field = null, $uniqueId = null)
	{
		$linkedField = $this->getLinkedField($field);
		
		return
		[
			'urlListTitle' => route($this->getRouteListTitle(), ['id' => (int)$field->{$linkedField}]),
			'urlListTable' => route($this->getRouteListTable(), ["id" => (int)$model->getKey(), "fieldId" => $field->getKey(), "uniqueId" => $uniqueId]),
			'urlWizardChoose' => route($this->getRouteWizardChoose(), ['id' => $this->getChooseTypeId($field)]),
			'urlWizardCreate' => route($this->getRouteWizardCreate(), [ 'id' => $field->{$linkedField}, 'saveBtn' => 1, 'chooseBtn' => 1]),
			'urlWizardEdit' => route($this->getRouteWizardEdit(), ['id' => '--id--', 'saveBtn' => 1]),
		];
	}

	public function getModelFillableField($model, $field)
	{
		return $field->relation_one_to_one_belong_to ? [$field->code] : [];
	}

	public function getFormModelContent($controller = null, $model = null, $field = null, $uniqueId = null)
	{
		if ($field->relation_one_to_one_has || $field->relation_one_to_one_belong_to)
		{
			return parent::getFormModelContent($controller, $model, $field, $uniqueId);
		}
	}

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

	public function saveModelField($field, $model, $input)
	{
		if ($model instanceof \Telenok\Core\Model\Object\Field && !$input->get('id'))
		{
			return $model;
		}

		if ($field->relation_one_to_one_has)
		{
			$method = camel_case($field->code);

			$relatedQuery = $model->{$method}();

			try
			{
				$relatedQuery->getRelated()->findOrFail((int) $input->get($field->code, 0))
						->storeOrUpdate([$relatedQuery->getPlainForeignKey() => $model->getKey()], true);
			}
            catch (\Exception $e) {}
		}

		return $model;
	}

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

		$input->put('relation_one_to_one_has', intval(\App\Telenok\Core\Model\Object\Type::where('code', $input->get('relation_one_to_one_has'))->orWhere('id', $input->get('relation_one_to_one_has'))->pluck('id')));
		$input->put('multilanguage', 0);
		$input->put('allow_sort', 0);

		return parent::preProcess($model, $type, $input);
	}

	public function postProcess($model, $type, $input)
	{
        $model->fill(['relation_one_to_one_has' => $input->get('relation_one_to_one_has')])->save();

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

            $validator = $this->validator(new \App\Telenok\Core\Model\Object\Field(), $toSave, []);

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
    

    public function getStubFileDirectory()
    {
        return __DIR__;
    }
}