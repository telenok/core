<?php

namespace Telenok\Core\Field\TimeRange;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration; 

class Controller extends \Telenok\Core\Interfaces\Field\Controller {

    protected $key = 'time-range'; 
    protected $allowMultilanguage = false;
	protected $specialDateField = ['time_range_default_start', 'time_range_default_end'];

    public function getDateField($model, $field)
    { 
		return [$field->code . '_start', $field->code . '_end'];
    } 

    public function getModelField($model, $field)
    {
		return [];
    } 

    public function getListFieldContent($field, $item, $type = null)
    {  
        $value = [];
        $value[] = ($v = $item->{$field->code . '_start'}) ? $v->toTimeString() : "";
        $value[] = ($v = $item->{$field->code . '_end'}) ? $v->toTimeString() : "";
        
        return count($value) ? implode(' ... ', $value) : '';
    } 

    public function setModelAttribute($model, $key, $value, $field)
    {   
        if (in_array($key, [$field->code . '_start', $field->code . '_end'], true))
        {
            if ($value === null)
            {
                $value = $field->$key ?: null;
            }
            else if (is_scalar($value) && $value)
            {
                $value = \Carbon\Carbon::createFromFormat('H:i:s', $value);
            } 
        }

        parent::setModelAttribute($model, $key, $value, $field);
    }
    
    public function getModelSpecialAttribute($model, $key, $value)
    {
        try
        {
			if (in_array($key, ['time_range_default_start', 'time_range_default_end'], true) && $value === null)
			{ 
                return \Carbon\Carbon::now();
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
        if (in_array($key, ['time_range_default_start', 'time_range_default_end'], true))
		{
            if ($value === null)
            {
                $value = \Carbon\Carbon::now();
            }
            else if (is_scalar($value) && $value)
            {
                $value = \Carbon\Carbon::createFromFormat('H:i:s', $value);
            }
		}

        return parent::setModelSpecialAttribute($model, $key, $value);
    }
    
    public function getFilterContent($field = null)
    {
        return view($this->getViewFilter(), [
            'controller' => $this,
            'field' => $field,
        ]);
    }

    public function getFilterQuery($field = null, $model = null, $query = null, $name = null, $value = null) 
    {
		if ($value !== null)
		{
			$query->where(function($query) use ($value, $name, $model)
			{
                if ($v = trim(array_get($value, 'start')))
                {
                    $query->where(\DB::raw('TIME(' . $model->getTable() . '.' . $name . '_end)'), '>=', $v);
                }

                if ($v = trim(array_get($value, 'end')))
                {
                    $query->where(\DB::raw('TIME(' . $model->getTable() . '.' . $name . '_start)'), '<=', $v);
                }
			});
		}
    }

    public function postProcess($model, $type, $input)
    {
		$table = $model->fieldObjectType()->first()->code;
        $fieldName = $model->code;

		if (!\Schema::hasColumn($table, $fieldName . '_start') && !\Schema::hasColumn($table, "`{$fieldName}_start`"))
		{
			\Schema::table($table, function(Blueprint $table) use ($fieldName)
			{
				$table->timestamp($fieldName . '_start')->nullable();
			});
		}

		if (!\Schema::hasColumn($table, $fieldName . '_end') && !\Schema::hasColumn($table, "`{$fieldName}_end`"))
		{
			\Schema::table($table, function(Blueprint $table) use ($fieldName)
			{
				$table->timestamp($fieldName . '_end')->nullable();
			});
		}

        $fields = []; 

        if ($input->get('required'))
        {
            $fields['rule'][] = 'required';
        }

        $model->fill($fields)->save();

        return parent::postProcess($model, $type, $input);
    }
}

