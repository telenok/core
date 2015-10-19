<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateObjectFieldMorphOneToManyTable extends Migration {

	public function up()
	{
		if (Schema::hasTable('object_field'))
		{
			Schema::table('object_field', function($table)
			{
				$table->integer('morph_one_to_many_has')->unsigned()->default('0')->nullable();
				$table->integer('morph_one_to_many_belong_to')->unsigned()->default('0')->nullable();
				$table->string('morph_one_to_many_belong_to_type_list')->nullable();
			});
		}
	}

}
