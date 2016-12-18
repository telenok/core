<?php

namespace Telenok\Core\Support\Validator;

/**
 * @class Telenok.Core.Support.Validator.Config
 * Validator for application's config.
 */
class Config
{
    /**
     * @protected
     *
     * @property {Array} $ruleList
     * List of validation's rules.
     * @member Telenok.Core.Support.Validator.Config
     */
    protected $ruleList = [];

    /**
     * @protected
     *
     * @property {Illuminate.Support.Collection} $input
     * Input collection to validate.
     * @member Telenok.Core.Support.Validator.Config
     */
    protected $input = [];

    /**
     * @protected
     *
     * @property {Telenok.Core.Support.Validator.Validator} $validator
     * Validator which make validation.
     * @member Telenok.Core.Support.Validator.Config
     */
    protected $validator;

    /**
     * @protected
     *
     * @property {Array} $message
     * List of custom messages.
     * @member Telenok.Core.Support.Validator.Config
     */
    protected $message = [];

    /**
     * @protected
     *
     * @property {Array} $customAttribute
     * Custom notification attributes.
     * @member Telenok.Core.Support.Validator.Config
     */
    protected $customAttribute = [];

    /**
     * @method setInput
     * Set input.
     *
     * @param {Array} $param
     *
     * @return {Telenok.Core.Support.Validator.Config}
     * @member Telenok.Core.Support.Validator.Config
     */
    public function setInput($param = [])
    {
        $this->input = collect($param);

        return $this;
    }

    /**
     * @method getInput
     * Return input collection.
     *
     * @return {Illuminate.Support.Collection}
     * @member Telenok.Core.Support.Validator.Config
     */
    public function getInput()
    {
        return $this->input;
    }

    /**
     * @method setMessage
     * Set messages.
     *
     * @param {Array} $param
     *
     * @return {Telenok.Core.Support.Validator.Config}
     * @member Telenok.Core.Support.Validator.Config
     */
    public function setMessage($param = [])
    {
        $this->message = array_merge(trans('core::default.error'), (array) $param);

        return $this;
    }

    /**
     * @method getMessage
     * Return messages.
     *
     * @return {Array}
     * @member Telenok.Core.Support.Validator.Config
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @method setRuleList
     * Set rule's list.
     *
     * @param {Array} $param
     *
     * @return {Telenok.Core.Support.Validator.Config}
     * @member Telenok.Core.Support.Validator.Config
     */
    public function setRuleList($param = [])
    {
        $this->ruleList = $this->processRule($param);

        return $this;
    }

    /**
     * @method getRuleList
     * Return rule's list.
     *
     * @return {Array}
     * @member Telenok.Core.Support.Validator.Config
     */
    public function getRuleList()
    {
        return $this->ruleList;
    }

    /**
     * @method setCustomAttribute
     * Set list of custom attributes for better notificate user about error.
     *
     * @param {Array} $param
     *
     * @return {Telenok.Core.Support.Validator.Config}
     * @member Telenok.Core.Support.Validator.Config
     */
    public function setCustomAttribute($param = [])
    {
        $this->customAttribute = $param;

        return $this;
    }

    /**
     * @method getCustomAttribute
     * Return custom attribute list.
     *
     * @return {Array}
     * @member Telenok.Core.Support.Validator.Config
     */
    public function getCustomAttribute()
    {
        return $this->customAttribute;
    }

    /**
     * @method processRule
     * Process rule for converting them to one-level array.
     *
     * @param {Array} $rule
     *
     * @return {Array}
     * @member Telenok.Core.Support.Validator.Config
     */
    protected function processRule($rule)
    {
        array_walk_recursive($rule, function (&$el, $key, $this_) {
            $el = str_replace('{{id}}', $this_->getInput()->get('id'), $el);
        }, $this);

        return $rule;
    }

    /**
     * @method passes
     * Start validation process.
     *
     * @return {Boolean}
     * @member Telenok.Core.Support.Validator.Config
     */
    public function passes()
    {
        $this->validator = app('validator_telenok')->make($this->getInput()->toArray(), $this->getRuleList(), $this->getMessage());

        if ($this->validator->passes()) {
            return true;
        }

        return false;
    }
}
