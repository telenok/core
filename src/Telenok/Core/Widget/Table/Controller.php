<?php namespace Telenok\Core\Widget\Table;

/**
 * @class Telenok.Core.Widget.Table.Controller
 * Class presents table widget.
 * 
 * @extends Telenok.Core.Interfaces.Widget.Controller
 */
class Controller extends \App\Telenok\Core\Interfaces\Widget\Controller {

    /**
     * @protected
     * @property {String} $key
     * Key of widget.
     * @member Telenok.Core.Widget.Table.Controller
     */
    protected $key = 'table';

    /**
     * @protected
     * @property {String} $parent
     * Parent's widget key.
     * @member Telenok.Core.Widget.Table.Controller
     */
    protected $parent = 'standart';
    
    /**
     * @protected
     * @property {String} $backendView
     * Name of view for show properties in backend.
     * @member Telenok.Core.Widget.Table.Controller
     */
    protected $backendView = "core::widget.table.widget-backend";
    
    /**
     * @protected
     * @property {String} $defaultFrontendView
     * Name of view for fronend if user dont want to create own view.
     * @member Telenok.Core.Widget.Table.Controller
     */
    protected $defaultFrontendView = "core::widget.table.widget-frontend";
    
    /**
     * @protected
     * @property {Integer} $row
     * Amount of rows in table.
     * @member Telenok.Core.Widget.Table.Controller
     */
    protected $row = 2;
    
    /**
     * @protected
     * @property {Integer} $col
     * Amount of columns in table.
     * @member Telenok.Core.Widget.Table.Controller
     */
    protected $col = 2;
    
    /**
     * @protected
     * @property {Array} $containerIds
     * List of containers dom's id. Container can be &lt;div&gt; with custom id attribute.
     * @member Telenok.Core.Widget.Table.Controller
     */
    protected $containerIds = [];

    /**
     * @method setConfig
     * Set config for widget.
     * @param {Array} $config
     * Config array.
     * @return {Telenok.Core.Widget.Table.Controller}
     * @member Telenok.Core.Widget.Table.Controller
     */
    public function setConfig($config = [])
    {
        parent::setConfig($config);
        
        if ($m = $this->getWidgetModel())
        {
            $structure = $m->structure;

            $this->row = array_get($structure, 'row');
            $this->col = array_get($structure, 'col');
            $this->containerIds = array_get($structure, 'containerIds');
        }
        else 
        {
            $this->row = $this->getConfig('row', $this->row);
            $this->col = $this->getConfig('col', $this->col);
            $this->containerIds = $this->getConfig('containerIds', $this->containerIds);
        }

        return $this;
    }
    
    /**
     * @method getNotCachedContent
     * Return not cached content of widget.
     * @return {String}
     * @member Telenok.Core.Widget.Table.Controller
     */
	public function getNotCachedContent()
	{ 
        $containerIds = $this->containerIds;

        $rows = [];

        for ($r = 0; $r < $this->row; $r++)
        {
            for ($c = 0; $c < $this->col; $c++)
            {
                $container_id = $containerIds["$r:$c"];

                $rows[$r][$c] = ['container_id' => $containerIds["$r:$c"], 'content' => $this->getContainerContent($container_id)];
            }
        }

        return view($this->getFrontendView(), [
                            'widget' => $this->getWidgetModel(),
                            'id' => $this->getWidgetModel()->getKey(),
                            'key' => $this->getKey(),
                            'rows' => $rows,
                            'controller' => $this,
                        ])->render();
	}

    /**
     * @method getContainerContent
     * Return content of one container by its dom id.
     * @param {String} $container_id
     * Container name.
     * @return {String}
     * @member Telenok.Core.Widget.Table.Controller
     */
	public function getContainerContent($container_id = "")
	{
		$content = [];

		$wop = \App\Telenok\Core\Model\Web\WidgetOnPage::where('container', $container_id)->active()->orderBy('widget_order')->get();

		$widgetConfig = app('telenok.config.repository')->getWidget();

		$wop->each(function($w) use (&$content, $widgetConfig)
		{
            $content[] = $widgetConfig->get($w->key)
                            ->setWidgetModel($w)
                            ->setFrontendController($this)
                            ->setConfig($w->structure)
                            ->getContent();
		});

		return $content;
	}

