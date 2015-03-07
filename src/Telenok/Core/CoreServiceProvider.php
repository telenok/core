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

		if (!file_exists(storage_path() . '/installedTelenokCore'))
		{
			return;
		}
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		if (!file_exists(storage_path() . '/installedTelenokCore'))
		{
			return;
		}

		$this->app->singleton('telenok.config.repository', '\Telenok\Core\Support\Config\Repository');
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
