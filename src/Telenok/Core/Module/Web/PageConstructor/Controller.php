<?php

namespace Telenok\Core\Module\Web\PageConstructor;

class Controller extends \App\Http\Controllers\Module\Objects\Lists\Controller {

	protected $key = 'web-page-constructor';
	protected $parent = 'web';
	protected $presentation = 'web-page-widget';
	protected $presentationView = 'core::module.web-page-constructor.presentation';
	protected $presentationContentView = 'core::module.web-page-constructor.content';

	public function getActionParam()
	{
		return json_encode([
			'presentation' => $this->getPresentation(),
			'presentationModuleKey' => $this->getPresentationModuleKey(),
			'presentationContent' => $this->getPresentationContent(),
			'key' => $this->getKey(),
			'breadcrumbs' => $this->getBreadcrumbs(),
			'pageHeader' => $this->getPageHeader(),
			'uniqueId' => str_random(),
		]);
	}

	public function getPresentationContent()
	{
		return view($this->getPresentationView(), [
					'presentation' => $this->getPresentation(),
					'presentationModuleKey' => $this->getPresentationModuleKey(),
					'controller' => $this,
					'iDisplayLength' => $this->displayLength,
					'uniqueId' => str_random()
				])->render();
	}

	public function viewPageContainer($id = 0, $languageId = 0)
	{
		try
		{
			$page = \App\Model\Telenok\Web\Page::findOrFail($id);
			$controllerClass = app($page->pagePageController->controller_class);

			return [
				'pageId' => $id,
				'tabKey' => $this->getTabKey() . '-widget-page-' . md5($id),
				'tabLabel' => "#{$page->getKey()} " . $page->translate('title'),
				'tabContent' => $controllerClass->getContainerContent($id, $languageId)
			];
		}
		catch (\Illuminate\Database\Eloquent\ModelNotFoundException $ex)
		{
            return [];
		}
		catch (\Exception $e)
		{           
            return \Response::json($e->getMessage(), 417 /* Expectation Failed */);
		}
	}

	public function getListPage()
	{
		$return = \Illuminate\Support\Collection::make();

		$query = \App\Model\Telenok\Web\Page::query();

		if ($this->getRequest()->input('term'))
		{
			$query->where('title', 'like', '%' . trim($this->getRequest()->input('term')) . '%');
		}

		$query->get()->each(function($item) use ($return)
		{
			$return->push(['id' => $item->id, 'title' => $item->translate('title') . " [{$item->url_pattern}]"]);
		});

		return $return;
	}

	public function insertWidget($languageId = 0, $pageId = 0, $key = '', $id = 0, $container = '', $bufferId = 0, $order = 0)
	{
		if (!intval($pageId) || !trim($key) || !trim($container))
		{
			return \Response::json('Empty page id or widget key', 403);
		}
		
		$widget = app('telenok.config')->getWidget()->get($key); 
		
		if (intval($bufferId))
		{
			$w = $widget->insertFromBufferOnPage($languageId, $pageId, $key, $id, $container, $order, $bufferId);
		}
		else
		{
			$w = $widget->insertOnPage($languageId, $pageId, $key, $id, $container, $order);
		}

		return $widget->getInsertContent($w->getKey());
	}

	public function removeWidget($id = 0)
	{
		try
		{
			$widget = \App\Model\Telenok\Web\WidgetOnPage::findOrFail($id);

			app('telenok.config')->getWidget()->get($widget->key)->removeFromPage($id);

			return ['success' => 1];
		}
		catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e)
		{
			return \Response::json($this->LL('notice.error.undefined'), 417);
		}
		catch (\Exception $e)
		{ 
            return \Response::json($e->getMessage(), 417);
		}
	}

	public function addBufferWidget($id = 0, $key = 'copy')
	{
		$widget = \App\Model\Telenok\Web\WidgetOnPage::findOrFail($id);

		$buffer = \App\Model\Telenok\System\Buffer::addBuffer(\Auth::user()->getKey(), $widget->getKey(), 'web-page', $key);

		return ['widget' => $widget, 'buffer' => $buffer];
	}

	public function deleteBufferWidget($id = 0)
	{
		$w = \App\Model\Telenok\System\Buffer::find($id);

		if ($w)
		{
			$w->forceDelete();
		}
	}

}