    /**
     * @method getInsertContent
     * Return content of widgetOnPage to show it in modal window in backend.
     * @param {Integer} $id
     * Id of WidgetOnPage model.
     * @return {String}
     * @member Telenok.Core.Widget.Table.Controller
     */
     public function getInsertContent($id = 0)
	{
		$widgetOnPage = \App\Telenok\Core\Model\Web\WidgetOnPage::findOrFail($id);

		if ($widgetOnPage->isWidgetLink())
		{
			$this->backendView = "core::module.web-page-constructor.widget-backend";

			return parent::getInsertContent($id);
		}

		$structure = $widgetOnPage->structure; 

		if (!$structure->has('row'))
		{
			$structure->put('row', $this->row);
		}

		if (!$structure->has('col'))
		{
			$structure->put('col', $this->col);
		}

		if (!$structure->has('containerIds') || (count($structure->get('containerIds', [])) < $structure->get('col') * $structure->get('row') ))
		{
			$ids = [];

			for ($row = 0; $row < $structure->get('row'); $row++)
			{
				for ($col = 0; $col < $structure->get('col'); $col++)
				{
					$ids["$row:$col"] = isset($ids["$row:$col"]) ? $ids["$row:$col"] : 'container-' . $widgetOnPage->id . '-' . $row . '-' . $col;
				}
			}

			$structure->put('containerIds', $ids);
			$widgetOnPage->structure = $structure->all();
			$widgetOnPage->save();
		}

		$rows = [];
		$containerIds = $structure->get('containerIds');

		for ($r = 0; $r < $structure->get('row'); $r++)
		{
			for ($c = 0; $c < $structure->get('col'); $c++)
			{
				$container_id = $containerIds["$r:$c"];

				$rows[$r][$c] = ['container_id' => $containerIds["$r:$c"], 'content' => $this->getContainerInsertContent($container_id)];
			}
		}

		return view($this->getBackendView(), [
                            'header' => $this->LL('header'),
                            'title' => $widgetOnPage->title,
                            'id' => $widgetOnPage->getKey(),
                            'key' => $this->getKey(),
                            'rows' => $rows,
                            'widgetOnPage' => $widgetOnPage,
                        ])->render();
	}

    /**
     * @method getContainerInsertContent
     * Return content of one container by its dom id in backend.
     * @param {String} $container_id
     * Container name.
     * @return {String}
     * @member Telenok.Core.Widget.Table.Controller
     */
     public function getContainerInsertContent($container_id = "")
	{
		$content = [];

		$wop = \App\Telenok\Core\Model\Web\WidgetOnPage::where('container', $container_id)->orderBy('widget_order')->get();

		$widgetConfig = app('telenok.config.repository')->getWidget();

		$wop->each(function($w) use (&$content, $widgetConfig)
		{
            $content[$w->container] = $widgetConfig->get($w->key)->getInsertContent($w->getKey());
		});

		return $content;
	}
    
