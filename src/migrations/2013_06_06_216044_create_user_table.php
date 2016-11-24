<?php

use Illuminate\Database\Schema\Blueprint;

class CreateUserTable extends \App\Vendor\Telenok\Core\Support\Migrations\Migration {

    public function up()
    {
        parent::up();

        if (!Schema::hasTable('user'))
		{
			Schema::create('user', function(Blueprint $table)
			{
				$table->increments('id');
				$table->nullableTimestamps();
				$table->softDeletes();
				$table->mediumText('title')->nullable();
				$table->integer('active')->unsigned()->nullable();
				$table->dateTime('active_at_start')->nullable();
				$table->dateTime('active_at_end')->nullable();
				$table->dateTime('locked_at')->nullable();
				$table->string('username')->nullable();
				$table->string('usernick')->nullable();
				$table->string('email')->nullable();
				$table->string('password', 100)->nullable();
				$table->rememberToken();
				$table->longText('configuration')->nullable();
				$table->integer('author_user_message')->unsigned()->nullable();
				$table->integer('created_by_user')->unsigned()->nullable();
				$table->integer('updated_by_user')->unsigned()->nullable();
				$table->integer('deleted_by_user')->unsigned()->nullable()->default(null);
				$table->integer('locked_by_user')->unsigned()->nullable()->default(null); 
			});
		}
	}

}
