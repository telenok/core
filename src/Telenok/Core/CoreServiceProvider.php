<?php namespace Telenok\Core;

use Illuminate\Support\ServiceProvider;

/**
 * @class Telenok.Core.CoreServiceProvider
 * Core service provider.
 * @extends Illuminate.Support.ServiceProvider
 */
class CoreServiceProvider extends ServiceProvider {

    protected $defer = false;

    /**
     * @method boot
     * Load config, routers, create singletons and others.
     * @return {void}
     * @member Telenok.Core.CoreServiceProvider
     */
    public function boot()
    {
        $this->app->resolving(function(\Telenok\Core\Contract\Injection\Request $object, $app)
        {
            $object->setRequest($app['request']);
        });

        $this->loadViewsFrom(realpath(__DIR__ . '/../../view'), 'core');
        $this->loadTranslationsFrom(realpath(__DIR__ . '/../../lang'), 'core');

        $this->publishes([realpath(__DIR__ . '/../../../public') => public_path('packages/telenok/core')], 'public');

        $this->publishes([realpath(__DIR__ . '/../../../resources/app') => app_path()], 'resourcesapp');

        include __DIR__ . '/../../config/helpers.php';
        include __DIR__ . '/../../config/routes.php';
        include __DIR__ . '/../../config/event.php';

        $this->commands('command.telenok.install');
        $this->commands('command.telenok.seed');
        $this->commands('command.telenok.package');

        // using custom provider
        app('auth')->provider('telenok', function($app, array $config)
        {
            return new \App\Telenok\Core\Security\UserProvider(
                    $app['hash'], 
                    $app['config']['auth.providers.users']['model']);
        });

        // using custom guard
        app('auth')->extend('telenok', function($app, $name, array $config)
        {
            return app(
                        '\App\Telenok\Core\Security\Guard', 
                        [
                            $name,
                            $app['auth']->createUserProvider($config['provider'])
                        ]
                    );
        });        

        if (!file_exists(storage_path('telenok/installedTelenokCore.lock')))
        {
            return;
        }

        \Telenok\Core\Abstraction\Field\Relation\Controller::readMacroFile();
        
        \Event::fire('telenok.compile.setting');

        if (!app('request')->is('telenok', 'telenok/*') && !app()->routesAreCached())
        {
            $routersPath = storage_path('telenok/route/route.php');

            if (!file_exists($routersPath))
            {
                \Event::fire('telenok.compile.route');
            }
        }
        
        if ($theme = \App\Telenok\Core\Support\Config\Theme::activeTheme())
        {
            $this->loadViewsFrom(base_path(str_finish(config('app.path_theme'), '/') . $theme . '/views'), 'theme');
            $this->loadTranslationsFrom(base_path('resources/views/template/' . $theme . '/lang'), 'theme');
        }
    }

    /**
     * @method register
     * Register the service provider.
     * @return {void}
     * @member Telenok.Core.CoreServiceProvider
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

        $this->app['command.telenok.package'] = $this->app->share(function($app)
        {
            return new \App\Telenok\Core\Command\Package($app['composer']);
        });

        $this->registerDBConnection();
        $this->registerMemcache();
    }

    /**
     * @method registerDBConnection
     * Create singletones for all registered types of DB connections. 
     * Use those custom connections to cache databse queries.
     * @return {void}
     * @member Telenok.Core.CoreServiceProvider
     */
    public function registerDBConnection()
    {
        foreach([
                'mysql' => 'MySql',
                'pgsql' => 'Postgres',
                'sqlite' => 'SQLite',
                'sqlsrv' => 'SqlServer',
            ] as $key => $driver)
        {           
            $this->app->singleton('db.connection.' . $key, function ($app, $parameters) use ($driver)
            {
                list($connection, $database, $prefix, $config) = $parameters;

                $class = '\App\Telenok\Core\Abstraction\Database\Connection\\' . $driver . 'Connection';

                return new $class($connection, $database, $prefix, $config);
            });
        }
    }

    /**
     * @method registerMemcache
     * Configure memcache
     * @return {void}
     * @member Telenok.Core.CoreServiceProvider
     */
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
     * @method provides
     * Get the services provided by the provider.
     * @return {Array}
     * @member Telenok.Core.CoreServiceProvider
     */
    public function provides()
    {
        return [];
    }
}