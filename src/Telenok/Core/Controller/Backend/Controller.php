<?php namespace Telenok\Core\Controller\Backend;

class Controller extends \Telenok\Core\Interfaces\Controller\Backend\Controller {

	protected $key = 'backend';

	public function __construct()
	{
		if (!app()->runningInConsole())
		{
			$this->beforeFilter('control-panel', ['except' => ['errorAccessDenied']]);
		}
	}

	public function updateBackendUISetting($key = null, $value = null)
	{
		$key = $key ? : $this->getRequest()->input('key');
		$value = $value ? : $this->getRequest()->input('value');

		if ($key)
		{
			$user = app('auth')->user();

			$userConfig = $user->configuration;

			$userConfig->put($key, $value);

			$user->configuration = $userConfig;

			$user->update();
		}
	}

	public function login()
	{
		$username = trim($this->getRequest()->input('username'));
		$password = trim($this->getRequest()->input('password'));
		$remember = intval($this->getRequest()->input('remember'));

		if ($username && $password && (app('auth')->attempt(['username' => $username, 'password' => $password], $remember) || app('auth')->attempt(['email' => $username, 'password' => $password], $remember)))
		{
			if (app('auth')->can('read', 'control_panel'))
			{
				return app('redirect')->route('cmf.content');
			}
			else
			{
				return app('redirect')->route('error.access-denied');
			}
		}

		return view('core::controller.backend-login', ['controller' => $this]);
	}

	public function logout()
	{
		app('auth')->logout();

		return ['success' => true];
	}

	public function errorAccessDenied()
	{
		return view('core::controller.backend-denied', ['controller' => $this]);
	}

	public function frontendAreaWidgetList()
	{
		return view('core::controller.backend-frontend-iframe-widget-list', ['controller' => $this]);
	}

	public function frontendArea()
	{
		return view('core::controller.backend-frontend-iframe-content', ['controller' => $this]);
	}

	public function updateCsrf()
	{
		return ['csrf_token' => csrf_token()];
	}

	public function getContent()
	{
		$listModuleMenuLeft = \Illuminate\Support\Collection::make();
		\Event::fire('telenok.module.menu.left', $listModuleMenuLeft);

		$config = app('telenok.config.repository');

		$setArray = [];
		
		$listModule = $config->getModule()
				->filter(function($item) use ($listModuleMenuLeft)
				{
					if (!$item->getParent() && $listModuleMenuLeft->has($item->getKey()))
					{
						return true;
					}

					if ($item->getParent() && app('auth')->can('read', 'module.' . $item->getKey()) && $listModuleMenuLeft->has($item->getKey()))
					{
						return true;
					}
				})
				->sortBy(function($item) use ($listModuleMenuLeft)
		{
			return $item->getModelModule()->module_order;
		});


		$listModule = $listModule->filter(function($item) use ($listModule)
		{
			foreach ($listModule as $module)
			{
				if ($item->getParent() || (!$item->getParent() && $module->getParent() == $item->getKey()))
				{
					return true;
				}
			}

			return false;
		});

		$listModuleGroup = $config->getModuleGroup()->filter(function($item) use ($listModule)
		{
			foreach ($listModule as $module)
			{
				if ($module->getGroup() && $module->getGroup() == $item->getKey())
				{
					return true;
				}
			}

			return false;
		});

		$setArray['listModule'] = $listModule;
		$setArray['listModuleGroup'] = $listModuleGroup;


		$listModuleMenuTop = \Illuminate\Support\Collection::make();

		$listModuleMenuTopCollection = \Illuminate\Support\Collection::make();

		\Event::fire('telenok.module.menu.top', $listModuleMenuTopCollection);

		$listModuleMenuTopCollection->each(function($item) use ($listModuleMenuTop, $config)
		{
			list($code, $method) = explode('@', $item, 2);

			$listModuleMenuTop->push($config->getModule()->get($code)->$method());
		});

		$listModuleMenuTop->sortBy(function($item)
		{
			return $item->get('order');
		});


		$setArray['listModuleMenuTop'] = $listModuleMenuTop;
		$setArray['controller'] = $this;

		return view('core::controller.backend', $setArray);
	}

}
