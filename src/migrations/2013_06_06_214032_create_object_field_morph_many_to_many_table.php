<?php

class CreateObjectFieldMorphManyToManyTable extends \App\Vendor\Telenok\Core\Support\Migrations\Migration {

    public function up()
    {
        parent::up();

        if (Schema::hasTable('object_field'))
		{
			Schema::table('object_field', function($table)
			{
				$table->integer('morph_many_to_many_has')->unsigned()->default('0')->nullable();
				$table->integer('morph_many_to_many_belong_to')->unsigned()->default('0')->nullable();
			});
		}
	}

}
