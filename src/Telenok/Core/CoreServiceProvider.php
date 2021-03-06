<?php namespace Telenok\Core;

use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Support\ServiceProvider;
use App\Vendor\Telenok\Core\Support\Validator\Factory;
use Telenok\Core\Event\CompileRoute;
use Telenok\Core\Event\CompileConfig;

/**
 * @class \Telenok\Core\CoreServiceProvider
 * Core service provider.
 * @extends \Illuminate\Support\ServiceProvider
 */
class CoreServiceProvider extends ServiceProvider {

    /**
     * @method boot
     * Load config, routers, create singletons and others.
     * @return {void}
     * @member \Telenok\Core\CoreServiceProvider
     */
    public function boot()
    {
        $this->addResolver();
        $this->extendValidator();
        $this->loadRouterFile();
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

        $this->compileConfig();
        $this->compileRoute();


        if ($theme = \App\Vendor\Telenok\Core\Support\Theme::activeTheme())
        {
            $this->loadViewsFrom(base_path(str_finish(config('app.path_theme'), '/') . $theme . '/views'), 'theme');
            $this->loadTranslationsFrom(base_path('resources/views/theme/' . $theme . '/lang'), 'theme');
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
        $this->registerTelenokRepository();
        $this->registerValidationFactory();
        $this->registerEventSubscribe();

        $this->registerCommandInstall();
        $this->registerCommandSeed();
        $this->registerCommandPackage();

        $this->registerDBConnection();
        $this->registerMemcache();
    }

    public function registerEventSubscribe()
    {
        $this->app['events']->subscribe(\App\Vendor\Telenok\Core\Event\Subscribe::class);
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

            \Illuminate\Database\Connection::resolverFor($key, function ($connection, $database, $prefix, $config) use ($driver)
            {
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

    /**
     * Register the validation factory.
     *
     * @return void
     */
    protected function registerValidationFactory()
    {
        $this->app->singleton('validator_telenok', function ($app)
        {
            $validator = new Factory($app['translator'], $app);

            // The validation presence verifier is responsible for determining the existence
            // of values in a given data collection, typically a relational database or
            // other persistent data stores. And it is used to check for uniqueness.
            if (isset($app['validation.presence']))
            {
                $validator->setPresenceVerifier($app['validation.presence']);
            }

            return $validator;
        });
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
        app('auth')->extend('telenok', function($app, $name, array $config)
        {
            $guard = new \App\Vendor\Telenok\Core\Security\Guard(
                    $name,
                    $app['auth']->createUserProvider($config['provider']),
                    $app['session.store']
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

                app('log')->debug($sql . "\n\n");
            }
        });
    }

    public function extendValidator()
    {
        app('validator_telenok')->extend('valid_regex', function($attribute, $value, $parameters, $validator)
        {
            return (@preg_match($value, NULL) !== FALSE);
        });
    }

    public function registerTelenokRepository()
    {
        $this->app->singleton('telenok.repository', '\App\Vendor\Telenok\Core\Support\Repository');
    }

    public function registerCommandInstall()
    {
        $this->app->singleton('command.telenok.install', function($app)
        {
            return new \App\Vendor\Telenok\Core\Command\Install();
        });
    }

    public function registerCommandSeed()
    {
        $this->app->singleton('command.telenok.seed', function($app)
        {
            return new \App\Vendor\Telenok\Core\Command\Seed();
        });
    }

    public function registerCommandPackage()
    {
        $this->app->singleton('command.telenok.package', function($app)
        {
            return new \App\Vendor\Telenok\Core\Command\Package($app['composer']);
        });
    }

    public function addResolver()
    {
        $this->app->resolving('\Telenok\Core\Contract\Injection\Request', function($object, $app)
        {
            $object->setRequest($app['request']);
        });
    }

    public function loadRouterFile()
    {
        $this->loadRoutesFrom(__DIR__ . '/../../config/routes.php');
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

    public function compileConfig()
    {
        app('events')->fire(new CompileConfig());
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