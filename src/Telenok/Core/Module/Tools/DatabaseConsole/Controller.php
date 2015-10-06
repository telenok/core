<?php

namespace Telenok\Core\Module\Tools\DatabaseConsole;

class Controller extends \Telenok\Core\Interfaces\Presentation\Simple\Controller {

    protected $key = 'database-console';
    protected $parent = 'tools';
    protected $icon = 'fa fa-database';

    public function processSelect()
    {
        try
        {
            return \DB::select(\DB::Raw( $this->getRequest()->get('content') ));
        } 
        catch (\Exception $ex) 
        {
            throw $ex;
        }
    }

    public function processStatement()
    {
        try
        {
            return \DB::affectingStatement(\DB::Raw( $this->getRequest()->get('content') ));
        } 
        catch (\Exception $ex) 
        {
            throw $ex;
        }
    }
}