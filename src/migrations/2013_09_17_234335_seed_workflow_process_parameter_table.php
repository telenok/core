<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SeedWorkflowProcessParameterTable extends Migration {

    public function up()
    {
        $modelTypeId = DB::table('object_type')->where('code', 'workflow_process_parameter')->pluck('id');

        $tabMainId = \SeedCommonFields::createTabMain($modelTypeId);
        $tabVisibleId = \SeedCommonFields::createTabVisible($modelTypeId);
        $tabAdditionallyId = \SeedCommonFields::createTabAdditionally($modelTypeId);

        \SeedCommonFields::alterId($modelTypeId, $tabMainId);
        \SeedCommonFields::alterTitle($modelTypeId, $tabMainId);
        \SeedCommonFields::alterActive($modelTypeId, $tabVisibleId);
        \SeedCommonFields::alterCreateUpdateBy($modelTypeId, $tabAdditionallyId);

        (new \Telenok\Core\Model\Object\Field())->storeOrUpdate(
                [
                    'title' => ['ru' => 'Описание', 'en' => 'Description'],
                    'title_list' => ['ru' => 'Описание', 'en' => 'Description'],
                    'key' => 'string',
                    'code' => 'description',
                    'active' => 1,
                    'field_object_type' => $modelTypeId,
                    'field_object_tab' => $tabMainId,
                    'multilanguage' => 1,
                    'show_in_form' => 1,
                    'show_in_list' => 0,
                    'allow_search' => 0,
                    'allow_create' => 1,
                    'allow_update' => 1,
                    'field_order' => 5,
                ]
        );

        (new \Telenok\Core\Model\Object\Field())->storeOrUpdate(
                [
                    'title' => ['ru' => 'Код', 'en' => 'Code'],
                    'title_list' => ['ru' => 'Код', 'en' => 'Code'],
                    'key' => 'string',
                    'code' => 'code',
                    'active' => 1,
                    'field_object_type' => $modelTypeId,
                    'field_object_tab' => $tabMainId,
                    'show_in_form' => 1,
                    'show_in_list' => 1,
                    'allow_search' => 0,
                    'allow_create' => 1,
                    'allow_update' => 1,
                    'required' => 1,
                    'field_order' => 6,
                ]
        ); 

        (new \Telenok\Core\Model\Object\Field())->storeOrUpdate(
                [
                    'title' => ['ru' => 'Тип параметра', 'en' => 'Type of parameter'],
                    'title_list' => ['ru' => 'Тип параметра', 'en' => 'Type of parameter'],
                    'key' => 'string',
                    'code' => 'key',
                    'active' => 1,
                    'field_object_type' => $modelTypeId,
                    'field_object_tab' => $tabMainId,
                    'show_in_form' => 1,
                    'show_in_list' => 1,
                    'allow_search' => 0,
                    'allow_create' => 1,
                    'allow_update' => 1,
                    'required' => 1,
                    'field_order' => 7,
                ]
        ); 

        (new \Telenok\Core\Model\Object\Field())->storeOrUpdate(
                [
                    'title' => ['ru' => 'Конфигурация', 'en' => 'Configuration'],
                    'title_list' => ['ru' => 'Конфигурация', 'en' => 'Configuration'],
                    'key' => 'complex-array',
                    'code' => 'configuration',
                    'active' => 1,
                    'field_object_type' => $modelTypeId,
                    'field_object_tab' => $tabMainId,
                    'show_in_form' => 0,
                    'show_in_list' => 0,
                    'allow_search' => 0,
                    'field_order' => 8,
                ]
        ); 

        (new \Telenok\Core\Model\Object\Field())->storeOrUpdate(
                [
                    'title' => ['ru' => 'Значение по умолчанию', 'en' => 'Default value'],
                    'title_list' => ['ru' => 'Значение по умолчанию', 'en' => 'Default value'],
                    'key' => 'string',
                    'code' => 'default_value',
                    'active' => 1,
                    'field_object_type' => $modelTypeId,
                    'field_object_tab' => $tabMainId,
                    'show_in_form' => 1,
                    'show_in_list' => 0,
                    'allow_search' => 0,
                    'allow_create' => 1,
                    'allow_update' => 1,
                    'field_order' => 9,
                ]
        ); 

        (new \Telenok\Core\Model\Object\Field())->storeOrUpdate(
                [
                    'title' => ['ru' => 'Обязательное', 'en' => 'Required'],
                    'title_list' => ['ru' => 'Обязательное', 'en' => 'Required'],
                    'key' => 'select-one',
                    'code' => 'required',
                    'select_one_data' => [
                        'title' => \SeedCommonFields::llYesNo(),
                        'key' => [0, 1],
                        'default' => 0,
                    ],
                    'active' => 1,
                    'field_view' => 'core::field.select-one.model-toggle-button',
                    'field_object_type' => $modelTypeId,
                    'field_object_tab' => $tabMainId,
                    'show_in_form' => 1,
                    'show_in_list' => 0,
                    'allow_search' => 0,
                    'allow_create' => 1,
                    'allow_update' => 1,
                    'field_order' => 10,
                ]
        ); 
    }
}
