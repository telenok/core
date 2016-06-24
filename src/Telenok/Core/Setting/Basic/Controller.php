<?php namespace Telenok\Core\Setting\Basic;

/**
 * @class Telenok.Core.Setting.Basic.Controller
 * Controller base setting.
 * 
 * @extends Telenok.Core.Abstraction.Setting.Controller
 */
class Controller extends \App\Vendor\Telenok\Core\Abstraction\Setting\Controller {

    /**
     * @protected
     * @property {String} $key
     * Controller's key.
     * @member Telenok.Core.Setting.Basic.Controller
     */
    protected $key = 'telenok.basic';
    
    /**
     * @protected
     * @property {Array} $defaultValue
     * Default values for current settings.
     * @member Telenok.Core.Setting.Basic.Controller
     */
    protected $defaultValue = [
        'app.localeDefault' => 'en',
        'app.locales' => ['en'],
        'app.timezone' => 'UTC'
    ];

    /**
     * @method fillSettingValue
     * Set setting's values in global app('config').
     * @param {Telenok.Core.Abstraction.Eloquent.Object.Model} $model
     * @param {mixed} $value
     * @return {void}
     * @member Telenok.Core.Setting.Basic.Controller
     */
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