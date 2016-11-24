<?php

class CreateObjectFieldUploadTable extends \App\Vendor\Telenok\Core\Support\Migrations\Migration {

    public function up()
    {
        parent::up();

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

				if (!\Schema::hasColumn('object_field', 'upload_storage'))
				{
					$table->text('upload_storage')->nullable();
				}
			});
		}
	}
}