<?php namespace Telenok\Core\Abstraction\ConfigGroup;

/**
 * @class Telenok.Core.Abstraction.ConfigGroup.Controller
 * Base controller for config.
 * 
 * @extends Telenok.Core.Abstraction.Controller.Controller
 */
abstract class Controller extends \Telenok\Core\Abstraction\Controller\Controller {

    /**
     * @protected
     * @property {Array} $defaultValue
     * Default config values.
     * @member Telenok.Core.Abstraction.ConfigGroup.Controller
     */	
    protected $defaultValue = [];
    
    /**
     * @protected
     * @property {Array} $ruleList
     * List of rules to validate config before saving.
     * @member Telenok.Core.Abstraction.ConfigGroup.Controller
     */	
    protected $ruleList = [];
    
    /**
     * @protected
     * @property {String} $formConfigContentView
     * Name of view for display settings in form.
     * @member Telenok.Core.Abstraction.ConfigGroup.Controller
     */	
    protected $formConfigContentView = '';
    
    /**
     * @protected
     * @property {String} $languageDirectory
     * Relative path to language directory of settings.
     * @member Telenok.Core.Abstraction.ConfigGroup.Controller
     */	
    protected $languageDirectory = 'config-group';

    /**
     * @method getFormConfigContent
     * Set group widget's model.
     * @param {Telenok.Core.Abstraction.Eloquent.Object.Model} $field
     * @param {Telenok.Core.Abstraction.Eloquent.Object.Model} $model
     * @param {String} $uniqueId
     * @return {String}
     * @member Telenok.Core.Abstraction.ConfigGroup.Controller
     */
    public function getFormConfigContent($field, $model, $uniqueId)
    {
        return view($this->getFormConfigContentView(), [
                    'controller' => $this,
                    'field' => $field,
                    'model' => $model,
                    'uniqueId' => $uniqueId,
                ])->render();
    }

    /**
     * @method getFormConfigContentView
     * Return name of view for display settings in form.
     * @return {String}
     * @member Telenok.Core.Abstraction.ConfigGroup.Controller
     */
    public function getFormConfigContentView()
    {
        return $this->formConfigContentView ? : "{$this->getPackage()}::config/{$this->getKey()}.content";
    }

    /**
     * @method validate
     * Validate input data.
     * @param {Array} $input
     * @return {Boolean}
     * @member Telenok.Core.Abstraction.ConfigGroup.Controller
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
     * @return {Telenok.Core.Support.Validator.Config}
     * @member Telenok.Core.Abstraction.ConfigGroup.Controller
     */
    public function validator($rule = [], $input = [], $message = [], $customAttribute = [])
    {
        return app('\App\Vendor\Telenok\Core\Support\Validator\Config')
                        ->setRuleList($rule)
                        ->setInput($input)
                        ->setMessage($message)
                        ->setCustomAttribute($customAttribute);
    }

    /**
     * @method validateException
     * Create and return validator's exception.
     * @return {Telenok.Core.Support.Exception.Validator}
     * @member Telenok.Core.Abstraction.ConfigGroup.Controller
     */
    public function validateException()
    {
        return new \Telenok\Core\Support\Exception\Validator;
    }

    /**
     * @method fillConfigValue
     * Set config's values in global app('config').
     * @param {Telenok.Core.Abstraction.Eloquent.Object.Model} $model
     * @param {mixed} $value
     * @return {void}
     * @member Telenok.Core.Abstraction.ConfigGroup.Controller
     */
    public function fillSettingValue($model, $value)
    {
        collect($value)->each(function($item, $key)
        {
            app('config')->set($key, $item);
        });
    }

    /**
     * @method save
     * Save config's values in database.
     * @param {Telenok.Core.Abstraction.Eloquent.Object.Model} $model
     * @param {Illuminate.Support.Collection} $input
     * @return {Telenok.Core.Abstraction.Eloquent.Object.Model}
     * @member Telenok.Core.Abstraction.ConfigGroup.Controller
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