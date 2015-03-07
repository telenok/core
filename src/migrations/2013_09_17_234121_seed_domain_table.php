<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SeedDomainTable extends Migration {

    public function up()
    {
        $modelTypeId = DB::table('object_type')->where('code', 'domain')->pluck('id');

        $tabMainId = \SeedCommonFields::createTabMain($modelTypeId);
        $tabVisibleId = \SeedCommonFields::createTabVisible($modelTypeId);
        $tabAdditionallyId = \SeedCommonFields::createTabAdditionally($modelTypeId);

        \SeedCommonFields::alterId($modelTypeId, $tabMainId);
        \SeedCommonFields::alterTitle($modelTypeId, $tabMainId);
        \SeedCommonFields::alterActive($modelTypeId, $tabVisibleId);
        \SeedCommonFields::alterCreateUpdateBy($modelTypeId, $tabAdditionallyId);

		(new \App\Model\Telenok\Object\Field())->storeOrUpdate(
				[
                    'title' => ['ru' => "Домен", 'en' => "Domain"], 
                    'title_list' => ['ru' => "Домен", 'en' => "Domain"], 
                    'key' => 'string',
                    'code' => 'domain',
                    'active' => 1,
                    'field_object_type' => $modelTypeId,
                    'field_object_tab' => $tabMainId,
                    'multilanguage' => 0,
                    'show_in_form' => 1,
                    'show_in_list' => 1,
                    'allow_create' => 1,
                    'allow_update' => 1,
                    'allow_search' => 1,
                    'field_order' => 3,
                    'string_list_size' => 150,
                ]
        );

        (new \App\Model\Telenok\Object\Field())->storeOrUpdate(
                [
                    'title' => ['en' => 'Page'],
                    'title_list' => ['en' => 'Page'],
                    'key' => 'relation-one-to-many',
                    'code' => 'page',
                    'active' => 1,
                    'field_object_type' => 'domain',
                    'field_object_tab' => 'main',
                    'relation_one_to_many_has' => 'page',
                    'show_in_form' => 1,
                    'show_in_list' => 0,
                    'allow_search' => 1,
                    'multilanguage' => 0,
                    'allow_create' => 1,
                    'allow_update' => 1,
                    'field_order' => 21,
                ]
        );
    }

}
