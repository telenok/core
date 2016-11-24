<?php

class CreateObjectFieldCheckboxTable extends \App\Vendor\Telenok\Core\Support\Migrations\Migration {

    public function up()
    {
        parent::up();

        if (Schema::hasTable('object_field'))
		{
			Schema::table('object_field', function($table)
			{
				if (!\Schema::hasColumn('object_field', 'checkbox_default'))
				{
					$table->integer('checkbox_default')->unsigned()->nullable();
				}
			});
		}
	}

}
