<?php

namespace Telenok\Core\Interfaces\Controller\Frontend;

class Controller extends \Telenok\Core\Interfaces\Controller\Controller {

	protected $controllerModel;
	protected $container = [];
	protected $jsFilePath = [];
	protected $cssFilePath = [];
	protected $cssCode = [];
	protected $jsCode = [];
	protected $cacheTime = 3600;
	protected $frontendView = 'core::controller.frontend';
	protected $backendView = 'core::controller.frontend-container';

	public function __construct()
	{
		$this->languageDirectory = 'controller';

		parent::__construct();
	}
	
	public function setCacheTime($param = 0)
	{
		$this->cacheTime = min($this->getCacheTime(), $param);

		return $this;
	}

	public function getCacheTime()
	{
		return $this->cacheTime;
	}

	public function getContainerContent($pageId = 0, $languageId = 0)
	{
		$content = ['controller' => $this];

		$wop = \App\Telenok\Core\Model\Web\WidgetOnPage::where('widget_page', $pageId)->whereHas('widgetLanguageLanguage', function($query) use ($languageId)
						{
							$query->where('id', $languageId);
						})
						->orderBy('widget_order')->get();

		$widgetConfig = app('telenok.config.repository')->getWidget();

		$wop->each(function($w) use (&$content, $widgetConfig)
		{
			$content[$w->container][] = $widgetConfig->get($w->key)->getInsertContent($w->getKey());
		});

		return view($this->backendView, $content)->render();
	}

	public function getContiner()
	{
		return $this->container;
	}

	public function getBackendView()
	{
		return $this->backendView;
	}

	public function setBackendView($param = '')
	{
		$this->backendView = $param;

		return $this;
	}

	public function getFrontendView()
	{
		return $this->frontendView;
	}

	public function setFrontendView($param = '')
	{
		$this->frontendView = $param;

		return $this;
	}

	public function getContent()
	{
		$content = [];

		$listWidget = app('telenok.config.repository')->getWidget();
		$pageId = intval(str_replace('page_', '', \Route::currentRouteName()));

		try
		{
			$page = \App\Telenok\Core\Model\Web\Page::findOrFail($pageId);

			$this->setCacheTime($page->cache_time);

			if ($t = $page->translate('template_view'))
			{
				$this->setFrontendView($t);
			}

			foreach ($this->container as $containerId)
			{
				$page->widget()->active()->get()->filter(function($item) use ($containerId)
				{
					return $item->container === $containerId;
				})->each(function($item) use (&$content, $containerId, $listWidget)
				{
					$content[$containerId][] = $listWidget->get($item->key)->setWidgetModel($item)->setFrontendController($this)->getContent();
				});
			}
		}
		catch (\Exception $e)
		{
			throw $e;
		}

		return view($this->getFrontendView(), [
			'page' => $page,
			'controller' => $this,
			'content' => $content,
		])->render();
	}

	public function hasAddedCssFile($filePath = '', $key = '')
	{
		foreach($this->cssFilePath as $k => $p)
		{
			if ($p['file'] == $filePath)
			{
				return true;
			}
			else if (!is_array($key) && strpos(".$k.", ".$key.") !== FALSE)
			{
				return true;
			}
		}
	}

	public function addCssFile($filePath, $key = '', $order = 1000000)
	{
		if (!$this->hasAddedCssFile($filePath, $key))
		{
			if (is_array($key))
			{
				$key = implode(".", $key);
			}
			
			$this->cssFilePath[($key ?: $filePath)] = ['file' => $filePath, 'order' => $order];
		}

		return $this;
	}

	public function addCssCode($code)
	{
		$this->cssCode[] = $code;

		return $this;
	}

	public function hasAddedJsFile($filePath = '', $key = '')
	{
		foreach($this->jsFilePath as $k => $p)
		{
			if ($p['file'] == $filePath)
			{
				return true;
			}
			else if (!is_array($key) && strpos(".$k.", ".$key.") !== FALSE)
			{
				return true;
			}
		}
	}

	public function addJsFile($filePath, $key = '', $order = 100000)
	{
		if (!$this->hasAddedJsFile($filePath, $key))
		{
			if (is_array($key))
			{
				$key = implode(".", $key);
			}
			
			$this->jsFilePath[($key ?: $filePath)] = ['file' => $filePath, 'order' => $order];
		}

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
	

	public function getName()
	{
		return $this->LL('name');
	}

	public function setControllerModel($model)
	{
		$this->controllerModel = $model;

		return $this;
	}

	public function getControllerModel()
	{
		return $this->controllerModel;
	}

	public function getKey()
	{
		return '';
	}

}
