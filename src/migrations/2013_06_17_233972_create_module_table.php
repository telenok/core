<?php

use Illuminate\Database\Schema\Blueprint;

class CreateModuleTable extends \App\Vendor\Telenok\Core\Support\Migrations\Migration {

    public function up()
    {
        parent::up();

        if (!Schema::hasTable('module'))
		{
			Schema::create('module', function(Blueprint $table)
			{
				$table->increments('id');
				$table->nullableTimestamps();
				$table->softDeletes();
				$table->mediumText('title')->nullable();
				$table->string('controller_class')->nullable();
				$table->integer('active')->unsigned()->nullable();
				$table->integer('module_order')->unsigned()->nullable();
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
