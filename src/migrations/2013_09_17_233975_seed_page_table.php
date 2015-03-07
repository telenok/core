<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SeedPageTable extends Migration {

    public function up()
    {
        $modelTypeId = DB::table('object_type')->where('code', 'page')->pluck('id');

        $tabMainId = \SeedCommonFields::createTabMain($modelTypeId);
        $tabVisibleId = \SeedCommonFields::createTabVisible($modelTypeId);
        $tabAdditionallyId = \SeedCommonFields::createTabAdditionally($modelTypeId);

        \SeedCommonFields::alterId($modelTypeId, $tabMainId);
        \SeedCommonFields::alterTitle($modelTypeId, $tabMainId);
        \SeedCommonFields::alterActive($modelTypeId, $tabVisibleId);
        \SeedCommonFields::alterCreateUpdateBy($modelTypeId, $tabAdditionallyId);

		(new \App\Model\Telenok\Object\Field())->storeOrUpdate(
				[
                    'title' => ['ru' => "Заголовок в теге <meta>", 'en' => "Title in <meta> tag"],
                    'title_list' => ['ru' => "Заголовок в теге <meta>", 'en' => "Title in <meta> tag"],
                    'key' => 'string',
                    'code' => 'title_ceo',
                    'active' => 1,
                    'field_object_type' => $modelTypeId,
                    'field_object_tab' => $tabMainId,
                    'multilanguage' => 1,
                    'show_in_form' => 1,
                    'show_in_list' => 0,
                    'allow_search' => 1,
                    'allow_create' => 1,
                    'allow_update' => 1,
                    'required' => 0,
                    'field_order' => 3,
                ]
        );

		(new \App\Model\Telenok\Object\Field())->storeOrUpdate(
				[
                    'title' => ['ru' => "Ключевые слова в теге <meta>", 'en' => "Keywords in <meta> tag"],
                    'title_list' => ['ru' => "Ключевые слова в теге <meta>", 'en' => "Keywords in <meta> tag"],
                    'key' => 'string',
                    'code' => 'keywords_ceo',
                    'active' => 1,
                    'field_object_type' => $modelTypeId,
                    'field_object_tab' => $tabMainId,
                    'multilanguage' => 1,
                    'show_in_form' => 1,
                    'show_in_list' => 0,
                    'allow_search' => 1,
                    'allow_create' => 1,
                    'allow_update' => 1,
                    'required' => 0,
                    'field_order' => 4,
                ]
        );

		(new \App\Model\Telenok\Object\Field())->storeOrUpdate(
				[
                    'title' => ['ru' => "Описание in <meta> tag", 'en' => "Description in <meta> tag"],
                    'title_list' => ['ru' => "Описание in <meta> tag", 'en' => "Description in <meta> tag"],
                    'key' => 'string',
                    'code' => 'description_ceo',
                    'active' => 1,
                    'field_object_type' => $modelTypeId,
                    'field_object_tab' => $tabMainId,
                    'multilanguage' => 1,
                    'show_in_form' => 1,
                    'show_in_list' => 0,
                    'allow_search' => 1,
                    'allow_create' => 1,
                    'allow_update' => 1,
                    'required' => 0,
                    'field_order' => 5,
                ]
        );

		(new \App\Model\Telenok\Object\Field())->storeOrUpdate(
				[
                    'title' => ['ru' => "Имя файла шаблона", 'en' => "File name of template"],
                    'title_list' => ['ru' => "Имя файла шаблона", 'en' => "File name of template"],
                    'key' => 'string',
                    'code' => 'template_view',
                    'active' => 1,
                    'field_object_type' => $modelTypeId,
                    'field_object_tab' => $tabMainId,
                    'multilanguage' => 1,
                    'show_in_form' => 1,
                    'show_in_list' => 1,
                    'allow_search' => 1,
                    'allow_create' => 1,
                    'allow_update' => 1,
                    'field_order' => 6,
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
                    'title' => ['en' => 'URL pattern', 'ru' => 'URL шаблон'],
                    'title_list' => ['en' => 'URL pattern', 'ru' => 'URL шаблон'],
                    'key' => 'string',
                    'code' => 'url_pattern',
                    'active' => 1,
                    'string_default' => '/',
                    'field_object_type' => $modelTypeId,
                    'field_object_tab' => $tabMainId,
                    'multilanguage' => 0,
                    'show_in_form' => 1,
                    'show_in_list' => 1,
                    'allow_search' => 1,
                    'allow_create' => 1,
                    'allow_update' => 1,
                    'field_order' => 8,
                ]
        );

        (new \App\Model\Telenok\Object\Field())->storeOrUpdate(
                [
                    'title' => ['en' => 'URL redirect', 'ru' => 'URL перенаправления'],
                    'title_list' => ['en' => 'URL redirect', 'ru' => 'URL перенаправления'],
                    'key' => 'string',
                    'code' => 'url_redirect',
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
                'key' => 'tree',
                'field_object_type' => $modelTypeId,
                'field_object_tab' => 'main',
                'allow_create' => 1,
                'allow_update' => 1,
                'field_order' => 20,
            ]
        );
    }
}
