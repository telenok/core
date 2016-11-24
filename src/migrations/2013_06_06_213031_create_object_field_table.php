<?php

use Illuminate\Database\Schema\Blueprint;

class CreateObjectFieldTable extends \App\Vendor\Telenok\Core\Support\Migrations\Migration {

    public function up()
    {
        parent::up();

        if (!Schema::hasTable('object_field'))
		{
			Schema::create('object_field', function(Blueprint $table)
			{
				$table->increments('id');
				$table->nullableTimestamps();
				$table->softDeletes();

				$table->mediumText('title')->nullable();
				$table->mediumText('title_list')->nullable();
				$table->string('code')->nullable();
				$table->integer('created_by_user')->unsigned()->nullable();
				$table->integer('updated_by_user')->unsigned()->nullable();
				$table->integer('deleted_by_user')->unsigned()->nullable()->default(null);
				$table->integer('locked_by_user')->unsigned()->nullable()->default(null); 
				$table->integer('active')->unsigned()->nullable();
				$table->dateTime('active_at_start')->nullable();
				$table->dateTime('active_at_end')->nullable();
				$table->dateTime('locked_at')->nullable();
				$table->string('key')->nullable();
				$table->string('rule')->nullable();
				$table->string('field_view')->nullable();
				$table->integer('field_object_type')->unsigned()->nullable();
				$table->integer('field_object_tab')->unsigned()->nullable();
				$table->integer('required')->unsigned()->nullable();
				$table->integer('show_in_list')->unsigned()->nullable()->default(0);
				$table->integer('show_in_form')->unsigned()->nullable()->default(0);
				$table->integer('allow_search')->unsigned()->nullable()->default(0);
				$table->integer('allow_create')->unsigned()->nullable()->default(0);
				$table->integer('allow_update')->unsigned()->nullable()->default(0);
				$table->integer('allow_sort')->unsigned()->nullable()->default(0);
				$table->integer('multilanguage')->unsigned()->nullable()->default(0);
				$table->integer('field_order')->unsigned()->nullable()->default(0);
				$table->string('css_class')->nullable();
				$table->string('icon_class')->nullable();
				$table->mediumText('description')->nullable();

				$table->index('field_object_type');
			});
		}
	}

}