    /**
     * @method setCacheTime
     * Set cache time of widgetOnPage in minuts. Can be float as part of minute.
     * @param {Number} $param
     * @return {Telenok.Core.Widget.Table.Controller}
     * @member Telenok.Core.Widget.Table.Controller
     */
     public function setCacheTime($param = 0)
	{
		$this->cacheTime = min($this->getCacheTime(), $param);
        
		return $this;
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
     * @member Telenok.Core.Widget.Table.Controller
     */
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
					"widget_order" => $order,
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
						"widget_order" => $order,
					]); 
			}
			else if ($buffer->key == 'copy-link')
			{
				try
				{
					$originalWidget = $this->findOriginalWidget($id);

					if ($originalWidget->isWidgetLink())
					{
						throw new \Exception();
					}
				}
				catch (\Exception $e)
				{
					throw new \Exception($this->LL('rror.widget.link.nonexistent'));
				}

				$widgetOnPage = $originalWidget->replicate();
				$widgetOnPage->push();
				$widgetOnPage->storeOrUpdate([
						"container" => $container,
						"widget_order" => $order,
					]);

				$originalWidget->widgetLink()->save($widgetOnPage);
			}

			\App\Telenok\Core\Model\Web\WidgetOnPage::where("widget_order", ">=", $order)
					->where("container", $container)->get()->each(function($item)
			{
				$item->storeOrUpdate(["widget_order" => $item->order + 1]);
			});

			$widgetOnPage->widgetPage()->associate(\App\Telenok\Core\Model\Web\Page::findOrFail($pageId))->save(); 
			$widgetOnPage->widgetLanguageLanguage()->associate(\App\Telenok\Core\Model\System\Language::findOrFail($languageId))->save(); 
			$widgetOnPage->save();

			if ($buffer->key == 'cut' || $buffer->key == 'copy')
			{
				$this->copyAndInsertChild($widgetOnPage, $buffer);
			}
		});

		return $widgetOnPage;
	}

    /**
     * @method copyAndInsertChild
     * Copy widgetOnPage in other place of containers via buffer.
     * @param {Telenok.Core.Model.Web.WidgetOnPage} $widgetOnPage
     * @param {Telenok.Core.Model.System.Buffer} $buffer
     * @return {void}
     * @member Telenok.Core.Widget.Table.Controller
     */
     public function copyAndInsertChild($widgetOnPage, $buffer)
	{  
		$structure = $widgetOnPage->structure; 

		$newContainres = [];

		foreach($structure->get('containerIds') as $key => $container)
		{ 
			$newContainres[$key] = preg_replace('/(container-)(\d+)(-\d+-\d+)/', "\${1}" . $widgetOnPage->id . "\${3}", $container);

			\App\Telenok\Core\Model\Web\WidgetOnPage::where("container", $container)->get()->each(function($item) use ($widgetOnPage, $buffer, $newContainres, $key)
			{
				$buffer = \App\Telenok\Core\Model\System\Buffer::addBuffer(app('auth')->user()->getKey(), $item->getKey(), 'web-page', $buffer->key);
				
				$widget = app('telenok.config.repository')->getWidget()->get($item->key);
				
				$widget->insertFromBufferOnPage(
						$widgetOnPage->widgetLanguageLanguage()->first()->value('id'), 
						$widgetOnPage->widgetPage()->first()->value('id'), 
						$item->key, 
						$item->getKey(), 
						$newContainres[$key], 
						$item->widget_order, 
						$buffer->getKey());
			});
		}

		$structure->put('containerIds', $newContainres);
		$widgetOnPage->structure = $structure->all();
		$widgetOnPage->save();
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
     * @return {void}
     * @member Telenok.Core.Widget.Table.Controller
     */
	public function insertOnPage($languageId = 0, $pageId = 0, $key = '', $id = 0, $container = '', $order = 0)
	{
		$w = parent::insertOnPage($languageId, $pageId, $key, $id, $container, $order);

		$structure = $w->structure;

		if (!$structure->has('row'))
		{
			$structure->put('row', $this->row);
		}

		if (!$structure->has('col'))
		{
			$structure->put('col', $this->col);
		}

		if (!$structure->has('containerIds')/* || (count($structure->get('containerIds')) < $structure->get('row') * $structure->get('row') )*/)
		{
			$ids = [];

			for ($row = 0; $row < $structure->get('row'); $row++)
			{
				for ($col = 0; $col < $structure->get('col'); $col++)
				{
					$ids["$row:$col"] = isset($ids["$row:$col"]) ? $ids["$row:$col"] : 'container-' . $w->id . '-' . $row . '-' . $col;
				}
			}

			$structure->put('containerIds', $ids);
			$w->structure = $structure->all();
			$w->save();
		}

		return $w;
	}

    /**
     * @method removeFromPage
     * Remove widgetOnPage by id.
     * @param {Integer} $id
     * Id of widgetOnPage.
     * @return {void}
     * @throws Exception
     * @member Telenok.Core.Widget.Table.Controller
     */
	public function removeFromPage($id = 0)
	{
		$w = \App\Telenok\Core\Model\Web\WidgetOnPage::findOrFail($id);
        
		if (\App\Telenok\Core\Model\Web\WidgetOnPage::whereIn('container', $w->structure->get('containerIds', []))->count())
		{
            throw new \Exception($this->LL('widget.has.child'));
		}
		else
		{
			return parent::removeFromPage($id);
		}
	}

    /**
     * @method validate
     * validate structure data before saving.
     * @param {Telenok.Core.Model.Web.WidgetOnPage} $model
     * @param {Illuminate.Support.Collection} $input
     * @return {Telenok.Core.Widget.Table.Controller}
     * @member Telenok.Core.Widget.Table.Controller
     */
	public function validate($model = null, $input = [])
	{
        if (!$model->exists)
        {
            return $this;
        }

        $row = intval($model->structure->get('row'));
        $col = intval($model->structure->get('col'));

        $structure = $input->get('structure');
        
        $inputRow = intval(array_get($structure, 'row', 0));
        $inputCol = intval(array_get($structure, 'col', 0));
        
        if ($row > $inputRow || $col > $inputCol)
        {
            for($x = $inputCol; $x < $col; $x++)
            {
                for($y = 0; $y < $row; $y++)
                {
                    if (\App\Telenok\Core\Model\Web\WidgetOnPage::where('container', 'container-' . $model->id . '-' . $y . '-' . $x)->count())
                    {
                        throw new \Exception($this->LL('widget.has.child'));
                    }
                }               
            }
            
            for($x = 0; $x < $inputCol; $x++)
            {
                for($y = $inputRow; $y < $row; $y++)
                {
                    if (\App\Telenok\Core\Model\Web\WidgetOnPage::where('container', 'container-' . $model->id . '-' . $y . '-' . $x)->count())
                    {
                        throw new \Exception($this->LL('widget.has.child'));
                    }
                }               
            }
        }
            return $this;
	}
    
    /**
     * @method postProcess
     * Hook called after saving widget.
     * @param {Telenok.Core.Model.Web.WidgetOnPage} $model
     * @param {Telenok.Core.Model.Object.Type} $type
     * @param {Illuminate.Support.Collection} $input
     * @return {Telenok.Core.Widget.Table.Controller}
     * @member Telenok.Core.Widget.Table.Controller
     */
    public function postProcess($model, $type, $input)
    { 
        $ids = [];

        $structure = $model->structure;

        for ($row = 0; $row < intval($structure->get('row')); $row++)
        {
            for ($col = 0; $col < intval($structure->get('col')); $col++)
            {
                $ids["$row:$col"] = isset($ids["$row:$col"]) ? $ids["$row:$col"] : 'container-' . $model->id . '-' . $row . '-' . $col;
            }
        }

        $structure->put('containerIds', $ids);
        $model->structure = $structure->all();
        $model->save();

        return parent::postProcess($model, $type, $input);
    }
}
