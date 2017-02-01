<?php

class SeedCommonFields extends \App\Vendor\Telenok\Core\Support\Migrations\Migration {

    public function up()
    {
        parent::up();
    }

    public static function llYesNo()
    {
        return [
                'en' => ['No', 'Yes'],
                'ru' => ['Нет', 'Да'],
            ];
    }
    
    public static function createTabMain($typeId = null)
    {
        return DB::table('object_tab')->insertGetId(
                [
                    'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'model_class' => '\App\Vendor\Telenok\Core\Model\Object\Tab']),
                    'title' => json_encode(['en' => 'Main', 'ru' => 'Основное'], JSON_UNESCAPED_UNICODE),
                    'code' => 'main',
                    'active' => 1,
                    'tab_object_type' => $typeId,
                    'tab_order' => 1
                ]
        );
    }
    
    public static function createTabVisible($typeId = null)
    {
        return DB::table('object_tab')->insertGetId(
                [
                    'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'model_class' => '\App\Vendor\Telenok\Core\Model\Object\Tab']),
                    'title' => json_encode(['en' => 'Visibility', 'ru' => 'Видимость'], JSON_UNESCAPED_UNICODE),
                    'code' => 'visibility',
                    'active' => 1,
                    'tab_object_type' => $typeId,
                    'tab_order' => 2
                ]
        );
    }
    
    public static function createTabAdditionally($typeId = null)
    {
        return DB::table('object_tab')->insertGetId(
                [
                    'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'model_class' => '\App\Vendor\Telenok\Core\Model\Object\Tab']),
                    'title' => json_encode(['en' => 'Additionally', 'ru' => 'Дополнительно'], JSON_UNESCAPED_UNICODE),
                    'code' => 'additionally',
                    'active' => 1,
                    'tab_object_type' => $typeId,
                    'tab_order' => 3
                ]
        );
    }   
    
    
    public static function alterId($typeId = null, $tabId = null)
    {
        DB::table('object_field')->insert(
                [
                    'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'model_class' => '\App\Vendor\Telenok\Core\Model\Object\Field']),
                    'title' => json_encode(['en' => '№', 'ru' => '№'], JSON_UNESCAPED_UNICODE),
                    'title_list' => json_encode(['en' => '№', 'ru' => '№'], JSON_UNESCAPED_UNICODE),
                    'key' => 'integer-unsigned',
                    'code' => 'id',
                    'active' => 1,
                    'field_object_type' => $typeId,
                    'field_object_tab' => $tabId,
                    'multilanguage' => 0,
                    'show_in_list' => 1,
                    'show_in_form' => 1,
                    'allow_search' => 1,
                    'allow_create' => 0,
                    'allow_update' => 0,
                    'field_order' => 1,
                ]
        ); 
    }
    
    public static function alterTitle($typeId = null, $tabId = null, $multilanguage = 1)
    {
        DB::table('object_field')->insert(
                [
                    'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'model_class' => '\App\Vendor\Telenok\Core\Model\Object\Field']),
                    'title' => json_encode(['en' => 'Title', 'ru' => 'Заголовок'], JSON_UNESCAPED_UNICODE),
                    'title_list' => json_encode(['en' => 'Title', 'ru' => 'Заголовок'], JSON_UNESCAPED_UNICODE),
                    'key' => 'string',
                    'code' => 'title',
                    'active' => 1,
                    'field_object_type' => $typeId,
                    'field_object_tab' => $tabId,
                    'multilanguage' => $multilanguage,
                    'show_in_list' => 1,
                    'show_in_form' => 1,
                    'allow_search' => 1,
                    'allow_create' => 1,
                    'allow_update' => 1,
                    'required' => 1,
                    'field_order' => 2,
                ]
        );
    }

    public static function alterTitleList($typeId = null, $tabId = null, $multilanguage = 1)
    {
        DB::table('object_field')->insert(
                [
                    'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'model_class' => '\App\Vendor\Telenok\Core\Model\Object\Field']),
                    'title' => json_encode(['en' => 'Title of list', 'ru' => 'Заголовок списка'], JSON_UNESCAPED_UNICODE),
                    'title_list' => json_encode(['en' => 'Title of list', 'ru' => 'Заголовок списка'], JSON_UNESCAPED_UNICODE),
                    'key' => 'string',
                    'code' => 'title_list',
                    'active' => 1,
                    'field_object_type' => $typeId,
                    'field_object_tab' => $tabId,
                    'multilanguage' => $multilanguage,
                    'show_in_list' => 0,
                    'show_in_form' => 1,
                    'allow_search' => 1,
                    'allow_create' => 1,
                    'allow_update' => 1,
                    'required' => 1,
                    'field_order' => 4,
                ]
        ); 
    }
    
