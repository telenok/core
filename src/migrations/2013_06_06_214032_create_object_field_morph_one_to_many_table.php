<?php

class CreateObjectFieldMorphOneToManyTable extends \App\Vendor\Telenok\Core\Support\Migrations\Migration {

    public function up()
    {
        parent::up();

        if (Schema::hasTable('object_field'))
		{
			Schema::table('object_field', function($table)
			{
                if (!\Schema::hasColumn('object_field', 'morph_one_to_many_has'))
                {
                    $table->integer('morph_one_to_many_has')->unsigned()->default('0')->nullable();
                }

                if (!\Schema::hasColumn('object_field', 'morph_one_to_many_belong_to'))
                {
                    $table->string('morph_one_to_many_belong_to')->nullable();
                }
			});
		}
	}

}
