<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SeedSettingTable extends Migration {

	public function up()
	{
		$modelTypeId = DB::table('object_type')->where('code', 'setting')->value('id');

        $tabMainId = \SeedCommonFields::createTabMain($modelTypeId);
        $tabVisibleId = \SeedCommonFields::createTabVisible($modelTypeId);
        $tabAdditionallyId = \SeedCommonFields::createTabAdditionally($modelTypeId);

        \SeedCommonFields::alterId($modelTypeId, $tabMainId);
        \SeedCommonFields::alterTitle($modelTypeId, $tabMainId);
        \SeedCommonFields::alterActive($modelTypeId, $tabVisibleId);
        \SeedCommonFields::alterCreateUpdateBy($modelTypeId, $tabAdditionallyId);

		DB::table('object_field')->insertGetId(
				[
					'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\App\Telenok\Core\Model\Object\Field']),
					'title' => json_encode(SeedSettingTableTranslation::get('field.code'), JSON_UNESCAPED_UNICODE),
					'title_list' => json_encode(SeedSettingTableTranslation::get('field.code'), JSON_UNESCAPED_UNICODE),
					'key' => 'string',
					'code' => 'code',
					'active' => 1,
					'field_object_type' => $modelTypeId,
					'field_object_tab' => $tabMainId,
					'multilanguage' => 0,
					'show_in_form' => 1,
					'show_in_list' => 1,
					'allow_search' => 1,
					'required' => 1,
					'field_order' => 7,
				]
		);

		DB::table('object_field')->insertGetId(
				[
					'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\App\Telenok\Core\Model\Object\Field']),
					'title' => json_encode(SeedSettingTableTranslation::get('field.value'), JSON_UNESCAPED_UNICODE),
					'title_list' => json_encode(SeedSettingTableTranslation::get('field.value'), JSON_UNESCAPED_UNICODE),
					'key' => 'complex-array',
					'code' => 'value',
					'active' => 1,
					'field_object_type' => $modelTypeId,
					'field_object_tab' => $tabMainId,
					'multilanguage' => 0,
					'show_in_form' => 1,
					'show_in_list' => 1,
					'allow_search' => 0,
					'required' => 0,
					'field_order' => 8,
				]
		);

        app('config')->set('app.localeDefault', 'en');
        app('config')->set('app.locales', ['en', 'ru']);
        app('config')->set('app.timezone', 'UTC');
	}
}

class SeedSettingTableTranslation extends \Telenok\Core\Abstraction\Translation\Controller {

	public static $keys = [
        'field' => [
            'code' => [
                'ru' => "Код",
                'en' => "Code",
            ],
            'value' => [
                'ru' => "Значение",
                'en' => "Value",
            ]
        ],
    ];
}