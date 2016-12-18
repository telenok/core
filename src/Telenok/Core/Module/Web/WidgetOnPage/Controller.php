<?php

namespace Telenok\Core\Module\Web\WidgetOnPage;

/**
 * @class Telenok.Core.Module.Web.WidgetOnPage.Controller
 * @extends Telenok.Core.Abstraction.Presentation.TreeTabObject.Controller
 */
class Controller extends \Telenok\Core\Abstraction\Presentation\TreeTabObject\Controller
{
    protected $key = 'web-page-wop';
    protected $presentation = 'tree-tab-object';
    protected $modelListClass = '\App\Vendor\Telenok\Core\Model\Web\WidgetOnPage';
    protected $presentationFormFieldListView = 'core::module.web-page-wop.form-field-list';
    protected $presentationModuleKey = 'web-page-widget-web-page-constructor';

    public function getGridId($key = 'gridId')
    {
        return "{$this->getPresentation()}-{$this->getTabKey()}-{$this->getTypeList()->code}";
    }

    public function preProcess($model, $type, $input)
    {
        if ($input->get('key')) {
            app('telenok.repository')->getWidget()->get($input->get('key'))->preProcess($model, $type, $input);
        }

        return parent::postProcess($model, $type, $input);
    }

    public function postProcess($model, $type, $input)
    {
        if ($input->get('key')) {
            app('telenok.repository')->getWidget()->get($input->get('key'))->postProcess($model, $type, $input);
        }

        return parent::postProcess($model, $type, $input);
    }
}
