<?php

namespace Telenok\Core\Module\Web\Domain;

/**
 * @class Telenok.Core.Module.Web.Domain.Controller
 * @extends Telenok.Core.Interfaces.Presentation.TreeTabObject.Controller
 */
class Controller extends \Telenok\Core\Interfaces\Presentation\TreeTabObject\Controller {

    protected $key = 'web-domain';
    protected $parent = 'web';
    protected $presentation = 'tree-tab-object';
    protected $modelListClass = '\App\Telenok\Core\Model\Web\Domain';

}
