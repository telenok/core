<?php

namespace Telenok\Core\Module\System\ConfigGroup;

/**
 * @class Telenok.Core.Module.System.ConfigGroup.Controller
 * @extends Telenok.Core.Abstraction.Presentation.TreeTabObject.Controller
 */
class Controller extends \Telenok\Core\Abstraction\Presentation\TreeTabObject\Controller {

    protected $key = 'system-config-group';
    protected $presentation = 'tree-tab-object';
    protected $presentationFormFieldListView = 'core::module.system-config-group.form-field-list';
    protected $modelListClass = '\App\Vendor\Telenok\Core\Model\System\ConfigGroup';

    public function save__($input = [], $type = null)
    {
        $input = collect($input);
        $model = $this->getModelList();

        try
        {
            $w = app('telenok.repository')->getConfigGroup(strtolower($input->get('code')));

            return $w->save($model, $input);
        }
        catch (\Exception $e)
        {
            return parent::save($input, $type);
        }
    }
}