    public static function alterActive($typeId = null, $tabId = null)
    {
        $now = \Carbon\Carbon::now()->toDateTimeString();
        $plus15Year = \Carbon\Carbon::now()->addYears(15)->toDateTimeString();
        
        DB::table('object_field')->insert(
                [
                    'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'model_class' => '\App\Vendor\Telenok\Core\Model\Object\Field']),
                    'title' => json_encode(['en' => 'Active', 'ru' => 'Активность'], JSON_UNESCAPED_UNICODE),
                    'title_list' => json_encode(['en' => 'Active', 'ru' => 'Активность'], JSON_UNESCAPED_UNICODE),
                    'key' => 'select-one',
                    'code' => 'active',
                    'select_one_data' => json_encode([
                        'title' => \SeedCommonFields::llYesNo(),
                        'key' => [0, 1],
                        'default' => 0,
                    ], JSON_UNESCAPED_UNICODE),
                    'active' => 1,
                    'field_view' => 'core::field.select-one.model-toggle-button',
                    'field_object_type' => $typeId,
                    'field_object_tab' => $tabId,
                    'multilanguage' => 1,
                    'show_in_form' => 1,
                    'show_in_list' => 0,
                    'allow_search' => 1,
                    'allow_create' => 1,
                    'allow_update' => 1,
                    'field_order' => 9,
                ]
        );

        DB::table('object_field')->insert(
                [
                    'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'model_class' => '\App\Vendor\Telenok\Core\Model\Object\Field']),
                    'title' => json_encode(['en' => 'Active time', 'ru' => 'Время активности'], JSON_UNESCAPED_UNICODE),
                    'title_list' => json_encode(['en' => 'Active time', 'ru' => 'Время активности'], JSON_UNESCAPED_UNICODE),
                    'key' => 'datetime-range',
                    'code' => 'active_at',
                    'datetime_range_default_start' => $now,
                    'datetime_range_default_end' => $plus15Year,
                    'active' => 1,
                    'field_object_type' => $typeId,
                    'field_object_tab' => $tabId,
                    'multilanguage' => 0,
                    'show_in_form' => 1,
                    'show_in_list' => 0,
                    'allow_search' => 1,
                    'allow_create' => 1,
                    'allow_update' => 1,
                    'field_order' => 10,
                ]
        );
    }
    
    public static function alterCreateUpdateBy($typeId = null, $tabId = null)
    {
        DB::table('object_field')->insert(
                [
                    'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'model_class' => '\App\Vendor\Telenok\Core\Model\Object\Field']),
                    'title' => json_encode(['en' => 'Created by', 'ru' => 'Кем создано'], JSON_UNESCAPED_UNICODE),
                    'title_list' => json_encode(['en' => 'Created by', 'ru' => 'Кем создано'], JSON_UNESCAPED_UNICODE),
                    'key' => 'created-by',
                    'code' => 'created_by_user',
                    'active' => 1,
                    'field_object_type' => $typeId,
                    'field_object_tab' => $tabId,
                    'relation_one_to_many_belong_to' => DB::table('object_type')->where('code', 'user')->value('id'),
                    'multilanguage' => 0,
                    'show_in_form' => 1,
                    'show_in_list' => 0,
                    'allow_search' => 1,
                    'field_order' => 1,
                ]
        );

        DB::table('object_field')->insert(
            [
                'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'model_class' => '\App\Vendor\Telenok\Core\Model\Object\Field']),
                'title' => json_encode(['en' => 'Updated by', 'ru' => 'Кем обновлено'], JSON_UNESCAPED_UNICODE),
                'title_list' => json_encode(['en' => 'Updated by', 'ru' => 'Кем обновлено'], JSON_UNESCAPED_UNICODE),
                'key' => 'updated-by',
                'code' => 'updated_by_user',
                'active' => 1,
                'field_object_type' => $typeId,
                'field_object_tab' => $tabId,
                'relation_one_to_many_belong_to' => DB::table('object_type')->where('code', 'user')->value('id'),
                'multilanguage' => 0,
                'show_in_form' => 1,
                'show_in_list' => 0,
                'allow_search' => 1,
                'field_order' => 2,
            ]
        );

        DB::table('object_field')->insert(
            [
                'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'model_class' => '\App\Vendor\Telenok\Core\Model\Object\Field']),
                'title' => json_encode(['en' => 'Deleted by', 'ru' => 'Кем удалено'], JSON_UNESCAPED_UNICODE),
                'title_list' => json_encode(['en' => 'Deleted by', 'ru' => 'Кем удалено'], JSON_UNESCAPED_UNICODE),
                'key' => 'deleted-by',
                'code' => 'deleted_by_user',
                'active' => 1,
                'field_object_type' => $typeId,
                'field_object_tab' => $tabId,
                'relation_one_to_many_belong_to' => DB::table('object_type')->where('code', 'user')->value('id'),
                'multilanguage' => 0,
                'show_in_form' => 1,
                'show_in_list' => 0,
                'allow_search' => 1,
                'field_order' => 2,
            ]
        );

        DB::table('object_field')->insert(
            [
                'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'model_class' => '\App\Vendor\Telenok\Core\Model\Object\Field']),
                'title' => json_encode(['en' => 'Locked by', 'ru' => 'Кем заблокировано'], JSON_UNESCAPED_UNICODE),
                'title_list' => json_encode(['en' => 'Locked by', 'ru' => 'Кем заблокировано'], JSON_UNESCAPED_UNICODE),
                'key' => 'locked-by',
                'code' => 'locked_by_user',
                'active' => 1,
                'field_object_type' => $typeId,
                'field_object_tab' => $tabId,
                'relation_one_to_many_belong_to' => DB::table('object_type')->where('code', 'user')->value('id'),
                'multilanguage' => 0,
                'show_in_form' => 1,
                'show_in_list' => 0,
                'allow_search' => 1,
                'field_order' => 2,
            ]
        );
    }
}