<?php

class CreateObjectFieldActiveTable extends \App\Vendor\Telenok\Core\Support\Migrations\Migration
{
    public function up()
    {
        parent::up();

        if (Schema::hasTable('object_field')) {
            Schema::table('object_field', function ($table) {
                if (!\Schema::hasColumn('object_field', 'active_default')) {
                    $table->integer('active_default')->unsigned()->nullable();
                }
            });
        }
    }
}
