<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SeedObjectSequenceTable extends Migration {

	public function up()
	{
		if (Schema::hasTable('object_sequence'))
		{
			$modelTypeId = DB::table('object_type')->where('code', 'object_sequence')->pluck('id');

			$tabMainId = \SeedCommonFields::createTabMain($modelTypeId);
			$tabVisibleId = \SeedCommonFields::createTabVisible($modelTypeId);
			$tabAdditionallyId = \SeedCommonFields::createTabAdditionally($modelTypeId);
			
            \SeedCommonFields::alterId($modelTypeId, $tabMainId);
            \SeedCommonFields::alterTitle($modelTypeId, $tabMainId);
            \SeedCommonFields::alterActive($modelTypeId, $tabVisibleId);
            \SeedCommonFields::alterCreateUpdateBy($modelTypeId, $tabAdditionallyId);

			DB::table('object_field')->insertGetId(
					[
						'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\App\Model\Telenok\Object\Field']),
						'title' => json_encode(['ru' => 'Класс модели', 'en' => 'Class model'], JSON_UNESCAPED_UNICODE),
						'title_list' => json_encode(['ru' => 'Класс модели', 'en' => 'Class model'], JSON_UNESCAPED_UNICODE),
						'key' => 'string',
						'code' => 'class_model',
						'active' => 1,
						'field_object_type' => $modelTypeId,
						'field_object_tab' => $tabMainId,
						'multilanguage' => 0,
						'show_in_form' => 1,
						'show_in_list' => 1,
						'allow_search' => 1,
						'allow_update' => 0,
						'field_order' => 3,
					]
			);  

			DB::table('object_field')->insertGetId(
					[
						'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\App\Model\Telenok\Object\Field']),
						'title' => json_encode(['ru' => 'Тип', 'en' => 'Type'], JSON_UNESCAPED_UNICODE),
						'title_list' => json_encode(['ru' => 'Тип', 'en' => 'Type'], JSON_UNESCAPED_UNICODE),
						'key' => 'relation-one-to-many',
						'code' => 'sequences_object_type',
						'active' => 1,
						'field_object_type' => $modelTypeId,
						'field_object_tab' => $tabAdditionallyId,
						'relation_one_to_many_belong_to' => DB::table('object_type')->where('code', 'object_type')->pluck('id'),
						'multilanguage' => 0,
						'show_in_form' => 1,
						'show_in_list' => 1,
						'allow_search' => 1,
						'allow_create' => 0,
						'allow_update' => 1,
						'field_order' => 4,
					]
			);

			DB::table('object_field')->insert(
					[
						'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\App\Model\Telenok\Object\Field']),
						'title' => json_encode(['en' => 'Parent'], JSON_UNESCAPED_UNICODE),
						'title_list' => json_encode(['en' => 'Parent'], JSON_UNESCAPED_UNICODE),
                        'key' => 'select-one',
                        'code' => 'treeable',
                        'select_one_data' => json_encode([
                            'title' => \SeedCommonFields::llYesNo(),
                            'key' => [0, 1],
                            'default' => 0,
                        ], JSON_UNESCAPED_UNICODE),
						'active' => 1,
                        'field_view' => 'core::field.select-one.model-toggle-button',
						'field_object_type' => $modelTypeId,
						'field_object_tab' => $tabMainId,
						'multilanguage' => 0,
						'show_in_form' => 1,
						'show_in_list' => 1,
						'allow_search' => 1,
						'allow_create' => 1,
						'allow_update' => 1,
						'field_order' => 5,
					]
			);
		}
	}
}
