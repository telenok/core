<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateObjectFieldTimerangeTable extends Migration {

	public function up()
	{
		if (Schema::hasTable('object_field'))
		{
			Schema::table('object_field', function($table)
			{
				if (!\Schema::hasColumn('object_field', 'time_range_default_start'))
				{
					$table->timestamp('time_range_default_start')->nullable();
				}

				if (!\Schema::hasColumn('object_field', 'time_range_default_end'))
				{
					$table->timestamp('time_range_default_end')->nullable();
				}
			});
		}
	}
}
