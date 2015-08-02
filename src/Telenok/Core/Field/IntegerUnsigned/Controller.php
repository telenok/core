<?php namespace Telenok\Core\Field\IntegerUnsigned;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;  

class Controller extends \Telenok\Core\Interfaces\Field\Controller {

	protected $key = 'integer-unsigned';
	protected $specialField = ['integer_unsigned_default', 'integer_unsigned_min', 'integer_unsigned_max'];
	protected $ruleList = ['integer_unsigned_default' => ['integer', 'between:0,4294967295'], 'integer_unsigned_min' => ['integer', 'between:0,4294967295'], 'integer_unsigned_max' => ['integer', 'between:0,4294967295']];
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
			$value = $field->integer_unsigned_default;
		}

		return $value;
	}

	public function setModelAttribute($model, $key, $value, $field)
	{
		if ($value === null)
		{
            $default = $field->integer_unsigned_default?:0;
            
			$model->setAttribute($key, $default);
		}
		else
		{
			$model->setAttribute($key, (int)$value);
		}
	}
    public function getModelSpecialAttribute($model, $key, $value)
    {
		if (in_array($key, ['integer_unsigned_default', 'integer_unsigned_min', 'integer_unsigned_max'], true) && $value === null)
		{ 
			if ($key == 'integer_unsigned_default')
			{
				return 0;
			}
			else if ($key == 'integer_unsigned_min')
			{
				return 0;
			}
			else if ($key == 'integer_unsigned_max')
			{
				return 4294967295;
			}
		}

		return parent::getModelSpecialAttribute($model, $key, $value);
    }

    public function setModelSpecialAttribute($model, $key, $value)
    {
        if (in_array($key, ['integer_unsigned_default', 'integer_unsigned_min', 'integer_unsigned_max'], true))
		{
            if ($value === null)
            {
                if ($key == 'integer_unsigned_default')
				{
					$value = 0;
				}
				else if ($key == 'integer_unsigned_min')
				{
					$value = 0;
				}
				else if ($key == 'integer_unsigned_max')
				{
					$value = 4294967295;
				}
            }
			else
			{
				$value = (int)$value;
			}
		}

        return parent::setModelSpecialAttribute($model, $key, $value);
    }

	public function postProcess($model, $type, $input)
	{
		$table = $model->fieldObjectType()->first()->code;
		$fieldName = $model->getAttribute('code');

		if (!\Schema::hasColumn($table, $fieldName) && !\Schema::hasColumn($table, "`{$fieldName}`"))
		{
			\Schema::table($table, function(Blueprint $table) use ($fieldName)
			{
				$table->integer($fieldName)->unsigned()->nullable();
			});
		}

		$field = [];
		$field['multilanguage'] = 0;
		$field['rule'][] = 'integer';
		
		$field['integer_unsigned_default'] = $input->get('integer_unsigned_min');

		if ($input->get('required'))
		{
			$field['rule'][] = 'required';
		}

		if ($input->get('integer_unsigned_min'))
		{
			$field['rule'][] = "min:{(int)$input->get('integer_unsigned_min')}";
		}

		if ($input->get('integer_unsigned_max'))
		{
			$field['rule'][] = "max:{(int)$input->get('integer_unsigned_max')}";
		}

		$model->fill($field)->save();

		return parent::postProcess($model, $type, $input);
	}
}