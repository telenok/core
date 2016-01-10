<?php namespace Telenok\Core\Support\Config;

class Repository {

	public function getValue($event, $key = '')
	{
		try
		{
			$collection = collect();

			\Event::fire($event, $collection);

			$list = collect();

			foreach ($collection as $class)
			{
				$object = app($class);

				$list->put($object->getKey(), $object);
			}
		}
		catch (\Exception $e)
		{
			throw new \RuntimeException('Failed to fire event "' . $event . '". Error: ' . $e->getMessage());
		}

		if ($key)
		{
			$el = $list->get($key);
			
			if ($el)
			{
				$class = get_class($el);
				
				return app($class);
			}
			else
			{
				throw new \RuntimeException('Failed to fire event "' . $event . '" with key "' . $key . '". Error: Class "' . e($el) . '" not exists.');
			}
		}
		else
		{
			return $list;
		}
	}
	
	public function getAclResourceFilter($key = '')
	{ 
		return $this->getValue('telenok.acl.filter.resource', $key);
	}
	
	public function getPackage($key = '')
	{
		return $this->getValue('telenok.repository.package', $key);
	}

	public function getSetting($key = '')
	{
		return $this->getValue('telenok.repository.setting', $key);
	}

	public function getObjectFieldController($key = '')
	{
		return $this->getValue('telenok.repository.objects-field', $key);
	}

	public function getObjectFieldViewModel($flush = false)
	{
		static $list = null;

		if ($list === null || $flush)
		{
			try
			{
				$collection = collect();

				\Event::fire('telenok.repository.objects-field.view.model', [$collection]);

				$l = [];

				foreach ($collection as $view)
				{
					list($fieldKey, $viewModel) = explode('#', $view, 2);

					$l[$fieldKey][] = $viewModel;
				}

				$list = collect($l);
			}
			catch (\Exception $e)
			{
				throw new \RuntimeException('Failed to get view model of field. Error: ' . $e->getMessage());
			}
		}

		return $list;
	}

	public function getModuleGroup()
	{
		try
		{
			$list = collect();

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

		return $list;
	}

	public function getModule()
	{
		try
		{
			$list = collect();

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

		return $list;
	}

	public function getWidgetGroup()
	{
		try
		{
			$list = collect();

			\App\Telenok\Core\Model\Web\WidgetGroup::active()->get()->each(function($item) use (&$list)
			{
				$object = app($item->controller_class);
				$object->setWidgetGroupModel($item);
				$list->put($object->getKey(), $object);
			});
		}
		catch (\Exception $e)
		{
			throw new \RuntimeException('Failed to get widget group. Error: ' . $e->getMessage());
		}

		return $list;
	}

	public function getWidget()
	{
		try
		{
			$list = collect();

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

        $pages = \App\Telenok\Core\Model\Web\Page::whereHas('pageDomain', function($query) use ($domains)
                {
                    $query->whereIn('page_domain', $domains->modelKeys() ? : [0]);
                }, '>=', 0)
                ->whereHas('pagePageController', function($query)
				{
                    $query->where(function($query)
                    {
                        $r = range_minutes(config('cache.db_query.minutes', 0));

                        $query->where('active', 1)
							->where('active_at_start', '<=', $r[1])
							->where('active_at_end', '>=', $r[0]);
                    });
				})
                ->active()
                ->get();

		foreach ($domains->all() as $domain)
		{
			foreach ($pages->all() as $page)
			{
				if (!method_exists($page->pagePageController->controller_class, $page->pagePageController->controller_method))
				{
					throw new \Exception('Method "' . $page->pagePageController->controller_method . '" not exists in class "' . $page->pagePageController->controller_class . '"');
				}

				if ($page->page_domain && $domain->getKey() == $page->page_domain)
				{
					$routeDomain[$page->page_domain][] = 'get("' . $page->getAttribute('url_pattern') . '", array("as" => "page_' . $page->getKey() . '",'
							. ' "uses" => "' . addcslashes($page->pagePageController->controller_class, '"') . '@' . $page->pagePageController->controller_method . '"));'
					;
				}
				else if (!$page->page_domain)
				{
					$routeCommon[$page->getKey()] = 'get("' . $page->getAttribute('url_pattern') . '", array("as" => "page_' . $page->getKey() . '",'
							. ' "uses" => "' . addcslashes($page->pagePageController->controller_class, '"') . '@' . $page->pagePageController->controller_method . '"));'
					;
				}
			}
		}

		foreach ($domains->all() as $domain)
		{
			if (!empty($routeDomain[$domain->getKey()]) && !empty($routeDomain[$domain->getKey()]))
			{
				$content[] = '\Route::group(array("domain" => "' . $domain->domain . '"), function() {';

				foreach ($routeDomain[$domain->getKey()] as $dC)
				{
					$content[] = $dC;
				}

				$content[] = '});';
			}
		}

		file_put_contents(
            $path . '/' . $file, 
            '<?php ' . PHP_EOL . PHP_EOL . implode(PHP_EOL, $content) . PHP_EOL . implode(PHP_EOL, $routeCommon) . PHP_EOL . PHP_EOL . '?>', 
            LOCK_EX
        );
	}

	public function compileSetting()
	{
		if (app('db')->table('setting')->where('active', 1)->count())
		{
			foreach (\App\Telenok\Core\Model\System\Setting::all() as $setting)
			{
                try
                {
                    $w = app('telenok.config.repository')->getSetting(strtolower($setting->code));
                    
                    $w->fillSettingValue($setting, $setting->value);
                }
                catch (\Exception $e)
                {
                    app('config')->set($setting->code, $setting->value);
                }
			}
		}
	}
}