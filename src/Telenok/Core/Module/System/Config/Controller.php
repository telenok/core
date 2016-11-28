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

    public function save($input = [], $type = null)
    {
        $input = collect($input);

        $model = parent::save($input, $type);

        if ($model->controller_class && class_exists($model->controller_class))
        {
            $controller = new $model->controller_class;

            $controller->validate($input->get('value', []));
            $controller->save($model, $input);
        }
        else
        {
            throw new \Exception();
        }
    }
}
