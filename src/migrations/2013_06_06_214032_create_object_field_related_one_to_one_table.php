<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateObjectFieldRelatedOnetoOneTable extends Migration {

	public function up()
	{
		if (Schema::hasTable('object_field'))
		{
			Schema::table('object_field', function($table)
			{
				$table->integer('relation_one_to_one_has')->unsigned()->default('0')->nullable();
				$table->integer('relation_one_to_one_belong_to')->unsigned()->default('0')->nullable();
			});
		}
	}

}
