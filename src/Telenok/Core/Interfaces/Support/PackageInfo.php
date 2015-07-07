<?php namespace Telenok\Core\Interfaces\Support;

abstract class PackageInfo {

    use \Telenok\Core\Support\Traits\Language;

	protected $key;
	protected $baseClass;
	protected $versionPackage;
	protected $versionTelenok;
	protected $title;
	protected $description;
	protected $image;
	
	public function setKey($param = '')
	{
		$this->key = $param;
		
		return $this;
	}

	public function getKey()
	{
		return $this->key;
	}

	public function setTitle($param = '')
	{
		$this->title = $param;
		
		return $this;
	}

	public function getTitle()
	{
		return $this->title ?: $this->LL('title');
	}

	public function setDescription($param = '')
	{
		$this->description = $param;
		
		return $this;
	}

	public function getDescription()
	{
		return $this->description ?: $this->LL("description");
	}

	public function setImage($param = '')
	{
		$this->image = $param;
		
		return $this;
	}

	public function getImage()
	{
		return $this->image;
	}
	
	public function getBaseClass()
	{
		return $this->baseClass;
	}
	
	public function setBaseClass($param)
	{
		$this->baseClass = $param;
		
		return $this;
	}
}