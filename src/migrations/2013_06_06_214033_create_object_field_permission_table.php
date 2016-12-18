<?php

class CreateObjectFieldPermissionTable extends \App\Vendor\Telenok\Core\Support\Migrations\Migration
{
    public function up()
    {
        parent::up();

        if (Schema::hasTable('object_field')) {
            Schema::table('object_field', function ($table) {
                if (!\Schema::hasColumn('object_field', 'permission_default')) {
                    $table->mediumText('permission_default')->nullable();
                }
            });
        }
    }
}
