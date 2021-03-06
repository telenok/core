<?php

class SeedPageTable extends \App\Vendor\Telenok\Core\Support\Migrations\Migration {

    public function up()
    {
        parent::up();
/*
        $modelTypeId = DB::table('object_type')->where('code', 'page')->value('id');

        $tabMainId = \SeedCommonFields::createTabMain($modelTypeId);
        $tabVisibleId = \SeedCommonFields::createTabVisible($modelTypeId);
        $tabAdditionallyId = \SeedCommonFields::createTabAdditionally($modelTypeId);

        \SeedCommonFields::alterId($modelTypeId, $tabMainId);
        \SeedCommonFields::alterTitle($modelTypeId, $tabMainId);
        \SeedCommonFields::alterActive($modelTypeId, $tabVisibleId);
        \SeedCommonFields::alterCreateUpdateBy($modelTypeId, $tabAdditionallyId);
*/

        (new \App\Vendor\Telenok\Core\Model\Object\Type())->storeOrUpdate([
            'title'            => ['ru' => 'Страница', 'en' => 'Page'],
            'title_list'       => ['ru' => 'Страница', 'en' => 'Page'],
            'code'             => 'page',
            'active'           => 1,
            'model_class'      => '\App\Vendor\Telenok\Core\Model\Web\Page',
            'controller_class' => '\App\Vendor\Telenok\Core\Module\Web\Page\Controller',
            'treeable'         => 1,
            'multilanguage' => 1,
        ]);

		(new \App\Vendor\Telenok\Core\Model\Object\Field())->storeOrUpdate([
            'title' => ['ru' => "Заголовок в теге <title>", 'en' => "Title in <title> tag"],
            'title_list' => ['ru' => "Заголовок в теге <title>", 'en' => "Title in <title> tag"],
            'key' => 'string',
            'code' => 'title_ceo',
            'active' => 1,
            'field_object_type' => 'page',
            'field_object_tab' => 'main',
            'multilanguage' => 1,
            'show_in_form' => 1,
            'show_in_list' => 0,
            'allow_search' => 1,
            'allow_create' => 1,
            'allow_update' => 1,
            'required' => 0,
            'field_order' => 3,
        ]);

		(new \App\Vendor\Telenok\Core\Model\Object\Field())->storeOrUpdate(
            [
                'title' => ['ru' => "Ключевые слова в теге <meta>", 'en' => "Keywords in <meta> tag"],
                'title_list' => ['ru' => "Ключевые слова в теге <meta>", 'en' => "Keywords in <meta> tag"],
                'key' => 'string',
                'code' => 'keywords_ceo',
                'active' => 1,
                'field_object_type' => 'page',
                'field_object_tab' => 'main',
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

		(new \App\Vendor\Telenok\Core\Model\Object\Field())->storeOrUpdate(
            [
                'title' => ['ru' => "Описание in <meta> tag", 'en' => "Description in <meta> tag"],
                'title_list' => ['ru' => "Описание in <meta> tag", 'en' => "Description in <meta> tag"],
                'key' => 'string',
                'code' => 'description_ceo',
                'active' => 1,
                'field_object_type' => 'page',
                'field_object_tab' => 'main',
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

        (new \App\Vendor\Telenok\Core\Model\Object\Field())->storeOrUpdate([
            'title' => ['en' => 'Controller template', 'ru' => 'Шаблон контроллера'],
            'title_list' => ['en' => 'Controller template', 'ru' => 'Шаблон контроллера'],
            'key' => 'string',
            'code' => 'template_view',
            'active' => 1,
            'string_default' => 'core::controller.frontend',
            'field_object_type' => 'page',
            'field_object_tab' => 'main',
            'multilanguage' => 0,
            'show_in_form' => 1,
            'show_in_list' => 1,
            'allow_search' => 1,
            'allow_create' => 1,
            'allow_update' => 1,
            'field_order' => 6,
        ]);

        (new \App\Vendor\Telenok\Core\Model\Object\Field())->storeOrUpdate(
            [
                'title' => ['en' => 'Controller\'s container template', 'ru' => 'Шаблон контейнера контроллера'],
                'title_list' => ['en' => 'Controller\'s container template', 'ru' => 'Шаблон контейнера контроллера'],
                'key' => 'string',
                'code' => 'controller_template_container',
                'active' => 1,
                'string_default' => 'core::controller.frontend-container',
                'field_object_type' => 'page',
                'field_object_tab' => 'main',
                'multilanguage' => 0,
                'show_in_form' => 1,
                'show_in_list' => 0,
                'allow_search' => 1,
                'allow_create' => 1,
                'allow_update' => 1,
                'field_order' => 6,
            ]
        );

        (new \App\Vendor\Telenok\Core\Model\Object\Field())->storeOrUpdate(
            [
                'title' => ['ru' => "Период кэширования", 'en' => "Cache time"],
                'title_list' => ['ru' => "Период кэширования", 'en' => "Cache time"],
                'description' => ['ru' => "Период кэширования задается в минутах от 0 (без кэширования)", 'en' => "Cache time in minutes from 0 (no cache)"],
                'key' => 'integer-unsigned',
                'code' => 'cache_time',
                'active' => 1,
                'integer_unsigned_default' => 3600,
                'field_object_type' => 'page',
                'field_object_tab' => 'main',
                'multilanguage' => 0,
                'show_in_form' => 1,
                'show_in_list' => 1,
                'allow_search' => 1,
                'allow_create' => 1,
                'allow_update' => 1,
                'field_order' => 7,
            ]
        );

        (new \App\Vendor\Telenok\Core\Model\Object\Field())->storeOrUpdate([
            'title' => ['en' => 'Controller class', 'ru' => 'Класс контроллера'],
            'title_list' => ['en' => 'Controller class', 'ru' => 'Класс контроллера'],
            'key' => 'string',
            'code' => 'controller_class',
            'active' => 1,
            'string_default' => '\App\Vendor\Telenok\Core\Controller\Frontend\Controller',
            'field_object_type' => 'page',
            'field_object_tab' => 'main',
            'multilanguage' => 0,
            'show_in_form' => 1,
            'show_in_list' => 1,
            'allow_search' => 1,
            'allow_create' => 1,
            'allow_update' => 1,
            'field_order' => 4,
        ]);

        (new \App\Vendor\Telenok\Core\Model\Object\Field())->storeOrUpdate(
            [
                'title' => ['en' => 'Controller method', 'ru' => 'Метод контроллера'],
                'title_list' => ['en' => 'Controller method', 'ru' => 'Метод контроллера'],
                'key' => 'string',
                'code' => 'controller_method',
                'active' => 1,
                'string_default' => 'getContent',
                'field_object_type' => 'page',
                'field_object_tab' => 'main',
                'multilanguage' => 0,
                'show_in_form' => 1,
                'show_in_list' => 1,
                'allow_search' => 1,
                'allow_create' => 1,
                'allow_update' => 1,
                'required' => 0,
                'field_order' => 8,
            ]
        );

        (new \App\Vendor\Telenok\Core\Model\Object\Field())->storeOrUpdate(
            [
                'title' => ['en' => 'HTTP request method', 'ru' => 'HTTP метод запроса'],
                'title_list' => ['en' => 'HTTP request method', 'ru' => 'HTTP метод запроса'],
                'key' => 'select-one',
                'code' => 'http_method',
                'select_one_data' => [
                    'title' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'],
                    'key' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'],
                    'default' => 'GET'
                ],
                'active' => 1,
                'field_view' => 'core::field.select-one.model-toggle-button',
                'field_object_type' => 'page',
                'field_object_tab' => 'main',
                'multilanguage' => 0,
                'show_in_form' => 1,
                'show_in_list' => 0,
                'allow_search' => 1,
                'allow_create' => 1,
                'allow_update' => 1,
                'field_order' => 9,
            ]
        );

        (new \App\Vendor\Telenok\Core\Model\Object\Field())->storeOrUpdate(
            [
                'title' => ['en' => 'URL pattern', 'ru' => 'URL шаблон'],
                'title_list' => ['en' => 'URL pattern', 'ru' => 'URL шаблон'],
                'key' => 'string',
                'code' => 'url_pattern',
                'active' => 1,
                'string_default' => '/',
                'field_object_type' => 'page',
                'field_object_tab' => 'main',
                'multilanguage' => 0,
                'show_in_form' => 1,
                'show_in_list' => 1,
                'allow_search' => 1,
                'allow_create' => 1,
                'allow_update' => 1,
                'field_order' => 9,
            ]
        );

        (new \App\Vendor\Telenok\Core\Model\Object\Field())->storeOrUpdate(
            [
                'title' => ['en' => 'URL redirect', 'ru' => 'URL перенаправления'],
                'title_list' => ['en' => 'URL redirect', 'ru' => 'URL перенаправления'],
                'key' => 'string',
                'code' => 'url_redirect',
                'active' => 1,
                'field_object_type' => 'page',
                'field_object_tab' => 'main',
                'multilanguage' => 0,
                'show_in_form' => 1,
                'show_in_list' => 1,
                'allow_search' => 1,
                'allow_create' => 1,
                'allow_update' => 1,
                'field_order' => 10,
            ]
        );

        (new \App\Vendor\Telenok\Core\Model\Object\Field())->storeOrUpdate(
            [
                'title' => ['en' => 'Router name', 'ru' => 'Название роутера'],
                'title_list' => ['en' => 'Router name', 'ru' => 'Название роутера'],
                'key' => 'string',
                'code' => 'router_name',
                'active' => 1,
                'field_object_type' => 'page',
                'field_object_tab' => 'main',
                'multilanguage' => 0,
                'show_in_form' => 1,
                'show_in_list' => 1,
                'allow_search' => 1,
                'allow_create' => 1,
                'allow_update' => 1,
                'field_order' => 11,
            ]
        );
    }
}
