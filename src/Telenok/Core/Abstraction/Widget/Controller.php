<?php

namespace Telenok\Core\Abstraction\Widget;
use Telenok\Core\Event\CompileConfig;

/**
 * @class Telenok.Core.Abstraction.Widget.Controller
 * Base controller for widgets.
 *
 * @extends Telenok.Core.Abstraction.Controller.Controller
 */
abstract class Controller extends \Telenok\Core\Abstraction\Controller\Controller {

    /**
     * @protected
     * @property {String} $parent
     * Parent's widget key.
     * @member Telenok.Core.Abstraction.Widget.Controller
     */
    protected $parent = '';

    /**
     * @protected
     * @property {String} $group
     * Key of parent widget group.
     * @member Telenok.Core.Abstraction.Widget.Controller
     */
    protected $group = '';

    /**
     * @protected
     * @property {String} $icon
     * Class of widget's icon.
     * @member Telenok.Core.Abstraction.Widget.Controller
     */
    protected $icon = 'fa fa-desktop';

    /**
     * @protected
     * @property {Telenok.Core.Abstraction.Eloquent.Object.Model} $widgetModel
     * Model present widget in database.
     * @member Telenok.Core.Abstraction.Widget.Controller
     */
    protected $widgetModel;

    /**
     * @protected
     * @property {String} $backendView
     * Name of view for show properties in backend.
     * @member Telenok.Core.Abstraction.Widget.Controller
     */
    protected $backendView = '';

    /**
     * @property {String} $frontendView
     * Name of view for show properties in frontend.
     * @member Telenok.Core.Abstraction.Widget.Controller
     */
    protected $frontendView = '';

    /**
     * @protected
     * @property {String} $defaultFrontendView
     * Name of view for fronend if user dont want to create own view.
     * @member Telenok.Core.Abstraction.Widget.Controller
     */
    protected $defaultFrontendView = 'core::module.web-page-constructor.widget-frontend';

    /**
     * @protected
     * @property {String} $structureView
     * Name of view for show widget's feature and settings.
     * @member Telenok.Core.Abstraction.Widget.Controller
     */
    protected $structureView = '';

    /**
     * @protected
     * @property {Telenok.Core.Abstraction.Controller.Frontend.Controller} $frontendController
     * Frontend controller object.
     * @member Telenok.Core.Abstraction.Widget.Controller
     */
    protected $frontendController;

    /**
     * @protected
     * @property {Number} $cacheTime
     * Amount of minuts to cache. Can be float to define part of minute.
     * @member Telenok.Core.Abstraction.Widget.Controller
     */
    protected $cacheTime = 60;

    /**
     * @protected
     * @property {String} $cacheKey
     * Cache key of widget.
     * @member Telenok.Core.Abstraction.Widget.Controller
     */
    protected $cacheKey;

    /**
     * @protected
     * @property {Boolean} $cacheEnabled
     * Enable caching.
     * @member Telenok.Core.Abstraction.Widget.Controller
     */
    protected $cacheEnabled = true;

    /**
     * @protected
     * @property {Array} $config
     * Widget's config.
     * @member Telenok.Core.Abstraction.Widget.Controller
     */
    protected $config = [];

    /**
     * @protected
     * @property {String} $widgetTemplateDirectory
     * Where store template's changes which user make in backend.
     * @member Telenok.Core.Abstraction.Widget.Controller
     */
    protected $widgetTemplateDirectory = 'resources/views/page_constructor/widget/';

    /**
     * @protected
     * @property {String} $languageDirectory
     * Language directory for widgets.
     * @member Telenok.Core.Abstraction.Widget.Controller
     */
    protected $languageDirectory = 'widget';

    /**
     * @method setCacheEnabled
     * Enable or disable cache.
     * @return {Telenok.Core.Abstraction.Widget.Controller}
     * @member Telenok.Core.Abstraction.Widget.Controller
     */
    public function setCacheEnabled($param)
    {
        $this->cacheEnabled = $param;

        return $this;
    }

