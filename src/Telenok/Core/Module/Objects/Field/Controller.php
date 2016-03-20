<?php

namespace Telenok\Core\Module\Objects\Field;

/**
 * @class Telenok.Core.Module.Objects.Field.Controller
 * @extends Telenok.Core.Interfaces.Presentation.TreeTabObject.Controller
 */
class Controller extends \Telenok\Core\Interfaces\Presentation\TreeTabObject\Controller {

    protected $key = 'objects-field';
    protected $parent = 'objects';
    protected $modelListClass = '\App\Telenok\Core\Model\Object\Field';
    protected $modelTreeClass = '\App\Telenok\Core\Model\Object\Type';
    protected $presentation = 'tree-tab-object';
    protected $presentationTreeView = 'core::module.objects-field.tree';
    protected $presentationFormFieldListView = 'core::module.objects-field.form-field-list';

    public function getFormFieldContent($fieldKey, $modelId, $uniqueId)
    {
        try
        {
            $model = \App\Telenok\Core\Model\Object\Field::withPermission()->findOrFail($modelId);
        }
        catch (\Exception $ex)
        {
            $model = app('\App\Telenok\Core\Model\Object\Field');
        }


        return app('telenok.config.repository')->getObjectFieldController($fieldKey)->getFormFieldContent($model, $uniqueId);
    }

    public function getTreeListTypes()
    {
        $types = \App\Telenok\Core\Model\Object\Type::whereIn('code', ['folder', 'object_type'])->active()->get()->pluck('id')->toArray();

        return $types;
    }

    public function validate($model = null, $input = [], $message = [])
    {
        $key = $model->exists && $model->key ? $model->key : $input->get('key');

        if ($key)
        {
            app('telenok.config.repository')->getObjectFieldController($key)->validate($model, $input, $message);
        }

        return $this;
    }

    public function preProcess($model, $type, $input)
    {
        if (!$type)
        {
            $type = $this->getTypeList();
        }

        if ($model->exists)
        {
            $id = $model->getOriginal('field_object_type');
            $key = $model->getOriginal('key');

            if ($id > 0 && $input->get('field_object_type') > 0 && $id != $input->get('field_object_type'))
            {
                throw new \Exception($this->LL('error.change.field.linked.type'));
            }

            if ($key && $input->get('key') && $key != $input->get('key'))
            {
                throw new \Exception($this->LL('error.change.field.key'));
            }
        }
        else
        {
            $modelType = \App\Telenok\Core\Model\Object\Type::where('code', $input->get('field_object_type'))->orWhere('id', $input->get('field_object_type'))->firstOrFail();

            $input->put('field_object_type', $modelType->getKey());
        }

        // preprocessing at field controller
        if (!app('telenok.config.repository')->getObjectFieldController()->has($input->get('key')))
        {
            throw new \Exception('There are not field controller for field key "' . $input->get('key') . '"');
        }
        else
        {
            app('telenok.config.repository')->getObjectFieldController($input->get('key'))->preProcess($model, $type, $input);
        }

        return parent::preProcess($model, $type, $input);
    }

    public function postProcess($model, $type, $input)
    {
        $field = app('telenok.config.repository')->getObjectFieldController($input->get('key'));

        $field->postProcess($model, $type, $input);

        return parent::postProcess($model, $type, $input);
    }

}
