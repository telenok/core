<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SeedObjectTypeTable extends Migration {

	public function up()
	{
		if (Schema::hasTable('object_type') && Schema::hasTable('object_field'))
		{
			$modelTypeId = DB::table('object_type')->where('code', 'object_type')->pluck('id');
			$modelFieldId = DB::table('object_type')->where('code', 'object_field')->pluck('id');

			$tabMainId = \SeedCommonFields::createTabMain($modelTypeId);
			$tabVisibleId = \SeedCommonFields::createTabVisible($modelTypeId);
			$tabAdditionallyId = \SeedCommonFields::createTabAdditionally($modelTypeId);

            \SeedCommonFields::alterId($modelTypeId, $tabMainId);
            \SeedCommonFields::alterTitle($modelTypeId, $tabMainId);
            \SeedCommonFields::alterTitleList($modelTypeId, $tabMainId);
            \SeedCommonFields::alterActive($modelTypeId, $tabVisibleId);
            \SeedCommonFields::alterCreateUpdateBy($modelTypeId, $tabAdditionallyId);

			DB::table('object_field')->insert(
					[
						'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\App\Model\Telenok\Object\Field']),
						'title' => json_encode(SeedObjectTypeTableTranslation::get('field.code'), JSON_UNESCAPED_UNICODE),
						'title_list' => json_encode(SeedObjectTypeTableTranslation::get('field.code'), JSON_UNESCAPED_UNICODE),
						'key' => 'string',
						'code' => 'code',
						'active' => 1,
						'field_object_type' => $modelTypeId,
						'field_object_tab' => $tabMainId,
						'multilanguage' => 0,
						'show_in_form' => 1,
						'show_in_list' => 1,
						'allow_search' => 1,
						'allow_create' => 1,
						'allow_update' => 0,
						'field_order' => 4,
					]
			);

			DB::table('object_field')->insert(
					[
						'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\App\Model\Telenok\Object\Field']),
						'title' => json_encode(SeedObjectTypeTableTranslation::get('field.class_model'), JSON_UNESCAPED_UNICODE),
						'title_list' => json_encode(SeedObjectTypeTableTranslation::get('field.class_model'), JSON_UNESCAPED_UNICODE),
						'key' => 'string',
						'code' => 'class_model',
						'active' => 1,
						'field_object_type' => $modelTypeId,
						'field_object_tab' => $tabMainId,
						'multilanguage' => 0,
						'show_in_form' => 1,
						'show_in_list' => 1,
						'allow_search' => 1,
						'allow_create' => 1,
						'allow_update' => 0,
						'field_order' => 5,
					]
			);

			DB::table('object_field')->insert(
					[
						'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\App\Model\Telenok\Object\Field']),
						'title' => json_encode(SeedObjectTypeTableTranslation::get('field.class_controller'), JSON_UNESCAPED_UNICODE),
						'title_list' => json_encode(SeedObjectTypeTableTranslation::get('field.class_controller'), JSON_UNESCAPED_UNICODE),
						'key' => 'string',
						'code' => 'class_controller',
						'active' => 1,
						'field_object_type' => $modelTypeId,
						'field_object_tab' => $tabMainId,
						'multilanguage' => 0,
						'show_in_form' => 1,
						'show_in_list' => 0,
						'allow_update' => 0,
						'allow_search' => 1,
						'field_order' => 6,
					]
			);

			DB::table('object_field')->insert(
					[
						'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\App\Model\Telenok\Object\Field']),
						'title' => json_encode(SeedObjectTypeTableTranslation::get('field.treeable'), JSON_UNESCAPED_UNICODE),
						'title_list' => json_encode(SeedObjectTypeTableTranslation::get('field.treeable'), JSON_UNESCAPED_UNICODE),
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
						'show_in_list' => 0,
						'show_in_form' => 1,
						'allow_search' => 1,
						'allow_create' => 1,
						'allow_update' => 1,
						'field_order' => 8,
					]
			);

			DB::table('object_field')->insert(
					[
						'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\App\Model\Telenok\Object\Field']),
						'title' => json_encode(SeedObjectTypeTableTranslation::get('field.field'), JSON_UNESCAPED_UNICODE),
						'title_list' => json_encode(SeedObjectTypeTableTranslation::get('field.field'), JSON_UNESCAPED_UNICODE),
						'key' => 'relation-one-to-many',
						'code' => 'field',
						'active' => 1,
						'field_object_type' => $modelTypeId,
						'field_object_tab' => $tabAdditionallyId,
						'relation_one_to_many_has' => $modelFieldId,
						'multilanguage' => 0,
						'show_in_form' => 1,
						'show_in_list' => 0,
						'allow_search' => 1,
						'allow_create' => 0,
						'allow_update' => 1,
						'field_order' => 10,
					]
			); 

			DB::table('object_field')->insert(
					[
						'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\App\Model\Telenok\Object\Field']),
						'title' => json_encode(['en' => 'Tab'], JSON_UNESCAPED_UNICODE),
						'title_list' => json_encode(['en' => 'Tab'], JSON_UNESCAPED_UNICODE),
						'key' => 'relation-one-to-many',
						'code' => 'tab',
						'active' => 1,
						'field_object_type' => $modelTypeId,
						'field_object_tab' => $tabAdditionallyId,
						'relation_one_to_many_has' => DB::table('object_type')->where('code', 'object_tab')->pluck('id'),
						'multilanguage' => 0,
						'show_in_form' => 1,
						'show_in_list' => 0,
						'allow_search' => 1,
						'allow_create' => 1,
						'allow_update' => 1,
						'field_order' => 10,
					]
			); 

			DB::table('object_field')->insertGetId(
					[
						'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\App\Model\Telenok\Object\Field']),
						'title' => json_encode(['ru' => 'Объекты', 'en' => 'Objects'], JSON_UNESCAPED_UNICODE),
						'title_list' => json_encode(['ru' => 'Объекты', 'en' => 'Objects'], JSON_UNESCAPED_UNICODE),
						'key' => 'relation-one-to-many',
						'code' => 'sequences',
						'active' => 1,
						'field_object_type' => $modelTypeId,
						'field_object_tab' => $tabAdditionallyId,
						'relation_one_to_many_has' => DB::table('object_type')->where('code', 'object_sequence')->pluck('id'),
						'multilanguage' => 0,
						'show_in_form' => 1,
						'show_in_list' => 0,
						'allow_search' => 1,
						'allow_create' => 0,
						'allow_update' => 1,
						'field_order' => 11,
					]
			); 
		}
	}

}

class SeedObjectTypeTableTranslation extends \Telenok\Core\Interfaces\Translation\Controller {

	public static $keys = [
        'field' => [
            'code' => [
                'ru' => "Код",
                'en' => "Code",
            ],
            'field' => [
                'ru' => "Поле",
                'en' => "Field",
            ],
            'treeable' => [
                'ru' => "Деревообразный",
                'en' => "Treeable",
            ],
            'multilanguage' => [
                'ru' => "Мультиязычный",
                'en' => "Multilanguage",
            ],
            'class_model' => [
                'ru' => "Класс модели",
                'en' => "Class of model",
            ],
            'class_controller' => [
                'ru' => "Класс формы",
                'en' => "Class of form",
            ],
        ],
	];

}
