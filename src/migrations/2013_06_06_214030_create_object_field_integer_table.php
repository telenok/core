<?php

class CreateObjectFieldIntegerTable extends \App\Vendor\Telenok\Core\Support\Migrations\Migration {

    public function up()
    {
        parent::up();

        if (Schema::hasTable('object_field'))
		{
			Schema::table('object_field', function($table)
			{
				if (!\Schema::hasColumn('object_field', 'integer_min'))
				{
					$table->integer('integer_min')->default(-2147483648)->nullable();
				}
				if (!\Schema::hasColumn('object_field', 'integer_max'))
				{
					$table->integer('integer_max')->default(2147483647)->nullable();
				}
				if (!\Schema::hasColumn('object_field', 'integer_default'))
				{
					$table->integer('integer_default')->nullable()->default(null);
				}
			});
		}
	}

}
