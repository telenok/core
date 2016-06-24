<?php

namespace Telenok\Core\Support\Install;

class Custom {

    public function postInstall(\Composer\Script\Event $event)
    {
        $this->run($event);
    }

    public function postUpdate(\Composer\Script\Event $event)
    {
        $this->run($event);
    }

    public function run(\Composer\Script\Event $event)
    {
        $composer = $event->getComposer();
        $installationManager = $composer->getInstallationManager();
        $allPackages = $composer->getRepositoryManager()->getLocalRepository()->getPackages();

        $processed = [];

        foreach ($allPackages as $package)
        {
            $originDir = $installationManager->getInstallPath($package);

            $file = rtrim($originDir, '\\/') . '/resources/package-process/custom.php';

            if (file_exists($file) && !isset($processed[$package->getName()]))
            {
                $processed[$package->getName()] = true;

                $event->getIO()->write('Process package ' . $package->getName());
                $event->getIO()->write('Include and process ' . $file);

                require $file;
            }
        }
    }

    public static function recursiveCopy($src, $dst, $rewrite = false)
    {
        try
        {
            @mkdir($dst);

            $dir = opendir($src);

            while (false !== ($file = readdir($dir)))
            {
                if (($file != '.') && ($file != '..'))
                {
                    if (is_dir($src . '/' . $file))
                    {
                        static::recursiveCopy($src . '/' . $file, $dst . '/' . $file, $rewrite);
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

        if (strpos('\App\Vendor\Telenok\Core\Event\Listener', $content) === FALSE)
        {
            $content = str_replace('###listener###', "'{$listener}'\n###listener###", $content);
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
}

