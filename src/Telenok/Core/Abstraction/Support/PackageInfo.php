<?php namespace Telenok\Core\Abstraction\Support;

/**
 * @class Telenok.Core.Abstraction.Support.PackageInfo
 * Class describe package's data like title, key, base class, description, image
 * @mixins Telenok.Core.Support.Traits.Language
 * @abstract
 */
abstract class PackageInfo {

    use \Telenok\Core\Support\Traits\Language;

    /**
     * @protected
     * @property {String} $key
     * Package's key.
     * @member Telenok.Core.Abstraction.Support.PackageInfo
     */	
	protected $key;
	
    /**
     * @protected
     * @property {String} $baseClass
     * Package's base class.
     * @member Telenok.Core.Abstraction.Support.PackageInfo
     */	
	protected $baseClass;
	
    /**
     * @protected
     * @property {String} $versionPackage
     * Package's version.
     * @member Telenok.Core.Abstraction.Support.PackageInfo
     */	
	protected $versionPackage;
	
    /**
     * @protected
     * @property {String} $versionTelenok
     * Maximum version of Telenok CMS.
     * @member Telenok.Core.Abstraction.Support.PackageInfo
     */	
	protected $versionTelenok;
	
    /**
     * @protected
     * @property {String} $title
     * Title of package.
     * @member Telenok.Core.Abstraction.Support.PackageInfo
     */	
	protected $title;
	
    /**
     * @protected
     * @property {String} $description
     * Description of package.
     * @member Telenok.Core.Abstraction.Support.PackageInfo
     */	
	protected $description;
	
    /**
     * @protected
     * @property {String} $image
     * Class of image for package.
     * @member Telenok.Core.Abstraction.Support.PackageInfo
     */	
	protected $image;
	
    /**
     * @method setKey
     * Set key of package.
     * @param {String} $param
     * @return {Telenok.Core.Abstraction.Support.PackageInfo}
     * @member Telenok.Core.Abstraction.Support.PackageInfo
     */
	public function setKey($param = '')
	{
		$this->key = $param;
		
		return $this;
	}

    /**
     * @method getKey
     * Return key of package.
     * @return {String}
     * @member Telenok.Core.Abstraction.Support.PackageInfo
     */
	public function getKey()
	{
		return $this->key;
	}

    /**
     * @method setTitle
     * Set title of package.
     * @param {String} $param
     * @return {Telenok.Core.Abstraction.Support.PackageInfo}
     * @member Telenok.Core.Abstraction.Support.PackageInfo
     */
	public function setTitle($param = '')
	{
		$this->title = $param;
		
		return $this;
	}

    /**
     * @method getTitle
     * Return title of package.
     * @return {String}
     * @member Telenok.Core.Abstraction.Support.PackageInfo
     */
	public function getTitle()
	{
		return $this->title ?: $this->LL('title');
	}

    /**
     * @method setDescription
     * Set description of package.
     * @param {String} $param
     * @return {Telenok.Core.Abstraction.Support.PackageInfo}
     * @member Telenok.Core.Abstraction.Support.PackageInfo
     */
	public function setDescription($param = '')
	{
		$this->description = $param;
		
		return $this;
	}

    /**
     * @method getDescription
     * Return description of package.
     * @return {String}
     * @member Telenok.Core.Abstraction.Support.PackageInfo
     */
	public function getDescription()
	{
		return $this->description ?: $this->LL("description");
	}

    /**
     * @method setImage
     * Set class of package's image.
     * @param {String} $param
     * @return {Telenok.Core.Abstraction.Support.PackageInfo}
     * @member Telenok.Core.Abstraction.Support.PackageInfo
     */
	public function setImage($param = '')
	{
		$this->image = $param;
		
		return $this;
	}

    /**
     * @method getImage
     * Return package's image class.
     * @return {String}
     * @member Telenok.Core.Abstraction.Support.PackageInfo
     */
	public function getImage()
	{
		return $this->image;
	}

    /**
     * @method getBaseClass
     * Return base class of package.
     * @return {String}
     * @member Telenok.Core.Abstraction.Support.PackageInfo
     */
	public function getBaseClass()
	{
		return $this->baseClass;
	}

    /**
     * @method setBaseClass
     * Set base class of package.
     * @param {String} $param
     * @return {Telenok.Core.Abstraction.Support.PackageInfo}
     * @member Telenok.Core.Abstraction.Support.PackageInfo
     */
	public function setBaseClass($param)
	{
		$this->baseClass = $param;
		
		return $this;
	}
}
