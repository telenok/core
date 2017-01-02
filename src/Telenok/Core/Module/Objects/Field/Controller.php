<?php

namespace Telenok\Core\Module\Objects\Field;

/**
 * @class Telenok.Core.Module.Objects.Field.Controller
 * @extends Telenok.Core.Abstraction.Presentation.TreeTabObject.Controller
 */
class Controller extends \Telenok\Core\Abstraction\Presentation\TreeTabObject\Controller
{

    protected $key                           = 'objects-field';
    protected $parent                        = 'objects';
    protected $modelListClass                = '\App\Vendor\Telenok\Core\Model\Object\Field';
    protected $modelTreeClass                = '\App\Vendor\Telenok\Core\Model\Object\Type';
    protected $presentation                  = 'tree-tab-object';
    protected $presentationTreeView          = 'core::module.objects-field.tree';
    protected $presentationFormFieldListView = 'core::module.objects-field.form-field-list';

    public function getFormFieldContent($fieldKey, $modelId, $uniqueId)
    {
        try {
            $model = \App\Vendor\Telenok\Core\Model\Object\Field::withPermission()->findOrFail($modelId);
        } catch (\Exception $ex) {
            $model = app('\App\Vendor\Telenok\Core\Model\Object\Field');
        }

        return app('telenok.repository')->getObjectFieldController($fieldKey)->getFormFieldContent($model, $uniqueId);
    }

    public function getTreeListTypes()
    {
        $types = \App\Vendor\Telenok\Core\Model\Object\Type::whereIn('code', ['folder', 'object_type'])->active()->get()->pluck('id')->toArray();

        return $types;
    }

    public function validate($model = null, $input = [], $message = [])
    {
        $key = $model->exists && $model->key ? $model->key : $input->get('key');

        if ($key) {
            app('telenok.repository')->getObjectFieldController($key)->validate($model, $input, $message);
        }

        return $this;
    }

    public function preProcess($model, $type, $input)
    {
        if (!$type) {
            $type = $this->getTypeList();
        }

        if ($model->exists) {

            $id = $model->getOriginal('field_object_type');
            $key = $model->getOriginal('key');

            if ($id > 0 && $input->get('field_object_type') > 0 && ($id != $input->get('field_object_type'))) {
                throw new \Exception($this->LL('error.change.field.linked.type'));
            }

            if ($key && $input->get('key') && ($key != $input->get('key'))) {
                throw new \Exception($this->LL('error.change.field.key'));
            }
        } else {

            $modelType = \App\Vendor\Telenok\Core\Model\Object\Type::active()->where('code', (string)$input->get('field_object_type'))->orWhere('id', $input->get('field_object_type'))->first();

            if (!$modelType) {
                throw new \Exception('Please, choose "Type" for the field');
            }

            $input->put('field_object_type', $modelType->getKey());
        }

        // preprocessing at field controller
        if (!app('telenok.repository')->getObjectFieldController()->has($input->get('key'))) {
            throw new \Exception('There are not field controller for field key "' . $input->get('key') . '"');
        } else {
            app('telenok.repository')->getObjectFieldController($input->get('key'))->preProcess($model, $type, $input);
        }

        return parent::preProcess($model, $type, $input);
    }

    public function postProcess($model, $type, $input)
    {
        $field = app('telenok.repository')->getObjectFieldController($input->get('key'));

        $field->postProcess($model, $type, $input);

        return parent::postProcess($model, $type, $input);
    }

    /**
     * @method deleteProcess
     * @member Telenok.Core.Abstraction.Presentation.TreeTab.Controller
     */
    public function deleteProcess($id = null, $force = false)
    {
        $model = $this->getModelTrashed($id);

        $field = app('telenok.repository')->getObjectFieldController($model->key);

        $field->preDeleteProcess($model, $force);

        if (!app('auth')->can('delete', $id))
        {
            throw new \LogicException($this->LL('error.access'));
        }

        app('db')->transaction(function() use ($model, $force)
        {
            if ($force || $model->trashed())
            {
                $model->forceDelete();
            }
            else
            {
                $model->delete();
            }
        });
    }
}
