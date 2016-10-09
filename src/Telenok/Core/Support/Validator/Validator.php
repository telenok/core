<?php namespace Telenok\Core\Support\Validator;

/**
 * @class Telenok.Core.Support.Validator.Validator
 * Validator for eloquent models.
 * 
 * @uses Symfony.Component.Translation.TranslatorInterface
 * @extends Illuminate.Validation.Validator
 */
class Validator extends \Illuminate\Validation\Validator {

    /**
     * @protected
     * @property {Telenok.Core.Abstraction.Eloquent.Object.Model} $model
     * Model to validate.
     * @member Telenok.Core.Support.Validator.Validator
     */
    protected $model = null;

    protected function doReplacements($message, $attribute, $rule, $parameters)
    {
        $message = parent::doReplacements($message, $attribute, $rule, $parameters);

        $matches = [];

        preg_match_all('/:(\w+)[^\w]?/', $message, $matches);

        foreach ($matches[1] as $match)
        {
            $message = str_replace(":{$match}", $this->getAttribute($match), $message);
        }

        return $message;
    }

    /**
     * Validate a given attribute against a rule.
     *
     * @protected
     * @method validate
     * @member string  $attribute
     * @member string  $rule
     * @return void
     */
    protected function validateAttribute($attribute, $rule)
    {
        list($rule, $parameters) = $this->parseRule($rule);

        if ($rule == '')
        {
            return;
        }

        // First we will get the correct keys for the given attribute in case the field is nested in
        // an array. Then we determine if the given rule accepts other field names as parameters.
        // If so, we will replace any asterisks found in the parameters with the correct keys.
        if (($keys = $this->getExplicitKeys($attribute)) &&
            $this->dependsOnOtherFields($rule)) {
            $parameters = $this->replaceAsterisksInParameters($parameters, $keys);
        }

        $value = $this->getValue($attribute);

        // If the attribute is a file, we will verify that the file upload was actually successful
        // and if it wasn't we will add a failure for the attribute. Files may not successfully
        // upload if they are too large based on PHP's settings so we will bail in this case.
        if (
            $value instanceof UploadedFile && ! $value->isValid() &&
            $this->hasRule($attribute, array_merge($this->fileRules, $this->implicitRules))
        ) {
            return $this->addFailure($attribute, 'uploaded', []);
        }

        // If we have made it this far we will make sure the attribute is validatable and if it is
        // we will call the validation method with the attribute. If a method returns false the
        // attribute is invalid and we will add a failure message for this failing attribute.
        $validatable = $this->isValidatable($rule, $attribute, $value);

        $method = "validate{$rule}";

        if ($validatable)
        {
            if (is_array($value))
            {
                $error = true;

                foreach ($value as $v)
                {
                    if ($this->{$method}($attribute, $v, $parameters, $this))
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
            else if (!$this->{$method}($attribute, $value, $parameters, $this))
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