<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateObjectTypeTable extends Migration {

	public function up()
	{
		if (!Schema::hasTable('object_type'))
		{
			Schema::create('object_type', function(Blueprint $table)
			{
				$table->increments('id');
				$table->nullableTimestamps();
				$table->softDeletes();

				$table->mediumText('title')->nullable();
				$table->mediumText('title_list')->nullable();
				$table->string('code')->unique()->nullable();
				$table->integer('active')->unsigned()->nullable();
				$table->timestamp('active_at_start')->nullable();
				$table->timestamp('active_at_end')->nullable();
				$table->timestamp('locked_at')->nullable();
				$table->string('class_model')->nullable();
				$table->string('class_controller')->nullable();
				$table->integer('treeable')->unsigned()->nullable()->default(0);
				$table->integer('created_by_user')->unsigned()->nullable();
				$table->integer('updated_by_user')->unsigned()->nullable();
				$table->integer('deleted_by_user')->unsigned()->nullable()->default(null);
				$table->integer('locked_by_user')->unsigned()->nullable()->default(null); 
			});
		}
	}

}
