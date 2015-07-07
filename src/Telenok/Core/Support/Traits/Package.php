<?php namespace Telenok\Core\Support\Traits;

trait Package
{
	protected $package;

    public function getPackage()
    {
        static $ns = [];

        $class = get_class($this);

        if (!isset($ns[$class]))
        {
            if ($this->package)
			{
				return $this->package;
			}
			
			$package = app('telenok.config.repository')->getPackage()->filter(function($item)
			{
				return strpos('\\' . trim(get_class($this), '\\') . '\\', $item->getBaseClass()) !== FALSE;
			})->first();

            $ns[$class] = strtolower($package->getKey());
        }

        return $ns[$class];
    }

    public function setPackage($param)
    {
        $this->package = $param;

        return $this;
    }
}