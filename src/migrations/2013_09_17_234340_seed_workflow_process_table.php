<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SeedWorkflowProcessTable extends Migration {

    public function up()
    {
        $modelTypeId = DB::table('object_type')->where('code', 'workflow_process')->pluck('id');

        $tabMainId = \SeedCommonFields::createTabMain($modelTypeId);
        $tabVisibleId = \SeedCommonFields::createTabVisible($modelTypeId);
        $tabAdditionallyId = \SeedCommonFields::createTabAdditionally($modelTypeId);
        
        $tabParameterId = \DB::table('object_tab')->insertGetId(
                [
                    'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\Telenok\Object\Tab']),
                    'title' => json_encode(['en' => 'Parameter', 'ru' => 'Параметры'], JSON_UNESCAPED_UNICODE),
                    'code' => 'parameter',
                    'active' => 1,
                    'tab_object_type' => $modelTypeId,
                    'tab_order' => 4
                ]
        );
        
        $tabVariableId = \DB::table('object_tab')->insertGetId(
                [
                    'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\Telenok\Object\Tab']),
                    'title' => json_encode(['en' => 'Variable', 'ru' => 'Переменные'], JSON_UNESCAPED_UNICODE),
                    'code' => 'variable',
                    'active' => 1,
                    'tab_object_type' => $modelTypeId,
                    'tab_order' => 4
                ]
        );

        \SeedCommonFields::alterId($modelTypeId, $tabMainId);
        \SeedCommonFields::alterTitle($modelTypeId, $tabMainId);
        \SeedCommonFields::alterActive($modelTypeId, $tabVisibleId);
        \SeedCommonFields::alterCreateUpdateBy($modelTypeId, $tabAdditionallyId);

        (new \Telenok\Core\Model\Object\Field())->storeOrUpdate(
                [
                    'title' => ['ru' => 'Описание', 'en' => 'Description'],
                    'title_list' => ['ru' => 'Описание', 'en' => 'Description'],
                    'key' => 'text',
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
                    'title' => ['ru' => 'Процесс', 'en' => 'Process'],
                    'title_list' => ['ru' => 'Процесс', 'en' => 'Process'],
                    'key' => 'complex-array',
                    'code' => 'process',
                    'active' => 1,
                    'field_object_type' => $modelTypeId,
                    'field_object_tab' => $tabMainId,
                    'show_in_form' => 1,
                    'show_in_list' => 0,
                    'allow_search' => 0,
                    'field_order' => 6,
                ]
        );

        (new \Telenok\Core\Model\Object\Field())->storeOrUpdate(
                [
                    'title' => ['ru' => 'Событие-Oбъект', 'en' => 'Event-Object'],
                    'title_list' => ['ru' => 'Событие-Oбъект', 'en' => 'Event-Object'],
                    'key' => 'complex-array',
                    'code' => 'event_object',
                    'active' => 1,
                    'field_object_type' => $modelTypeId,
                    'field_object_tab' => $tabMainId,
                    'show_in_form' => 0,
                    'show_in_list' => 0,
                    'allow_search' => 0,
                    'field_order' => 6,
                ]
        );

        (new \Telenok\Core\Model\Object\Field())->storeOrUpdate(
                [
                    'title' => ['ru' => "Схема без ошибок?", 'en' => "Scheme without errors?"],
                    'title_list' => ['ru' => "Схема без ошибок?", 'en' => "Scheme without errors?"],
                    'key' => 'select-one',
                    'code' => 'is_valid',
                    'select_one_data' => [
                        'title' => [
                            'en' => ['No', 'Yes'],
                            'ru' => ['Нет', 'Да'],
                        ],
                        'key' => [
                            0,
                            1,
                        ],
                        'default' => 0
                    ],
                    'active' => 1,
                    'field_view' => 'core::field.select-one.model-toggle-button',
                    'field_object_type' => $modelTypeId,
                    'field_object_tab' => $tabMainId,
                    'show_in_form' => 1,
                    'show_in_list' => 1,
                    'allow_search' => 1,
                    'allow_create' => 0,
                    'allow_update' => 0,
                    'field_order' => 7,
                ]
        );
        
        (new \Telenok\Core\Model\Object\Field())->storeOrUpdate(
                [
                    'title' => ['ru' => "Параметр", 'en' => "Parameter"],
                    'title_list' => ['ru' => "Параметр", 'en' => "Parameter"],
                    'key' => 'relation-one-to-many',
                    'code' => 'parameter',
                    'active' => 1,
                    'field_object_type' => 'workflow_process',
                    'field_object_tab' => $tabParameterId,
                    'field_object_tab_belong' => 'additionally',
                    'show_in_form' => 1,
                    'show_in_list' => 0,
                    'allow_search' => 1,
                    'multilanguage' => 0,
                    'field_has' => 'workflow_process_parameter',
                    'allow_create' => 1,
                    'allow_update' => 1,
                    'field_order' => 8,
                ]
        );

        (new \Telenok\Core\Model\Object\Field())->storeOrUpdate(
                [
                    'title' => ['ru' => "Переменная", 'en' => "Variable"],
                    'title_list' => ['ru' => "Переменная", 'en' => "Variable"],
                    'key' => 'relation-one-to-many',
                    'code' => 'variable',
                    'active' => 1,
                    'field_object_type' => 'workflow_process',
                    'field_object_tab' => $tabVariableId,
                    'field_object_tab_belong' => 'additionally',
                    'show_in_form' => 1,
                    'show_in_list' => 0,
                    'allow_search' => 1,
                    'multilanguage' => 0,
                    'field_has' => 'workflow_process_variable',
                    'allow_create' => 1,
                    'allow_update' => 1,
                    'field_order' => 8,
                ]
        );
    }
}
