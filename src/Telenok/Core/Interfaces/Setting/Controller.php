<?php namespace Telenok\Core\Interfaces\Setting;

class Controller extends \Telenok\Core\Interfaces\Controller\Controller { 
 
    protected $defaultValue = [];
    protected $ruleList = [];
    protected $formSettingContentView = '';
    protected $languageDirectory = 'setting';
	
	public function getFormSettingContent($field, $model, $uniqueId)
	{
		return view($this->getFormSettingContentView(), [
				'controller' => $this,
				'field' => $field,
				'model' => $model,
				'uniqueId' => $uniqueId,
			])->render();
	}

	public function getFormSettingContentView()
	{
		return $this->formSettingContentView ?: "{$this->getPackage()}::setting/{$this->getKey()}.content";
	}

    public function validate($input = [])
    {
        $validator = $this->validator($this->ruleList, $input);
         
        if ($validator->fails()) 
        {
            throw $this->validateException()->setMessageError($validator->messages());
        }
    } 

    public function validator($rule = [], $input = [], $message = [], $customAttribute = [])
    {
        return app('\Telenok\Core\Interfaces\Validator\Setting')
                    ->setRuleList($rule)
                    ->setInput($input)
                    ->setMessage($message)
                    ->setCustomAttribute($customAttribute);
    }

    public function validateException()
    {
        return new \Telenok\Core\Support\Exception\Validator;
    }

    public function fillSettingValue($model, $value)
    {
        app('config')->set($model->code, $value);
    }

    public function save($model, $input)
    {
        if ($this->defaultValue)
        {
            $inputCollect = collect($input->get('value', []));
            $defaultCollect = collect($this->defaultValue);

            $defaultCollect->each(function($item, $key) use ($inputCollect)
            {
                if ($inputCollect->get($key) === '' || $inputCollect->get($key) === null)
                {
                    $inputCollect->put($key, $item);
                }
            });

            $input->put('value', $inputCollect->all());
        }

		return $model->storeOrUpdate($input, true);
    }
}