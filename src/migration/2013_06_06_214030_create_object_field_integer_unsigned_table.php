<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateObjectFieldIntegerUnsignedTable extends Migration {

	public function up()
	{
		if (Schema::hasTable('object_field'))
		{
			Schema::table('object_field', function($table)
			{
				if (!\Schema::hasColumn('object_field', 'integer_unsigned_min'))
				{
					$table->integer('integer_unsigned_min')->unsigned()->default(0)->nullable();
				}

				if (!\Schema::hasColumn('object_field', 'integer_unsigned_max'))
				{
					$table->integer('integer_unsigned_max')->unsigned()->default(2147483647)->nullable();
				}

				if (!\Schema::hasColumn('object_field', 'integer_unsigned_default'))
				{
					$table->integer('integer_unsigned_default')->unsigned()->nullable()->default(null);
				}
			});
		}
	}

}
