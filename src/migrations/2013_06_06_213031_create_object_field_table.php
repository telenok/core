<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateObjectFieldTable extends Migration {

	public function up()
	{
		if (!Schema::hasTable('object_field'))
		{
			Schema::create('object_field', function(Blueprint $table)
			{
				$table->increments('id');
				$table->nullableTimestamps();
				$table->softDeletes();

				$table->text('title')->nullable();
				$table->text('title_list')->nullable();
				$table->string('code')->nullable();
				$table->integer('created_by_user')->unsigned()->nullable();
				$table->integer('updated_by_user')->unsigned()->nullable();
				$table->integer('deleted_by_user')->unsigned()->nullable()->default(null);
				$table->integer('locked_by_user')->unsigned()->nullable()->default(null); 
				$table->integer('active')->unsigned()->nullable();
				$table->timestamp('active_at_start')->nullable();
				$table->timestamp('active_at_end')->nullable();
				$table->timestamp('locked_at')->nullable();
				$table->string('key')->nullable();
				$table->string('rule')->nullable();
				$table->string('field_view')->nullable();
				$table->integer('field_object_type')->unsigned()->nullable();
				$table->integer('field_object_tab')->unsigned()->nullable();
				$table->integer('required')->unsigned()->nullable();
				$table->integer('show_in_list')->unsigned()->nullable()->default(0);
				$table->integer('show_in_form')->unsigned()->nullable()->default(0);
				$table->integer('allow_search')->unsigned()->nullable()->default(0);
				$table->integer('allow_create')->unsigned()->nullable()->default(1);
				$table->integer('allow_update')->unsigned()->nullable()->default(1);
				$table->integer('allow_sort')->unsigned()->nullable()->default(0);
				$table->integer('multilanguage')->unsigned()->nullable()->default(0);
				$table->integer('field_order')->unsigned()->nullable()->default(0);
				$table->string('css_class')->nullable();
				$table->string('icon_class')->nullable();
				$table->text('description')->nullable();

				$table->index('field_object_type');
			});
		}
	}

}
