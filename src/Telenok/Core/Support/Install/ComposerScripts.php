<?php

namespace Telenok\Core\Support\Install;

class ComposerScripts {

    public static function postInstall(\Composer\Script\Event $event)
    {
        static::run($event);

        $event->getIO()->write('Install TELENOK');

        app('artisan')->call('telenok:install');
    }

    public static function postUpdate(\Composer\Script\Event $event)
    {
        static::run($event);

        $event->getIO()->write('Update TELENOK');

        app('artisan')->call('telenok:install');
    }

    public static function run(\Composer\Script\Event $event)
    {
        $composer = $event->getComposer();
        $installationManager = $composer->getInstallationManager();
        $allPackages = $composer->getRepositoryManager()->getLocalRepository()->getPackages();

        $packageComposerScripts = [];

        foreach ($allPackages as $package)
        {
            $originDir = $installationManager->getInstallPath($package);

            $files = glob(rtrim($originDir, '\\/') . '/resources/package-process/composer-subscript.*.php');

            foreach($files as $file)
            {
                $packageComposerScripts[basename($file) . $package->getName()] = ['file' => $file, 'package' => $package->getName()];
            }
        }

        ksort($packageComposerScripts);

        foreach($packageComposerScripts as $script)
        {
            if (file_exists($script['file'])) {

                $event->getIO()->write('Process package ' . $script['package']);
                $event->getIO()->write('Include and process ' . $script['file']);

                require $script['file'];
            }
        }
    }

    public static function recursiveCopy($src, $dst, $rewrite = false, $mode = 0755)
    {
        try
        {
            @mkdir($dst, $mode, true);

            $dir = opendir($src);

            while (false !== ($file = readdir($dir)))
            {
                if (($file != '.') && ($file != '..'))
                {
                    if (is_dir($src . '/' . $file))
                    {
                        static::recursiveCopy($src . '/' . $file, $dst . '/' . $file, $rewrite, $mode);
                    }
                    else if ((file_exists($dst . '/' . $file) && $rewrite) || !file_exists($dst . '/' . $file))
                    {
                        copy($src . '/' . $file, $dst . '/' . $file);
                    }
                }
            }
        }
        finally
        {
            closedir($dir);
        }
    }

    /*
     *
     * Add $listener to \App\Providers\EventServiceProvider
     *
     */
    public static function addListener($listener)
    {
        $fn = (new \ReflectionClass('App\Providers\EventServiceProvider'))->getFileName();
        $content = file_get_contents($fn);

        if (strpos($content, $listener) === FALSE)
        {
            $content = file_get_contents($fn);
            $content = str_replace('###listener###', "'{$listener}',\n###listener###", $content);
            file_put_contents($fn, $content, LOCK_EX);
        }
    }

    /*
     *
     * Add $provider to \App\Providers\EventServiceProvider
     *
     */
    public static function addServiceProvider($provider)
    {
        $content = file_get_contents(config_path('app.php'));

        if (strpos($content, $provider) === FALSE)
        {
            $content = str_replace('###providers###', "'{$provider}',\n###providers###", $content);
            file_put_contents(config_path('app.php'), $content, LOCK_EX);
        }
    }

    /*
     *
     * Merge $config with root composer.json
     *
     */
    public static function mergeRootComposerJson($config, $rewrite = false)
    {
        $content = json_decode(file_get_contents(base_path('composer.json')), true);

        if (is_array($content))
        {
            file_put_contents(base_path('composer.json'), json_encode($rewrite
                ? array_replace_recursive($content, $config) : array_replace_recursive($config, $content), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES), LOCK_EX);
        }
        else
        {
            throw new \Exception('Cant merge composer.json with data ' . json_encode($config, JSON_PRETTY_PRINT));
        }
    }

}

