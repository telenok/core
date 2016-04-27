<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateObjectFieldDatetimerangeTable extends Migration {

	public function up()
	{
		if (Schema::hasTable('object_field'))
		{
			Schema::table('object_field', function($table)
			{
				if (!\Schema::hasColumn('object_field', 'datetime_range_default_start'))
				{
					$table->dateTime('datetime_range_default_start')->nullable();
				}

				if (!\Schema::hasColumn('object_field', 'datetime_range_default_end'))
				{
					$table->dateTime('datetime_range_default_end')->nullable();
				}
			});
		}
	}
}
