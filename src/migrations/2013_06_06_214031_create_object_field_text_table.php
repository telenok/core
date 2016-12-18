<?php

class CreateObjectFieldTextTable extends \App\Vendor\Telenok\Core\Support\Migrations\Migration
{
    public function up()
    {
        parent::up();

        if (Schema::hasTable('object_field')) {
            Schema::table('object_field', function ($table) {
                if (!\Schema::hasColumn('object_field', 'text_width')) {
                    $table->string('text_width')->default('')->nullable();
                }

                if (!\Schema::hasColumn('object_field', 'text_rte')) {
                    $table->integer('text_rte')->default('0')->nullable();
                }

                if (!\Schema::hasColumn('object_field', 'text_height')) {
                    $table->string('text_height')->default('')->nullable();
                }

                if (!\Schema::hasColumn('object_field', 'text_default')) {
                    $table->mediumText('text_default')->nullable();
                }
            });
        }
    }
}
