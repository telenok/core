<?php namespace Telenok\Core\Command;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

/**
 * Command to add licensed Telenok Packages
 * 
 * @class Telenok.Core.Command.Package
 * @extends Illuminate.Console.Command
 */
class Package extends Command {

    /**
     * @protected
     * @property {String} $name
     * Command name. Calling without parameters.
     * @member Telenok.Core.Command.Package
     */
    protected $name = 'telenok:package';

    /**
     * @protected
     * @property {String} $description
     * Command description.
     * @member Telenok.Core.Command.Package
     */
    protected $description = 'Updating Telenok CMS packages';

    protected function getArguments() {
        return [
            ['action', InputArgument::OPTIONAL, 'Can be "refresh", "add-provider", "add-listener"', null],
        ];
    }

    protected function getOptions() {
        return [
            ['provider', 'p', InputOption::VALUE_OPTIONAL,
                'What should be service provider added to app.php. Example: "\App\Vendor\Telenok\News\NewsServiceProvider"',
                null],
            ['listener', 'l', InputOption::VALUE_OPTIONAL,
                'What should be listener added to \App\Providers\EventServiceProvider',
                null],
        ];
    }

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
            $this->refreshCommand();
        }
        else if ($this->argument('action') == 'add-provider')
        {
            $this->addProviderCommand();
        }
        else if ($this->argument('action') == 'add-listener' && ($provider = $this->option('listener')))
        {
            $this->addListenerCommand();
        }
    }

    public function refreshCommand()
    {
        $this->info('Updating Telenok CMS package/s');

        $composer = (new \Telenok\Core\Composer\Application())->getEmbeddedComposer();

        $installationManager = $composer->getInstallationManager();

        if ($package = $this->hasOption('package'))
        {
            $allPackages = [$package];
        }
        else
        {
            $allPackages = $composer->getRepositoryManager()->getLocalRepository()->getPackages();
        }

        $packageArtisan = [];

        foreach ($allPackages as $package)
        {
            $originDir = $installationManager->getInstallPath($package);

            $files = glob(rtrim($originDir, '\\/') . '/resources/package-process/artisan.*.php');

            foreach($files as $file)
            {
                $packageArtisan[basename($file) . $package->getName()] = ['file' => $file, 'package' => $package->getName()];
            }
        }

        ksort($packageArtisan);

        foreach($packageArtisan as $script)
        {
            if (file_exists($script['file']))
            {
                $this->info('Process package ' . $script['package']);
                $this->comment('Include and process ' . $script['file']);
                require $script['file'];
            }
        }
    }

    public function addProviderCommand()
    {
        if (!($provider = $this->option('provider')))
        {
            $this->error('Please, set option --provider=\Your\Class\Provider');

            return;
        }

        $c = file_get_contents(config_path('app.php'));

        if (strpos($c, $provider) === FALSE)
        {
            $this->line('Update app.php. Try to add ' . $provider);

            if (strpos($c, '###providers###') === FALSE)
            {
                $this->error('Please, add marker ###providers### to config/app.php file in "providers" array');

                return;
            }
            else
            {
                $c = str_replace('###providers###', "'$provider',\n###providers###", $c);
            }
        }

        file_put_contents(config_path('app.php'), $c, LOCK_EX);
    }

    public function addListenerCommand()
    {
        if (!($listener = $this->option('listener')))
        {
            $this->error('Please, set option --listener=\Your\Class\Listener');

            return;
        }

        $c = file_get_contents(app_path('Providers/EventServiceProvider.php'));

        if (strpos($c, $listener) === FALSE)
        {
            $this->line('Update \App\Providers\EventServiceProvider. Try to add ' . $listener);

            if (strpos($c, '###listener###') === FALSE)
            {
                $this->error('Please, put marker ###listener### to \App\Providers\EventServiceProvider class in "$subscribe" member');

                return;
            }
            else
            {
                $c = str_replace('###listener###', "'$listener',\n###listener###", $c);
            }
        }

        file_put_contents(app_path('Providers/EventServiceProvider.php'), $c, LOCK_EX);
    }
}