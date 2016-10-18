<?php

namespace Telenok\Core\Support\Validator;


class Factory extends \Illuminate\Validation\Factory
{
    /**
     * Resolve a new Validator instance.
     *
     * @param  array $data
     * @param  array $rules
     * @param  array $messages
     * @param  array $customAttributes
     *
     * @return App\Vendor\Telenok\Core\Support\Validator
     */
    protected function resolve(array $data, array $rules, array $messages, array $customAttributes)
    {
        if (is_null($this->resolver))
        {
            return new Validator($this->translator, $data, $rules, $messages, $customAttributes);
        }

        return call_user_func($this->resolver, $this->translator, $data, $rules, $messages, $customAttributes);
    }
}