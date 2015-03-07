<?php

namespace Telenok\Core\Interfaces\Validator;

use Symfony\Component\Translation\TranslatorInterface;

class Validator extends \Illuminate\Validation\Validator {

    protected $model = null;
    
    protected function doReplacements($message, $attribute, $rule, $parameters)
    {
        $message = str_replace(':key', $attribute, $message);
        $message = str_replace(':attribute', $this->getAttribute($attribute), $message);

        if (method_exists($this, $replacer = "replace{$rule}")) {
            $message = $this->$replacer($message, $attribute, $rule, $parameters);
        }

        $matches = [];

        preg_match_all('/:(\w+)[^\w]?/', $message, $matches);

        foreach ($matches[1] as $match)
        {
            $message = str_replace(":{$match}", $this->getAttribute($match), $message);
        }

        return $message;
    }
    
    /**
     * Special validation for fields with array-values
     * Validate a given attribute against a rule.
     *
     * @param  string  $attribute
     * @param  string  $rule
     * @return void
     */
    protected function validate($attribute, $rule)
    {
        list($rule, $parameters) = $this->parseRule($rule);

        // We will get the value for the given attribute from the array of data and then
        // verify that the attribute is indeed validatable. Unless the rule implies
        // that the attribute is required, rules are not run for missing values.
        $value = $this->getValue($attribute);

        $validatable = $this->isValidatable($rule, $attribute, $value);

        $method = "validate{$rule}";

        if ($validatable) 
        {
            if (is_array($value)) 
            {
                $error = true;

                foreach ($value as $v)
                {
                    if ($this->$method($attribute, $v, $parameters, $this)) 
                    {
                        $error = false;
                        break;
                    }
                }
                
                if ($error)
                {
                    $this->addFailure($attribute, $rule, $parameters);
                }
            } 
            else if (!$this->$method($attribute, $value, $parameters, $this)) 
            {
                $this->addFailure($attribute, $rule, $parameters);
            }
        }
    }

    public function setModel($model)
    {
        $this->model = $model;

        return $this;
    }

    public function getModel()
    {
        return $this->model;
    }
    
}