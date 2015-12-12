<?php

namespace Telenok\Core\Module\Tools\DatabaseConsole;

class Controller extends \Telenok\Core\Interfaces\Presentation\Simple\Controller {

    protected $key = 'database-console';
    protected $parent = 'tools';
    protected $icon = 'fa fa-database';

    public function processSelect()
    {
        return \DB::select(\DB::Raw( $this->getRequest()->input('content') ));
    }

    public function processStatement()
    {
        return \DB::affectingStatement(\DB::Raw( $this->getRequest()->input('content') ));
    }
}