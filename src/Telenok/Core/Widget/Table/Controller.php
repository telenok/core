<?php

namespace Telenok\Core\Widget\Table;

class Controller extends \Telenok\Core\Interfaces\Widget\Controller {

	protected $key = 'table';
	protected $parent = 'standart';
	protected $backendView = "core::widget.table.widget-backend";
	protected $frontendView = "core::widget.table.widget-frontend";
	protected $row = 2;
	protected $col = 2;

	public function getContent($structure = null)
	{ 
        if (!($model = $this->getWidgetModel()))
        {
            return;
        }

        $structure = $structure === null ? $model->structure : $structure;
        
        $this->setCacheTime($model->cache_time);

        if (($content = $this->getCachedContent()) !== false)
        {
            return $content; 
        }  

        $containerIds = $structure->get('containerIds', []);

        if (!$structure->has('row'))
        {
            $structure->put('row', $this->row);
        }

        if (!$structure->has('col'))
        {
            $structure->put('col', $this->col);
        }

        $rows = [];

        for ($r = 0; $r < $structure->get('row'); $r++)
        {
            for ($c = 0; $c < $structure->get('col'); $c++)
            {
                $container_id = $containerIds["$r:$c"];

                $rows[$r][$c] = ['container_id' => $containerIds["$r:$c"], 'content' => $this->getContainerContent($container_id)];
            }
        }

        $content = view('widget.' . $model->getKey(), [
                            'widget' => $this->getWidgetModel(),
                            'id' => $this->getWidgetModel()->getKey(),
                            'key' => $this->getKey(),
                            'rows' => $rows,
                            'controller' => $this,
                            'frontendController' => $this->getFrontendController(),
                        ])->render();
        
        $this->setCachedContent($content);
        
		return $content;
	}

	public function getContainerContent($container_id = "")
	{
		$content = [];

		$wop = \App\Model\Telenok\Web\WidgetOnPage::where('container', $container_id)->orderBy('widget_order')->get();

		$widgetConfig = app('telenok.config')->getWidget();

		$wop->each(function($w) use (&$content, $widgetConfig)
		{
            $content[] = $widgetConfig->get($w->key)->setWidgetModel($w)->setFrontendController($this)->getContent();
		});

		return $content;
	}

	public function getInsertContent($id = 0)
	{
		$widgetOnPage = \App\Model\Telenok\Web\WidgetOnPage::findOrFail($id);

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

	public function getContainerInsertContent($container_id = "")
	{
		$content = [];

		$wop = \App\Model\Telenok\Web\WidgetOnPage::where('container', $container_id)->orderBy('widget_order')->get();

		$widgetConfig = app('telenok.config')->getWidget();

		$wop->each(function($w) use (&$content, $widgetConfig)
		{
            $content[$w->container] = $widgetConfig->get($w->key)->getInsertContent($w->getKey());
		});

		return $content;
	}
    
	public function setCacheTime($param = 0)
	{
		$this->cacheTime = min($this->getCacheTime(), $param);
        
		return $this;
	}
	
    public function insertFromBufferOnPage($languageId = 0, $pageId = 0, $key = '', $id = 0, $container = '', $order = 0, $bufferId = 0)
	{
		$widgetOnPage = null;
		
		
		\DB::transaction(function() use ($languageId, $pageId, $key, $id, $container, $order, &$widgetOnPage, $bufferId)
		{
			$widgetOnPage = \App\Model\Telenok\Web\WidgetOnPage::findOrFail($id);
			$buffer = \App\Model\Telenok\System\Buffer::findOrFail($bufferId);

			if ($buffer->key == 'cut')
			{
				$widgetOnPage->storeOrUpdate([
					"container" => $container,
					"widget_order" => $order,
					"key" => $key,
				]);

				$bufferWidget = \App\Model\Telenok\System\Buffer::find($bufferId);

				if ($bufferWidget)
				{
					$bufferWidget->forceDelete();
				}
			}
			else if ($buffer->key == 'copy')
			{
				$widgetOnPage = \App\Model\Telenok\Web\WidgetOnPage::findOrFail($id)->replicate();
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

			\App\Model\Telenok\Web\WidgetOnPage::where("widget_order", ">=", $order)
					->where("container", $container)->get()->each(function($item)
			{
				$item->storeOrUpdate(["widget_order" => $item->order + 1]);
			});

			$widgetOnPage->widgetPage()->associate(\App\Model\Telenok\Web\Page::findOrFail($pageId))->save(); 
			$widgetOnPage->widgetLanguageLanguage()->associate(\App\Model\Telenok\System\Language::findOrFail($languageId))->save(); 
			$widgetOnPage->save();

			if ($buffer->key == 'cut' || $buffer->key == 'copy')
			{
				$this->copyAndInsertChild($widgetOnPage, $buffer);
			}
		});

		return $widgetOnPage;
	}

	public function copyAndInsertChild($widgetOnPage, $buffer)
	{  
		$structure = $widgetOnPage->structure; 

		$newContainres = [];

		foreach($structure->get('containerIds') as $key => $container)
		{ 
			$newContainres[$key] = preg_replace('/(container-)(\d+)(-\d+-\d+)/', "\${1}" . $widgetOnPage->id . "\${3}", $container);

			\App\Model\Telenok\Web\WidgetOnPage::where("container", $container)->get()->each(function($item) use ($widgetOnPage, $buffer, $newContainres, $key)
			{
				$buffer = \App\Model\Telenok\System\Buffer::addBuffer(\Auth::user()->getKey(), $item->getKey(), 'web-page', $buffer->key);
				
				$widget = app('telenok.config')->getWidget()->get($item->key);
				
				$widget->insertFromBufferOnPage(
						$widgetOnPage->widgetLanguageLanguage()->first()->pluck('id'), 
						$widgetOnPage->widgetPage()->first()->pluck('id'), 
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

	public function removeFromPage($id = 0)
	{
		$w = \App\Model\Telenok\Web\WidgetOnPage::findOrFail($id);
        
		if (\App\Model\Telenok\Web\WidgetOnPage::whereIn('container', $w->structure->get('containerIds', []))->count())
		{
            throw new \Exception($this->LL('widget.has.child'));
		}
		else
		{
			return parent::removeFromPage($id);
		}
	}

	public function validate($model = null, $input = [])
	{
        if (!$model->exists)
        {
            return;
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
                    if (\App\Model\Telenok\Web\WidgetOnPage::where('container', 'container-' . $model->id . '-' . $x . '-' . $y)->count())
                    {
                        throw new \Exception($this->LL('widget.has.child'));
                    }
                }               
            }
            
            for($x = 0; $x < $inputCol; $x++)
            {
                for($y = $inputRow; $y < $row; $y++)
                {
                    if (\App\Model\Telenok\Web\WidgetOnPage::where('container', 'container-' . $model->id . '-' . $x . '-' . $y)->count())
                    {
                        throw new \Exception($this->LL('widget.has.child'));
                    }
                }               
            }
        }
	}
    
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