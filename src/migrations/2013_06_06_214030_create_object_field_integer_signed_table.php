<?php

class CreateObjectFieldIntegerSignedTable extends \App\Vendor\Telenok\Core\Support\Migrations\Migration {

    public function up()
    {
        parent::up();

        if (Schema::hasTable('object_field'))
		{
			Schema::table('object_field', function($table)
			{
				if (!\Schema::hasColumn('object_field', 'integer_signed_min'))
				{
					$table->integer('integer_signed_min')->default(-2147483648)->nullable();
				}
				if (!\Schema::hasColumn('object_field', 'integer_signed_max'))
				{
					$table->integer('integer_signed_max')->default(2147483647)->nullable();
				}
				if (!\Schema::hasColumn('object_field', 'integer_signed_default'))
				{
					$table->integer('integer_signed_default')->nullable()->default(null);
				}
			});
		}
	}

}
