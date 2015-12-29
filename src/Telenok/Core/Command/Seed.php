<?php namespace Telenok\Core\Command;

use Illuminate\Console\Command;

class Seed extends Command implements \Illuminate\Contracts\Bus\SelfHandling {

    protected $name = 'telenok:seed';
    protected $description = 'Seeding Telenok CMS';
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

        $this->info('Create and seed tables');

        if ($this->confirm('Do you want to create and seed tables in database [yes/no]: ', false))
        {
            $this->inputSuperuserLogin();
            $this->inputSuperuserEmail();
            $this->inputSuperuserPassword();

            $this->info('Start creating tables and seed database. Please, wait. It can take some minutes.');

            $this->processingController->createBaseTable($this);

            $this->call('migrate', array('--force' => true, '--path' => 'vendor/telenok/core/src/migrations'));

            $this->processingController->touchInstallFlag();

            $user = \App\Telenok\Core\Model\User\User::where('username', 'admin')->first();

            $user->storeOrUpdate([
                'username' => $this->processingController->getSuperuserLogin(),
                'email' => $this->processingController->getSuperuserEmail(),
                'password' => $this->processingController->getSuperuserPassword(),
            ]);
        }
    }

    public function inputSuperuserPassword()
    {
        while (true)
        {
            $question = new \Symfony\Component\Console\Question\Question('What is password for superuser in backend:', 'random');

            $question->setHidden(true);
            $question->setValidator(null);

            $password = $this->output->askQuestion($question);            

            try
            {
                $this->processingController->setSuperuserPassword($password);
            }
            catch (\Exception $e)
            {
                $this->error($e->getMessage() . ' Please, retry.');

                continue;
            }

            if ($password !== 'random')
            {
                $confirmPassword = $this->secret('Please, type password again to confirm it: ');

                if ($password === $confirmPassword)
                {
                    break;
                }
                else
                {
                    $this->error('Wrong confirmed password. Try again, please.');
                }
            }
            else
            {
                $this->info('Your password: ' . $this->processingController->getSuperuserPassword());

                break;
            }
        }
    }

    public function inputSuperuserLogin()
    {
        while (true)
        {
            $name = $this->ask('What is login for superuser in backend: ');

            try
            {
                $this->processingController->setSuperuserLogin($name);
                break;
            }
            catch (\Exception $e)
            {
                $this->error($e->getMessage() . ' Please, retry.');
            }
        }
    }

    public function inputSuperuserEmail()
    {
        while (true)
        {
            $name = $this->ask('What is superuser\'s email: ');

            try
            {
                $this->processingController->setSuperuserEmail($name);
                break;
            }
            catch (\Exception $e)
            {
                $this->error($e->getMessage() . ' Please, retry.');
            }
        }
    }
}