<?php

use Illuminate\Database\Schema\Blueprint;

class CreateObjectTypeTable extends \App\Vendor\Telenok\Core\Support\Migrations\Migration
{
    public function up()
    {
        parent::up();

        if (!Schema::hasTable('object_type')) {
            Schema::create('object_type', function (Blueprint $table) {
                $table->increments('id');
                $table->nullableTimestamps();
                $table->softDeletes();

                $table->mediumText('title')->nullable();
                $table->mediumText('title_list')->nullable();
                $table->string('code')->unique()->nullable();
                $table->integer('active')->unsigned()->nullable();
                $table->dateTime('active_at_start')->nullable();
                $table->dateTime('active_at_end')->nullable();
                $table->dateTime('locked_at')->nullable();
                $table->string('model_class')->nullable();
                $table->string('controller_class')->nullable();
                $table->integer('has_versioning')->unsigned()->nullable();
                $table->integer('treeable')->unsigned()->nullable()->default(0);
                $table->integer('created_by_user')->unsigned()->nullable();
                $table->integer('updated_by_user')->unsigned()->nullable();
                $table->integer('deleted_by_user')->unsigned()->nullable()->default(null);
                $table->integer('locked_by_user')->unsigned()->nullable()->default(null);
            });
        }
    }
}
