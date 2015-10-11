<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateObjectFieldActiveTable extends Migration {

	public function up()
	{
		if (Schema::hasTable('object_field'))
		{
			Schema::table('object_field', function($table)
			{
				if (!\Schema::hasColumn('object_field', 'active_default'))
				{
					$table->integer('active_default')->unsigned()->nullable();
				}
			});
		}
	}

}
