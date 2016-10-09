<?php

namespace Telenok\Core\Support\Validator;

/**
 * @class Telenok.Core.Support.Validator.Model
 * Validator for eloquent models.
 */
class Model {

    /**
     * @protected
     * @property {Telenok.Core.Abstraction.Eloquent.Object.Model} $model
     * Model to validate.
     * @member Telenok.Core.Support.Validator.Model
     */
    protected $model;

    /**
     * @protected
     * @property {Array} $ruleList
     * List of validation's rules.
     * @member Telenok.Core.Support.Validator.Model
     */
    protected $ruleList = [];

    /**
     * @protected
     * @property {Illuminate.Support.Collection} $input
     * Input collection to validate.
     * @member Telenok.Core.Support.Validator.Model
     */
    protected $input;

    /**
     * @protected
     * @property {Telenok.Core.Support.Validator.Validator} $validator
     * Validator which make validation.
     * @member Telenok.Core.Support.Validator.Model
     */
    protected $validator;

    /**
     * @protected
     * @property {Array} $message
     * List of custom messages.
     * @member Telenok.Core.Support.Validator.Model
     */
    protected $message = [];

    /**
     * @protected
     * @property {Array} $customAttribute
     * Custom notification attributes.
     * @member Telenok.Core.Support.Validator.Model
     */
    protected $customAttribute = [];

    /**
     * @method setModel
     * Set validation model.
     * @param {Telenok.Core.Abstraction.Eloquent.Object.Model} $param
     * @return {Telenok.Core.Support.Validator.Model}
     * @member Telenok.Core.Support.Validator.Model
     */
    public function setModel($param = null)
    {
        $this->model = $param;

        return $this;
    }

    /**
     * @method getModel
     * Return model.
     * @return {Telenok.Core.Abstraction.Eloquent.Object.Model}
     * @member Telenok.Core.Support.Validator.Model
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @method setInput
     * Set input.
     * @param {Array} $param
     * @return {Telenok.Core.Support.Validator.Model}
     * @member Telenok.Core.Support.Validator.Model
     */
    public function setInput($param = [])
    {
        $this->input = collect($param);

        return $this;
    }

    /**
     * @method getInput
     * Return input collection.
     * @return {Illuminate.Support.Collection}
     * @member Telenok.Core.Support.Validator.Model
     */
    public function getInput()
    {
        return $this->input;
    }

    /**
     * @method setMessage
     * Set messages.
     * @param {Array} $param
     * @return {Telenok.Core.Support.Validator.Model}
     * @member Telenok.Core.Support.Validator.Model
     */
    public function setMessage($param = [])
    {
        $this->message = array_merge(trans('core::default.error'), (array) $param);

        return $this;
    }

    /**
     * @method getMessage
     * Return messages.
     * @return {Array}
     * @member Telenok.Core.Support.Validator.Model
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @method setRuleList
     * Set rule's list.
     * @param {Array} $param
     * @return {Telenok.Core.Support.Validator.Model}
     * @member Telenok.Core.Support.Validator.Model
     */
    public function setRuleList($param = [])
    {
        $this->ruleList = $param;

        return $this;
    }

    /**
     * @method getRuleList
     * Return rule's list.
     * @return {Array}
     * @member Telenok.Core.Support.Validator.Model
     */
    public function getRuleList()
    {
        if (empty($this->ruleList))
        {
            $this->ruleList = $this->processRule($this->getModel()->getRule());
        }

        return $this->ruleList;
    }

    /**
     * @method setCustomAttribute
     * Set list of custom attributes for better notificate user about error.
     * @param {Array} $param
     * @return {Telenok.Core.Support.Validator.Model}
     * @member Telenok.Core.Support.Validator.Model
     */
    public function setCustomAttribute($param = [])
    {
        $this->customAttribute = $param;

        return $this;
    }

    /**
     * @method getCustomAttribute
     * Return custom attribute list.
     * @return {Array}
     * @member Telenok.Core.Support.Validator.Model
     */
    public function getCustomAttribute()
    {
        return $this->customAttribute;
    }

    /**
     * @method processRule
     * Process rule for converting them to one-level array.
     * @param {Array} $rule
     * @return {Array}
     * @member Telenok.Core.Support.Validator.Model
     */
    protected function processRule($rule)
    {
        array_walk_recursive($rule, function(&$el, $key, $this_)
        {
            $el = preg_replace_callback('/\:\w+\:/', function($matches) use ($this_)
            {
                return $this_->getInput()->get(trim($matches[0], ':'), 'NULL');
            }, $el);
        }, $this);

        return $rule;
    }

    /**
     * @method passes
     * Start validation process.
     * @return {Boolean}
     * @member Telenok.Core.Support.Validator.Model
     */
    public function passes()
    {
        if ($this->model instanceof \Telenok\Core\Abstraction\Eloquent\Object\Model && $this->model->exists)
        {
            $this->ruleList = array_intersect_key($this->getRuleList(), $this->getInput()->toArray());

            if (empty($this->ruleList))
            {
                return true;
            }
        }

        $this->validator = app('validator')->make(
                    $this->getInput()->toArray(),
                    $this->getRuleList(),
                    $this->getMessage(),
                    $this->getInput()->merge($this->getCustomAttribute())->toArray())
                ->setModel($this->getModel());

        if ($this->validator()->passes())
        {
            return true;
        }

        return false;
    }

    /**
     * @method fails
     * Start validation process.
     * @return {Boolean}
     * @member Telenok.Core.Support.Validator.Model
     */
    public function fails()
    {
        return !$this->passes();
    }

    /**
     * @method messages
     * Return message's list with error descriptions.
     * @return {Array}
     * @member Telenok.Core.Support.Validator.Model
     */
    public function messages()
    {
        $messages = $this->validator()->messages()->all();

        return empty($messages) ? ['undefined' => $this->message['undefined']] : $messages;
    }

    /**
     * @method validator
     * Return validator.
     * @return {Telenok.Core.Support.Validator.Validator}
     * @member Telenok.Core.Support.Validator.Model
     */
    public function validator()
    {
        return $this->validator;
    }
}