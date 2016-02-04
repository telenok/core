<?php namespace Telenok\Core\Interfaces\Support;

/**
 * @class Telenok.Core.Interfaces.Support.PackageInfo
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
     * @member Telenok.Core.Interfaces.Support.PackageInfo
     */	
	protected $key;
	
    /**
     * @protected
     * @property {String} $baseClass
     * Package's base class.
     * @member Telenok.Core.Interfaces.Support.PackageInfo
     */	
	protected $baseClass;
	
    /**
     * @protected
     * @property {String} $versionPackage
     * Package's version.
     * @member Telenok.Core.Interfaces.Support.PackageInfo
     */	
	protected $versionPackage;
	
    /**
     * @protected
     * @property {String} $versionTelenok
     * Maximum version of Telenok CMS.
     * @member Telenok.Core.Interfaces.Support.PackageInfo
     */	
	protected $versionTelenok;
	
    /**
     * @protected
     * @property {String} $title
     * Title of package.
     * @member Telenok.Core.Interfaces.Support.PackageInfo
     */	
	protected $title;
	
    /**
     * @protected
     * @property {String} $description
     * Description of package.
     * @member Telenok.Core.Interfaces.Support.PackageInfo
     */	
	protected $description;
	
    /**
     * @protected
     * @property {String} $image
     * Class of image for package.
     * @member Telenok.Core.Interfaces.Support.PackageInfo
     */	
	protected $image;
	
    /**
     * @protected
     * @property {String} $languageDirectory
     * Language directory for widgets.
     * @member Telenok.Core.Interfaces.Widget.Group.Controller
     */
	public function setKey($param = '')
	{
		$this->key = $param;
		
		return $this;
	}

    /**
     * @protected
     * @property {String} $languageDirectory
     * Language directory for widgets.
     * @member Telenok.Core.Interfaces.Widget.Group.Controller
     */
	public function getKey()
	{
		return $this->key;
	}

	public function setTitle($param = '')
	{
		$this->title = $param;
		
		return $this;
	}

    /**
     * @protected
     * @property {String} $languageDirectory
     * Language directory for widgets.
     * @member Telenok.Core.Interfaces.Widget.Group.Controller
     */
	public function getTitle()
	{
		return $this->title ?: $this->LL('title');
	}

    /**
     * @protected
     * @property {String} $languageDirectory
     * Language directory for widgets.
     * @member Telenok.Core.Interfaces.Widget.Group.Controller
     */
	public function setDescription($param = '')
	{
		$this->description = $param;
		
		return $this;
	}

    /**
     * @protected
     * @property {String} $languageDirectory
     * Language directory for widgets.
     * @member Telenok.Core.Interfaces.Widget.Group.Controller
     */
	public function getDescription()
	{
		return $this->description ?: $this->LL("description");
	}

    /**
     * @protected
     * @property {String} $languageDirectory
     * Language directory for widgets.
     * @member Telenok.Core.Interfaces.Widget.Group.Controller
     */
	public function setImage($param = '')
	{
		$this->image = $param;
		
		return $this;
	}

    /**
     * @protected
     * @property {String} $languageDirectory
     * Language directory for widgets.
     * @member Telenok.Core.Interfaces.Widget.Group.Controller
     */
	public function getImage()
	{
		return $this->image;
	}

    /**
     * @protected
     * @property {String} $languageDirectory
     * Language directory for widgets.
     * @member Telenok.Core.Interfaces.Widget.Group.Controller
     */
	public function getBaseClass()
	{
		return $this->baseClass;
	}

    /**
     * @protected
     * @property {String} $languageDirectory
     * Language directory for widgets.
     * @member Telenok.Core.Interfaces.Widget.Group.Controller
     */
	public function setBaseClass($param)
	{
		$this->baseClass = $param;
		
		return $this;
	}
}
