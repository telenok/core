<?php namespace Telenok\Core\Field\SelectOne;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration; 

class Controller extends \Telenok\Core\Interfaces\Field\Controller {

    protected $key = 'select-one'; 
    protected $allowMultilanguage = false;
    protected $specialField = ['select_one_data'];
    protected $viewModel = "core::field.select-one.model-select-box";

	public function getModelAttribute($model, $key, $value, $field)
	{
		if ($value === null)
		{
            $value = array_get((array)json_decode($field->select_one_data, true), 'default', null);
		}

		return $value;
	}

	public function setModelAttribute($model, $key, $value, $field)
	{
        if ($value === null)
        {
            $default = array_get((array)json_decode($field->select_one_data, true), 'default', null);
            $model->setAttribute($key, $default);
        }
        else
        {
            $model->setAttribute($key, $value);
        }
	}

    public function getModelSpecialAttribute($model, $key, $value)
    {
        try
        {
			if (in_array($key, ['select_one_data'], true))
			{ 
				return \Illuminate\Support\Collection::make(json_decode($value, true));
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
		if (in_array($key, ['select_one_data'], true))
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

            if ($key == 'select_one_data')
            {
                $localeDefault = config('app.localeDefault');

                $title = array_get($value, 'title.' . $localeDefault, []);
                
                foreach(array_get($value, 'title', []) as $k => $t)
                {
                    if ($k != $localeDefault)
                    {
                        foreach($t as $k_ => $t_)
                        {
                            if (!trim($t_))
                            {
                                $value['title'][$k][$k_] = $title[$k_];
                            }
                        }
                    }
                }
            }
            
			$model->setAttribute($key, json_encode($value, JSON_UNESCAPED_UNICODE));
		}
		else
		{
			parent::setModelSpecialAttribute($model, $key, $value);
		}

        return $this;
    }
    
    public function getListFieldContent($field, $item, $type = null)
    {  
        $value = $item->{$field->code};

        if (!empty($value))
        {
            $config = $field->select_one_data->toArray();
            $locale = config('app.locale');
            $title = array_get($config, 'title.' . $locale, []);
            $key = array_get($config, 'key', []);

            $val = array_get(array_combine($key, $title), $value);

            return e(\Str::limit($val, 20));
        }
    }
    
    public function getFilterContent($field = null)
    {
        return view($this->getViewFilter(), [
            'controller' => $this,
            'field' => $field,
        ])->render();
    }

    public function getFilterQuery($field = null, $model = null, $query = null, $name = null, $value = null) 
    {
		if ($value !== null)
		{
            $query->whereIn($model->getTable() . '.' . $name, $value);
		}
    }

    public function preProcess($model, $type, $input)
    {
		if ($input->get('required'))
		{
			$input->put('rule', ['required']);
		}
        else
        {
			$input->put('rule', []);
        }
		
        return parent::preProcess($model, $type, $input);
    } 

    public function postProcess($model, $type, $input)
    {
		$table = $model->fieldObjectType()->first()->code;
        $fieldName = $model->code;

		if (!\Schema::hasColumn($table, $fieldName))
		{
			\Schema::table($table, function(Blueprint $table) use ($fieldName)
			{
				$table->string($fieldName, 20)->nullable();
			});
		}

        return parent::postProcess($model, $type, $input);
    }
}