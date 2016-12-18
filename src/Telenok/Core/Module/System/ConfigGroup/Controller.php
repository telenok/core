<?php

namespace Telenok\Core\Module\System\ConfigGroup;

/**
 * @class Telenok.Core.Module.System.ConfigGroup.Controller
 * @extends Telenok.Core.Abstraction.Presentation.TreeTabObject.Controller
 */
class Controller extends \Telenok\Core\Abstraction\Presentation\TreeTabObject\Controller
{
    protected $key = 'system-config-group';
    protected $presentation = 'tree-tab-object';
    protected $presentationFormFieldListView = 'core::module.system-config-group.form-field-list';
    protected $modelListClass = '\App\Vendor\Telenok\Core\Model\System\ConfigGroup';

    public function postProcess($model, $type, $input)
    {
        /*
         * Value can holds many values for other config like
         *
         * $value = ['license.key' => 'demo', 'locale' => 'en'];
         *
         */

        $value = collect($input->get('value'));

        $value->each(function ($item, $key) {
            $config = \App\Vendor\Telenok\Core\Model\System\Config::active()->where('code', $key)->first();

            $config->storeOrUpdate([
                'value' => $item,
            ], true, true);
        });
    }
}
