<?php

class CreateObjectFieldRelatedManytoManyTable extends \App\Vendor\Telenok\Core\Support\Migrations\Migration
{
    public function up()
    {
        parent::up();

        if (Schema::hasTable('object_field')) {
            Schema::table('object_field', function ($table) {
                if (!Schema::hasColumn('object_field', 'relation_many_to_many_has')) {
                    $table->integer('relation_many_to_many_has')->unsigned()->default('0')->nullable();
                }

                if (!Schema::hasColumn('object_field', 'relation_many_to_many_belong_to')) {
                    $table->integer('relation_many_to_many_belong_to')->unsigned()->default('0')->nullable();
                }

                if (!Schema::hasColumn('object_field', 'relation_many_to_many_default')) {
                    $table->string('relation_many_to_many_default')->nullable();
                }
            });
        }
    }
}
