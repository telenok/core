<?php namespace Telenok\Core\Interfaces\Widget;

/**
 * @class Telenok.Core.Interfaces.Widget.Controller
 * Base controller for widgets.
 * 
 * @extends Telenok.Core.Interfaces.Controller.Controller
 */
class Controller extends \Telenok\Core\Interfaces\Controller\Controller { 

    /**
     * @protected
     * @property {String} $parent
     * Parent's widget key.
     * @member Telenok.Core.Interfaces.Widget.Controller
     */
	protected $parent = '';

    /**
     * @protected
     * @property {String} $group
     * Key of parent widget group.
     * @member Telenok.Core.Interfaces.Widget.Controller
     */
     protected $group = '';

    /**
     * @protected
     * @property {String} $icon
     * Class of widget's icon.
     * @member Telenok.Core.Interfaces.Widget.Controller
     */	
     protected $icon = 'fa fa-desktop';

    /**
     * @protected
     * @property {Telenok.Core.Interfaces.Eloquent.Object.Model} $widgetModel
     * Model present widget in database.
     * @member Telenok.Core.Interfaces.Widget.Controller
     */
     protected $widgetModel;

    /**
     * @protected
     * @property {String} $backendView
     * Name of view for show properties in backend.
     * @member Telenok.Core.Interfaces.Widget.Controller
     */
     protected $backendView = '';

    /**
     * @public
     * @property {String} $frontendView
     * Name of view for show properties in frontend.
     * @member Telenok.Core.Interfaces.Widget.Controller
     */
     protected $frontendView = '';

    /**
     * @protected
     * @property {String} $defaultFrontendView
     * Name of view for fronend if user dont want to create own view.
     * @member Telenok.Core.Interfaces.Widget.Controller
     */
     protected $defaultFrontendView = 'core::module.web-page-constructor.widget-frontend';

    /**
     * @protected
     * @property {String} $structureView
     * Name of view for show widget's feature and settings.
     * @member Telenok.Core.Interfaces.Widget.Controller
     */
     protected $structureView = '';

    /**
     * @protected
     * @property {Telenok.Core.Interfaces.Controller.Frontend.Controller} $frontendController
     * Frontend controller object.
     * @member Telenok.Core.Interfaces.Widget.Controller
     */	
     protected $frontendController;

    /**
     * @protected
     * @property {Number} $cacheTime
     * Amount of minuts to cache. Can be float to define part of minute.
     * @member Telenok.Core.Interfaces.Widget.Controller
     */	
     protected $cacheTime = 60;

    /**
     * @protected
     * @property {String} $cacheKey
     * Cache key of widget.
     * @member Telenok.Core.Interfaces.Widget.Controller
     */
     protected $cacheKey;

    /**
     * @protected
     * @property {Boolean} $cacheEnabled
     * Enable caching.
     * @member Telenok.Core.Interfaces.Widget.Controller
     */
     protected $cacheEnabled = true;

    /**
     * @protected
     * @property {Array} $config
     * Widget's config.
     * @member Telenok.Core.Interfaces.Widget.Controller
     */
     protected $config = [];

    /**
     * @public
     * @property {Boolean} $timestamps
     * Allow set time when create and update.
     * @member Telenok.Core.Interfaces.Widget.Controller
     */    protected $widgetTemplateDirectory = 'resources/views/widget/';
    /**
     * @protected
     * @property {Boolean} $timestamps
     * Allow set time when create and update.
     * @member Telenok.Core.Interfaces.Widget.Controller
     */    protected $languageDirectory = 'widget';
     
    public function setCacheEnabled($param)
    {
        $this->cacheEnabled = $param;
        
        return $this;
    }

    public function getCacheEnabled()
    {
        return $this->cacheEnabled;
    }
    
	public function getIcon()
	{
		return $this->icon;
	}

    public function getParent()
    {
        return $this->parent;
    }

    public function setConfig($config = [])
    {
		$this->config = $config;

        if ($m = $this->getWidgetModel())
        {
            $this->cacheTime = array_get($m->structure, 'cache_time',$this->cacheTime);
        }

		$this->frontendView = $this->getConfig('frontend_view', $this->getFrontendView());
		$this->cacheKey = $this->getConfig('cache_key', $this->cacheKey);
        $this->cacheTime = $this->getConfig('cache_time', $this->cacheTime);

        return $this;
    }

	public function getConfig($key = null, $default = null)
	{
		if (empty($key))
		{
			return $this->config;
		}
		else
		{
			return array_get($this->config, $key, $default);
		}
	}

    public function setWidgetModel($param)
	{
		$this->widgetModel = $param;
		$this->setCacheTime($param->cache_time);

		return $this;
	}

	public function getWidgetModel()
	{
		return $this->widgetModel;
	}

	public function setCacheTime($param = 0)
	{
		$this->cacheTime = $param;

        ($c = $this->getFrontendController()) ? $c->setCacheTime($param) : '';

		return $this;
	}

	public function getCacheTime()
	{
		return $this->cacheTime;
	}

