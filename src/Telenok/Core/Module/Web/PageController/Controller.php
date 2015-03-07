<?php

namespace Telenok\Core\Module\Web\PageController;

class Controller extends \Telenok\Core\Interfaces\Presentation\TreeTabObject\Controller {

	protected $key = 'web-page-controller';
	protected $parent = 'web';
	protected $presentation = 'tree-tab-object';
    protected $modelListClass = '\App\Model\Telenok\Web\PageController';

    public function getGridId($key = 'gridId')
    {
        return "{$this->getPresentation()}-{$this->getTabKey()}-{$this->getTypeList()->code}";
    }  
	
}

