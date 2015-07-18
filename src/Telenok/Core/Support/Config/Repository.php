<?php namespace Telenok\Core\Support\Config;

class Repository {

	public function getValue($event, $type, $flush = false)
	{
		static $list = [];

		if (!isset($list[$type]) || $flush)
		{
			try
			{
				$collection = \Illuminate\Support\Collection::make();

				\Event::fire($event, $collection);

				$list[$type] = \Illuminate\Support\Collection::make();

				foreach ($collection as $class)
				{
					$object = app($class);

					$list[$type]->put($object->getKey(), $object);
				}
			}
			catch (\Exception $e)
			{
				throw new \RuntimeException('Failed to fire event "' . $event . '". Error: ' . $e->getMessage());
			}
		}

		return $list[$type];
	}
	
	public function getAclResourceFilter($flush = false)
	{ 
		return $this->getValue('telenok.acl.filter.resource', 'acl.filter', $flush);
	}
	
	public function getPackage($flush = false)
	{ 
		return $this->getValue('telenok.repository.package', 'package', $flush);
	}

	public function getSetting($flush = false)
	{
		return $this->getValue('telenok.repository.setting', 'setting', $flush);
	}

	public function getObjectFieldController($flush = false)
	{
		return $this->getValue('telenok.repository.objects-field', 'objects-field', $flush);
	}

	public function getObjectFieldViewModel($flush = false)
	{
		static $list = null;

		if ($list === null || $flush)
		{
			try
			{
				$collection = \Illuminate\Support\Collection::make();

				\Event::fire('telenok.repository.objects-field.view.model', $collection);

				$l = [];

				foreach ($collection as $view)
				{
					list($fieldKey, $viewModel) = explode('#', $view, 2);

					$l[$fieldKey][] = $viewModel;
				}

				$list = \Illuminate\Support\Collection::make($l);
			}
			catch (\Exception $e)
			{
				throw new \RuntimeException('Failed to get view model of field. Error: ' . $e->getMessage());
			}
		}

		return $list;
	}

	public function getModuleGroup($flush = false)
	{
		static $list = null;

		if ($list === null || $flush)
		{
			try
			{
				$list = \Illuminate\Support\Collection::make();

				\App\Telenok\Core\Model\Web\ModuleGroup::active()->get()->each(function($item) use (&$list)
				{
					$object = app($item->controller_class);
					$object->setModelModuleGroup($item);
					$list->put($object->getKey(), $object);
				});
			}
			catch (\Exception $e)
			{
				throw new \RuntimeException('Failed to get module-group. Error: ' . $e->getMessage());
			}
		}

		return $list;
	}

	public function getModule($flush = false)
	{
		static $list = null;

		if ($list === null || $flush)
		{
			try
			{
				$list = \Illuminate\Support\Collection::make();

				\App\Telenok\Core\Model\Web\Module::active()->get()->each(function($item) use (&$list)
				{
					$object = app($item->controller_class);
					$object->setModelModule($item);
					$list->put($object->getKey(), $object);
				});
			}
			catch (\Exception $e)
			{
				throw new \RuntimeException('Failed to get module. Error: ' . $e->getMessage());
			}
		}

		return $list;
	}

	public function getWidgetGroup($flush = false)
	{
		static $list = null;

		if ($list === null || $flush)
		{
			try
			{
				$list = \Illuminate\Support\Collection::make();

				\App\Telenok\Core\Model\Web\WidgetGroup::active()->get()->each(function($item) use (&$list)
				{
					$object = app($item->controller_class);
					$object->setWidgetGroupModel($item);
					$list->put($object->getKey(), $object);
				});
			}
			catch (\Exception $e)
			{
				throw new \RuntimeException('Failed to get widget. Error: ' . $e->getMessage());
			}
		}

		return $list;
	}

	public function getWidget($flush = false)
	{
		static $list = null;

		if ($list === null || $flush)
		{
			try
			{
				$list = \Illuminate\Support\Collection::make();

				\App\Telenok\Core\Model\Web\Widget::active()->get()->each(function($item) use (&$list)
				{
					$object = app($item->controller_class);
					$list->put($object->getKey(), $object);
				});
			}
			catch (\Exception $e)
			{
				throw new \RuntimeException('Failed to get widget. Error: ' . $e->getMessage());
			}
		}

		return $list;
	}

	public function compileRouter()
	{
		$path = storage_path('telenok/route');

		$file = 'route.php';

		if (!\File::exists($path))
		{
			\File::makeDirectory($path, 0777, true);
		}

		$content = [];
		$routeCommon = [];
		$routeDomain = [];

		$domains = \App\Telenok\Core\Model\Web\Domain::active()->get();

		$pages = \App\Telenok\Core\Model\Web\Page::whereHas('pagePageController', function($query)
				{
					$now = \Carbon\Carbon::now();
					$query->where('active', 1)
							->where('active_at_start', '<=', $now)
							->where('active_at_end', '>=', $now);
				})->active()->where(function($query) use ($domains)
				{
					$domains = $domains->modelKeys();

					$query->whereNull('page_domain')
							->orWhere('page_domain', 0)
							->orWhereIn('page_domain', $domains? : [0]);
				})->get();

		foreach ($domains->all() as $domain)
		{
			foreach ($pages->all() as $key => $page)
			{
				if (!method_exists($page->pagePageController->controller_class, $page->pagePageController->controller_method))
				{
					throw new \Exception('Method "' . $page->pagePageController->controller_method . '" not exists in class "' . $page->pagePageController->controller_class . '"');
				}

				if ($page->page_domain && $domain->getKey() == $page->page_domain)
				{
					$routeDomain[$page->page_domain][] = '	Route::get("' . implode("/", array_map("rawurlencode", explode("/", $page->getAttribute('url_pattern')))) . '", array("as" => "page_' . $page->getKey() . '",'
							. ' "uses" => "' . addcslashes($page->pagePageController->controller_class, '"') . '@' . $page->pagePageController->controller_method . '"));'
					;
				}
				else if (!$page->page_domain)
				{
					$routeCommon[$page->getKey()] = 'Route::get("' . implode("/", array_map("rawurlencode", explode("/", $page->getAttribute('url_pattern')))) . '", array("as" => "page_' . $page->getKey() . '",'
							. ' "uses" => "' . addcslashes($page->pagePageController->controller_class, '"') . '@' . $page->pagePageController->controller_method . '"));'
					;
				}
			}
		}

		foreach ($domains->all() as $domain)
		{
			if (!empty($routeDomain[$domain->getKey()]) && !empty($routeDomain[$domain->getKey()]))
			{
				$content[] = 'Route::group(array("domain" => "' . $domain->domain . '"), function() {';

				foreach ($routeDomain[$domain->getKey()] as $dC)
				{
					$content[] = $dC;
				}

				$content[] = '});';
			}
		}

		\File::put($path . '/' . $file, '<?php ' . PHP_EOL . PHP_EOL . implode(PHP_EOL, $content) . PHP_EOL . implode(PHP_EOL, $routeCommon) . PHP_EOL . PHP_EOL . '?>');
	}

	public function compileSetting()
	{
		if (\DB::table('setting')->where('active', 1)->count())
		{
			foreach (\App\Telenok\Core\Model\System\Setting::all() as $setting)
			{
				\Config::set($setting->code, $setting->value);
			}
		}
	}

}
