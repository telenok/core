<?php namespace Telenok\Core\Abstraction\Controller\Frontend;

/**
 * @class Telenok.Core.Abstraction.Controller.Frontend.Controller
 * Class to display and process frontend data.
 * 
 * @extends Telenok.Core.Abstraction.Controller.Controller
 */
abstract class Controller extends \Telenok\Core\Abstraction\Controller\Controller {
    
    /**
     * @protected
     * @property {Array} $container
     * List with DOM IDs of containers from HTML container's template.
     * @member Telenok.Core.Abstraction.Controller.Frontend.Controller
     */
    protected $container = [];
    
    /**
     * @protected
     * @property {Array} $jsFilePath
     * List with DOM IDs of containers from HTML container's template.
     * @member Telenok.Core.Abstraction.Controller.Frontend.Controller
     */
    protected $jsFilePath = [];
    
    /**
     * @protected
     * @property {Array} $cssFilePath
     * Accumulate CSS files.
     * @member Telenok.Core.Abstraction.Controller.Frontend.Controller
     */
    protected $cssFilePath = [];
    
    /**
     * @protected
     * @property {Array} $cssCode
     * Accumulate CSS code.
     * @member Telenok.Core.Abstraction.Controller.Frontend.Controller
     */
    protected $cssCode = [];
    
    /**
     * @protected
     * @property {Array} $jsCode
     * Accumulate JS code.
     * @member Telenok.Core.Abstraction.Controller.Frontend.Controller
     */
    protected $jsCode = [];
    
    /**
     * @protected
     * @property {Number} $cacheTime
     * Cache time in minuts for page. Can be float to set as part's of minute.
     * @member Telenok.Core.Abstraction.Controller.Frontend.Controller
     */
    protected $cacheTime = 60;
    
    /**
     * @protected
     * @property {String} $cacheKey
     * Cache key for caching page.
     * @member Telenok.Core.Abstraction.Controller.Frontend.Controller
     */
    protected $cacheKey = 'frontend-controller';

    /**
     * @protected
     * @property {String} $frontendView
     * Default view for frontend.
     * @member Telenok.Core.Abstraction.Controller.Frontend.Controller
     */
    protected $frontendView = 'core::controller.frontend';

    /**
     * @protected
     * @property {String} $backendView
     * Default view for backend to show containers with widgets.
     * @member Telenok.Core.Abstraction.Controller.Frontend.Controller
     */
    protected $backendView = 'core::controller.frontend-container';
    
    /**
     * @protected
     * @property {Array} $languageDirectory
     * Define directory with translated files.
     * @member Telenok.Core.Abstraction.Controller.Frontend.Controller
     */
    protected $languageDirectory = 'controller';
    
    /**
     * @protected
     * @property {String} $pageMetaTitle
     * Title in meta tag of page.
     * @member Telenok.Core.Abstraction.Controller.Frontend.Controller
     */
    protected $pageMetaTitle;
    
    /**
     * @protected
     * @property {String} $pageMetaDescription
     * Description in meta tag of page.
     * @member Telenok.Core.Abstraction.Controller.Frontend.Controller
     */
    protected $pageMetaDescription;
    
    /**
     * @protected
     * @property {String} $pageMetaKeywords
     * Key words in meta tag of page.
     * @member Telenok.Core.Abstraction.Controller.Frontend.Controller
     */
    protected $pageMetaKeywords;

    /**
     * @method setCacheTime
     * Set cache time in minuts for page. Can be float to set as part's of minute.
     * 
     * @param {Number} $param
     * Time in minuts or float as part of minute.
     * @return {Telenok.Core.Abstraction.Controller.Frontend.Controller}
     * @member Telenok.Core.Abstraction.Controller.Frontend.Controller
     */
    public function setCacheTime($param = 0)
    {
        $this->cacheTime = min($this->getCacheTime(), $param);

        return $this;
    }

    /**
     * @method getCacheTime
     * Return $cacheTime.
     * 
     * @return {Number}
     * @member Telenok.Core.Abstraction.Controller.Frontend.Controller
     */
    public function getCacheTime()
    {
        return $this->cacheTime;
    }
    
