<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateObjectFieldSelectOneTable extends Migration {

	public function up()
	{
		if (Schema::hasTable('object_field'))
		{
			Schema::table('object_field', function($table)
			{
				if (!\Schema::hasColumn('object_field', 'select_one_data'))
				{
					$table->text('select_one_data')->nullable();
				}
			});
		}
	}

}
