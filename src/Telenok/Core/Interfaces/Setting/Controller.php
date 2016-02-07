<?php namespace Telenok\Core\Interfaces\Setting;

/**
 * @class Telenok.Core.Interfaces.Setting.Controller
 * Base controller for setting.
 * 
 * @extends Telenok.Core.Interfaces.Controller.Controller
 */
class Controller extends \Telenok\Core\Interfaces\Controller\Controller {

    /**
     * @protected
     * @property {Array} $defaultValue
     * Default setting values.
     * @member Telenok.Core.Interfaces.Setting.Controller
     */	
    protected $defaultValue = [];
    
    /**
     * @protected
     * @property {Array} $ruleList
     * List of rules to validate setting before saving.
     * @member Telenok.Core.Interfaces.Setting.Controller
     */	
    protected $ruleList = [];
    
    /**
     * @protected
     * @property {String} $formSettingContentView
     * Name of view for display settings in form.
     * @member Telenok.Core.Interfaces.Setting.Controller
     */	
    protected $formSettingContentView = '';
    
    /**
     * @protected
     * @property {String} $languageDirectory
     * Relative path to language directory of settings.
     * @member Telenok.Core.Interfaces.Setting.Controller
     */	
    protected $languageDirectory = 'setting';

    /**
     * @method getFormSettingContent
     * Set group widget's model.
     * @param {Telenok.Core.Interfaces.Eloquent.Object.Model} $field
     * @param {Telenok.Core.Interfaces.Eloquent.Object.Model} $model
     * @param {String} $uniqueId
     * @return {String}
     * @member Telenok.Core.Interfaces.Setting.Controller
     */
    public function getFormSettingContent($field, $model, $uniqueId)
    {
        return view($this->getFormSettingContentView(), [
                    'controller' => $this,
                    'field' => $field,
                    'model' => $model,
                    'uniqueId' => $uniqueId,
                ])->render();
    }

    /**
     * @method getFormSettingContentView
     * Return name of view for display settings in form.
     * @return {String}
     * @member Telenok.Core.Interfaces.Setting.Controller
     */
    public function getFormSettingContentView()
    {
        return $this->formSettingContentView ? : "{$this->getPackage()}::setting/{$this->getKey()}.content";
    }

    /**
     * @method validate
     * Validate input data.
     * @param {Array} $input
     * @return {Boolean}
     * @member Telenok.Core.Interfaces.Setting.Controller
     */
    public function validate($input = [])
    {
        $validator = $this->validator($this->ruleList, $input);

        if ($validator->fails())
        {
            throw $this->validateException()->setMessageError($validator->messages());
        }
    }
    
    /**
     * @method validator
     * Create and return validator.
     * @param {Array} $rule
     * @param {Array} $input
     * @param {Array} $message
     * @param {Array} $customAttribute
     * @return {Telenok.Core.Support.Validator.Setting}
     * @member Telenok.Core.Interfaces.Setting.Controller
     */
    public function validator($rule = [], $input = [], $message = [], $customAttribute = [])
    {
        return app('\Telenok\Core\Support\Validator\Setting')
                        ->setRuleList($rule)
                        ->setInput($input)
                        ->setMessage($message)
                        ->setCustomAttribute($customAttribute);
    }

    /**
     * @method validateException
     * Create and return validator's exception.
     * @return {Telenok.Core.Support.Exception.Validator}
     * @member Telenok.Core.Interfaces.Setting.Controller
     */
    public function validateException()
    {
        return new \Telenok\Core\Support\Exception\Validator;
    }

    /**
     * @method fillSettingValue
     * Set setting's values in global app('config').
     * @param {Telenok.Core.Interfaces.Eloquent.Object.Model} $model
     * @param {mixed} $value
     * @return {void}
     * @member Telenok.Core.Interfaces.Setting.Controller
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

    /**
     * @method save
     * Save setting's values in database.
     * @param {Telenok.Core.Interfaces.Eloquent.Object.Model} $model
     * @param {Illuminate.Support.Collection} $input
     * @return {Telenok.Core.Interfaces.Eloquent.Object.Model}
     * @member Telenok.Core.Interfaces.Setting.Controller
     */
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