	public function getCacheKey($additional = '')
	{
        $append = $this->getFrontendView() 
                . "." . config('app.locale', config('app.localeDefault'))
                . "." . $this->getRequest()->fullUrl();
        
        if ($this->cacheKey)
        {
            return $this->cacheKey . $append;
        }
        else if ($m = $this->getWidgetModel())
        {
            return $m->getKey() . $append;
        }
        else
        {
            throw new \Exception($this->LL('Setup cache-key for widget ' . $this->getKey()));
        }

		return false;
	}

	public function getCachedContent()
	{
        if (($k = $this->getCacheKey()) !== false)
        {
            return app('cache')->get($k, false);
        }

		return false;
	}

	public function setCachedContent($content = '')
	{
        if ($this->getCacheEnabled() 
                && ($t = $this->getCacheTime()) 
                && ($k = $this->getCacheKey()) !== false)
        {
            app('cache')->put($k, $content, $t);
        }
        
		return $this;
	}

	public function getContent()
	{
        $this->setCacheTime($this->getCacheTime());

        if (($content = $this->getCachedContent()) !== false)
        {
            return $this->processContent($content);
        }

        $content = $this->getNotCachedContent();
        
        $this->setCachedContent($content);

        return $this->processContent($content);
	}
    
	public function getNotCachedContent()
	{
        return view($this->getFrontendView(), ['controller' => $this])->render();
	}
    
    public function processContent($content = '')
    {
        $content = $this->processContentJsCode($content);
        
        return $content;
    }

    public function processContentJsCode($content = '')
    {
        $jsCode = '';

        $doc = new \DOMDocument();

        @$doc->loadHTML('<?xml version="1.0" encoding="UTF-8"?><html><body>' . $content);

        $scriptNodes = $doc->getElementsByTagName('script');

        for ($i = 0; $i < $scriptNodes->length; $i++)
        {
            $scriptNode = $scriptNodes->item($i);

            if (!$scriptNode->getAttribute('data-skip-moving'))
            {
                $jsCode .= $doc->saveHTML($scriptNode);
            }
        }

        while($scriptNodes->length)
        {
            $scriptNode = $scriptNodes->item(0);
            $scriptNode->parentNode->removeChild($scriptNode);
        }

        app('controllerRequest')->addJsCode($jsCode);

        return mb_substr($doc->saveHTML($doc->getElementsByTagName('body')->item(0)), 6, -7);
    }

	public function getBackendView()
	{
		return $this->backendView ? : "core::module.web-page-constructor.widget-backend";
	}

	public function getFrontendView()
	{
        if ($m = $this->getWidgetModel())
        {
            return 'widget.' . $m->getKey();
        }
        else if ($this->frontendView)
        {
            return $this->frontendView;
        }
        else
        {
            return $this->defaultFrontendView;
        }
	}

	public function getStructureView()
	{
        return $this->structureView ? : "{$this->getPackage()}::widget.{$this->getKey()}.structure";
	}
    
    public function setFrontendController($param = null)
    {
        $this->frontendController = $param;
        
        return $this;
    }
    
    public function getFrontendController()
    {   
        try
        {
            return $this->frontendController ?: app('controllerRequest');
        }
        catch (\Exception $e) {}
    }
    
	public function getTemplateContent()
	{
        if (($p = $this->getFileTemplatePath()) && ($content = file_get_contents($p)))
        {
            return $content;
        }
        else
        {
            try
            {
                return file_get_contents(app('view')->getFinder()->find("{$this->getPackage()}::widget.{$this->getKey()}.widget-frontend"));
            } 
            catch (\Exception $e) {}
        }
	}
    
    public function getFileTemplatePath()
    {
        try
        {
            if ($this->getFrontendView() !== $this->defaultFrontendView)
            {
                return app('view')->getFinder()->find($this->getFrontendView());
            }
        } 
        catch (\Exception $e) {}

        return false;
    }

	public function getInsertContent($id = 0)
	{
		$widgetOnPage = \App\Telenok\Core\Model\Web\WidgetOnPage::findOrFail($id);

		return view($this->getBackendView(), [
                        'header' => $this->LL('header'),
                        'title' => $widgetOnPage->title,
                        'id' => $widgetOnPage->getKey(),
                        'key' => $this->getKey(),
                        'widgetOnPage' => $widgetOnPage,
                    ])->render();
	}   
	
