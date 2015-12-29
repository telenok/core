<?php namespace Telenok\Core\Field\Text;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Controller extends \Telenok\Core\Interfaces\Field\Controller {

	protected $key = 'text';
	protected $specialField = ['text_width', 'text_height', 'text_default', 'text_rte'];

    public function getFilterQuery($field = null, $model = null, $query = null, $name = null, $value = null) 
    {
		if ($value !== null && trim($value))
		{
            $fieldCode = $field->code;
            $translate = new \App\Telenok\Core\Model\Object\Translation();

            if (in_array($fieldCode, $model->getMultilanguage(), true))
            {
                $query->leftJoin($translate->getTable(), function($join) use ($model, $translate, $fieldCode)
                {
                    $join   ->on($model->getTable().'.id', '=', $translate->getTable().'.translation_object_model_id')
                            ->on($translate->getTable().'.translation_object_field_code', '=', app('db')->raw("'" . $fieldCode . "'"))
                            ->on($translate->getTable().'.translation_object_language', '=', app('db')->raw("'".config('app.locale')."'"));
                });

                $query->where(function($query) use ($value, $model, $translate)
                {
                    collect(explode(' ', $value))
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
            $value = collect(json_decode($value ?: '[]', true));

            foreach($value->all() as $k => $v)
            {
                $value->put($k, app('\App\Telenok\Core\Field\Text\Processing')->setRawValue($v));
            }
        }
        else
        {
            $value = app('\App\Telenok\Core\Field\Text\Processing')->setRawValue($value);
        }

        return $value;
    }

    public function setModelAttribute($model, $key, $value, $field)
    { 
        if ($field->multilanguage)
        { 
			$default = (array)json_decode($field->text_default ?: "[]", true);

            foreach ($default as $language => $v)
            {
                if (!isset($value[$language]) || !strlen($value[$language]))
                {
                    $value[$language] = $v;
                }
            }
            
            $value = json_encode($value, JSON_UNESCAPED_UNICODE);
        }
        else if (!strlen($value))
        {
            $value = $field->text_default ?: "";
        }

        $model->setAttribute($key, $value);
    }

    public function getModelSpecialAttribute($model, $key, $value)
    {
        try
        {
			if (in_array($key, ['text_default'], true) && $model->multilanguage)
			{ 
				return collect(json_decode($value, true));
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
		if (in_array($key, ['text_default'], true) && ($model->multilanguage || is_array($value)))
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
				$table->mediumText($fieldName)->nullable();
			});
		}

        return parent::postProcess($model, $type, $input);
    }
}