    /**
     * @method getCacheEnabled
     * Return whether cache enabled.
     * @return {Boolean}
     * @member Telenok.Core.Abstraction.Widget.Controller
     */
    public function getCacheEnabled()
    {
        return $this->cacheEnabled;
    }

    /**
     * @method getIcon
     * Return icon class.
     * @return {String}
     * @member Telenok.Core.Abstraction.Widget.Controller
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * @method getParent
     * Return parent widget key.
     * @return {String}
     * @member Telenok.Core.Abstraction.Widget.Controller
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @method setConfig
     * Set config for widget.
     * @param {Array} $config
     * @return {Telenok.Core.Abstraction.Widget.Controller}
     * @member Telenok.Core.Abstraction.Widget.Controller
     */
    public function setConfig($config = [])
    {
        $config = collect($config)->all();

        $this->config = array_merge([
            'cache_key'         => array_get($config, 'cache_key', $this->cacheKey),
            'cache_time'        => array_get($config, 'cache_time', $this->cacheTime),
            'frontend_view'     => array_get($config, 'frontend_view', $this->getFrontendView()),
        ], $config);

        if ($c = $this->getCachedConfig())
        {
            $this->config = $c;
        }

        /*
         * We can restore widget config from cache by cache_key, so set object member value manually
         *
         */
        $this->cacheKey     = $this->getConfig('cache_key');
        $this->cacheTime    = $this->getConfig('cache_time');
        $this->frontendView = $this->getConfig('frontend_view');

        return $this;
    }

    /**
     * @method getConfig
     * Return config of widget or value by key from config.
     * @param {String} $key
     * @param {mixed} $default
     * @return {mixed}
     * @member Telenok.Core.Abstraction.Widget.Controller
     */
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

    /**
     * @method getCachedConfig
     * Set widget's config in cache.
     * @return {Telenok.Core.Abstraction.Widget.Controller}
     * @member Telenok.Core.Abstraction.Widget.Controller
     */
    public function getCachedConfig()
    {
        if (!($cacheKey = $this->getConfig('cache_key')))
        {
            throw new \Exception('Please, set in config of widget "' . $this->getKey() . '" parameter "cache_key"');
        }

        return collect(config('telenok.widget.config'))->get($this->getKey() . '.' . $cacheKey, []);
    }

    public function saveCachedConfig()
    {


        // !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
        return;





        if ($this->getWidgetModel())
        {
            return;
        }

        $cacheKey = $this->getConfig('cache_key');

        $config = collect(config('telenok.widget.config'))->get($this->getKey() . '.' . $cacheKey, []);

        $md5Key = md5(serialize($this->getConfig()));

        if ($md5Key == array_get($config, '__md5_key'))
        {
            return;
        }

        $configData = app(\App\Vendor\Telenok\Core\Model\System\Config::class)->where('code', 'telenok.widget.config')->first();

        $widgetConfigs = $configData->value;

        $wc = $this->getConfig();
        $wc['__md5_key'] = $md5Key;
        $wc['__created_at'] = time();

        $widgetConfigs->put($this->getKey() . '.' . $cacheKey, $wc);

        // clear old widgets config
        if (rand(0, 500000) == 1)
        {
            $t = time();

            foreach($widgetConfigs->all() as $k => $c)
            {
                if ($t - $c['__created_at'] > 8640000/* 3 months */)
                {
                    $widgetConfigs->forget($k);
                }
            }
        }

        $configData->storeOrUpdate(['value' => $widgetConfigs]);

        app('events')->fire(new CompileConfig());
    }

    /**
     * @method setWidgetModel
     * Set widget's model.
     * @param {Telenok.Core.Abstraction.Eloquent.Object.Model} $param
     * @return {Telenok.Core.Abstraction.Widget.Controller}
     * @member Telenok.Core.Abstraction.Widget.Controller
     */
    public function setWidgetModel($param)
    {
        $this->widgetModel = $param;
        $this->setCacheTime($param->cache_time);

        return $this;
    }

