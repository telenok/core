<?php namespace Telenok\Core\Command;

use Illuminate\Console\Command;

class PackagesUpdate extends Command implements \Illuminate\Contracts\Bus\SelfHandling {

    protected $name = 'telenok:packagesupdate';
    protected $description = 'Updating Telenok CMS packages';

    public function fire()
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
}