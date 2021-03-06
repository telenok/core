<?php

class CreateObjectFieldStringTable extends \App\Vendor\Telenok\Core\Support\Migrations\Migration {

    public function up()
    {
        parent::up();

        if (Schema::hasTable('object_field'))
		{
			Schema::table('object_field', function($table)
			{
				if (!\Schema::hasColumn('object_field', 'string_default'))
				{
					$table->mediumText('string_default')->nullable();
				}

				if (!\Schema::hasColumn('object_field', 'string_regex'))
				{
					$table->string('string_regex')->nullable();
				}

				if (!\Schema::hasColumn('object_field', 'string_max'))
				{
					$table->integer('string_max')->unsigned()->default(1000)->nullable();
				}

				if (!\Schema::hasColumn('object_field', 'string_min'))
				{
					$table->integer('string_min')->unsigned()->nullable();
				}

				if (!\Schema::hasColumn('object_field', 'string_password'))
				{
					$table->integer('string_password')->unsigned()->nullable();
				}

				if (!\Schema::hasColumn('object_field', 'string_list_size'))
				{
					$table->integer('string_list_size')->unsigned()->nullable();
				}

				if (!\Schema::hasColumn('object_field', 'string_unique'))
				{
					$table->integer('string_unique')->unsigned()->nullable();
				}
			});
		}
	}

}
