<?php

class CreateObjectFieldMorphManyToManyTable extends \App\Vendor\Telenok\Core\Support\Migrations\Migration {

    public function up()
    {
        parent::up();

        if (Schema::hasTable('object_field'))
		{
			Schema::table('object_field', function($table)
			{
                if (!\Schema::hasColumn('object_field', 'morph_many_to_many_has'))
                {
                    $table->integer('morph_many_to_many_has')->unsigned()->default('0')->nullable();
                }

                if (!\Schema::hasColumn('object_field', 'morph_many_to_many_belong_to'))
                {
                    $table->integer('morph_many_to_many_belong_to')->unsigned()->default('0')->nullable();
                }
			});
		}
	}

}
