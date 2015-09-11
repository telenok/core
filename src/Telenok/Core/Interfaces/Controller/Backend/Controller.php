<?php namespace Telenok\Core\Interfaces\Controller\Backend;

class Controller extends \Telenok\Core\Interfaces\Controller\Controller {

	protected $jsFilePath = [];
	protected $cssFilePath = [];
	protected $cssCode = [];
	protected $jsCode = [];
	
	public function addCssFile($filePath, $key = '', $order = 1000000)
	{
		$this->cssFilePath[($key ?: $filePath)] = ['file' => $filePath, 'order' => $order];

		return $this;
	}

	public function addCssCode($code)
	{
		$this->cssCode[] = $code;

		return $this;
	}

	public function addJsFile($filePath, $key = '', $order = 100000)
	{
		$this->jsFilePath[($key ?: $filePath)] = ['file' => $filePath, 'order' => $order];

		return $this;
	}

	public function addJsCode($code)
	{
		$this->jsCode[] = $code;

		return $this;
	}

	public function getJsFile()
	{
		usort($this->jsFilePath, function($a, $b) { return $a['order'] < $b['order'] ? -1 : 1; });
		
		return $this->jsFilePath;
	}

	public function getJsCode()
	{
		return $this->jsCode;
	}

	public function getCssFile()
	{
		usort($this->cssFilePath, function($a, $b) { return $a['order'] < $b['order'] ? -1 : 1; });
		
		return $this->cssFilePath;
	}

	public function getCssCode()
	{
		return $this->cssCode;
	}
	
}