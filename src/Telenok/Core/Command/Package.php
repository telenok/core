<?php namespace Telenok\Core\Command;

use Illuminate\Console\Command;
use Illuminate\Foundation\Composer;

class Package extends Command implements \Illuminate\Contracts\Bus\SelfHandling {

    protected $name = 'telenok:package {action=refresh} {--provider=null}';
    protected $description = 'Updating Telenok CMS packages';
    protected $signature = 'telenok:package
                        {action : Can be "refresh" or "add-provider"}
                        {--provider= : For action="add-provider". The service provider should be added to app.php. Example: "Telenok\News\NewsServiceProvider"}';

    public function fire()
    {
        if ($this->argument('action') == 'refresh')
        {
            $this->info('Updating Telenok CMS packages');

            $composer = (new \Telenok\Core\Composer\Application())
                            ->getEmbeddedComposer();

            $installationManager = $composer->getInstallationManager();

            $processed = [];

            foreach ($composer->getRepositoryManager()->getLocalRepository()->getPackages() as $package) 
            {
                $originDir = $installationManager->getInstallPath($package);

                $file = rtrim($originDir, '\\/') . '/resources/package-process/package-update.php';

                if (file_exists($file) && !isset($processed[$package->getName()]))
                {
                    $processed[$package->getName()] = true;

                    $this->info('Process package ' . $package->getName());
                    $this->comment('Include and process ' . $file);

                    require $file;
                }
            }
        }
        else if ($this->argument('action') == 'add-provider' && ($provider = $this->option('provider')))
        {
            $c = file_get_contents(config_path('app.php'));

            if (strpos($c, $provider) === FALSE)
            {
                $this->line('Update app.php. Try to add ' . $provider);

                if (strpos($c, '###providers###') === FALSE)
                {
                    $c = preg_replace(
                        '/["\']' . preg_quote('Telenok\Core\CoreServiceProvider', '/') . '["\']/',
                        "\"Telenok\Core\CoreServiceProvider\",\n\"$provider\"",
                        $c
                    );
                }
                else
                {
                    $c = str_replace('###providers###', "\"$provider\",\n###providers###", $c);
                }
            }
            
            file_put_contents(config_path('app.php'), $c);
        }
    }
}