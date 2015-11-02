<?php namespace Telenok\Core\Interfaces\Controller;

class Controller extends \Illuminate\Routing\Controller implements \Telenok\Core\Interfaces\Support\IRequest {

    use \Telenok\Core\Support\Traits\Language;
    use \Illuminate\Foundation\Bus\DispatchesCommands;

    protected $key = '';
    protected $request;
    protected $vendorName = 'telenok';

    public function __construct() {}
	
    public function getVendorName()
    {
        return $this->vendorName;
    }

    public function setVendorName($key)
    {
        $this->vendorName = $key;
		
		return $this;
    }
    
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
    
	/**
	 * Get new instance
	 * 
	 * @return $this
	 */
    public static function make()
	{
        return new static;
	}
}