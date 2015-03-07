<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateObjectVersionTable extends Migration {

	public function up()
	{
		if (!Schema::hasTable('object_version'))
		{
			Schema::create('object_version', function(Blueprint $table)
			{
				$table->increments('id');
				$table->nullableTimestamps();
				$table->softDeletes();

				$table->text('title')->nullable();
				$table->integer('object_id')->nullable();
				$table->integer('object_type_id')->nullable();
				$table->integer('active')->unsigned()->nullable()->default(null);
				$table->timestamp('active_at_start')->nullable();
				$table->timestamp('active_at_end')->nullable();
				$table->timestamp('locked_at')->nullable();
				$table->integer('created_by_user')->unsigned()->nullable()->default(null);
				$table->integer('updated_by_user')->unsigned()->nullable()->default(null);
				$table->integer('deleted_by_user')->unsigned()->nullable()->default(null);
				$table->integer('locked_by_user')->unsigned()->nullable()->default(null); 
				$table->mediumText('object_data')->nullable();
			});
		}
	}

}
