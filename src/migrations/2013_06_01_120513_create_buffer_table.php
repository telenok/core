<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBufferTable extends Migration {

	public function up()
	{
		if (!Schema::hasTable('buffer'))
		{
			Schema::create('buffer', function(Blueprint $table)
			{
				$table->increments('id');
				$table->nullableTimestamps();
				$table->softDeletes();
				$table->integer('user_id')->unsigned()->default(0)->nullable();
				$table->integer('sequence_id')->unsigned()->default(0)->nullable();
				$table->string('key')->nullable();
				$table->string('place')->nullable();
				
				$table->unique(['user_id', 'sequence_id', 'place'], 'user_seq_place');
			});
		}
	}

}
