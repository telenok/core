<?php

namespace Telenok\Core\Interfaces\Controller;

abstract class Controller extends \Illuminate\Routing\Controller implements \Telenok\Core\Interfaces\Request {

    use \Telenok\Core\Support\Language\Load;
    use \Illuminate\Foundation\Bus\DispatchesCommands;
    use \Illuminate\Foundation\Validation\ValidatesRequests;

    protected $key = '';
    protected $package = '';
    protected $request; 

    public function getName()
    {
        return $this->LL('name');
    }

    public function getKey()
    {
        return $this->key;
    }
    
    public function setRequest(\Illuminate\Http\Request $request = null)
    {
        $this->request = $request;
        
        return $this;
    }

    public function getRequest()
    {
        return $this->request;
    }
}