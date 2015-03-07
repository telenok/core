<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateObjectFieldFileManyToManyTable extends Migration {

	public function up()
	{
		if (Schema::hasTable('object_field'))
		{
			Schema::table('object_field', function($table)
			{
				if (!\Schema::hasColumn('object_field', 'file_many_to_many_allow_ext'))
				{
					$table->text('file_many_to_many_allow_ext')->nullable();
				}

				if (!\Schema::hasColumn('object_field', 'file_many_to_many_allow_mime'))
				{
					$table->text('file_many_to_many_allow_mime')->nullable();
				}
			});
		}
	}

}
