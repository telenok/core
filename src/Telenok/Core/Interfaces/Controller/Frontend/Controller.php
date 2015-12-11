<?php namespace Telenok\Core\Interfaces\Controller\Frontend;

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

    protected $cacheKey = 'frontend-controller';
    protected $pageMetaTitle;
    protected $pageMetaDescription;
    protected $pageMetaKeywords;

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

		$widgetRepository = app('telenok.config.repository')->getWidget();

		$wop->each(function($w) use (&$content, $widgetRepository)
		{
			$content[$w->container][] = $widgetRepository->get($w->key)->getInsertContent($w->getKey());
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
        
		$pageId = intval(str_replace('page_', '', \Route::currentRouteName()));

        try
        {
            $page = \Cache::remember(
                $this->getCacheKey(), 
                $this->getCacheTime(),
                function() use ($pageId)
                {
                    return \App\Telenok\Core\Model\Web\Page::active()->withPermission()->findOrFail($pageId);
                });
        }
        catch (\Exception $e)
        {
            app()->abort(404);
        }

        if ($t = $page->translate('template_view'))
        {
            $this->setFrontendView($t);
        }
        else if (($v = $page->pagePageController) && ($controllerTemplate = $v->template_view))
        {
            $this->setFrontendView($controllerTemplate);
        }

        $this->setCacheTime($page->cache_time);

        if (($content = $this->getCachedContent()) !== false)
        {
            return $this->processContent($content);
        }
        
        $content = $this->getNotCachedContent($page);

        $this->setCachedContent($content);

        return $this->processContent($content);
	}
    
	public function getNotCachedContent($page)
	{
		$listWidget = app('telenok.config.repository')->getWidget();
        
        foreach ($this->container as $containerId)
        {
            $page->widget()->active()->get()->filter(function($item) use ($containerId)
            {
                return $item->container === $containerId;
            })
            ->each(function($item) use (&$content, $containerId, $listWidget)
            {
                $content[$containerId][] = $listWidget->get($item->key)
                                            ->setWidgetModel($item)
                                            ->setConfig($item->structure)
                                            ->setFrontendController($this)
                                            ->getContent();
            });
        }

		return view($this->getFrontendView(), [
                    'page' => $page,
                    'controller' => $this,
                    'content' => $content,
                ])->render();
    }
    
    public function processContent($content = '')
    {
        return $content;
    }

	public function getCacheKey()
	{        
            return $this->cacheKey ? $this->cacheKey . $this->getFrontendView() 
                    . "." . config('app.locale', config('app.localeDefault'))
                    . "." . implode('', (array)app('router')->getCurrentRoute()->parameters())
                    . "." . collect($this->getRequest()->all())->toJson() : false;
	}

	public function getCachedContent()
	{
        if (($k = $this->getCacheKey()) !== false)
        {
            return \Cache::get($k, false);
        }

		return false;
	}

	public function setCachedContent($param = '')
	{
        if (($t = $this->getCacheTime()) && ($k = $this->getCacheKey()) !== false)
        {
            \Cache::put($k, $param, $t);
        }
        
		return $this;
	}

    public function validateSession()
    {
        return ['logined' => (int)app('auth')->check(), 'csrf_token' => csrf_token()];
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

    // page meta title
    public function setPageMetaTitle($param)
    {
        $this->pageMetaTitle = $param;
        
        return $this;
    }
    
    public function getPageMetaTitle()
    {
        return $this->pageMetaTitle;
    }

    // page meta description
    public function setPageMetaDescription($param)
    {
        $this->pageMetaDescription = $param;
        
        return $this;
    }
    
    public function getPageMetaDescription()
    {
        return $this->pageMetaDescription;
    }

    // page meta keywords
    public function setPageMetaKeywords($param)
    {
        $this->pageMetaKeywords = $param;
        
        return $this;
    }
    
    public function getPageMetaKeywords()
    {
        return $this->pageMetaKeywords;
    }
}