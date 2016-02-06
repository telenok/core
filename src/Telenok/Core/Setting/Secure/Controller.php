<?php namespace Telenok\Core\Setting\Secure;

/**
 * @class Telenok.Core.Setting.Secure.Controller
 * Controller secure setting.
 * 
 * @extends Telenok.Core.Interfaces.Setting.Controller
 */
class Controller extends \App\Telenok\Core\Interfaces\Setting\Controller {

    /**
     * @protected
     * @property {String} $key
     * Controller's key.
     * @member Telenok.Core.Setting.Secure.Controller
     */
    protected $key = 'telenok.secure';

    /**
     * @protected
     * @property {Array} $defaultValue
     * Default values for current settings.
     * @member Telenok.Core.Setting.Secure.Controller
     */
    protected $defaultValue = [
        'auth.logout.period' => 20,
        'auth.password.length-min' => 8
    ];

    /**
     * @method save
     * Save setting's values in database.
     * @param {Telenok.Core.Interfaces.Eloquent.Object.Model} $model
     * @param {Illuminte.Support.Collection} $input
     * @return {Telenok.Core.Interfaces.Eloquent.Object.Model}
     * @member Telenok.Core.Setting.Secure.Controller
     */
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