<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateObjectFieldDecimalTable extends Migration {

    public function up()
    {
        if (Schema::hasTable('object_field'))
        {
            Schema::table('object_field', function($table)
            {
                if (!\Schema::hasColumn('object_field', 'decimal_min'))
                {
                    $table->decimal('decimal_min', 30, 10)->default(0)->nullable();
                }

                if (!\Schema::hasColumn('object_field', 'decimal_max'))
                {
                    $table->decimal('decimal_max', 30, 10)->default(0)->nullable();
                }

                if (!\Schema::hasColumn('object_field', 'decimal_default'))
                {
                    $table->decimal('decimal_default', 30, 10)->default(null)->nullable();
                }

                if (!\Schema::hasColumn('object_field', 'decimal_precision'))
                {
                    $table->integer('decimal_precision')->default(30)->nullable();
                }

                if (!\Schema::hasColumn('object_field', 'decimal_scale'))
                {
                    $table->integer('decimal_scale')->default(2)->nullable();
                }
            });
        }
    }
}