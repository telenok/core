<?php

use Illuminate\Database\Schema\Blueprint;

class CreateObjectFieldSelectManyTable extends \App\Vendor\Telenok\Core\Support\Migrations\Migration
{
    public function up()
    {
        parent::up();

        if (Schema::hasTable('object_field')) {
            Schema::table('object_field', function ($table) {
                if (!\Schema::hasColumn('object_field', 'select_many_data')) {
                    $table->mediumText('select_many_data')->nullable();
                }
            });
        }

        if (!Schema::hasTable('pivot_relation_o2m_field_select_many')) {
            Schema::create('pivot_relation_o2m_field_select_many', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('field_id')->unsigned()->nullable();
                $table->integer('sequence_id')->unsigned()->nullable();
                $table->string('key')->nullable();

                $table->unique(['sequence_id', 'field_id', 'key'], 'uniq_cp');
            });
        }
    }
}
