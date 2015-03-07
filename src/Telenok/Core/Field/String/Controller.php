<?php

namespace Telenok\Core\Field\String;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;  

class Controller extends \Telenok\Core\Interfaces\Field\Controller {

    protected $key = 'string';

    protected $specialField = ['string_default', 'string_regex', 'string_password', 'string_max', 'string_min', 'string_list_size'];
    protected $ruleList = ['string_regex' => ['valid_regex']];

    public function getFilterQuery($field = null, $model = null, $query = null, $name = null, $value = null) 
    {
		if ($value !== null && trim($value))
		{
            $fieldCode = $field->code;
            $translate = new \App\Model\Telenok\Object\Translation();

            if (in_array($fieldCode, $model->getMultilanguage(), true))
            {
                $query->leftJoin($translate->getTable(), function($join) use ($model, $translate, $fieldCode)
                {
                    $join   ->on($model->getTable().'.id', '=', $translate->getTable().'.translation_object_model_id')
                            ->on($translate->getTable().'.translation_object_field_code', '=', \DB::raw("'" . $fieldCode . "'"))
                            ->on($translate->getTable().'.translation_object_language', '=', \DB::raw("'".\Config::get('app.locale')."'"));
                });

                $query->where(function($query) use ($value, $model, $translate)
                {
                    \Illuminate\Support\Collection::make(explode(' ', $value))
                            ->filter(function($i) { return trim($i); })
                            ->each(function($i) use ($query, $translate)
                    {
                        $query->orWhere($translate->getTable().'.translation_object_string', 'like', '%' . trim($i) . '%');
                    });

                    $query->orWhere($model->getTable() . '.id', intval($value));
                });
            }
            else 
            {
                parent::getFilterQuery($field, $model, $query, $name, $value);
            }
		}
    }

    public function getModelAttribute($model, $key, $value, $field)
    {
        if ($field->multilanguage)
        {
            $value = \Illuminate\Support\Collection::make(json_decode($value ?: '[]', true));
        }

        return $value;
    }

    public function setModelAttribute($model, $key, $value, $field)
    { 
        if ($field->multilanguage)
        { 
			if ($value === null)
			{
				$value = [];
			}

			$defaultLanguage = \Config::get('app.localeDefault', "en");

			if (is_string($value) )
			{
				$value = [$defaultLanguage => $value];
			}

			$default = json_decode($field->string_default ?: "[]", true);

            foreach ($default as $language => $v)
            {
                if (!isset($value[$language]))
                {
                    $value[$language] = $v;
                }
            }

            $value = json_encode($value, JSON_UNESCAPED_UNICODE);
        }
        else
        {
			if ($value === null || !strlen($value))
			{
				$value = $field->string_default ?: null;
			}
        }

        $model->setAttribute($key, $value);
    }

    public function getModelSpecialAttribute($model, $key, $value)
    {
        try
        {
			if (in_array($key, ['string_default'], true) && $model->multilanguage)
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
		if (in_array($key, ['string_default'], true) && $model->multilanguage)
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
		else if ($key === 'string_min' && !$value)
		{
			$model->setAttribute($key, 0); 
		}
		else if ($key === 'string_max' && !$value)
		{
			$model->setAttribute($key, 255); 
		}
		else if ($key === 'string_password' && !$value)
		{
			$model->setAttribute($key, 0); 
		}
		else if ($key === 'string_list_size' && !$value)
		{
			$model->setAttribute($key, 50); 
		}
		else
		{
			parent::setModelSpecialAttribute($model, $key, $value);
		}

        return $this;
    }
	
    public function getListFieldContent($field, $item, $type = null)
    {  
        return \Str::limit($item->translate((string)$field->code), $field->string_list_size ?: 30);
    } 

    public function postProcess($model, $type, $input)
    {
		$table = $model->fieldObjectType()->first()->code;
        $fieldName = $model->code;

		if (!\Schema::hasColumn($table, $fieldName) && !\Schema::hasColumn($table, "`{$fieldName}`"))
		{
			\Schema::table($table, function(Blueprint $table) use ($fieldName)
			{
				$table->text($fieldName)->nullable();
			});
		}

        $fields = []; 
        
        $fields['rule'] = [];
        
        if ($input->get('required'))
        {
            $fields['rule'][] = 'required';
        }
        
        if ($string_regex = trim($input->get('string_regex')))
        {
			$fields['rule'][] = "regex:{$string_regex}";
        }

        if ($string_max = intval($input->get('string_max')))
        {
            $fields['rule'][] = "max:{$string_max}";
        }

        if ($string_min = intval($input->get('string_min')))
        {
            $fields['rule'][] = "min:{$string_min}";
        }
        
        if ($string_list_size = intval($input->get('string_list_size')))
        {
            $fields['string_list_size'] = $string_list_size;
        }
		else
		{
            $fields['string_list_size'] = 20;
		}
		
        $model->fill($fields)->save();

        return parent::postProcess($model, $type, $input);
    }

}