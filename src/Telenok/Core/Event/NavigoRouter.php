<?php namespace Telenok\Core\Event;

use App\Events\Event;

class NavigoRouter extends Event {

    protected $list;

    public function __construct()
    {
        $this->list = collect();
    }

    public function getList()
    {
        return $this->list;
    }

    public function getContentCollection()
    {
        $list = $this->getList();
        $output = collect();

        $list->each(function ($item) use ($output)
        {
            list($class, $method) = explode("@", $item);

            $output->push((new $class)->$method());
        });

        return $output;
    }
}