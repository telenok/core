<?php

namespace Telenok\Core\Interfaces\Setting;

abstract class Controller {

    use \Telenok\Core\Support\PackageLoad;

    protected $key = '';
    protected $ruleList = [];
    protected $formSettingContentView = '';
    protected $package = '';
    protected $languageDirectory = 'setting';

    public function getKey()
    {
        return $this->key;
    } 

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
        return app('\Telenok\Core\Interfaces\Exception\Validate');
    }
  
}

