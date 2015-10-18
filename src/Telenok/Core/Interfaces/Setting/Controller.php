<?php namespace Telenok\Core\Interfaces\Setting;

class Controller extends \Telenok\Core\Interfaces\Controller\Controller { 
 
    protected $ruleList = [];
    protected $formSettingContentView = '';

	public function __construct()
	{
		$this->languageDirectory = 'setting';
        
        parent::__construct();
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
        return new \Telenok\Core\Support\Exception\Validate;
    }
}