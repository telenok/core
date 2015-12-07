<?php namespace Telenok\Core\Field\Decimal;

use Illuminate\Database\Schema\Blueprint;

class Controller extends \Telenok\Core\Interfaces\Field\Controller {

    protected $key = 'decimal';
    protected $specialField = ['decimal_default', 'decimal_min', 'decimal_max', 'decimal_precision', 'decimal_scale'];
    protected $ruleList = [
                'decimal_default' => ['string', 'max:37'], 
                'decimal_min' => ['string', 'max:37'], 
                'decimal_precision' => ['integer', 'max:37'],
                'decimal_scale' => ['integer', 'max:37'],
            ];
    protected $allowMultilanguage = false;

    public function getListFieldContent($field, $item, $type = null)
    {
        return $item->{$field->code}->value();
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
            $value = $field->decimal_default;
        }
        
        return \App\Telenok\Core\Field\Decimal\BigDecimal::create($value, $field->decimal_scale);
    }

    public function setModelAttribute($model, $key, $value, $field)
    {
        if ($value instanceof \Telenok\Core\Field\Decimal\BigDecimal)
        {
            $value_ = $value->value();
        }
        else if ($value !== null)
        {
            $value_ = $value;
        }
        else
        {
            $value_ = $field->decimal_default;
        }

        $model->setAttribute($key, $value_);
    }

    public function getModelSpecialAttribute($model, $key, $value)
    {
        if (in_array($key, ['decimal_default', 'decimal_min', 'decimal_max', 'decimal_precision', 'decimal_scale'], true) && $value === null)
        { 
            if ($key == 'decimal_default')
            {
                return \App\Telenok\Core\Field\Decimal\BigDecimal::create(0, 2);
            }
            else if ($key == 'decimal_min')
            {
                return \App\Telenok\Core\Field\Decimal\BigDecimal::create('-9999999999999999999999999999', 2);
            }
            else if ($key == 'decimal_max')
            {
                return \App\Telenok\Core\Field\Decimal\BigDecimal::create('9999999999999999999999999999', 2);
            }
            else if ($key == 'decimal_precision')
            {
                return 30;
            }
            else if ($key == 'decimal_scale')
            {
                return 2;
            }
        }

        return parent::getModelSpecialAttribute($model, $key, $value);
    }

    public function setModelSpecialAttribute($model, $key, $value)
    {
        if (in_array($key, ['decimal_default', 'decimal_min', 'decimal_max', true]) && $value instanceof \App\Telenok\Core\Field\Decimal\BigDecimal)
        {
            $value = $value->value();
        }
        else if (in_array($key, ['decimal_default', 'decimal_min', 'decimal_max', 'decimal_precision', 'decimal_scale'], true) && $value === null)
        {            
            if ($key == 'decimal_default')
            {
                $value = 0;
            }
            else if ($key == 'decimal_min')
            {
                $value = '-9999999999999999999999999999.00';
            }
            else if ($key == 'decimal_max')
            {
                $value = '9999999999999999999999999999.00';
            }
        }

        return parent::setModelSpecialAttribute($model, $key, $value);
    }

    public function validate($model = null, $input = [], $messages = [])
	{
		if ($input->get('decimal_precision') < $input->get('decimal_scale'))
        {
			throw $this->validateException()->setMessageError($this->LL('error.precision_scale'));
        }

		return parent::validate($model, $input, $messages);
	}

    public function preProcess($model, $type, $input)
    {
        $rule = ['numeric'];

        if ($input->get('required'))
        {
            $rule[] = 'required';
        }

        $input->put('rule', $rule);
        $input->put('multilanguage', 0);
        $input->put('decimal_default', $input->get('decimal_default', null));
        
        return parent::preProcess($model, $type, $input);
    } 

    public function postProcess($model, $type, $input)
    {
        $table = $model->fieldObjectType()->first()->getAttribute('code');
        $fieldName = $model->getAttribute('code');

        if (!\Schema::hasColumn($table, $fieldName))
        {
            \Schema::table($table, function(Blueprint $table) use ($fieldName)
            {
                $table->decimal($fieldName)->nullable();
            });
        }

        return parent::postProcess($model, $type, $input);
    }
}