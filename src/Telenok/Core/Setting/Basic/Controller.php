<?php namespace Telenok\Core\Setting\Basic;

class Controller extends \Telenok\Core\Interfaces\Setting\Controller {

    protected $key = 'telenok.basic';
    protected $default = [
        'app.localeDefault' => 'en',
        'app.locales' => ['en'],
        'app.timezone' => 'UTC'
    ];

    public function fillSettingValue($model, $value)
    {
        collect($value)->each(function($item, $key)
        {
            if ($key == 'app.locales')
            {
                $item = collect($item);
            }
            
            app('config')->set($key, $item);
        });
    }
    
    public function save($model, $input)
    {
        $inputCollect = collect($input->get('value', []));
        $defaultCollect = collect($this->default);

        $defaultCollect->each(function($item, $key) use ($inputCollect)
        {
            if ($inputCollect->get($key) === '' || $inputCollect->get($key) === null)
            {
                $inputCollect->put($key, $item);
            }
        });
        
        $input->put('value', $inputCollect->all());
        
		return $model->storeOrUpdate($input, true);
    }
}