<?php namespace Telenok\Core\Setting\Secure;

class Controller extends \Telenok\Core\Interfaces\Setting\Controller {

    protected $key = 'telenok.secure';
    protected $defaultValue = [
        'auth.logout.period' => 20,
        'auth.password.length-min' => 8
    ];
    
    public function save($model, $input)
    {
        $inputCollect = collect($input->get('value', []));

        if (!intval($inputCollect->get('auth.logout.period')))
        {
            $inputCollect->put('auth.logout.period', $this->defaultValue);
        }

        if (!intval($inputCollect->get('auth.password.length-min')))
        {
            $inputCollect->put('auth.password.length-min', $this->defaultValue);
        }
        
        $input->put('value', $inputCollect->all());
        
		return parent::save($model, $input);
    }
}