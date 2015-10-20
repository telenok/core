<?php namespace Telenok\Core\Command;

use Illuminate\Console\Command;

class Install extends Command implements \Illuminate\Contracts\Bus\SelfHandling {

    protected $name = 'telenok:install';
    protected $description = 'Installing Telenok CMS';
    protected $processingController;

    public function setProcessingController($param = null)
    {
        $this->processingController = $param;
    }

    public function getProcessingController()
    {
        return $this->processingController;
    }

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

    public function inputDomainSecure()
    {
        $this->processingController->setDomainSecure($this->confirm('Is domain secure (aka site uses https) [yes/no]: '));
    }

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

    public function inputDbPassword()
    {
        while (true)
        {
            $name = $this->ask('What is database user\'s password: ', "");

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

    public function inputDbPrefix()
    {
        while (true)
        {
            $name = $this->ask('What is database prefix [empty default]: ', "");

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
