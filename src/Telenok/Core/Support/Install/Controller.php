<?php namespace Telenok\Core\Support\Install;

class Controller {

	protected $domain = '';
	protected $domainSecure = false;
	protected $superuserLogin = '';
	protected $superuserEmail = '';
	protected $superuserPassword = '';
	protected $locale = '';
	protected $dbDriver = '';
	protected $dbHost = '';
	protected $dbUsername = '';
	protected $dbPassword = '';
	protected $dbDatabase = '';
	protected $dbPrefix = '';

	protected $installFlag = 'telenok/installedTelenokCore.lock';
	
	public function setDomain($param = '')
	{
		if ($this->validateDomainOrIp($param))
		{
			$this->domain = $param;
		}
		else
		{
			throw new \Exception('Wrong domain or domain doesnt link to IP or wrong IP, may be server not running ?');
		}

		return $this;
	}

	public function getDomain()
	{
		return $this->domain;
	}

	public function setSuperuserLogin($param = '')
	{
		if (preg_match('/^[A-Za-z][A-Za-z0-9_]+$/', $param))
		{
			$this->superuserLogin = $param;
		}
		else
		{
			throw new \Exception('Wrong superuser login.');
		}

		return $this;
	}

	public function getSuperuserLogin()
	{
		return $this->superuserLogin;
	}

	public function setSuperuserEmail($param = '')
	{
		if (mb_strlen($param))
		{
			$this->superuserEmail = $param;
		}
		else
		{
			throw new \Exception('Wrong superuser email.');
		}

		return $this;
	}

	public function getSuperuserEmail()
	{
		return $this->superuserEmail;
	}

	public function setSuperuserPassword($param = '')
	{
        if ($param == 'random')
        {
			$this->superuserPassword = str_random(8);
        }
		else if (mb_strlen($param) < ($length = config('auth.password.length-min', 8)))
		{
			throw new \Exception('Wrong superuser password, it should be at least ' . $length . ' symbols.');
		}
		else
		{
			$this->superuserPassword = $param;
		}

		return $this;
	}

	public function getSuperuserPassword()
	{
		return $this->superuserPassword;
	}

	public function setLocale($param = '')
	{
		if (preg_match('/^[a-z]{2}$/', $param))
		{
			$this->locale = $param;
		}
		else
		{
			throw new \Exception('Wrong locale. It should contain two symbols like "en" or "ru"');
		}

		return $this;
	}

	public function getLocale()
	{
		return $this->locale;
	}

	public function setDbDatabase($param = '')
	{
		if (preg_match('/^[A-Za-z][A-Za-z0-9_]+$/', $param))
		{
			$this->dbDatabase = $param;
		}
		else
		{
			throw new \Exception('Wrong database name.');
		}

		return $this;
	}

	public function getDbDatabase()
	{
		return $this->dbDatabase;
	}

	public function setDbDriver($param = '')
	{
		if (in_array($param, ['sqlite', 'mysql', 'pgsql', 'sqlsrv'], true))
		{
			$this->dbDriver = $param;
		}
		else
		{
			throw new \Exception('Wrong database driver. Choose one of sqlite, mysql, pgsql, sqlsrv.');
		}

		return $this;
	}

	public function getDbDriver()
	{
		return $this->dbDriver;
	}

	public function setDbHost($param = '')
	{
		if ($this->validateDomainOrIp($param))
		{
			$this->dbHost = $param;
		}
		else if ($this->dbDriver != 'sqlite')
		{
			throw new \Exception('Wrong domain or domain hasnt link to IP or wrong IP, may be LAMP server (like Denwer, XAMPP) not running ?');
		}

		return $this;
	}

	public function getDbHost()
	{
		return $this->dbHost;
	}

	public function setDbUsername($param = '')
	{
		if (mb_strlen($param))
		{
			$this->dbUsername = $param;
		}
		else if ($this->dbDriver != 'sqlite')
		{
			throw new \Exception('Wrong database username.');
		}

		return $this;
	}

	public function getDbUsername()
	{
		return $this->dbUsername;
	}

	public function setDbPassword($param = '')
	{
		$this->dbPassword = $param ? $param : '';

		return $this;
	}

	public function getDbPassword()
	{
		return $this->dbPassword;
	}

	public function setDbPrefix($param = '')
	{
		if (mb_strlen($param) && preg_match('/^[A-Za-z][A-Za-z0-9_]+$/', $param))
		{
			$this->dbPrefix = $param;
		}
		else if (mb_strlen($param))
		{
			throw new \Exception('Wrong database prefix.');
		}

		return $this;
	}

	public function getDbPrefix()
	{
		return $this->dbPrefix;
	}

