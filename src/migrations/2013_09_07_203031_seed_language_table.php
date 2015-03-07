<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SeedLanguageTable extends Migration {

	public function up()
	{
		$modelTypeId = DB::table('object_type')->where('code', 'language')->pluck('id');

        $tabMainId = \SeedCommonFields::createTabMain($modelTypeId);
        $tabVisibleId = \SeedCommonFields::createTabVisible($modelTypeId);
        $tabAdditionallyId = \SeedCommonFields::createTabAdditionally($modelTypeId);

        \SeedCommonFields::alterId($modelTypeId, $tabMainId);
        \SeedCommonFields::alterTitle($modelTypeId, $tabMainId, 0);
        \SeedCommonFields::alterActive($modelTypeId, $tabVisibleId);
        \SeedCommonFields::alterCreateUpdateBy($modelTypeId, $tabAdditionallyId);

		DB::table('object_field')->insertGetId(
				[
					'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\App\Model\Telenok\Object\Field']),
					'title' => json_encode(['ru' => 'ISO код', 'en' => 'ISO code'], JSON_UNESCAPED_UNICODE),
					'title_list' => json_encode(['ru' => 'ISO код', 'en' => 'ISO code'], JSON_UNESCAPED_UNICODE),
					'key' => 'string',
					'code' => 'locale',
					'active' => 1,
					'field_object_type' => $modelTypeId,
					'field_object_tab' => $tabMainId,
					'show_in_form' => 1,
					'show_in_list' => 1,
					'allow_search' => 1,
					'field_order' => 6,
				]
		);
        
        $now = \Carbon\Carbon::now()->toDateTimeString();
        $plus15Year = \Carbon\Carbon::now()->addYears(15)->toDateTimeString();

		DB::table('language')->insertGetId(
				[
					'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\App\Model\Telenok\System\Language']),
					'title' => "English",
					'locale' => 'en',
					'active' => 1,
                    'active_at_start' => $now, 
                    'active_at_end' => $plus15Year,
				]
		);
	}
}