    /**
     * @method getWidgetModel
     * Return widget's model.
     * @return {Telenok.Core.Abstraction.Eloquent.Object.Model}
     * @member Telenok.Core.Abstraction.Widget.Controller
     */
    public function getWidgetModel()
    {
        return $this->widgetModel;
    }

    /**
     * @method setCacheTime
     * Set cache time of widgetOnPage in minuts. Can be float as part of minute.
     * @param {Number} $param
     * @member Telenok.Core.Abstraction.Widget.Controller
     * @return {Telenok.Core.Abstraction.Widget.Controller}
     */
    public function setCacheTime($param = 0)
    {
        $this->cacheTime = $param;

        ($c = $this->getFrontendController()) ? $c->setCacheTime($param) : '';

        return $this;
    }

    /**
     * @method getCacheTime
     * Return cache time of widgetOnPage.
     * @return {Number}
     * @member Telenok.Core.Abstraction.Widget.Controller
     */
    public function getCacheTime()
    {
        return $this->cacheTime;
    }

    /**
     * @method getCacheKey
     * Return cache key and add to it new part of key.
     * @param {String} $additional
     * Additional part of key.
     * @return {String}
     * @member Telenok.Core.Abstraction.Widget.Controller
     */
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
            throw new \Exception($this->LL('Please, setup in config "cache_key" parameter for widget "' . $this->getKey()) . '"');
        }

        return false;
    }

    /**
     * @method getCachedContent
     * Return cached content.
     * @return {mixed}
     * Can return false if cache not exitst.
     * @member Telenok.Core.Abstraction.Widget.Controller
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
     * @param {String} $content
     * @return {Telenok.Core.Abstraction.Widget.Controller}
     * @member Telenok.Core.Abstraction.Widget.Controller
     */
    public function setCachedContent($content = '')
    {
        if ($this->getCacheEnabled() && ($t = $this->getCacheTime()) && ($k = $this->getCacheKey()) !== false)
        {
            app('cache')->put($k, $content, $t);
        }

        return $this;
    }

    /**
     * @method getContent
     * Return content.
     * @return {String}
     * @member Telenok.Core.Abstraction.Widget.Controller
     */
    public function getContent()
    {
        $this->saveCachedConfig();

        $this->setCacheTime($this->getCacheTime());

        if (($content = $this->getCachedContent()) !== false)
        {
            return $this->processContent($content);
        }

        $content = $this->getNotCachedContent();

        $this->setCachedContent($content);

        return $this->processContent($content);
    }

    /**
     * @method getNotCachedContent
     * Return not cached content.
     * @return {String}
     * @member Telenok.Core.Abstraction.Widget.Controller
     */
    public function getNotCachedContent()
    {
        return view($this->getFrontendView(), ['controller' => $this])->render();
    }

    /**
     * @method processContent
     * Process content before return to frontend controller.
     * @param {String} $content
     * @return {String}
     * @member Telenok.Core.Abstraction.Widget.Controller
     */
    public function processContent($content = '')
    {
        $content = $this->processContentJsCode($content);

        return $content;
    }

    /**
     * @method processContentJsCode
     * Move all javascript tags to end of &lt;body&gt; content.
     * Process javascript content of widget.
     * @param {String} $content
     * @return {String}
     * @member Telenok.Core.Abstraction.Widget.Controller
     */
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

        while ($scriptNodes->length)
        {
            $scriptNode = $scriptNodes->item(0);
            $scriptNode->parentNode->removeChild($scriptNode);
        }

        app('controllerRequest')->addJsCode($jsCode);

        return mb_substr($doc->saveHTML($doc->getElementsByTagName('body')->item(0)), 6, -7);
    }

    /**
     * @method getBackendView
     * Return name of backend view.
     * @return {String}
     * @member Telenok.Core.Abstraction.Widget.Controller
     */
    public function getBackendView()
    {
        return $this->backendView ? : "core::module.web-page-constructor.widget-backend";
    }

    /**
     * @method getFrontendView
     * Return name of frontend view.
     * @return {String}
     * @member Telenok.Core.Abstraction.Widget.Controller
     */
    public function getFrontendView()
    {
        if ($m = $this->getWidgetModel())
        {
            return 'page_constructor.widget.' . $m->getKey();
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

    /**
     * @method getStructureView
     * Return name of structure view. This view show widget's features and settings.
     * @return {String}
     * @member Telenok.Core.Abstraction.Widget.Controller
     */
    public function getStructureView()
    {
        return $this->structureView ? : "{$this->getPackage()}::widget.{$this->getKey()}.structure";
    }

    /**
     * @method setFrontendController
     * Set frontend controller.
     * @param {Telenok.Core.Abstraction.Controller.Frontend.Controller} $param
     * @return {Telenok.Core.Abstraction.Widget.Controller}
     * @member Telenok.Core.Abstraction.Widget.Controller
     */
    public function setFrontendController($param = null)
    {
        $this->frontendController = $param;

        return $this;
    }

    /**
     * @method getFrontendController
     * Return frontend controller.
     * @return {Telenok.Core.Abstraction.Controller.Frontend.Controller}
     * @member Telenok.Core.Abstraction.Widget.Controller
     */
    public function getFrontendController()
    {
        try
        {
            return $this->frontendController ? : app('controllerRequest');
        }
        catch (\Exception $e)
        {

        }
    }

    /**
     * @method getTemplateContent
     * Return content of content's view. Allow user edit template via backend.
     * @return {String}
     * @member Telenok.Core.Abstraction.Widget.Controller
     */
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
            catch (\Exception $e)
            {

            }
        }
    }

    /**
     * @method getFileTemplatePath
     * Return path to widget's frontend view.
     * @return {String}
     * @member Telenok.Core.Abstraction.Widget.Controller
     */
    public function getFileTemplatePath()
    {
        try
        {
            if ($this->getFrontendView() !== $this->defaultFrontendView)
            {
                return app('view')->getFinder()->find($this->getFrontendView());
            }
        }
        catch (\Exception $e)
        {

        }

        return false;
    }

    /**
     * @method getInsertContent
     * Return content of WidgetOnPage for modal window.
     * @param {Integer} $id
     * @return {String}
     * @member Telenok.Core.Abstraction.Widget.Controller
     */
    public function getInsertContent($id = 0)
    {
        $widgetOnPage = \App\Vendor\Telenok\Core\Model\Web\WidgetOnPage::findOrFail($id);

        return view($this->getBackendView(), [
                    'header' => $this->LL('header'),
                    'title' => $widgetOnPage->title,
                    'id' => $widgetOnPage->getKey(),
                    'key' => $this->getKey(),
                    'widgetOnPage' => $widgetOnPage,
                ])->render();
    }

    /**
     * @method insertFromBufferOnPage
     * Cut from page and insert widgetOnPage in other place of containers.
     * @param {Integer} $languageId
     * Language Id of page where insert widgetOnPage.
     * @param {Integer} $pageId
     * Page id where inserted widgetOnPage.
     * @param {String} $key
     * @param {Integer} $id
     * Id of moved widgetOnPage.
     * @param {String} $container
     * Container dom id.
     * @param {Integer} $order
     * Order of moved widgetOnPage.
     * @param {Integer} $bufferId
     * Id of moved widgetOnPage on buffer.
     * @return {Telenok.Core.Model.Web.WidgetOnPage}
     * @member Telenok.Core.Abstraction.Widget.Controller
     */
    public function insertFromBufferOnPage($languageId = 0, $pageId = 0, $key = '', $id = 0, $container = '', $order = 0, $bufferId = 0)
    {
        $widgetOnPage = null;

        app('db')->transaction(function() use ($languageId, $pageId, $key, $id, $container, $order, &$widgetOnPage, $bufferId)
        {
            $widgetOnPage = \App\Vendor\Telenok\Core\Model\Web\WidgetOnPage::findOrFail($id);
            $buffer = \App\Vendor\Telenok\Core\Model\System\Buffer::findOrFail($bufferId);

            if ($buffer->key == 'cut')
            {
                $widgetOnPage->storeOrUpdate([
                    "container" => $container,
                    "order" => $order,
                    "key" => $key,
                ]);

                $bufferWidget = \App\Vendor\Telenok\Core\Model\System\Buffer::find($bufferId);

                if ($bufferWidget)
                {
                    $bufferWidget->forceDelete();
                }
            }
            else if ($buffer->key == 'copy')
            {
                $widgetOnPage = \App\Vendor\Telenok\Core\Model\Web\WidgetOnPage::findOrFail($id)->replicate();
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

            \App\Vendor\Telenok\Core\Model\Web\WidgetOnPage::where("widget_order", ">=", $order)
                    ->where("container", $container)->get()->each(function($item)
            {
                $item->storeOrUpdate(["widget_order" => $item->order + 1]);
            });

            $widgetOnPage->widgetLanguageLanguage()->associate(\App\Vendor\Telenok\Core\Model\System\Language::findOrFail($languageId));
            $widgetOnPage->widgetPage()->associate(\App\Vendor\Telenok\Core\Model\Web\Page::findOrFail($pageId));
            $widgetOnPage->save();
        });

        return $widgetOnPage;
    }

    /**
     * @method insertOnPage
     * Insert widgetOnPage in page.
     * @param {Integer} $languageId
     * Id of language.
     * @param {Integer} $pageId
     * Id of web page.
     * @param {String} $key
     * @param {String} $id
     * @param {String} $container
     * @param {Integer} $order
     * @return {Telenok.Core.Model.Web.WidgetOnPage}
     * @member Telenok.Core.Abstraction.Widget.Controller
     */
    public function insertOnPage($languageId = 0, $pageId = 0, $key = '', $id = 0, $container = '', $order = 0)
    {
        $widgetOnPage = null;

        try
        {
            app('db')->transaction(function() use ($languageId, $pageId, $key, $id, $container, $order, &$widgetOnPage)
            {
                $widgetOnPage = \App\Vendor\Telenok\Core\Model\Web\WidgetOnPage::findOrFail($id)
                    ->storeOrUpdate([
                        "title" => $this->LL('header'),
                        "container" => $container,
                        "widget_order" => $order,
                        "key" => $key,
                    ]);

                \App\Vendor\Telenok\Core\Model\Web\WidgetOnPage::where("widget_order", ">=", $order)
                        ->where("container", $container)->get()->each(function($item)
                {
                    $item->storeOrUpdate(["widget_order" => $item->order + 1]);
                });

                $widgetOnPage->widgetLanguageLanguage()->associate(\App\Vendor\Telenok\Core\Model\System\Language::findOrFail($languageId));
                $widgetOnPage->widgetPage()->associate(\App\Vendor\Telenok\Core\Model\Web\Page::findOrFail($pageId));
                $widgetOnPage->save();
            });
        }
        catch (\Exception $e)
        {
            app('db')->transaction(function() use ($languageId, $pageId, $key, $container, $order, &$widgetOnPage)
            {
                $widgetOnPage = (new \App\Vendor\Telenok\Core\Model\Web\WidgetOnPage())
                    ->storeOrUpdate([
                        "title" => $this->LL('header'),
                        "container" => $container,
                        "widget_order" => $order,
                        "key" => $key,
                    ]);

                \App\Vendor\Telenok\Core\Model\Web\WidgetOnPage::where("widget_order", ">=", $order)
                        ->where("container", $container)->get()->each(function($item)
                {
                    $item->storeOrUpdate(["widget_order" => $item->order + 1]);
                });

                $widgetOnPage->widgetLanguageLanguage()->associate(\App\Vendor\Telenok\Core\Model\System\Language::findOrFail($languageId));
                $widgetOnPage->widgetPage()->associate(\App\Vendor\Telenok\Core\Model\Web\Page::findOrFail($pageId));
                $widgetOnPage->save();
            });
        }

        return $widgetOnPage;
    }

    /**
     * @method removeFromPage
     * Remove widgetOnPage by id.
     * @param {Integer} $id
     * Id of widgetOnPage.
     * @return {void}
     * @member Telenok.Core.Abstraction.Widget.Controller
     */
    public function removeFromPage($id = 0)
    {
        \App\Vendor\Telenok\Core\Model\Web\WidgetOnPage::destroy($id);
    }

    /**
     * @method getStructureContent
     * Return content of widget's structure. Eg return view of settings etc.
     * @param {Telenok.Core.Abstraction.Eloquent.Object.Model} $model
     * @param {String} $uniqueId
     * Unique id from html code.
     * @return {void}
     * @member Telenok.Core.Abstraction.Widget.Controller
     */
    public function getStructureContent($model = null, $uniqueId = null)
    {
        $this->setWidgetModel($model);

        return view($this->getStructureView(), [
                    'controller' => $this,
                    'model' => $model,
                    'uniqueId' => $uniqueId,
                ])->render();
    }

    /**
     * @method findOriginalWidget
     * Search original widget if current has type "widget-link".
     * @param {Integer} $id
     * Id of current widget.
     * @return {Telenok.Core.Model.Web.WidgetOnPage}
     * @member Telenok.Core.Abstraction.Widget.Controller
     */
    public function findOriginalWidget($id = 0)
    {
        $widget = \App\Vendor\Telenok\Core\Model\Web\WidgetOnPage::findOrFail($id);

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

    /**
     * @method delete
     * Remove widgetOnPage.
     * @param {Telenok.Core.Model.Web.WidgetOnPage} $model
     * @return {Telenok.Core.Abstraction.Widget.Controller}
     * @member Telenok.Core.Abstraction.Widget.Controller
     */
    public function delete($model)
    {
        if ($p = $this->getFileTemplatePath())
        {
            @unlink($p);
        }

        return $this;
    }

    /**
     * @method validate
     * validate structure data before saving.
     * @param {Telenok.Core.Model.Web.WidgetOnPage} $model
     * @param {Illuminate.Support.Collection} $input
     * @return {Telenok.Core.Abstraction.Widget.Controller}
     * @member Telenok.Core.Abstraction.Widget.Controller
     */
    public function validate($model = null, $input = [])
    {
        return $this;
    }

    /**
     * @method preProcess
     * Hook called before saving widget.
     * @param {Telenok.Core.Model.Web.WidgetOnPage} $model
     * @param {Telenok.Core.Model.Object.Type} $type
     * @param {Illuminate.Support.Collection} $input
     * @return {Telenok.Core.Abstraction.Widget.Controller}
     * @member Telenok.Core.Abstraction.Widget.Controller
     */
    public function preProcess($model, $type, $input)
    {
        return $this;
    }

    /**
     * @method postProcess
     * Hook called after saving widget.
     * @param {Telenok.Core.Model.Web.WidgetOnPage} $model
     * @param {Telenok.Core.Model.Object.Type} $type
     * @param {Illuminate.Support.Collection} $input
     * @return {Telenok.Core.Abstraction.Widget.Controller}
     * @member Telenok.Core.Abstraction.Widget.Controller
     */
    public function postProcess($model, $type, $input)
    {
        $templateFile = $this->getFileTemplatePath();

        if (!$templateFile)
        {
            $templateFile = base_path($this->widgetTemplateDirectory . $model->getKey() . '.blade.php');
        }

        \File::makeDirectory(dirname($templateFile), 0775, true, true);

        file_put_contents($templateFile, $input->get('template_content', $this->getTemplateContent()));

        return $this;
    }

}
