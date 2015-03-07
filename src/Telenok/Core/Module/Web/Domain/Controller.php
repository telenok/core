<?php

namespace Telenok\Core\Module\Web\Domain;

class Controller extends \Telenok\Core\Interfaces\Presentation\TreeTabObject\Controller {

	protected $key = 'web-domain';
	protected $parent = 'web';
	protected $presentation = 'tree-tab-object';
    protected $modelListClass = '\App\Model\Telenok\Web\Domain';
}

