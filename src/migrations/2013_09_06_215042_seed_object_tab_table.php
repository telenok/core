<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SeedObjectTabTable extends Migration {

	public function up()
	{
		if (Schema::hasTable('object_type') && Schema::hasTable('object_field'))
		{  
			$modelTypeId = DB::table('object_type')->where('code', 'object_tab')->pluck('id');

			$tabMainId = \SeedCommonFields::createTabMain($modelTypeId);
			$tabVisibleId = \SeedCommonFields::createTabVisible($modelTypeId);
			$tabAdditionallyId = \SeedCommonFields::createTabAdditionally($modelTypeId);
			
            \SeedCommonFields::alterId($modelTypeId, $tabMainId);
            \SeedCommonFields::alterTitle($modelTypeId, $tabMainId);
            \SeedCommonFields::alterActive($modelTypeId, $tabVisibleId);
            \SeedCommonFields::alterCreateUpdateBy($modelTypeId, $tabAdditionallyId);

			DB::table('object_field')->insert(
					[
						'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\App\Model\Telenok\Object\Field']),
						'title' => json_encode(['ru' => 'Код', 'en' => 'Code'], JSON_UNESCAPED_UNICODE),
						'title_list' => json_encode(['ru' => 'Код', 'en' => 'Code'], JSON_UNESCAPED_UNICODE),
						'key' => 'string',
						'code' => 'code',
						'active' => 1,
						'field_object_type' => $modelTypeId,
						'field_object_tab' => $tabMainId,
						'show_in_form' => 1,
						'show_in_list' => 1,
						'allow_search' => 1,
						'multilanguage' => 0,
						'allow_create' => 1,
						'allow_update' => 1,
						'field_order' => 3,
					]
			);
			
			DB::table('object_field')->insert(
					[
						'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\App\Model\Telenok\Object\Field']),
						'title' => json_encode(['en' => 'Order in tab'], JSON_UNESCAPED_UNICODE),
						'title_list' => json_encode(['en' => 'Order in tab'], JSON_UNESCAPED_UNICODE),
						'key' => 'integer-unsigned',
						'code' => 'tab_order',
						'active' => 1,
						'field_object_type' => $modelTypeId,
						'field_object_tab' => $tabMainId,
						'multilanguage' => 0,
						'show_in_form' => 1,
						'show_in_list' => 0,
						'allow_search' => 1,
						'allow_create' => 1,
						'allow_update' => 1,
						'field_order' => 5,
					]
			);

			DB::table('object_field')->insert(
					[
						'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\App\Model\Telenok\Object\Field']),
						'title' => json_encode(['ru' => 'ICON класс', 'en' => 'ICON class'], JSON_UNESCAPED_UNICODE),
						'title_list' => json_encode(['ru' => 'ICON класс', 'en' => 'ICON class'], JSON_UNESCAPED_UNICODE),
						'key' => 'string',
						'code' => 'icon_class',
						'active' => 1,
						'field_object_type' => $modelTypeId,
						'field_object_tab' => $tabAdditionallyId,
						'show_in_form' => 1,
						'show_in_list' => 0,
						'allow_search' => 1,
						'multilanguage' => 0,
						'allow_create' => 1,
						'allow_update' => 1,
						'field_order' => 6,
					]
			);

			DB::table('object_field')->insert(
					[
						'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\App\Model\Telenok\Object\Field']),
						'title' => json_encode(['en' => 'Field'], JSON_UNESCAPED_UNICODE),
						'title_list' => json_encode(['en' => 'Field'], JSON_UNESCAPED_UNICODE),
						'key' => 'relation-one-to-many',
						'code' => 'field',
						'active' => 1,
						'field_object_type' => $modelTypeId,
						'field_object_tab' => $tabMainId,
						'relation_one_to_many_has' => DB::table('object_type')->where('code', 'object_field')->pluck('id'),
						'multilanguage' => 0,
						'show_in_form' => 1,
						'show_in_list' => 0,
						'allow_search' => 1,
						'allow_create' => 1,
						'allow_update' => 1,
						'field_order' => 7,
					]
			);

			DB::table('object_field')->insert(
					[
						'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\App\Model\Telenok\Object\Field']),
						'title' => json_encode(['en' => 'Type'], JSON_UNESCAPED_UNICODE),
						'title_list' => json_encode(['en' => 'Type'], JSON_UNESCAPED_UNICODE),
						'key' => 'relation-one-to-many',
						'code' => 'tab_object_type',
						'active' => 1,
						'field_object_type' => $modelTypeId,
						'field_object_tab' => $tabMainId,
						'relation_one_to_many_belong_to' => DB::table('object_type')->where('code', 'object_type')->pluck('id'),
						'multilanguage' => 0,
						'show_in_form' => 1,
						'show_in_list' => 1,
						'allow_search' => 1,
						'allow_create' => 1,
						'allow_update' => 0,
						'field_order' => 8,
					]
			);
		}
	}
}
