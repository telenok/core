<?php

class CreateSessionTable extends \App\Vendor\Telenok\Core\Support\Migrations\Migration
{
    public function up()
    {
        parent::up();

        Schema::create('session', function ($t) {
            $t->string('id')->unique();
            $t->mediumText('payload')->nullable();
            $t->integer('last_activity')->unsigned()->nullable()->default(0);
            $t->integer('user_id')->unsigned()->nullable()->default(0);
            $t->string('ip_address')->nullable();
            $t->text('user_agent')->nullable();
        });
    }
}