    /**
     * @method getContainerContent
     * Return HTML content of container.
     * 
     * @param {Integer} $pageId
     * ID of eloquent model of page.
     * @param {Integer} $languageId
     * ID of eloquent model of language.
     * @return {String}
     * @member Telenok.Core.Abstraction.Controller.Frontend.Controller
     */
    public function getContainerContent($pageId = 0, $languageId = 0)
    {
        $content = ['controller' => $this];

        $wop = \App\Vendor\Telenok\Core\Model\Web\WidgetOnPage::where('widget_page', $pageId)->whereHas('widgetLanguageLanguage', function($query) use ($languageId)
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

    /**
     * @method getContiner
     * Return IDs of DOM container's elements in $backendView.
     * 
     * @return {Array}
     * @member Telenok.Core.Abstraction.Controller.Frontend.Controller
     */
    public function getContiner()
    {
        return $this->container;
    }

    /**
     * @method getBackendView
     * Return value of $backendView.
     * 
     * @return {String}
     * @member Telenok.Core.Abstraction.Controller.Frontend.Controller
     */
    public function getBackendView()
    {
        return $this->backendView;
    }

    /**
     * @method setBackendView
     * Set value of $backendView.
     * 
     * @return {Telenok.Core.Abstraction.Controller.Frontend.Controller}
     * @member Telenok.Core.Abstraction.Controller.Frontend.Controller
     */
    public function setBackendView($param = '')
    {
        $this->backendView = $param;

        return $this;
    }

    /**
     * @method getFrontendView
     * Return value of frontendView.
     * 
     * @return {String}
     * @member Telenok.Core.Abstraction.Controller.Frontend.Controller
     */
    public function getFrontendView()
    {
        return $this->frontendView;
    }

    /**
     * @method setFrontendView
     * Set value of frontendView.
     * 
     * @param {String} $frontendView
     * View of fronend.
     * @return {Telenok.Core.Abstraction.Controller.Frontend.Controller}
     * @member Telenok.Core.Abstraction.Controller.Frontend.Controller
     */
    public function setFrontendView($frontendView = '')
    {
        $this->frontendView = $frontendView;

        return $this;
    }

    /**
     * @method getContent
     * Return HTML for current request.
     * 
     * @return {String}
     * @member Telenok.Core.Abstraction.Controller.Frontend.Controller
     */
    public function getContent()
    {
        $routerName = app('router')->currentRouteName();

        try
        {
            $pageModel = app(\App\Vendor\Telenok\Core\Model\Web\Page::class);

            $page = $pageModel->active()->withPermission()
                ->where(function($query) use ($pageModel, $routerName)
                {
                    $query->where($pageModel->getTable() . '.id', intval(str_replace('page_', '', $routerName)));
                    $query->orWhere($pageModel->getTable() . '.router_name', $routerName);
                })->cacheTags($routerName)->firstOrFail();
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

    /**
     * @method getNotCachedContent
     * Return not cached HTML for page.
     * 
     * @param {Telenok.Core.Model.Web.Page} $page
     * @return {String}
     * @member Telenok.Core.Abstraction.Controller.Frontend.Controller
     */
    public function getNotCachedContent($page)
    {
        $content = [];

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

    /**
     * @method processContent
     * Additionally process content. Here we can process tags like "script" and move them
     * 
     * @param {String} $content
     * @return {String}
     * @member Telenok.Core.Abstraction.Controller.Frontend.Controller
     */
    public function processContent($content = '')
    {
        return $content;
    }

    /**
     * @method getCacheKey
     * Return cache key for page.
     * 
     * @return {String}
     * @member Telenok.Core.Abstraction.Controller.Frontend.Controller
     */
    public function getCacheKey()
    {
        return $this->cacheKey ? $this->cacheKey . $this->getFrontendView()
                . "." . config('app.locale', config('app.localeDefault'))
                . "." . $this->getRequest()->fullUrl() : false;
    }

    /**
     * @method getCachedContent
     * Return cached content.
     * 
     * @return {String}
     * @member Telenok.Core.Abstraction.Controller.Frontend.Controller
     */
    public function getCachedContent()
    {
        if (($k = $this->getCacheKey()) !== false)
        {
            return app('cache')->get($k, false);
        }

        return false;
    }

    /**
     * @method setCachedContent
     * Set cached content.
     * 
     * @param {String} $content
     * @return {Telenok.Core.Abstraction.Controller.Frontend.Controller}
     * @member Telenok.Core.Abstraction.Controller.Frontend.Controller
     */
    public function setCachedContent($content = '')
    {
        if (($t = $this->getCacheTime()) && ($k = $this->getCacheKey()) !== false)
        {
            app('cache')->put($k, $content, $t);
        }

        return $this;
    }

    /**
     * @method validateSession
     * Validate session.
     * 
     * @return {void}
     * @member Telenok.Core.Abstraction.Controller.Frontend.Controller
     */
    public function validateSession()
    {
        return ['logined' => (int) app('auth')->check(), 'csrf_token' => csrf_token()];
    }

    /**
     * @method hasAddedCssFile
     * Search CSS file added already to $cssFilePath.
     * 
     * @param {String} $filePath
     * File path.
     * @param {mixed} $key
     * Key for the file.
     * @member Telenok.Core.Abstraction.Controller.Frontend.Controller
     */
    public function hasAddedCssFile($filePath = '', $key = '')
    {
        foreach ($this->cssFilePath as $k => $p)
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

    /**
     * @method addCssFile
     * Add CSS file to $cssFilePath.
     * 
     * @param {String} $filePath
     * File path.
     * @param {mixed} $key
     * Key for the file.
     * @param {Integer} $order
     * Order of file in array.
     * @member Telenok.Core.Abstraction.Controller.Frontend.Controller
     */
    public function addCssFile($filePath, $key = '', $order = 1000000)
    {
        if (!$this->hasAddedCssFile($filePath, $key))
        {
            if (is_array($key))
            {
                $key = implode(".", $key);
            }

            $this->cssFilePath[($key ? : $filePath)] = ['file' => $filePath, 'order' => $order];
        }

        return $this;
    }

    /**
     * @method addCssCode
     * Add CSS code to $cssCode.
     * 
     * @param {String} $code
     * CSS code.
     * @member Telenok.Core.Abstraction.Controller.Frontend.Controller
     */
    public function addCssCode($code)
    {
        $this->cssCode[] = $code;

        return $this;
    }

    /**
     * @method hasAddedJsFile
     * Search JS file added already to $jsFilePath.
     * 
     * @param {String} $filePath
     * File path.
     * @param {mixed} $key
     * Key for the file.
     * @member Telenok.Core.Abstraction.Controller.Frontend.Controller
     */
    public function hasAddedJsFile($filePath = '', $key = '')
    {
        foreach ($this->jsFilePath as $k => $p)
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

    /**
     * @method addJsFile
     * Add JS file to $jsFilePath.
     * 
     * @param {String} $filePath
     * File path.
     * @param {mixed} $key
     * Key for the file.
     * @param {Integer} $order
     * Order of file in array.
     * @member Telenok.Core.Abstraction.Controller.Frontend.Controller
     */
    public function addJsFile($filePath, $key = '', $order = 100000)
    {
        if (!$this->hasAddedJsFile($filePath, $key))
        {
            if (is_array($key))
            {
                $key = implode(".", $key);
            }

            $this->jsFilePath[($key ? : $filePath)] = ['file' => $filePath, 'order' => $order];
        }

        return $this;
    }

    /**
     * @method addJsCode
     * Add JS code to $jsCode.
     * 
     * @param {String} $code
     * CSS code.
     * @member Telenok.Core.Abstraction.Controller.Frontend.Controller
     */
    public function addJsCode($code)
    {
        $this->jsCode[] = $code;

        return $this;
    }

    /**
     * @method getJsFile
     * List of JS files.
     * 
     * @return {Array}
     * @member Telenok.Core.Abstraction.Controller.Frontend.Controller
     */
    public function getJsFile()
    {
        usort($this->jsFilePath, function($a, $b)
        {
            return $a['order'] < $b['order'] ? -1 : 1;
        });

        return $this->jsFilePath;
    }

    /**
     * @method getJsCode
     * List of JS codes.
     * 
     * @return {Array}
     * @member Telenok.Core.Abstraction.Controller.Frontend.Controller
     */
    public function getJsCode()
    {
        return $this->jsCode;
    }

    /**
     * @method getCssFile
     * List of CSS files.
     * 
     * @return {Array}
     * @member Telenok.Core.Abstraction.Controller.Frontend.Controller
     */
    public function getCssFile()
    {
        usort($this->cssFilePath, function($a, $b)
        {
            return $a['order'] < $b['order'] ? -1 : 1;
        });

        return $this->cssFilePath;
    }

    /**
     * @method getCssCode
     * List of CSS codes.
     * 
     * @return {Array}
     * @member Telenok.Core.Abstraction.Controller.Frontend.Controller
     */
    public function getCssCode()
    {
        return $this->cssCode;
    }

    /**
     * @method getName
     * Get name of controller.
     * 
     * @return {String}
     * @member Telenok.Core.Abstraction.Controller.Frontend.Controller
     */
    public function getName()
    {
        return $this->LL('name');
    }

    /**
     * @method getKey
     * Return key of controller.
     * @return {String}
     * @member Telenok.Core.Abstraction.Controller.Frontend.Controller
     */
    public function getKey()
    {
        return '';
    }

    /**
     * @method setPageMetaTitle
     * Set page meta title from any place of code.
     * 
     * If any view you can set page title in next way:
     * 
     *     @example
     *     $controllerRequest->setPageMetaTitle($news->translate('title'))
     * 
     * @param {String} $title
     * @return {Telenok.Core.Abstraction.Controller.Frontend.Controller}
     * @member Telenok.Core.Abstraction.Controller.Frontend.Controller
     */
    public function setPageMetaTitle($title)
    {
        $this->pageMetaTitle = $title;

        return $this;
    }

    /**
     * @method getPageMetaTitle
     * Return meta title of page.
     * 
     * @return {String}
     * @member Telenok.Core.Abstraction.Controller.Frontend.Controller
     */
    public function getPageMetaTitle()
    {
        return $this->pageMetaTitle;
    }

    /**
     * @method setPageMetaDescription
     * Set page meta description from any place of code.
     * 
     * If any view you can set page description in next way:
     * 
     *     @example
     *     $controllerRequest->setPageMetaDescription($news->translate('content'))
     * 
     * @return {Telenok.Core.Abstraction.Controller.Frontend.Controller}
     * @member Telenok.Core.Abstraction.Controller.Frontend.Controller
     */
    public function setPageMetaDescription($param)
    {
        $this->pageMetaDescription = $param;

        return $this;
    }

    /**
     * @method getPageMetaDescription
     * Return meta description of page.
     * 
     * @return {String}
     * @member Telenok.Core.Abstraction.Controller.Frontend.Controller
     */
    public function getPageMetaDescription()
    {
        return $this->pageMetaDescription;
    }

    /**
     * @method setPageMetaKeywords
     * Set page meta keywords from any place of code.
     * 
     * If any view you can set page keywords in next way:
     * 
     *     @example
     *     $controllerRequest->setPageMetaKeywords($news->translate('content'))
     * 
     * @return {Telenok.Core.Abstraction.Controller.Frontend.Controller}
     * @member Telenok.Core.Abstraction.Controller.Frontend.Controller
     */
    public function setPageMetaKeywords($param)
    {
        $this->pageMetaKeywords = $param;

        return $this;
    }

    /**
     * @method getPageMetaKeywords
     * Return meta keywords of page.
     * 
     * @return {String}
     * @member Telenok.Core.Abstraction.Controller.Frontend.Controller
     */
    public function getPageMetaKeywords()
    {
        return $this->pageMetaKeywords;
    }
}