	public function setDomainSecure($param = '')
	{
		$this->domainSecure = $param === true || $param == 'yes' || $param == 'y' ? true : false;

		return $this;
	}

	public function getDomainSecure()
	{
		return $this->domainSecure;
	}

	public function validateDomainOrIp($param)
	{
		return mb_strlen($param);
	}
	
	public function processConfigAppFile()
	{
		$param = array(
			'APP_URL' => 'http' . ($this->domainSecure ? 's' : '') . '://' . $this->domain,
			'APP_LOCALE' => $this->locale,
			'APP_KEY' => str_random(),
		);

		$path = base_path('.env');
		$path_example = base_path('.env.example');

		if (!file_exists($path))
		{
			copy($path_example, $path);
		}
		
		if (!file_exists($path))
		{
			throw new \Exception('Cant create "' . $path . '" file');
		}
		
		$stub = file_get_contents($path);

		foreach ($param as $k => $v)
		{
			if (preg_match('/^[ \t]*' . preg_quote($k, '/') . '=.*$/imu', $stub))
			{
				$stub = preg_replace('/^[ \t]*' . preg_quote($k, '/') . '=.*$/imu', $k . '=' . $v, $stub);
			}
			else
			{
				$stub .= "\n" . $k . '=' . $v;
			}
		}
		
		file_put_contents($path, $stub, LOCK_EX);
	}

	public function processConfigDatabaseFile()
	{
		$param = array(
			'DB_CONNECTION_DEFAULT' => $this->dbDriver,
			'DB_DATABASE' => $this->dbDatabase,
			'DB_HOST' => $this->dbHost,
			'DB_USERNAME' => $this->dbUsername,
			'DB_PASSWORD' => $this->dbPassword,
			'DB_PREFIX' => $this->dbPrefix,
		);

		$path = base_path('.env');
		$path_example = base_path('.env.example');

		if (!file_exists($path))
		{
			copy($path_example, $path);
		}
		
		if (!file_exists($path))
		{
			throw new \Exception('Cant create "' . $path . '" file');
		}
		
		$stub = file_get_contents($path);

		foreach ($param as $k => $v)
		{
			if (preg_match('/^[ \t]*' . preg_quote($k, '/') . '=.*$/imu', $stub))
			{
				$stub = preg_replace('/^[ \t]*' . preg_quote($k, '/') . '=.*$/imu', $k . '=' . $v, $stub);
			}
			else
			{
				$stub .= "\n" . $k . '=' . $v;
			}
		} 

		// validate database connection
		$conn = array(
			'driver' => $this->dbDriver,
			'host' => $this->dbHost,
			'database' => $this->dbDatabase,
			'username' => $this->dbUsername,
			'password' => $this->dbPassword,
			'charset' => 'utf8',
			'collation' => 'utf8_unicode_ci',
			'prefix' => $this->dbPrefix,
		);

		\Config::set('database.connections.install', $conn);
		\Config::set('database.default', 'install');

		try
		{
			if (\Schema::hasTable('deletemeplease'))
			{
				\Schema::drop('deletemeplease');
			}

			\Schema::create('deletemeplease', function($table)
			{
				$table->increments('id');
			});

			\Schema::drop('deletemeplease');
		}
		catch (\Exception $e)
		{
			throw new \Exception('Cant create table in database. Please, validate setting in app/config/database.php or set its again with current console command.');
		}

		file_put_contents($path, $stub, LOCK_EX);
	}

	public function installFlag()
	{
		return storage_path($this->installFlag);
	}
	
	public function touchInstallFlag()
	{
		touch($this->installFlag());
	}

	public function createBaseTable($commandObject)
	{
		if (!\Schema::hasTable('setting'))
		{
			\Schema::create('setting', function(\Illuminate\Database\Schema\Blueprint $table)
			{
				$table->increments('id');
				$table->timestamps();
				$table->softDeletes();

				$table->mediumText('title')->nullable();
				$table->string('code')->nullable()->default(null)->unique('code');
				$table->mediumText('value');
				$table->integer('active')->unsigned()->nullable()->default(null);
				$table->timestamp('active_at_start');
				$table->timestamp('active_at_end');
				$table->integer('created_by_user')->unsigned()->nullable()->default(null);
				$table->integer('updated_by_user')->unsigned()->nullable()->default(null);
				$table->integer('deleted_by_user')->unsigned()->nullable()->default(null);
			});
		}
		else
		{
			$commandObject->info('Seems, table "setting" already exists');
		}

		try
		{
			$commandObject->call('migrate:install');
		}
		catch (\Exception $ex)
		{
			$commandObject->info('Seems, table "migrations" already exists');
		}
	}
	
}
