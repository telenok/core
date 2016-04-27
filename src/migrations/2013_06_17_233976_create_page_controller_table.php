<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePageControllerTable extends Migration {

	public function up()
	{
		if (!Schema::hasTable('page_controller'))
		{
			Schema::create('page_controller', function(Blueprint $table)
			{
				$table->increments('id');
				$table->nullableTimestamps();
				$table->softDeletes();
				$table->mediumText('title')->nullable();
				$table->string('controller_class')->nullable();
				$table->string('controller_method')->nullable();
				$table->integer('active')->unsigned()->nullable();
				$table->dateTime('active_at_start')->nullable();
				$table->dateTime('active_at_end')->nullable();
				$table->dateTime('locked_at')->nullable();
				$table->integer('created_by_user')->unsigned()->nullable();
				$table->integer('updated_by_user')->unsigned()->nullable();
				$table->integer('deleted_by_user')->unsigned()->nullable()->default(null);
				$table->integer('locked_by_user')->unsigned()->nullable()->default(null); 
			});
		}
	}

}
