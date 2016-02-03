<?php namespace Telenok\Core\Interfaces\Validator;

/**
 * @class Telenok.Core.Interfaces.Validator.Setting
 * Validator for Telenok's setting.
 */
class Setting {

    /**
     * @protected
     * @property {Array} $ruleList
     * List of validation's rules.
     * @member Telenok.Core.Interfaces.Validator.Setting
     */
    protected $ruleList = [];
    
    /**
     * @protected
     * @property {Illuminate.Support.Collection} $input
     * Input collection to validate.
     * @member Telenok.Core.Interfaces.Validator.Setting
     */
    protected $input = [];
    
    /**
     * @protected
     * @property {Telenok.Core.Interfaces.Validator.Validator} $validator
     * Validator which make validation.
     * @member Telenok.Core.Interfaces.Validator.Setting
     */
    protected $validator;
    
    /**
     * @protected
     * @property {Array} $message
     * List of custom messages.
     * @member Telenok.Core.Interfaces.Validator.Setting
     */
    protected $message = [];
    
    /**
     * @protected
     * @property {Array} $customAttribute
     * Custom notification attributes.
     * @member Telenok.Core.Interfaces.Validator.Setting
     */
    protected $customAttribute = [];
    
    /**
     * @method setInput
     * Set input.
     * @param {Array} $param
     * @return {Telenok.Core.Interfaces.Validator.Setting}
     * @member Telenok.Core.Interfaces.Validator.Setting
     */
    public function setInput($param = [])
    {
        $this->input = collect($param);

        return $this;
    }

    /**
     * @method getInput
     * Return input collection.
     * @return {Illuminate.Support.Collection}
     * @member Telenok.Core.Interfaces.Validator.Setting
     */
    public function getInput()
    {
        return $this->input;
    }
    
    /**
     * @method setMessage
     * Set messages.
     * @param {Array} $param
     * @return {Telenok.Core.Interfaces.Validator.Setting}
     * @member Telenok.Core.Interfaces.Validator.Setting
     */
    public function setMessage($param = [])
    {
        $this->message = array_merge(trans('core::default.error'), (array)$param);

        return $this;
    }
    
    /**
     * @method getMessage
     * Return messages.
     * @return {Array}
     * @member Telenok.Core.Interfaces.Validator.Setting
     */
    public function getMessage()
    {
        return $this->message;
    }
    
    /**
     * @method setRuleList
     * Set rule's list.
     * @param {Array} $param
     * @return {Telenok.Core.Interfaces.Validator.Setting}
     * @member Telenok.Core.Interfaces.Validator.Setting
     */
    public function setRuleList($param = [])
    {
        $this->ruleList = $this->processRule($param);

        return $this;
    }
    
    /**
     * @method getRuleList
     * Return rule's list.
     * @return {Array}
     * @member Telenok.Core.Interfaces.Validator.Setting
     */
    public function getRuleList()
    {
        if (empty($this->ruleList))
        {
            $this->ruleList = $this->processRule($this->getModel()->getRule());
        }
        
        return $this->ruleList;
    } 
    
    /**
     * @method setCustomAttribute
     * Set list of custom attributes for better notificate user about error.
     * @param {Array} $param
     * @return {Telenok.Core.Interfaces.Validator.Setting}
     * @member Telenok.Core.Interfaces.Validator.Setting
     */
    public function setCustomAttribute($param = [])
    {
        $this->customAttribute = $param;

        return $this;
    }
    
    /**
     * @method getCustomAttribute
     * Return custom attribute list.
     * @return {Array}
     * @member Telenok.Core.Interfaces.Validator.Setting
     */
    public function getCustomAttribute()
    {
        return $this->customAttribute;
    }

    /**
     * @method processRule
     * Process rule for converting them to one-level array.
     * @param {Array} $rule
     * @return {Array}
     * @member Telenok.Core.Interfaces.Validator.Setting
     */
    protected function processRule($rule)
    {
        array_walk_recursive($rule, function(&$el, $key, $this_) {
            $el = str_replace('{{id}}', $this_->getInput()->get('id'), $el);
        }, $this);
        
        return $rule;
    }

    /**
     * @method passes
     * Start validation process.
     * @return {Boolean}
     * @member Telenok.Core.Interfaces.Validator.Setting
     */
    public function passes()
    {
        $this->validator = app('validator')->make($this->getInput()->toArray(), $this->getRuleList(), $this->getMessage());

        if ($this->validator->passes()) return true;
        
        return false;
    }
}
