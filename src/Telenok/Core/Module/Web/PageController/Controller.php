<?php

namespace Telenok\Core\Module\Web\PageController;

/**
 * @class Telenok.Core.Module.Web.PageController.Controller
 * @extends Telenok.Core.Abstraction.Presentation.TreeTabObject.Controller
 */
class Controller extends \Telenok\Core\Abstraction\Presentation\TreeTabObject\Controller {

    protected $key = 'web-page-controller';
    protected $parent = 'web';
    protected $presentation = 'tree-tab-object';
    protected $modelListClass = '\App\Telenok\Core\Model\Web\PageController';

    public function getGridId($key = 'gridId')
    {
        return "{$this->getPresentation()}-{$this->getTabKey()}-{$this->getTypeList()->code}";
    }

}
