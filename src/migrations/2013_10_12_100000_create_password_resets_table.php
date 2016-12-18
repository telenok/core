<?php

use Illuminate\Database\Schema\Blueprint;

class CreatePasswordResetsTable extends \App\Vendor\Telenok\Core\Support\Migrations\Migration
{
    public function up()
    {
        parent::up();

        Schema::create('password_resets', function (Blueprint $table) {
            $table->string('email')->index();
            $table->string('token')->index();
            $table->dateTime('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('password_resets');
    }
}
