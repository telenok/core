<?php namespace Telenok\Core\Event;

use App\Events\Event;

class ModuleMenuLeft extends Event{

    protected $list;

    public function __construct()
    {
        $this->list = collect();
    }

    public function getList()
    {
        return $this->list;
    }
}