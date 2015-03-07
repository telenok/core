<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SeedFileExtensionTable extends Migration {

    public function up()
    {
        $modelTypeId = DB::table('object_type')->where('code', 'file_extension')->pluck('id');

        $tabMainId = \SeedCommonFields::createTabMain($modelTypeId);
        $tabVisibleId = \SeedCommonFields::createTabVisible($modelTypeId);
        $tabAdditionallyId = \SeedCommonFields::createTabAdditionally($modelTypeId);

        \SeedCommonFields::alterId($modelTypeId, $tabMainId);
        \SeedCommonFields::alterTitle($modelTypeId, $tabMainId, 0);
        \SeedCommonFields::alterActive($modelTypeId, $tabVisibleId);
        \SeedCommonFields::alterCreateUpdateBy($modelTypeId, $tabAdditionallyId);

		(new \App\Model\Telenok\Object\Field())->storeOrUpdate(
				[
                    'title' => ['ru' => "Расширение", 'en' => "Extension"],
                    'title_list' => ['ru' => "Расширение", 'en' => "Extension"],
                    'key' => 'string',
                    'code' => 'extension',
                    'active' => 1,
                    'field_object_type' => $modelTypeId,
                    'field_object_tab' => $tabMainId,
                    'multilanguage' => 0,
                    'show_in_form' => 1,
                    'show_in_list' => 1,
                    'allow_search' => 1,
                    'required' => 1,
                    'field_order' => 3,
                ]
        );
    }

}
