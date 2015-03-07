<?php

namespace Telenok\Core\Field\DateTime;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration; 

class Controller extends \Telenok\Core\Interfaces\Field\Controller {

    protected $key = 'datetime'; 
    protected $allowMultilanguage = false;
	protected $specialDateField = ['datetime_default'];

    public function getDateField($model, $field)
    { 
		return [$field->code];
    } 
    
    public function getListFieldContent($field, $item, $type = null)
    {  
        return (string)$item->{$field->code};
    } 

    public function setModelAttribute($model, $key, $value, $field)
    {  
        if ($value === null)
        {
            $value = $field->datetime_default ?: null;
        }

        return parent::setModelAttribute($model, $key, $value, $field);
    }
    
    public function getModelSpecialAttribute($model, $key, $value)
    {
        try
        {
			if ($key == 'datetime_default' && $value === null)
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
                    $query->where($model->getTable() . '.' . $name, '>=', $v);
                }

                if ($v = trim(array_get($value, 'end')))
                {
                    $query->where($model->getTable() . '.' . $name, '<=', $v);
                }
			});
		}
    }

    public function setModelSpecialAttribute($model, $key, $value)
    {  
        if ($key == 'datetime_default')
		{
            if ($value === null)
            {
                $value = \Carbon\Carbon::now();
            }
		}

        return parent::setModelSpecialAttribute($model, $key, $value);
    }
    
    public function postProcess($model, $type, $input)
    {
		$table = $model->fieldObjectType()->first()->code;
        $fieldName = $model->code;

		if (!\Schema::hasColumn($table, $fieldName) && !\Schema::hasColumn($table, "`{$fieldName}`"))
		{
			\Schema::table($table, function(Blueprint $table) use ($fieldName)
			{
				$table->timestamp($fieldName)->nullable();
			});
		}
        
        $fields = []; 
        
        $fields['rule'] = [];

        if ($input->get('required'))
        {
            $fields['rule'][] = 'required';
        }
		
        $model->fill($fields)->save();
        
        return parent::postProcess($model, $type, $input);
    }
}

