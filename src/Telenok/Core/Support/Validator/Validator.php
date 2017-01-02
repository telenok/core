<?php namespace Telenok\Core\Support\Validator;

use Illuminate\Support\Arr;

/**
 * @class Telenok.Core.Support.Validator.Validator
 * Validator for eloquent models.
 * 
 * @uses Symfony.Component.Translation.TranslatorInterface
 * @extends Illuminate.Validation.Validator
 */
class Validator extends \Illuminate\Validation\Validator {

    /**
     * Replace all error message place-holders with actual values.
     *
     * @param  string  $message
     * @param  string  $attribute
     * @param  string  $rule
     * @param  array   $parameters
     * @return string
     */
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
}