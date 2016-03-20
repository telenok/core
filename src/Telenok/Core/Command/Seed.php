<?php namespace Telenok\Core\Command;

use Illuminate\Console\Command;

/**
 * @class Telenok.Core.Command.Seed
 * Command to seed database
 * 
 * @extends Illuminate.Console.Command
 */
class Seed extends Command {

    /**
     * @protected
     * @property {String} $name
     * Command name. Calling without parameters.
     * @member Telenok.Core.Command.Seed
     */
    protected $name = 'telenok:seed';

    /**
     * @protected
     * @property {String} $description
     * Command description.
     * @member Telenok.Core.Command.Seed
     */
    protected $description = 'Seeding Telenok CMS';

    /**
     * @protected
     * @property {Telenok.Core.Support.Install.Controller} $processingController 
     * Object which processed command data.
     * @member Telenok.Core.Command.Seed
     */
    protected $processingController;

    /**
     * @method setProcessingController
     * Set processing controller
     * @member Telenok.Core.Command.Seed
     * @param {Telenok.Core.Support.Install.Controller} $param
     * @return {void}
     */
    public function setProcessingController($param = null)
    {
        $this->processingController = $param;
    }

    /**
     * @method getProcessingController
     * Get processing controller
     * @member Telenok.Core.Command.Seed
     * @return {Telenok.Core.Support.Install.Controller}
     */
    public function getProcessingController()
    {
        return $this->processingController;
    }

    /**
     * @method fire
     * Fire command processing
     * @member Telenok.Core.Command.Seed
     * @return {void}
     */
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

    /**
     * @method inputSuperuserPassword
     * Fill password for superuser in administration panel
     * @member Telenok.Core.Command.Seed
     * @return {void}
     */
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

    /**
     * @method inputSuperuserLogin
     * Fill login for superuser in administration panel
     * @member Telenok.Core.Command.Seed
     * @return {void}
     */
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


    /**
     * @method inputSuperuserEmail
     * Fill email for superuser in administration panel
     * @member Telenok.Core.Command.Seed
     * @return {void}
     */
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