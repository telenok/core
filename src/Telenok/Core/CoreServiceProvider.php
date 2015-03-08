<?php

namespace Telenok\Core;

use Illuminate\Support\ServiceProvider;

class CoreServiceProvider extends ServiceProvider {

	protected $defer = false;

	public function boot()
	{
		$this->app->resolving(function(\Telenok\Core\Interfaces\IRequest $object, $app)
		{
			$object->setRequest($app['request']);
		});

		$this->loadViewsFrom(__DIR__ . '/../../views', 'core');
		$this->loadTranslationsFrom(__DIR__ . '/../../lang', 'core');

		$this->publishes([__DIR__ . '/../../view' => base_path('resources/views/telenok/core')], 'view');
		$this->publishes([__DIR__ . '/../../migrations' => base_path('/database/migrations')], 'migrations');
		$this->publishes([__DIR__ . '/../../seeds' => base_path('/database/seeds')], 'seeds');
		$this->publishes([__DIR__ . '/../../../public' => public_path('packages/telenok/core')], 'public');

		include __DIR__ . '/../../config/routes.php';
		include __DIR__ . '/../../config/event.php';

		$this->commands('command.telenok.install');

		\Auth::extend('custom', function()
		{
			return new \Telenok\Core\Security\Guard(
					new \Telenok\Core\Security\UserProvider($this->app['hash'], $this->app['config']['auth.model']), $this->app['session.store']
			);
		});		
		
		if (!file_exists(storage_path() . '/installedTelenokCore'))
		{
			return;
		}

		\Event::fire('telenok.compile.setting');

		if (!\Request::is('telenok', 'telenok/*'))
		{
			$routersPath = storage_path() . '/route/route.php';

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
		$this->app->singleton('telenok.config.repository', '\Telenok\Core\Support\Config\Repository');

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
			$memcache = (new \Telenok\Core\Support\Memcache\MemcacheConnector())->connect($servers);
			$repo = new \Illuminate\Cache\Repository(new \Telenok\Core\Support\Memcache\MemcacheStore($memcache, $prefix));

			$this->app->resolving('cache', function($cache) use ($repo)
			{
				$cache->extend('memcache', function($app) use ($repo)
				{
					return $repo;
				});
			});

			if ($isSessionDriver)
			{
				$handler = new \Telenok\Core\Support\Memcache\MemcacheHandler($repo, $minutes);
				$manager = new \Telenok\Core\Support\Memcache\MemcacheSessionManager($handler);

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
