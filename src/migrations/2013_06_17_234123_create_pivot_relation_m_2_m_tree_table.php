<?php

use Illuminate\Database\Schema\Blueprint;

class CreatePivotRelationM2MTreeTable extends \App\Vendor\Telenok\Core\Support\Migrations\Migration {

    public function up()
    {
        parent::up();

        if (!Schema::hasTable('pivot_relation_m2m_tree'))
		{
			Schema::create('pivot_relation_m2m_tree', function(Blueprint $table) 
			{
				$table->increments('id');
				$table->integer('tree_id')->unsigned()->nullable()->default(0);
				$table->integer('tree_pid')->unsigned()->nullable()->default(0);
				$table->integer('tree_order')->unsigned()->nullable()->default(0);
				$table->integer('tree_depth')->unsigned()->nullable()->default(0);
				$table->string('tree_path')->nullable();

				$table->unique(['tree_id'], 'uniq_id');
				$table->unique(['tree_id', 'tree_pid'], 'uniq_cp');
			});
		}
	}

}
