<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SeedModuleTable extends Migration {

    public function up()
    {
        $modelTypeId = DB::table('object_type')->where('code', 'module')->value('id');

        $tabMainId = \SeedCommonFields::createTabMain($modelTypeId);
        $tabVisibleId = \SeedCommonFields::createTabVisible($modelTypeId);
        $tabAdditionallyId = \SeedCommonFields::createTabAdditionally($modelTypeId);

        \SeedCommonFields::alterId($modelTypeId, $tabMainId);
        \SeedCommonFields::alterTitle($modelTypeId, $tabMainId);
        \SeedCommonFields::alterActive($modelTypeId, $tabVisibleId);
        \SeedCommonFields::alterCreateUpdateBy($modelTypeId, $tabAdditionallyId);

		(new \App\Telenok\Core\Model\Object\Field())->storeOrUpdate(
				[
                    'title' => ['en' => 'Controller class', 'ru' => 'Класс контроллера'],
                    'title_list' => ['en' => 'Controller class', 'ru' => 'Класс контроллера'],
                    'key' => 'string',
                    'code' => 'controller_class',
                    'active' => 1,
                    'field_object_type' => $modelTypeId,
                    'field_object_tab' => $tabMainId,
                    'multilanguage' => 0,
                    'show_in_form' => 1,
                    'show_in_list' => 1,
                    'allow_search' => 1,
                    'allow_create' => 1,
                    'allow_update' => 1,
                    'field_order' => 4,
                ]
        );

		(new \App\Telenok\Core\Model\Object\Field())->storeOrUpdate(
				[
                    'title' => ['ru' => "Порядок", 'en' => "Order"],
                    'title_list' => ['ru' => "Порядок", 'en' => "Order"],
                    'key' => 'integer-unsigned',
                    'code' => 'module_order',
                    'active' => 1,
                    'field_object_type' => $modelTypeId,
                    'field_object_tab' => $tabMainId,
                    'multilanguage' => 0,
                    'show_in_form' => 1,
                    'show_in_list' => 1,
                    'allow_search' => 1,
                    'allow_create' => 0,
                    'allow_update' => 0,
                    'field_order' => 5,
                ]
        );
    }
}
