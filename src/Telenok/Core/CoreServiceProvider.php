<?php

namespace Telenok\Core;

use Illuminate\Support\ServiceProvider;

class CoreServiceProvider extends ServiceProvider {

    protected $defer = false;

    public function boot()
    {
	$this->app->resolving(function(\Telenok\Core\Interfaces\Request $object, $app)
	{
	    $object->setRequest($app['request']);
	});
	
	
        $this->loadViewsFrom(__DIR__ . '/../../views', 'telenok-core');
        $this->loadTranslationsFrom(__DIR__.'/../../lang', 'telenok-core');

        $this->publishes([__DIR__ . '/../../view' => base_path('resources/views/telenok/core')], 'view');
        $this->publishes([__DIR__ . '/../../migrations' => base_path('/database/migrations')], 'migrations');
        $this->publishes([__DIR__ . '/../../seeds' => base_path('/database/seeds')], 'seeds');

        include __DIR__ . '/../../config/routes.php';
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
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
