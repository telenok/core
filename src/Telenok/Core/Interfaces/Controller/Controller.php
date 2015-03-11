<?php

namespace Telenok\Core\Interfaces\Controller;

abstract class Controller extends \Illuminate\Routing\Controller implements \Telenok\Core\Interfaces\IRequest {

    use \Telenok\Core\Support\Language\Load;
    use \Illuminate\Foundation\Bus\DispatchesCommands;

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

    public function setKey($key)
    {
        $this->key = $key;
		
		return $this;
    }
    
	
	/**
	 * Set http request
	 * 
	 * @param \Illuminate\Http\Request  $request
	 * @return $this
	 */
    public function setRequest($request = null)
    {
        $this->request = $request;
        
        return $this;
    }

	/**
	 * Get http request
	 * 
	 * @return \Illuminate\Http\Request
	 */
    public function getRequest()
    {
        return $this->request;
    }
}