<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateObjectSequenceTable extends Migration {

	public function up()
	{
		if (!Schema::hasTable('object_sequence'))
		{
			Schema::create('object_sequence', function(Blueprint $table)
			{
				$table->increments('id');
				$table->nullableTimestamps();
				$table->softDeletes();
				$table->text('title')->nullable();
				$table->integer('sequences_object_type')->unsigned()->nullable()->default(0);
				$table->integer('treeable')->unsigned()->nullable()->default(0);
				$table->integer('active')->unsigned()->nullable();
				$table->timestamp('active_at_start')->nullable();
				$table->timestamp('active_at_end')->nullable();
				$table->timestamp('locked_at')->nullable();

				$table->string('class_model')->nullable();
				
				$table->integer('created_by_user')->unsigned()->nullable()->default(null);
				$table->integer('updated_by_user')->unsigned()->nullable()->default(null);
				$table->integer('deleted_by_user')->unsigned()->nullable()->default(null);
				$table->integer('locked_by_user')->unsigned()->nullable()->default(null);
			});
		}
	}

}
