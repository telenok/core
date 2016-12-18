<?php

namespace Telenok\Core\Module\System\Config;

/**
 * @class Telenok.Core.Module.System.Config.Controller
 * @extends Telenok.Core.Abstraction.Presentation.TreeTabObject.Controller
 */
class Controller extends \Telenok\Core\Abstraction\Presentation\TreeTabObject\Controller
{
    protected $key = 'system-config';
    protected $presentation = 'tree-tab-object';
    protected $presentationFormFieldListView = 'core::module.system-config.form-field-list';
    protected $modelListClass = '\App\Vendor\Telenok\Core\Model\System\Config';

    public function preProcess($model, $type, $input)
    {
        /*
         * Value can holds many values for other config like
         *
         * $value = ['license.key' => 'demo', 'locale' => 'en'];
         *
         */
        if (($v = $input->get('value')) && isset($v[$input->get('code')])) {
            $input->put('value', $v[$input->get('code')]);
        }

        if ($model->controller_class) {
            if (class_exists($model->controller_class) && ($controller = new $model->controller_class())
                    && ($controller instanceof \Telenok\Core\Abstraction\Config\Controller)) {
                $controller->validate($input);
                $controller->preProcess($model, $type, $input);
            } else {
                throw new \Exception();
            }
        }
    }
}
