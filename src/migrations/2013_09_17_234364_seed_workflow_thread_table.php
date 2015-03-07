<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SeedWorkflowThreadTable extends Migration {

    public function up()
    {
        $modelTypeId = DB::table('object_type')->where('code', 'workflow_thread')->pluck('id');

        $tabMainId = \SeedCommonFields::createTabMain($modelTypeId);
        $tabVisibleId = \SeedCommonFields::createTabVisible($modelTypeId);
        $tabAdditionallyId = \SeedCommonFields::createTabAdditionally($modelTypeId);

        \SeedCommonFields::alterId($modelTypeId, $tabMainId);
        \SeedCommonFields::alterTitle($modelTypeId, $tabMainId);
        \SeedCommonFields::alterActive($modelTypeId, $tabVisibleId);
        \SeedCommonFields::alterCreateUpdateBy($modelTypeId, $tabAdditionallyId);

        (new \Telenok\Core\Model\Object\Field())->storeOrUpdate(
                [
                    'title' => ['ru' => 'Оригинальный процесс', 'en' => 'Original process'],
                    'title_list' => ['ru' => 'Оригинальный процесс', 'en' => 'Original process'],
                    'key' => 'complex-array',
                    'code' => 'original_process',
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
                    'title' => ['ru' => 'Оригинальные параметры', 'en' => 'Original parameter'],
                    'title_list' => ['ru' => 'Оригинальные параметры', 'en' => 'Original parameter'],
                    'key' => 'complex-array',
                    'code' => 'original_parameter',
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
                    'title' => ['ru' => 'Значения параметров', 'en' => 'Parameter\'s value'],
                    'title_list' => ['ru' => 'Значения параметров', 'en' => 'Parameter\'s value'],
                    'key' => 'complex-array',
                    'code' => 'parameter',
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
                    'title' => ['ru' => 'Оригинальные переменные', 'en' => 'Original variable'],
                    'title_list' => ['ru' => 'Оригинальные переменные', 'en' => 'Original variable'],
                    'key' => 'complex-array',
                    'code' => 'original_variable',
                    'active' => 1,
                    'field_object_type' => $modelTypeId,
                    'field_object_tab' => $tabMainId,
                    'show_in_form' => 1,
                    'show_in_list' => 0,
                    'allow_search' => 0,
                    'field_order' => 7,
                ]
        );

        (new \Telenok\Core\Model\Object\Field())->storeOrUpdate(
                [
                    'title' => ['ru' => 'Значения переменных', 'en' => 'Variable\'s value'],
                    'title_list' => ['ru' => 'Значения переменных', 'en' => 'Variable\'s value'],
                    'key' => 'complex-array',
                    'code' => 'variable',
                    'active' => 1,
                    'field_object_type' => $modelTypeId,
                    'field_object_tab' => $tabMainId,
                    'show_in_form' => 1,
                    'show_in_list' => 0,
                    'allow_search' => 0,
                    'field_order' => 8,
                ]
        );

        (new \Telenok\Core\Model\Object\Field())->storeOrUpdate(
                [
                    'title' => ['ru' => "Поток", 'en' => "Thread"],
                    'title_list' => ['ru' => "Поток", 'en' => "Thread"],
                    'key' => 'relation-one-to-many',
                    'code' => 'thread',
                    'active' => 1,
                    'field_object_type' => 'workflow_process',
                    'field_object_tab' => 'main',
                    'show_in_form' => 1,
                    'show_in_list' => 0,
                    'allow_search' => 1,
                    'multilanguage' => 0,
                    'field_has' => 'workflow_thread',
                    'allow_create' => 0,
                    'allow_update' => 0,
                    'field_order' => 8,
                ]
        );

        (new \Telenok\Core\Model\Object\Field())->storeOrUpdate(
                [
                    'title' => ['ru' => "Этап процесса", 'en' => "Processing stage"],
                    'title_list' => ['ru' => "Этап процесса", 'en' => "Processing stage"],
                    'key' => 'string',
                    'code' => 'processing_stage',
                    'active' => 1,
                    'field_object_type' => $modelTypeId,
                    'field_object_tab' => $tabMainId,
                    'multilanguage' => 0,
                    'show_in_form' => 1,
                    'show_in_list' => 1,
                    'allow_search' => 1,
                    'required' => 0,
                    'field_order' => 12,
                ]
        );

        (new \Telenok\Core\Model\Object\Field())->storeOrUpdate(
                [
                    'title' => ['ru' => 'Актуальные токены', 'en' => 'Actual tokens'],
                    'title_list' => ['ru' => 'Актуальные токены', 'en' => 'Actual tokens'],
                    'key' => 'complex-array',
                    'code' => 'processing_token',
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
                    'title' => ['ru' => 'Активные токены', 'en' => 'Active tokens'],
                    'title_list' => ['ru' => 'Активные токены', 'en' => 'Active tokens'],
                    'key' => 'complex-array',
                    'code' => 'processing_token_active',
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
                    'title' => ['ru' => 'Состояние элементов', 'en' => 'State of elements'],
                    'title_list' => ['ru' => 'Состояние элементов', 'en' => 'State of elements'],
                    'key' => 'complex-array',
                    'code' => 'processing_stencil_log',
                    'active' => 1,
                    'field_object_type' => $modelTypeId,
                    'field_object_tab' => $tabMainId,
                    'show_in_form' => 0,
                    'show_in_list' => 0,
                    'allow_search' => 0,
                    'field_order' => 6,
                ]
        );
    }
} 