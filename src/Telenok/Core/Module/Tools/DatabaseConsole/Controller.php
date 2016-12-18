<?php

namespace Telenok\Core\Module\Tools\DatabaseConsole;

/**
 * @class Telenok.Core.Module.Tools.DatabaseConsole.Controller
 * @extends Telenok.Core.Abstraction.Presentation.Simple.Controller
 */
class Controller extends \Telenok\Core\Abstraction\Presentation\Simple\Controller
{
    protected $key = 'database-console';
    protected $parent = 'tools';
    protected $icon = 'fa fa-database';

    public function processSelect()
    {
        return app('db')->select(app('db')->Raw($this->getRequest()->input('content')));
    }

    public function processStatement()
    {
        return app('db')->affectingStatement(app('db')->Raw($this->getRequest()->input('content')));
    }
}
