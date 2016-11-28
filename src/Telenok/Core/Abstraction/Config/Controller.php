<?php namespace Telenok\Core\Abstraction\Config;
use Illuminate\Support\Collection;

/**
 * @class Telenok.Core.Abstraction.Config.Controller
 * Base controller for config.
 *
 * @extends Telenok.Core.Abstraction.Controller.Controller
 */
abstract class Controller extends \Telenok\Core\Abstraction\Controller\Controller
{
    /**
     * @protected
     * @property {Array} $defaultValue
     * Default config values.
     * @member Telenok.Core.Abstraction.Config.Controller
     */
    protected $defaultValue = [];

    /**
     * @protected
     * @property {Array} $ruleList
     * List of rules to validate config before saving.
     * @member Telenok.Core.Abstraction.Config.Controller
     */
    protected $ruleList = [];

    /**
     * @protected
     * @property {String} $languageDirectory
     * Relative path to language directory of settings.
     * @member Telenok.Core.Abstraction.Config.Controller
     */
    protected $languageDirectory = 'config';

    /**
     * @protected
     * @property {String} $formConfigContentView
     * Name of view for display settings in form.
     * @member Telenok.Core.Abstraction.Config.Controller
     */
    protected $valueContentView = '';

    public function getValueContent($controller, $model, $field, $uniqueId)
    {
        return view($this->getValueContentView(), [
                'parentController' => $controller,
                'controller' => $this,
                'model' => $model,
                'field' => $field,
                'uniqueId' => $uniqueId
            ])->render();
    }

    /**
     * @method getValueContentView
     * Return name of view to display value in form.
     * @return {String}
     * @member Telenok.Core.Abstraction.Config.Controller
     */
    public function getValueContentView()
    {
        return $this->valueContentView ? : "{$this->getPackage()}::config.{$this->getKey()}.value";
    }

    /**
     * @method validate
     * Validate input data.
     * @param {Array} $input
     * @return {Boolean}
     * @member Telenok.Core.Abstraction.Config.Controller
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
     * @member Telenok.Core.Abstraction.Config.Controller
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
     * @member Telenok.Core.Abstraction.Config.Controller
     */
    public function validateException()
    {
        return new \Telenok\Core\Support\Exception\Validator;
    }

    public function save($model, $input) {}
}