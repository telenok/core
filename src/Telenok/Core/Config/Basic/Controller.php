<?php namespace Telenok\Core\Config\Basic;

/**
 * @class Telenok.Core.Config.Basic.Controller
 * Controller base config.
 * 
 * @extends Telenok.Core.Abstraction.Config.Controller
 */
class Controller extends \App\Vendor\Telenok\Core\Abstraction\Config\Controller {

    /**
     * @protected
     * @property {String} $key
     * Controller's key.
     * @member Telenok.Core.Config.Basic.Controller
     */
    protected $key = 'telenok.basic';
    
    /**
     * @protected
     * @property {Array} $defaultValue
     * Default values for current settings.
     * @member Telenok.Core.Config.Basic.Controller
     */
    protected $defaultValue = [
        'app.localeDefault' => 'en',
        'app.locales' => ['en'],
        'app.timezone' => 'UTC'
    ];

    /**
     * @method fillConfigValue
     * Set config's values in global app('config').
     * @param {Telenok.Core.Abstraction.Eloquent.Object.Model} $model
     * @param {mixed} $value
     * @return {void}
     * @member Telenok.Core.Config.Basic.Controller
     */
    public function fillConfigValue($model, $value)
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