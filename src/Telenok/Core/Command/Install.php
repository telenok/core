<?php namespace Telenok\Core\Command;

use Illuminate\Console\Command;

/**
 * Command to install core package
 * 
 * @class Telenok.Core.Command.Install
 * @extends Illuminate.Console.Command
 * @interface Illuminate.Contracts.Bus.SelfHandling
 */
class Install extends Command implements \Illuminate\Contracts\Bus\SelfHandling {

    /**
     * @protected
     * @property {String} $name
     * Command name. Calling without parameters.
     * @member Telenok.Core.Command.Install
     */
    protected $name = 'telenok:install';

    /**
     * @protected
     * @property {String} $description
     * Command description.
     * @member Telenok.Core.Command.Install
     */
    protected $description = 'Installing Telenok CMS';

    /**
     * @protected
     * @property {App.Telenok.Core.Support.Install.Controller} $processingController 
     * Object which processed command data.
     * @member Telenok.Core.Command.Install
     */
    protected $processingController;

    /**
     * @method setProcessingController
     * Set processing controller
     * @member Telenok.Core.Command.Install
     * @param {App.Telenok.Core.Support.Install.Controller}
     * @return {void}
     */
    public function setProcessingController($param = null)
    {
        $this->processingController = $param;
    }

    /**
     * @method getProcessingController
     * Get processing controller
     * @member Telenok.Core.Command.Install
     * @return {App.Telenok.Core.Support.Install.Controller}
     */
    public function getProcessingController()
    {
        return $this->processingController;
    }

    /**
     * @method fire
     * Fire command processing
     * @member Telenok.Core.Command.Install
     * @return {void}
     */
    public function fire()
    {
        $this->setProcessingController(app('\App\Telenok\Core\Support\Install\Controller'));

        $this->info('Configure Telenok CMS');

        if ($this->confirm('Do you want to configure enviroment for app.php [yes/no]: ', false))
        {
            $this->inputDomain();
            $this->inputDomainSecure();
            $this->inputLocale();

            if ($this->confirm('Do you want to update .env file [yes/no]: ', false))
            {
                try
                {
                    $this->processingController->processConfigAppFile();

                    $this->info('Done. Thank you.');
                }
                catch (\Exception $ex)
                {
                    $this->error('Sorry, an error occured.');
                    $this->error($ex->getMessage());
                }
            }
        }

        if ($this->confirm('Do you want to configure enviroment for database.php [yes/no]: ', false))
        {
            $this->inputDbDriver();
            $this->inputDbHost();
            $this->inputDbUsername();
            $this->inputDbPassword();
            $this->inputDbDatabase();
            $this->inputDbPrefix();

            if ($this->confirm('Do you want to update .env file [yes/no]: ', false))
            {
                try
                {
                    $this->processingController->processConfigDatabaseFile();

                    $this->info('Done. Thank you.');
                }
                catch (\Exception $ex)
                {
                    $this->error('Sorry, an error occured.');
                    $this->error($ex->getMessage());
                }
            }
        }
        
        $this->processingController->createBaseTable($this);
    }

    /**
     * @method inputDomain
     * Fill domain from console
     * @member Telenok.Core.Command.Install
     * @return {void}
     */
    public function inputDomain()
    {
        while (true)
        {
            $name = $this->ask('What is site domain or IP, eg, mysite.com or 192.168.0.1: ');

            $this->info('Wait, please...');

            try
            {
                $this->processingController->setDomain($name);
                break;
            }
            catch (\Exception $e)
            {
                $this->error($e->getMessage() . ' Please, retry.');
            }
        }
    }

    /**
     * @method inputDomainSecure
     * Fill domain secure param (yes/no https) from console
     * @member Telenok.Core.Command.Install
     * @return {void}
     */
    public function inputDomainSecure()
    {
        $this->processingController->setDomainSecure($this->confirm('Is domain secure (aka site uses https) [yes/no]: '));
    }

    /**
     * @method inputLocale
     * Fill site locale from console
     * @member Telenok.Core.Command.Install
     * @return {void}
     */
    public function inputLocale()
    {
        while (true)
        {
            $name = $this->ask('What is locale, eg, en: ');

            try
            {
                $this->processingController->setLocale($name);
                break;
            }
            catch (\Exception $e)
            {
                $this->error($e->getMessage() . ' Please, retry.');
            }
        }
    }

    /**
     * @method inputDbDriver
     * Fill default database driver from console
     * @member Telenok.Core.Command.Install
     * @return {void}
     */
    public function inputDbDriver()
    {
        while (true)
        {
            $name = $this->ask('What is database driver, eg, mysql: ');

            try
            {
                $this->processingController->setDbDriver($name);
                break;
            }
            catch (\Exception $e)
            {
                $this->error($e->getMessage() . ' Please, retry.');
            }
        }
    }

    /**
     * @method inputDbHost
     * Fill host for default database driver from console
     * @member Telenok.Core.Command.Install
     * @return {void}
     */
    public function inputDbHost()
    {
        while (true)
        {
            $name = $this->ask('What is database host, eg, 127.0.0.1 or mysql.mysite.com: ');

            $this->info('Wait, please...');

            try
            {
                $this->processingController->setDbHost($name);
                break;
            }
            catch (\Exception $e)
            {
                $this->error($e->getMessage() . ' Please, retry.');
            }
        }
    }

    /**
     * @method inputDbUsername
     * Fill username for default database driver from console
     * @member Telenok.Core.Command.Install
     * @return {void}
     */
    public function inputDbUsername()
    {
        while (true)
        {
            $name = $this->ask('What is database username: ');

            try
            {
                $this->processingController->setDbUsername($name);
                break;
            }
            catch (\Exception $e)
            {
                $this->error($e->getMessage() . ' Please, retry.');
            }
        }
    }

    /**
     * @method inputDbPassword
     * Fill password for default database driver from console
     * @member Telenok.Core.Command.Install
     * @return {void}
     */
    public function inputDbPassword()
    {
        while (true)
        {
            $name = $this->ask('What is database user\'s password: ', false);

            try
            {
                $this->processingController->setDbPassword($name);
                break;
            }
            catch (\Exception $e)
            {
                $this->error($e->getMessage() . ' Please, retry.');
            }
        }
    }

    /**
     * @method inputDbDatabase
     * Fill name of database for default database driver from console
     * @member Telenok.Core.Command.Install
     * @return {void}
     */
    public function inputDbDatabase()
    {
        while (true)
        {
            $name = $this->ask('What is database name: ');

            try
            {
                $this->processingController->setDbDatabase($name);
                break;
            }
            catch (\Exception $e)
            {
                $this->error($e->getMessage() . ' Please, retry.');
            }
        }
    }

    /**
     * @method inputDbPrefix
     * Fill prefix of database for default database driver from console. Can be empty.
     * @member Telenok.Core.Command.Install
     * @return {void}
     */
    public function inputDbPrefix()
    {
        while (true)
        {
            $name = $this->ask('What is database prefix [empty default]: ', false);

            try
            {
                $this->processingController->setDbPrefix($name);
                break;
            }
            catch (\Exception $e)
            {
                $this->error($e->getMessage() . ' Please, retry.');
            }
        }
    }
}