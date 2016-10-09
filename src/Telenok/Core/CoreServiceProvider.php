<?php namespace Telenok\Core;

use Illuminate\Support\ServiceProvider;
use Telenok\Core\Event\CompileRoute;
use Telenok\Core\Event\CompileSetting;

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
        $this->addResolver();
        $this->extendValidator();
        $this->loadConfigFile();
        $this->packageResourceRegister();
        $this->packageCommandRegister();

        $this->setAuthProvider();
        $this->setAuthGuard();

        if (!$this->validateInstallFlag())
        {
            return;
        }

        $this->addListener();

        $this->readDBMacro();

        $this->compileSetting();
        $this->compileRoute();

        if ($theme = \App\Vendor\Telenok\Core\Support\Config\Theme::activeTheme())
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
        $this->registerConfigRepository();

        $this->registerCommandInstall();
        $this->registerCommandSeed();
        $this->registerCommandPackage();

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

                $class = '\App\Vendor\Telenok\Core\Abstraction\Database\Connection\\' . $driver . 'Connection';

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
            $memcache = (new \App\Vendor\Telenok\Core\Support\Memcache\MemcacheConnector())->connect($servers);
            $repo = new \Illuminate\Cache\Repository(new \App\Vendor\Telenok\Core\Support\Memcache\MemcacheStore($memcache, $prefix));

            $this->app->resolving('cache', function($cache) use ($repo)
            {
                $cache->extend('memcache', function($app) use ($repo)
                {
                    return $repo;
                });
            });

            if ($isSessionDriver)
            {
                $handler = new \App\Vendor\Telenok\Core\Support\Memcache\MemcacheHandler($repo, $minutes);
                $manager = new \App\Vendor\Telenok\Core\Support\Memcache\MemcacheSessionManager($handler);

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

    public function setAuthProvider()
    {
        // using custom provider
        app('auth')->provider('telenok', function($app, array $config)
        {
            return new \App\Vendor\Telenok\Core\Security\UserProvider(
                $app['hash'],
                $app['config']['auth.providers.users']['model']);
        });
    }

    public function setAuthGuard()
    {
        // using custom guard
        app('auth')->extend('telenok', function($app, $name, array $config)
        {
            $guard = app(
                \App\Vendor\Telenok\Core\Security\Guard::class,
                [
                    $name,
                    $app['auth']->createUserProvider($config['provider'])
                ]
            );

            $guard->setCookieJar($app->make('cookie'));

            return $guard;
        });
    }

    public function addListener()
    {
        app('db')->listen(function ($event)
        {
            if (config('querylog'))
            {
                $sql = vsprintf(str_replace(array('%', '?'), array('%%', '"%s"'), $event->sql), $event->bindings);

                app('log')->debug($sql);
            }
        });
    }

    public function extendValidator()
    {
        app('validator')->extend('valid_regex', function($attribute, $value, $parameters, $validator)
        {
            return (@preg_match($value, NULL) !== FALSE);
        });
    }

    public function registerConfigRepository()
    {
        $this->app->singleton('telenok.config.repository', '\App\Vendor\Telenok\Core\Support\Config\Repository');
    }

    public function registerCommandInstall()
    {
        $this->app['command.telenok.install'] = $this->app->share(function($app)
        {
            return new \App\Vendor\Telenok\Core\Command\Install();
        });
    }

    public function registerCommandSeed()
    {
        $this->app['command.telenok.seed'] = $this->app->share(function($app)
        {
            return new \App\Vendor\Telenok\Core\Command\Seed();
        });
    }

    public function registerCommandPackage()
    {
        $this->app['command.telenok.package'] = $this->app->share(function($app)
        {
            return new \App\Vendor\Telenok\Core\Command\Package($app['composer']);
        });
    }

    public function addResolver()
    {
        $this->app->resolving(\Telenok\Core\Contract\Injection\Request::class, function($object, $app)
        {
            $object->setRequest($app['request']);
        });

        app('validator')->resolver(function($translator, $data, $rules, $messages, $customAttributes)
        {
            return new \App\Vendor\Telenok\Core\Support\Validator\Validator($translator, $data, $rules, $messages, $customAttributes);
        });
    }

    public function loadConfigFile()
    {
        include_once __DIR__ . '/../../config/helpers.php';
        include_once __DIR__ . '/../../config/routes.php';
    }

    public function packageResourceRegister()
    {
        $this->loadViewsFrom(realpath(__DIR__ . '/../../view'), 'core');
        $this->loadTranslationsFrom(realpath(__DIR__ . '/../../lang'), 'core');

        $this->publishes([realpath(__DIR__ . '/../../../public') => public_path('packages/telenok/core')], 'public');
        $this->publishes([realpath(__DIR__ . '/../../../resources/app') => app_path()], 'resourcesapp');
    }

    public function packageCommandRegister()
    {
        $this->commands('command.telenok.install');
        $this->commands('command.telenok.seed');
        $this->commands('command.telenok.package');
    }

    public function validateInstallFlag()
    {
        return file_exists(storage_path('telenok/installedTelenokCore.lock'));
    }

    public function readDBMacro()
    {
        \Telenok\Core\Abstraction\Field\Relation\Controller::readMacroFile();
    }

    public function compileSetting()
    {
        app('events')->fire(new CompileSetting());
    }

    public function compileRoute()
    {
        if (!app('request')->is('telenok', 'telenok/*') && !app()->routesAreCached())
        {
            $routersPath = base_path('routes/telenok.php');

            if (!file_exists($routersPath))
            {
                app('events')->fire(new CompileRoute());
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