	public function insertFromBufferOnPage($languageId = 0, $pageId = 0, $key = '', $id = 0, $container = '', $order = 0, $bufferId = 0)
	{
		$widgetOnPage = null;
		
		app('db')->transaction(function() use ($languageId, $pageId, $key, $id, $container, $order, &$widgetOnPage, $bufferId)
		{
			$widgetOnPage = \App\Telenok\Core\Model\Web\WidgetOnPage::findOrFail($id);
			$buffer = \App\Telenok\Core\Model\System\Buffer::findOrFail($bufferId);

			if ($buffer->key == 'cut')
			{
				$widgetOnPage->storeOrUpdate([
					"container" => $container,
					"order" => $order,
					"key" => $key,
				]);
				
				$bufferWidget = \App\Telenok\Core\Model\System\Buffer::find($bufferId);
				
				if ($bufferWidget)
				{
					$bufferWidget->forceDelete();
				}
			}
			else if ($buffer->key == 'copy')
			{
				$widgetOnPage = \App\Telenok\Core\Model\Web\WidgetOnPage::findOrFail($id)->replicate();
				$widgetOnPage->push();
				$widgetOnPage->storeOrUpdate([
						"container" => $container,
						"order" => $order,
					]);
			}
			else if ($buffer->key == 'copy-link')
			{
				$originalWidget = $this->findOriginalWidget($id);

				if ($originalWidget->isWidgetLink())
				{
					throw new \Exception($this->LL('error.widget.link.nonexistent'));
				}

				$widgetOnPage = $originalWidget->replicate();
				$widgetOnPage->push();
				$widgetOnPage->storeOrUpdate([
						"container" => $container,
						"order" => $order,
					]);

				$originalWidget->widgetLink()->save($widgetOnPage);
			}

			\App\Telenok\Core\Model\Web\WidgetOnPage::where("widget_order", ">=", $order)
					->where("container", $container)->get()->each(function($item)
			{
				$item->storeOrUpdate(["widget_order" => $item->order + 1]);
			});

			$widgetOnPage->widgetLanguageLanguage()->associate(\App\Telenok\Core\Model\System\Language::findOrFail($languageId));
			$widgetOnPage->widgetPage()->associate(\App\Telenok\Core\Model\Web\Page::findOrFail($pageId));
			$widgetOnPage->save(); 
		});

		return $widgetOnPage;
	}

	public function insertOnPage($languageId = 0, $pageId = 0, $key = '', $id = 0, $container = '', $order = 0)
	{
		$widgetOnPage = null;
		
		try
		{
			app('db')->transaction(function() use ($languageId, $pageId, $key, $id, $container, $order, &$widgetOnPage)
			{
				$widgetOnPage = \App\Telenok\Core\Model\Web\WidgetOnPage::findOrFail($id)
						->storeOrUpdate([
					"title" => $this->LL('header'),
					"container" => $container,
					"widget_order" => $order,
					"key" => $key,
				]);

				\App\Telenok\Core\Model\Web\WidgetOnPage::where("widget_order", ">=", $order)
						->where("container", $container)->get()->each(function($item)
				{
					$item->storeOrUpdate(["widget_order" => $item->order + 1]);
				});

				$widgetOnPage->widgetLanguageLanguage()->associate(\App\Telenok\Core\Model\System\Language::findOrFail($languageId));
				$widgetOnPage->widgetPage()->associate(\App\Telenok\Core\Model\Web\Page::findOrFail($pageId));
				$widgetOnPage->save();
			});
		}
		catch (\Exception $e)
		{
			app('db')->transaction(function() use ($languageId, $pageId, $key, $container, $order, &$widgetOnPage)
			{
				$widgetOnPage = (new \App\Telenok\Core\Model\Web\WidgetOnPage())
						->storeOrUpdate([
					"title" => $this->LL('header'),
					"container" => $container,
					"widget_order" => $order,
					"key" => $key,
				]); 

				\App\Telenok\Core\Model\Web\WidgetOnPage::where("widget_order", ">=", $order)
						->where("container", $container)->get()->each(function($item)
				{
					$item->storeOrUpdate(["widget_order" => $item->order + 1]);
				});

				$widgetOnPage->widgetLanguageLanguage()->associate(\App\Telenok\Core\Model\System\Language::findOrFail($languageId));
				$widgetOnPage->widgetPage()->associate(\App\Telenok\Core\Model\Web\Page::findOrFail($pageId));
				$widgetOnPage->save();
			});
		}

		return $widgetOnPage;
	}

	public function removeFromPage($id = 0)
	{
		\App\Telenok\Core\Model\Web\WidgetOnPage::destroy($id);
	}

	public function getStructureContent($model = null, $uniqueId = null)
	{
        $this->setWidgetModel($model);

		return view($this->getStructureView(), [
					'controller' => $this,
					'model' => $model,
					'uniqueId' => $uniqueId,
				])->render();
	}

	public function findOriginalWidget($id = 0)
	{
		$widget = \App\Telenok\Core\Model\Web\WidgetOnPage::findOrFail($id);
		
		$widgetLink = $widget->widgetLinkWidgetOnPage()->first();
		
		if ($widgetLink)
		{
			return $this->findOriginalWidget($widgetLink->getKey());
		}
		else
		{
			return $widget;
		}
	}

    public function delete($model)
    {
        if ($p = $this->getFileTemplatePath())
        {
            @unlink($p);
        }

        return $this;
    }

	public function validate($model = null, $input = [])
	{
        return $this;
	}

    public function preProcess($model, $type, $input)
    { 
        return $this;
    }

    public function postProcess($model, $type, $input)
    {
        $templateFile = $this->getFileTemplatePath();

        if (!$templateFile)
        {
            $templateFile = base_path($this->widgetTemplateDirectory . $model->getKey() . '.blade.php');
        }

        \File::makeDirectory(dirname(realpath($templateFile)), 0777, true, true);

        file_put_contents($templateFile, $input->get('template_content', $this->getTemplateContent()), LOCK_EX);

        return $this;
    }
}
