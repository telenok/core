<?php namespace Telenok\Core\Support\Traits;

/**
 * Trait to define path for specific packages
 * 
 * @class Telenok.Core.Support.Traits.Package
 */
trait Package
{
    /**
     * @protected
     * @property {String} $package
     * Key defined package. It can be "core" or "news" etc.
     * @member Telenok.Core.Support.Traits.Package
     */
	protected $package;

    /**
     * @method getPackage
     * Cache and return package key for called class.
     * @member Telenok.Core.Support.Traits.Package
     * @return {String}
     */
    public function getPackage()
    {
        static $ns = [];

        $class = get_called_class();

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

			if ($package)
			{
				$ns[$class] = strtolower($package->getKey());
			}
			else
			{
				$ns[$class] = '';
			}
        }

        return $ns[$class];
    }

    /**
     * @method setPackage
     * Set package key.
     * @member Telenok.Core.Support.Traits.Package
     * @return {void}
     */
    public function setPackage($param)
    {
        $this->package = $param;
    }
}