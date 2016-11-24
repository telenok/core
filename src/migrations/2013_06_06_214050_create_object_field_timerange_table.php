<?php

class CreateObjectFieldTimerangeTable extends \App\Vendor\Telenok\Core\Support\Migrations\Migration {

    public function up()
    {
        parent::up();

        if (Schema::hasTable('object_field'))
		{
			Schema::table('object_field', function($table)
			{
				if (!\Schema::hasColumn('object_field', 'time_range_default_start'))
				{
					$table->dateTime('time_range_default_start')->nullable();
				}

				if (!\Schema::hasColumn('object_field', 'time_range_default_end'))
				{
					$table->dateTime('time_range_default_end')->nullable();
				}
			});
		}
	}
}
