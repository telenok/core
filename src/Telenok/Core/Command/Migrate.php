<?php

namespace Telenok\Core\Command;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Illuminate\Database\Migrations\Migrator;

class Migrate extends Command {

    protected $name = 'telenok:migrate';
    protected $description = 'Migrate and seed package';

    /**
     * The migrator instance.
     *
     * @var \Illuminate\Database\Migrations\Migrator
     */
    protected $migrator;

    /**
     * The path to the packages directory (vendor).
     */
    protected $packagePath;

    /**
     * Create a new migration command instance.
     *
     * @param  \Illuminate\Database\Migrations\Migrator  $migrator
     * @param  string  $packagePath
     * @return void
     */
    public function __construct(Migrator $migrator, $packagePath)
    {
	parent::__construct();

	$this->migrator = $migrator;
	$this->packagePath = $packagePath;
    }

    /**
     * Prepare the migration database for running.
     *
     * @return void
     */
    protected function prepareDatabase()
    {
	$this->migrator->setConnection($this->input->getOption('database'));

	if (!$this->migrator->repositoryExists())
	{
	    $options = array('--database' => $this->input->getOption('database'));

	    $this->call('migrate:install', $options);
	}
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
	$this->prepareDatabase();

	// The pretend option can be used for "simulating" the migration and grabbing
	// the SQL queries that would fire if the migration were to be run against
	// a database for real, which is helpful for double checking migrations.
	$pretend = $this->input->getOption('pretend');

	$path = $this->getMigrationPath();

	$this->migrator->run($path, $pretend);

	// Once the migrator has run we will grab the note output and send it out to
	// the console screen, since the migrator itself functions without having
	// any instances of the OutputInterface contract passed into the class.
	foreach ($this->migrator->getNotes() as $note)
	{
	    $this->output->writeln($note);
	}

	// Finally, if the "seed" option has been given, we will re-run the database
	// seed task to re-populate the database, which is convenient when adding
	// a migration and a seed at the same time, as it is only this command.
	if ($this->input->getOption('seed'))
	{
	    $this->call('db:seed', ['--force' => true]);
	}
    }

    /**
     * Get the path to the migration directory.
     *
     * @return string
     */
    protected function getMigrationPath()
    {
	$path = $this->input->getOption('path');

	// First, we will check to see if a path option has been defined. If it has
	// we will use the path relative to the root of this installation folder
	// so that migrations may be run for any path within the applications.
	if (!is_null($path))
	{
	    return $this->laravel['path.base'] . '/' . $path;
	}

	$package = $this->input->getOption('package');

	// If the package is in the list of migration paths we received we will put
	// the migrations in that path. Otherwise, we will assume the package is
	// is in the package directories and will place them in that location.
	if (!is_null($package))
	{
	    return $this->packagePath . '/' . $package . '/src/migrations';
	}

	$bench = $this->input->getOption('bench');

	// Finally we will check for the workbench option, which is a shortcut into
	// specifying the full path for a "workbench" project. Workbenches allow
	// developers to develop packages along side a "standard" app install.
	if (!is_null($bench))
	{
	    $path = "/workbench/{$bench}/src/migrations";

	    return $this->laravel['path.base'] . $path;
	}

	return $this->laravel['path.database'] . '/migrations';
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
	return array(
	    array('bench', null, InputOption::VALUE_OPTIONAL, 'The name of the workbench to migrate.', null),
	    array('database', null, InputOption::VALUE_OPTIONAL, 'The database connection to use.'),
	    array('force', null, InputOption::VALUE_NONE, 'Force the operation to run when in production.'),
	    array('path', null, InputOption::VALUE_OPTIONAL, 'The path to migration files.', null),
	    array('package', null, InputOption::VALUE_OPTIONAL, 'The package to migrate.', null),
	    array('pretend', null, InputOption::VALUE_NONE, 'Dump the SQL queries that would be run.'),
	    array('seed', null, InputOption::VALUE_NONE, 'Indicates if the seed task should be re-run.'),
	);
    }

}
