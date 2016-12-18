<?php

class CreateObjectFieldTimeTable extends \App\Vendor\Telenok\Core\Support\Migrations\Migration
{
    public function up()
    {
        parent::up();

        if (Schema::hasTable('object_field')) {
            Schema::table('object_field', function ($table) {
                if (!\Schema::hasColumn('object_field', 'time_default')) {
                    $table->dateTime('time_default')->nullable();
                }
            });
        }
    }
}
