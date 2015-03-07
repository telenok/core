<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SeedPageControllerTable extends Migration {

    public function up()
    {
        $modelTypeId = DB::table('object_type')->where('code', 'page_controller')->pluck('id');

        $tabMainId = \SeedCommonFields::createTabMain($modelTypeId);
        $tabVisibleId = \SeedCommonFields::createTabVisible($modelTypeId);
        $tabAdditionallyId = \SeedCommonFields::createTabAdditionally($modelTypeId);

        \SeedCommonFields::alterId($modelTypeId, $tabMainId);
        \SeedCommonFields::alterTitle($modelTypeId, $tabMainId);
        \SeedCommonFields::alterActive($modelTypeId, $tabVisibleId);
        \SeedCommonFields::alterCreateUpdateBy($modelTypeId, $tabAdditionallyId);

        (new \App\Model\Telenok\Object\Field())->storeOrUpdate(
                [
                    'title' => ['en' => 'Controller class', 'ru' => 'Класс контроллера'],
                    'title_list' => ['en' => 'Controller class', 'ru' => 'Класс контроллера'],
                    'key' => 'string',
                    'code' => 'controller_class',
                    'active' => 1,
                    'string_default' => '\Telenok\Core\Controller\Frontend\Controller',
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

        (new \App\Model\Telenok\Object\Field())->storeOrUpdate(
                [
                    'title' => ['en' => 'Controller method', 'ru' => 'Метод контроллера'],
                    'title_list' => ['en' => 'Controller method', 'ru' => 'Метод контроллера'],
                    'key' => 'string',
                    'code' => 'controller_method',
                    'active' => 1,
                    'string_default' => 'getContent',
                    'field_object_type' => $modelTypeId,
                    'field_object_tab' => $tabMainId,
                    'multilanguage' => 0,
                    'show_in_form' => 1,
                    'show_in_list' => 0,
                    'allow_search' => 1,
                    'allow_create' => 1,
                    'allow_update' => 1,
                    'field_order' => 5,
                ]
        );

        (new \App\Model\Telenok\Object\Field())->storeOrUpdate(
                [
                    'title' => ['en' => 'Controller template', 'ru' => 'Шаблон контроллера'],
                    'title_list' => ['en' => 'Controller template', 'ru' => 'Шаблон контроллера'],
                    'key' => 'string',
                    'code' => 'template_view',
                    'active' => 1,
                    'string_default' => 'core::controller.frontend',
                    'field_object_type' => $modelTypeId,
                    'field_object_tab' => $tabMainId,
                    'multilanguage' => 0,
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
                    'title' => ['en' => 'Controller\'s container template', 'ru' => 'Шаблон контейнера контроллера'],
                    'title_list' => ['en' => 'Controller\'s container template', 'ru' => 'Шаблон контейнера контроллера'],
                    'key' => 'string',
                    'code' => 'controller_template_container',
                    'active' => 1,
                    'string_default' => 'core::controller.frontend-container',
                    'field_object_type' => $modelTypeId,
                    'field_object_tab' => $tabMainId,
                    'multilanguage' => 0,
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
                    'title' => ['en' => 'Page'],
                    'title_list' => ['en' => 'Page'],
                    'key' => 'relation-one-to-many',
                    'code' => 'page',
                    'active' => 1,
                    'field_object_type' => $modelTypeId,
                    'field_object_tab' => $tabMainId,
                    'relation_one_to_many_has' => DB::table('object_type')->where('code', 'page')->pluck('id'),
                    'show_in_form' => 1,
                    'show_in_list' => 0,
                    'allow_search' => 1,
                    'multilanguage' => 0,
                    'allow_create' => 1,
                    'allow_update' => 1,
                    'field_order' => 7,
                ]
        );
    }
}
