<?php

namespace Telenok\Core\Module\Objects\Version;

/**
 * @class Telenok.Core.Module.Objects.Version.Controller
 * @extends Telenok.Core.Interfaces.Presentation.TreeTabObject.Controller
 */
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
        return parent::delete($id, true);
    }

}
