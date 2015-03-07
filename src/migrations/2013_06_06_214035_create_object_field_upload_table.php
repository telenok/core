<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateObjectFieldUploadTable extends Migration {

	public function up()
	{
		if (Schema::hasTable('object_field'))
		{
			Schema::table('object_field', function($table)
			{
				if (!\Schema::hasColumn('object_field', 'upload_allow_ext'))
				{
					$table->text('upload_allow_ext')->nullable();
				}

				if (!\Schema::hasColumn('object_field', 'upload_allow_mime'))
				{
					$table->text('upload_allow_mime')->nullable();
				}

				if (!\Schema::hasColumn('object_field', 'upload_allow_size'))
				{
					$table->integer('upload_allow_size')->nullable();
				}
			});
		}
	}

}
