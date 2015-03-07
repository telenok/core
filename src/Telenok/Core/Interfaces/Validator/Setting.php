<?php

namespace Telenok\Core\Interfaces\Validator;

class Setting {

    protected $ruleList = [];
    protected $input = [];
    protected $validator;
    protected $message = [];
    protected $customAttribute = [];
    
    public function setInput($param = [])
    {
        $this->input = \Illuminate\Support\Collection::make($param);

        return $this;
    }
    
    public function getInput()
    {
        return $this->input;
    }
    
    public function setMessage($param = [])
    {
        $this->message = array_merge(\Lang::get('core::default.error'), (array)$param);

        return $this;
    }
    
    public function getMessage()
    {
        return $this->message;
    }
    
    public function setRuleList($param = [])
    {
        $this->ruleList = $this->processRule($param);

        return $this;
    }
    
    public function getRuleList()
    {
        if (empty($this->ruleList))
        {
            $this->ruleList = $this->processRule($this->getModel()->getRule());
        }
        
        return $this->ruleList;
    } 
    
    public function setCustomAttribute($param = [])
    {
        $this->customAttribute = $param;

        return $this;
    }
    
    public function getCustomAttribute()
    {
        return $this->customAttribute;
    }

    protected function processRule($rule)
    {
        array_walk_recursive($rule, function(&$el, $key, $this_) {
            $el = str_replace('{{id}}', $this_->getInput()->get('id'), $el);
        }, $this);
        
        return $rule;
    }

    public function passes()
    {
        $this->validator = \Validator::make($this->getInput()->toArray(), $this->getRuleList(), $this->getMessage());

        if ($this->validator->passes()) return true;
        
        return false;
    }
}