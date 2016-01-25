<?php

namespace Telenok\Core\Module\Objects\Version;

class Controller extends \Telenok\Core\Interfaces\Presentation\TreeTabObject\Controller {

    protected $key = 'objects-version';
    protected $parent = 'objects';
    protected $modelListClass = '\App\Telenok\Core\Model\Object\Version';
    protected $presentation = 'tree-tab-object';
    protected $presentationModelView = 'core::module.objects-version.model';
    protected $presentationView = 'core::module.objects-version.presentation';

    public function save($input = null, $type = null)
    {
        $input = collect($input);
        $model = \App\Telenok\Core\Model\Object\Version::findOrFail($input->get('id'));

        try
        {
            return \App\Telenok\Core\Model\Object\Version::toRestore($model);
        }
        catch (\Telenok\Core\Support\Exception\ObjectTypeNotFound $ex)
        {
            throw new \Exception($this->LL('error.restore.type.first', ['id' => $model->object_type_id]));
        }

        return $model;
    }

    public function delete($id = null, $force = false)
    {
        try
        {
            $objectId = \App\Telenok\Core\Model\Object\Version::findOrFail($id)->object_id;

            parent::deleteProcess($objectId, $force);
            parent::deleteProcess($id, $force);

            return ['success' => 1];
        }
        catch (\Exception $e)
        {
            return ['exception' => 1];
        }
    }
}