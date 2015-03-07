<?php

namespace Telenok\Core\Field\Integer;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;  

class Controller extends \Telenok\Core\Interfaces\Field\Controller {

	protected $key = 'integer';
	protected $specialField = ['integer_default', 'integer_min', 'integer_max'];
	protected $ruleList = ['integer_default' => ['integer', 'between:-2147483648,2147483647'], 'integer_min' => ['integer', 'between:-2147483648,2147483647'], 'integer_max' => ['integer', 'between:-2147483648,2147483647']];
	protected $allowMultilanguage = false;

	public function getListFieldContent($field, $item, $type = null)
	{
		return $item->{$field->code};
	}

	public function getFilterQuery($field = null, $model = null, $query = null, $name = null, $value = null)
	{
		if (($value = trim($value)) !== "")
		{
			$query->whereIn($model->getTable() . '.' . $name, explode(',', $value));
		}
	}

	public function getModelAttribute($model, $key, $value, $field)
	{
		if ($value === null)
		{
			$value = $field->integer_default?:0;
		}

		return $value;
	}

	public function setModelAttribute($model, $key, $value, $field)
	{
		if ($field->key == $key)
		{ 
			if ($value === null)
			{
                $default = $field->integer_default?:null;

				$model->setAttribute($key, $default);
			}
			else
			{
				$model->setAttribute($key, $value);
			}
		}
	}

	public function postProcess($model, $type, $input)
	{
		$table = $model->fieldObjectType()->first()->getAttribute('code');
		$fieldName = $model->getAttribute('code');

		if (!\Schema::hasColumn($table, $fieldName) && !\Schema::hasColumn($table, "`{$fieldName}`"))
		{
			\Schema::table($table, function(Blueprint $table) use ($fieldName)
			{
				$table->integer($fieldName)->nullable();
			});
		}

		$field = [];
		$field['multilanguage'] = 0;
		$field['rule'][] = 'integer';

		$field['integer_default'] = $input->get('integer_default', null);

		if ($input->get('required'))
		{
			$field['rule'][] = 'required';
		}

		if ($input->get('integer_min'))
		{
			$field['rule'][] = "min:{$input->get('integer_min')}";
		}

		if ($input->get('integer_max'))
		{
			$field['rule'][] = "max:{$input->get('integer_max')}";
		}

		$model->fill($field)->save();

		return parent::postProcess($model, $type, $input);
	}

}

