<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserMessageTable extends Migration {

	public function up()
	{
		if (!Schema::hasTable('user_message'))
		{
			Schema::create('user_message', function(Blueprint $table)
			{
				$table->increments('id');
				$table->nullableTimestamps();
				$table->softDeletes();
				$table->text('title')->nullable();
				$table->integer('active')->unsigned()->nullable();
				$table->timestamp('active_at_start')->nullable();
				$table->timestamp('active_at_end')->nullable();
				$table->timestamp('locked_at')->nullable();
				$table->integer('author_user_message')->unsigned()->nullable();
				$table->text('content')->nullable();
				$table->integer('created_by_user')->unsigned()->nullable();
				$table->integer('updated_by_user')->unsigned()->nullable();
				$table->integer('deleted_by_user')->unsigned()->nullable()->default(null);
				$table->integer('locked_by_user')->unsigned()->nullable()->default(null); 
			});
		}
	}

}
