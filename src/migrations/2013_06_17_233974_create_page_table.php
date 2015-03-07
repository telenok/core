<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePageTable extends Migration {

	public function up()
	{
		if (!Schema::hasTable('page'))
		{
			Schema::create('page', function(Blueprint $table)
			{
				$table->increments('id');
				$table->nullableTimestamps();
				$table->softDeletes();
				$table->text('title')->nullable();
				$table->text('title_ceo')->nullable();
				$table->text('description_ceo')->nullable();
				$table->string('keywords_ceo')->nullable();
				$table->string('template_view')->nullable();
				$table->string('url_pattern')->nullable();
				$table->string('url_redirect')->nullable();
				$table->integer('page_page_controller')->unsigned()->nullable();
				$table->integer('active')->unsigned()->nullable();
				$table->timestamp('active_at_start')->nullable();
				$table->timestamp('active_at_end')->nullable();
				$table->timestamp('locked_at')->nullable();
				$table->integer('created_by_user')->unsigned()->nullable();
				$table->integer('updated_by_user')->unsigned()->nullable();
				$table->integer('deleted_by_user')->unsigned()->nullable()->default(null);
				$table->integer('locked_by_user')->unsigned()->nullable()->default(null); 
			});
		}
	}

}
