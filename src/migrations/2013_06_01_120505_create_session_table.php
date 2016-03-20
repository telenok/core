<?php

use Illuminate\Database\Migrations\Migration;

class CreateSessionTable extends Migration {

    public function up()
    {
        Schema::create('session', function($t)
        {
            $t->string('id')->unique();
            $t->mediumText('payload')->nullable();
            $t->integer('last_activity')->unsigned()->nullable()->default(0);
            $t->integer('user_id')->unsigned()->nullable()->default(0);
            $t->string('ip_address')->nullable();
            $t->text('user_agent')->nullable();
        });
    }
}
