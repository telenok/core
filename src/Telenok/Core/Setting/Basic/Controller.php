<?php namespace Telenok\Core\Setting\Basic;

class Controller extends \Telenok\Core\Interfaces\Setting\Controller {

    protected $key = 'telenok.basic';
    protected $defaultValue = [
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
}