<?php

namespace Telenok\Core\Module\System\Setting;

/**
 * @class Telenok.Core.Module.System.Setting.Controller
 * @extends Telenok.Core.Abstraction.Presentation.TreeTabObject.Controller
 */
class Controller extends \Telenok\Core\Abstraction\Presentation\TreeTabObject\Controller {

    protected $key = 'system-setting';
    protected $presentation = 'tree-tab-object';
    protected $presentationFormFieldListView = 'core::module.setting.form-field-list';
    protected $modelListClass = '\App\Vendor\Telenok\Core\Model\System\Setting';

    public function save($input = [], $type = null)
    {
        $input = collect($input);
        $model = $this->getModelList();

        try
        {
            $w = app('telenok.config.repository')->getSetting(strtolower($input->get('code')));

            return $w->save($model, $input);
        }
        catch (\Exception $e)
        {
            return parent::save($input, $type);
        }
    }

}
