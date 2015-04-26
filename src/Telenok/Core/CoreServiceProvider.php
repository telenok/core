<?php namespace Telenok\Core;

use Illuminate\Support\ServiceProvider;

class CoreServiceProvider extends ServiceProvider {

	protected $defer = false;

	public function boot()
	{
		$this->app->resolving(function(\Telenok\Core\Interfaces\Support\IRequest $object, $app)
		{
			$object->setRequest($app['request']);
		});

		$this->loadViewsFrom(__DIR__ . '/../../views', 'core');
		$this->loadTranslationsFrom(__DIR__ . '/../../lang', 'core');

		$this->publishes([__DIR__ . '/../../../public' => public_path('packages/telenok/core')], 'public');

		include __DIR__ . '/../../config/routes.php';
		include __DIR__ . '/../../config/event.php';

		$this->commands('command.telenok.install');
		$this->commands('command.telenok.seed');

		\Auth::extend('custom', function()
		{
			return new \App\Telenok\Core\Security\Guard(
					new \App\Telenok\Core\Security\UserProvider($this->app['hash'], $this->app['config']['auth.model']), $this->app['session.store']
			);
		});		
		
		if (!file_exists(storage_path('telenok/installedTelenokCore.lock')))
		{
			return;
		}

		\Event::fire('telenok.compile.setting');

		if (!\Request::is('telenok', 'telenok/*'))
		{
			$routersPath = storage_path('telenok/route/route.php');

			if (!file_exists($routersPath))
			{
				\Event::fire('telenok.compile.route');
			}

			include $routersPath;
		}
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->singleton('telenok.config.repository', '\App\Telenok\Core\Support\Config\Repository');

		$this->app['command.telenok.install'] = $this->app->share(function($app)
		{
			return new \App\Telenok\Core\Command\Install();
		});

		$this->app['command.telenok.seed'] = $this->app->share(function($app)
		{
			return new \App\Telenok\Core\Command\Seed();
		});

		$this->registerMemcache();
	}

	public function registerMemcache()
	{
		$cfg = $this->app['config'];

		$isCacheDriver = $cfg['cache.driver'] == 'memcache';
		$servers = $cfg['cache.memcache']? : $cfg['cache.memcached'];
		$prefix = $cfg['cache.prefix'];
		$isSessionDriver = $cfg['session.driver'] == 'memcache';
		$minutes = $cfg['session.lifetime'];
		$memcache = $repo = $handler = $manager = $driver = null;

		if ($isCacheDriver)
		{
			$memcache = (new \App\Telenok\Core\Support\Memcache\MemcacheConnector())->connect($servers);
			$repo = new \Illuminate\Cache\Repository(new \App\Telenok\Core\Support\Memcache\MemcacheStore($memcache, $prefix));

			$this->app->resolving('cache', function($cache) use ($repo)
			{
				$cache->extend('memcache', function($app) use ($repo)
				{
					return $repo;
				});
			});

			if ($isSessionDriver)
			{
				$handler = new \App\Telenok\Core\Support\Memcache\MemcacheHandler($repo, $minutes);
				$manager = new \App\Telenok\Core\Support\Memcache\MemcacheSessionManager($handler);

				$driver = $manager->driver('memcache');

				$this->app->resolving('session', function($session) use ($driver)
				{
					$session->extend('memcache', function($app) use ($driver)
					{
						return $driver;
					});
				});
			}
		}
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return [];
	}

}
