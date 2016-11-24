<?php

class CreateObjectFieldMorphOneToOneTable extends \App\Vendor\Telenok\Core\Support\Migrations\Migration {

    public function up()
    {
        parent::up();

        if (Schema::hasTable('object_field'))
		{
			Schema::table('object_field', function($table)
			{
				$table->integer('morph_one_to_one_has')->unsigned()->default('0')->nullable();
				$table->integer('morph_one_to_one_belong_to')->unsigned()->default('0')->nullable();
				$table->string('morph_one_to_one_belong_to_type_list')->nullable();
			});
		}
	}

}
