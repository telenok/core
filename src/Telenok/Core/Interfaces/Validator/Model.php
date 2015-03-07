<?php 

namespace Telenok\Core\Interfaces\Validator;

class Model {

    protected $model;
    protected $ruleList = [];
    protected $input = [];
    protected $validator;
    protected $message = [];
    protected $customAttribute = [];
    
    public function setModel($param = null)
    {
        $this->model = $param;

        return $this;
    }
    
    public function getModel()
    {
        return $this->model;
    }
    
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
        $this->ruleList = $param;

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
        array_walk_recursive($rule, function(&$el, $key, $this_) 
		{
            $el = preg_replace_callback('/\:\w+\:/', function($matches) use ($this_) 
			{
                return $this_->getInput()->get(trim($matches[0], ':'), 'NULL');
            }, $el);
        }, $this);

        return $rule;
    }

    public function passes()
    {  
        if ($this->model instanceof \Telenok\Core\Interfaces\Eloquent\Object\Model && $this->model->exists)
        {
            $this->ruleList = array_intersect_key($this->ruleList, $this->getInput()->toArray());

            if (empty($this->ruleList))
            {
                return true;
            }
        }

        $this->validator = \Validator::make(
                                $this->getInput()->toArray(), 
                                $this->getRuleList(), 
                                $this->getMessage(), 
                                $this->getInput()->merge($this->getCustomAttribute())->toArray()
                            )
                            ->setModel($this->getModel());

        if ($this->validator()->passes()) 
		{
			return true;
		}

        return false;
    }

    public function fails()
    {
        return !$this->passes();
    }

    public function messages()
    {
        $messages = $this->validator()->messages()->all();

        return empty($messages) ? ['undefined' => $this->message['undefined']] : $messages;
    }

    public function validator()
    {
        return $this->validator;
    }
}

