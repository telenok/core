<?php namespace Telenok\Core\Command;

use Illuminate\Console\Command;

/**
 * Command to add licensed Telenok Packages
 * 
 * @class Telenok.Core.Command.Package
 * @extends Illuminate.Console.Command
 * @mixin Illuminate.Contracts.Bus.SelfHandling
 */
class Package extends Command implements \Illuminate\Contracts\Bus\SelfHandling {

    /**
     * @protected
     * @property {String} $name
     * Command name. Calling without parameters.
     * @member Telenok.Core.Command.Package
     */
    protected $name = 'telenok:package {action=refresh} {--provider=null}';

    /**
     * @protected
     * @property {String} $description
     * Command description.
     * @member Telenok.Core.Command.Package
     */
    protected $description = 'Updating Telenok CMS packages';

    /**
     * @protected
     * @property {String} $signature
     * Command signature.
     * @member Telenok.Core.Command.Package
     */
    protected $signature = 'telenok:package
                        {action : Can be "refresh" or "add-provider"}
                        {--provider= : For action="add-provider". The service provider should be added to app.php. Example: "Telenok\News\NewsServiceProvider"}';

    /**
     * @method fire
     * Fire command processing
     * @member Telenok.Core.Command.Package
     * @return {void}
     */
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
            
            file_put_contents(config_path('app.php'), $c, LOCK_EX);
        }
    }
}