<?php namespace Telenok\Core;

use Illuminate\Support\ServiceProvider;

/**
 * @class Telenok.Core.CoreServiceProvider
 * Core service provider
 */
class CoreServiceProvider extends ServiceProvider {

    protected $defer = false;

    /**
     * @method boot
     * @public
     * Load config, routers, create singletons and others
     * @return void
     */
    public function boot()
    {
        $this->app->resolving(function(\Telenok\Core\Interfaces\Support\IRequest $object, $app)
        {
            $object->setRequest($app['request']);
        });

        $this->loadViewsFrom(realpath(__DIR__ . '/../../view'), 'core');
        $this->loadTranslationsFrom(realpath(__DIR__ . '/../../lang'), 'core');

        $this->publishes([realpath(__DIR__ . '/../../../public') => public_path('packages/telenok/core')], 'public');

        include __DIR__ . '/../../config/helpers.php';
        include __DIR__ . '/../../config/routes.php';
        include __DIR__ . '/../../config/event.php';

        $this->commands('command.telenok.install');
        $this->commands('command.telenok.seed');
        $this->commands('command.telenok.package');

        app('auth')->extend('custom', function()
        {
            return new \App\Telenok\Core\Security\Guard(
                app(
                    '\App\Telenok\Core\Security\UserProvider', 
                    [
                        $this->app['hash'], 
                        $this->app['config']['auth.model']
                    ]
                ),
                $this->app['session.store']
            );
        });        
        
        if (!file_exists(storage_path('telenok/installedTelenokCore.lock')))
        {
            return;
        }

        \Telenok\Core\Interfaces\Field\Relation\Controller::readMacroFile();
        
        \Event::fire('telenok.compile.setting');

        if (!\Request::is('telenok', 'telenok/*'))
        {
            $routersPath = storage_path('telenok/route/route.php');

            if (!file_exists($routersPath))
            {
                \Event::fire('telenok.compile.route');
            }

            if (!$this->app->routesAreCached())
            {
                include $routersPath;
            }
        }
    }

    /**
     * @method register
     * @public
     * Register the service provider.
     * @return void
     */
    public function register()
    {
        foreach([
                'mysql' => 'MySql',
                'pgsql' => 'Postgres',
                'sqlite' => 'SQLite',
                'sqlsrv' => 'SqlServer',
            ] as $driver)
        {
            $this->app->singleton('db.connection.' . $driver, function ($app, $parameters) use ($driver)
            {
                list($connection, $database, $prefix, $config) = $parameters;

                $class = 'Telenok\Core\Interfaces\Database\Connection\\' . $driver . 'Connection';
                
                return new $class($connection, $database, $prefix, $config);
            });
        }

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

        $this->registerMemcache();
    }

    /**
     * @method registerMemcache
     * @public
     * Configure memcache
     * @return void
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
     * @public
     * Get the services provided by the provider.
     * @return array
     */
    public function provides()
    {
        return [];
    }
}