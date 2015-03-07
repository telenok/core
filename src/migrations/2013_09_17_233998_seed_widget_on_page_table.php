<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SeedWidgetOnPageTable extends Migration {

    public function up()
    {
        $modelTypeId = DB::table('object_type')->where('code', 'widget_on_page')->pluck('id');

        $tabMainId = \SeedCommonFields::createTabMain($modelTypeId);
        $tabVisibleId = \SeedCommonFields::createTabVisible($modelTypeId);
        $tabAdditionallyId = \SeedCommonFields::createTabAdditionally($modelTypeId);

        \SeedCommonFields::alterId($modelTypeId, $tabMainId);
        \SeedCommonFields::alterTitle($modelTypeId, $tabMainId);
        \SeedCommonFields::alterActive($modelTypeId, $tabVisibleId);
        \SeedCommonFields::alterCreateUpdateBy($modelTypeId, $tabAdditionallyId);

		(new \App\Model\Telenok\Object\Field())->storeOrUpdate(
				[
                    'title' => ['ru' => "Контейнер", 'en' => "Container"],
                    'title_list' => ['ru' => "Контейнер", 'en' => "Container"], 
                    'key' => 'string',
                    'code' => 'container',
                    'active' => 1,
                    'field_object_type' => $modelTypeId,
                    'field_object_tab' => $tabMainId,
                    'multilanguage' => 0,
                    'show_in_form' => 1,
                    'show_in_list' => 1,
                    'allow_create' => 0,
                    'allow_update' => 0,
                    'allow_search' => 1,
                    'required' => 1,
                    'field_order' => 3,
                ]
        );

		(new \App\Model\Telenok\Object\Field())->storeOrUpdate(
				[
                    'title' => ['ru' => "Ключ виджета", 'en' => "Widget key"],
                    'title_list' => ['ru' => "Ключ виджета", 'en' => "Widget key"],
                    'key' => 'string',
                    'code' => 'key',
                    'active' => 1,
                    'field_object_type' => $modelTypeId,
                    'field_object_tab' => $tabMainId,
                    'multilanguage' => 0,
                    'show_in_form' => 1,
                    'show_in_list' => 1,
                    'allow_create' => 0,
                    'allow_update' => 0,
                    'allow_search' => 1,
                    'required' => 1,
                    'field_order' => 4,
                ]
        );

		(new \App\Model\Telenok\Object\Field())->storeOrUpdate(
				[
                    'title' => ['ru' => "Порядок", 'en' => "Order"],
                    'title_list' => ['ru' => "Порядок", 'en' => "Order"],
                    'key' => 'integer-unsigned',
                    'code' => 'widget_order',
                    'active' => 1,
                    'field_object_type' => $modelTypeId,
                    'field_object_tab' => $tabAdditionallyId,
                    'multilanguage' => 0,
                    'show_in_form' => 1,
                    'show_in_list' => 0,
                    'allow_search' => 1,
                    'allow_create' => 0,
                    'allow_update' => 0,
                    'field_order' => 5,
                ]
        );

        (new \App\Model\Telenok\Object\Field())->storeOrUpdate(
                [
                    'title' => ['ru' => "Период кэширования", 'en' => "Cache time"],
                    'title_list' => ['ru' => "Период кэширования", 'en' => "Cache time"],
                    'description' => ['ru' => "Период кэширования задается в минутах от 0 (без кэширования)", 'en' => "Cache time in minuts from 0 (no cache)"],
                    'key' => 'integer-unsigned',
                    'code' => 'cache_time',
                    'active' => 1,
                    'integer_unsigned_default' => 3600,
                    'field_object_type' => $modelTypeId,
                    'field_object_tab' => $tabMainId,
                    'multilanguage' => 0,
                    'show_in_form' => 1,
                    'show_in_list' => 1,
                    'allow_search' => 1,
                    'allow_create' => 1,
                    'allow_update' => 1,
                    'field_order' => 7,
                ]
        );

		(new \App\Model\Telenok\Object\Field())->storeOrUpdate(
				[
                    'title' => ['en' => 'Structure', 'ru' => 'Структура'],
                    'title_list' => ['en' => 'Structure', 'ru' => 'Структура'],
                    'key' => 'complex-array',
                    'code' => 'structure',
                    'active' => 1,
                    'field_object_type' => $modelTypeId,
                    'field_object_tab' => $tabMainId,
                    'multilanguage' => 0,
                    'show_in_form' => 1,
                    'show_in_list' => 1,
                    'allow_search' => 1,
                    'allow_create' => 1,
                    'allow_update' => 1,
                    'field_order' => 9,
                ]
        );

        (new \App\Model\Telenok\Object\Field())->storeOrUpdate(
                [
                    'title' => ['en' => 'Link to widget'],
                    'title_list' => ['en' => 'Link to widget'],
                    'key' => 'relation-one-to-many',
                    'code' => 'widget_link',
                    'active' => 1,
                    'field_object_type' => 'widget_on_page',
                    'field_object_tab' => 'main',
                    'show_in_form' => 1,
                    'show_in_list' => 0,
                    'allow_search' => 1,
                    'multilanguage' => 0,
                    'relation_one_to_many_has' => 'widget_on_page',
                    'allow_create' => 0,
                    'allow_update' => 0,
                    'field_order' => 10,
                ]
        );

        (new \App\Model\Telenok\Object\Field())->storeOrUpdate(
                [
                    'title' => ['en' => 'Widget'],
                    'title_list' => ['en' => 'Widget'],
                    'key' => 'relation-one-to-many',
                    'code' => 'widget_language',
                    'active' => 1,
                    'field_object_type' => 'language',
                    'field_object_tab' => 'main',
                    'show_in_form' => 1,
                    'show_in_list' => 0,
                    'allow_search' => 1,
                    'multilanguage' => 0,
                    'relation_one_to_many_has' => 'widget_on_page',
                    'allow_create' => 0,
                    'allow_update' => 0,
                    'field_order' => 13,
                ]
        );

        (new \App\Model\Telenok\Object\Field())->storeOrUpdate(
                [
                    'title' => ['en' => 'Widget'],
                    'title_list' => ['en' => 'Widget'],
                    'key' => 'relation-one-to-many',
                    'code' => 'widget',
                    'active' => 1,
                    'field_object_type' => 'page',
                    'field_object_tab' => 'additionally',
                    'relation_one_to_many_has' => 'widget_on_page',
                    'show_in_form' => 1,
                    'show_in_list' => 0,
                    'allow_search' => 1,
                    'multilanguage' => 0,
                    'allow_create' => 0,
                    'allow_update' => 0,
                    'field_order' => 8,
                ]
        );
    }
}
