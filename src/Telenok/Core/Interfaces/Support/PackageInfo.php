<?php namespace Telenok\Core\Interfaces\Support;

abstract class PackageInfo {

	protected $key;
	protected $version;
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
		return $this->title ?: $this->LL("{$this->getKey()}::package.title");
	}

	public function setDescription($param = '')
	{
		$this->description = $param;
		
		return $this;
	}

	public function getDescription()
	{
		return $this->description ?: $this->LL("{$this->getKey()}::package.description");
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
}