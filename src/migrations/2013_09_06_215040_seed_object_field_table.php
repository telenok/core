<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SeedObjectFieldTable extends Migration {

    public function up()
    {
        if (Schema::hasTable('object_type') && Schema::hasTable('object_field'))
        {
            $typeId = DB::table('object_type')->where('code', 'object_type')->pluck('id');
            $modelTypeId = DB::table('object_type')->where('code', 'object_field')->pluck('id');

            $tabMainId = \SeedCommonFields::createTabMain($modelTypeId);
            $tabVisibleId = \SeedCommonFields::createTabVisible($modelTypeId);
            $tabAdditionallyId = \SeedCommonFields::createTabAdditionally($modelTypeId);

            \SeedCommonFields::alterId($modelTypeId, $tabMainId);
            \SeedCommonFields::alterTitle($modelTypeId, $tabMainId);
            \SeedCommonFields::alterTitleList($modelTypeId, $tabMainId);
            \SeedCommonFields::alterActive($modelTypeId, $tabVisibleId);
            \SeedCommonFields::alterCreateUpdateBy($modelTypeId, $tabAdditionallyId);

            DB::table('object_field')->insert(
                    [
                        'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\App\Model\Telenok\Object\Field']),
                        'title' => json_encode(SeedObjectFieldTableTranslation::get('field.required'), JSON_UNESCAPED_UNICODE),
                        'title_list' => json_encode(SeedObjectFieldTableTranslation::get('field.required'), JSON_UNESCAPED_UNICODE),
                        'key' => 'select-one',
                        'code' => 'required',
                        'select_one_data' => json_encode([
                            'title' => \SeedCommonFields::llYesNo(),
                            'key' => [0, 1],
                            'default' => 0,
                                ], JSON_UNESCAPED_UNICODE),
                        'active' => 1,
                        'field_view' => 'core::field.select-one.model-toggle-button',
                        'field_object_type' => $modelTypeId,
                        'field_object_tab' => $tabMainId,
                        'multilanguage' => 0,
                        'show_in_form' => 0,
                        'show_in_list' => 0,
                        'allow_search' => 1,
                        'required' => 0,
                        'field_order' => 5,
                    ]
            );

            DB::table('object_field')->insert(
                    [
                        'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\App\Model\Telenok\Object\Field']),
                        'title' => json_encode(SeedObjectFieldTableTranslation::get('field.code'), JSON_UNESCAPED_UNICODE),
                        'title_list' => json_encode(SeedObjectFieldTableTranslation::get('field.code'), JSON_UNESCAPED_UNICODE),
                        'key' => 'string',
                        'code' => 'code',
                        'active' => 1,
                        'field_object_type' => $modelTypeId,
                        'field_object_tab' => $tabMainId,
                        'multilanguage' => 0,
                        'show_in_form' => 1,
                        'show_in_list' => 1,
                        'allow_search' => 1,
                        'allow_create' => 1,
                        'allow_update' => 0,
                        'field_order' => 4,
                    ]
            );

            DB::table('object_field')->insert(
                    [
                        'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\App\Model\Telenok\Object\Field']),
                        'title' => json_encode(SeedObjectFieldTableTranslation::get('field.field_view'), JSON_UNESCAPED_UNICODE),
                        'title_list' => json_encode(SeedObjectFieldTableTranslation::get('field.field_view'), JSON_UNESCAPED_UNICODE),
                        'key' => 'string',
                        'code' => 'field_view',
                        'active' => 1,
                        'field_object_type' => $modelTypeId,
                        'field_object_tab' => $tabMainId,
                        'multilanguage' => 0,
                        'show_in_form' => 1,
                        'show_in_list' => 0,
                        'allow_search' => 1,
                        'required' => 1,
                        'field_order' => 7,
                    ]
            );
            DB::table('object_field')->insert(
                    [
                        'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\App\Model\Telenok\Object\Field']),
                        'title' => json_encode(SeedObjectFieldTableTranslation::get('field.field_object_type'), JSON_UNESCAPED_UNICODE),
                        'title_list' => json_encode(SeedObjectFieldTableTranslation::get('field.field_object_type'), JSON_UNESCAPED_UNICODE),
                        'key' => 'relation-one-to-many',
                        'code' => 'field_object_type',
                        'active' => 1,
                        'field_object_type' => $modelTypeId,
                        'field_object_tab' => $tabMainId,
                        'relation_one_to_many_belong_to' => $typeId,
                        'multilanguage' => 0,
                        'show_in_form' => 1,
                        'show_in_list' => 1,
                        'allow_search' => 1,
                        'allow_create' => 1,
                        'allow_update' => 0,
                        'field_order' => 6,
                    ]
            );

            DB::table('object_field')->insert(
                    [
                        'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\App\Model\Telenok\Object\Field']),
                        'title' => json_encode(['en' => 'Tab'], JSON_UNESCAPED_UNICODE),
                        'title_list' => json_encode(['en' => 'Tab'], JSON_UNESCAPED_UNICODE),
                        'key' => 'relation-one-to-many',
                        'code' => 'field_object_tab',
                        'active' => 1,
                        'field_object_type' => $modelTypeId,
                        'field_object_tab' => $tabAdditionallyId,
                        'relation_one_to_many_belong_to' => DB::table('object_type')->where('code', 'object_tab')->pluck('id'),
                        'multilanguage' => 0,
                        'show_in_form' => 1,
                        'show_in_list' => 0,
                        'allow_search' => 1,
                        'allow_create' => 1,
                        'allow_update' => 1,
                    ]
            );

            DB::table('object_field')->insert(
                    [
                        'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\App\Model\Telenok\Object\Field']),
                        'title' => json_encode(['en' => 'Order in field list'], JSON_UNESCAPED_UNICODE),
                        'title_list' => json_encode(['en' => 'Order in field list'], JSON_UNESCAPED_UNICODE),
                        'key' => 'integer-unsigned',
                        'code' => 'field_order',
                        'active' => 1,
                        'field_object_type' => $modelTypeId,
                        'field_object_tab' => $tabAdditionallyId,
                        'multilanguage' => 0,
                        'show_in_form' => 1,
                        'show_in_list' => 0,
                        'allow_search' => 1,
                        'allow_create' => 1,
                        'allow_update' => 1,
                        'field_order' => 6,
                    ]
            );

            DB::table('object_field')->insert(
                    [
                        'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\App\Model\Telenok\Object\Field']),
                        'title' => json_encode(SeedObjectFieldTableTranslation::get('field.multilanguage'), JSON_UNESCAPED_UNICODE),
                        'title_list' => json_encode(SeedObjectFieldTableTranslation::get('field.multilanguage'), JSON_UNESCAPED_UNICODE),
                        'key' => 'select-one',
                        'code' => 'multilanguage',
                        'select_one_data' => json_encode([
                            'title' => \SeedCommonFields::llYesNo(),
                            'key' => [0, 1],
                            'default' => 0,
                                ], JSON_UNESCAPED_UNICODE),
                        'active' => 1,
                        'field_view' => 'core::field.select-one.model-toggle-button',
                        'field_object_type' => $modelTypeId,
                        'field_object_tab' => $tabMainId,
                        'multilanguage' => 0,
                        'show_in_form' => 1,
                        'show_in_list' => 0,
                        'allow_search' => 0,
                        'allow_create' => 1,
                        'allow_update' => 1,
                        'field_order' => 5,
                    ]
            );

            DB::table('object_field')->insert(
                    [
                        'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\App\Model\Telenok\Object\Field']),
                        'title' => json_encode(SeedObjectFieldTableTranslation::get('field.rule'), JSON_UNESCAPED_UNICODE),
                        'title_list' => json_encode(SeedObjectFieldTableTranslation::get('field.rule'), JSON_UNESCAPED_UNICODE),
                        'key' => 'complex-array',
                        'code' => 'rule',
                        'active' => 1,
                        'field_object_type' => $modelTypeId,
                        'field_object_tab' => $tabMainId,
                        'multilanguage' => 0,
                        'show_in_form' => 0,
                        'show_in_list' => 0,
                        'allow_search' => 0,
                        'allow_create' => 1,
                        'allow_update' => 1,
                        'field_order' => 10,
                    ]
            );


            DB::table('object_field')->insert(
                    [
                        'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\App\Model\Telenok\Object\Field']),
                        'title' => json_encode(SeedObjectFieldTableTranslation::get('field.allow_create'), JSON_UNESCAPED_UNICODE),
                        'title_list' => json_encode(SeedObjectFieldTableTranslation::get('field.allow_create'), JSON_UNESCAPED_UNICODE),
                        'key' => 'select-one',
                        'code' => 'allow_create',
                        'select_one_data' => json_encode([
                            'title' => \SeedCommonFields::llYesNo(),
                            'key' => [0, 1],
                            'default' => 0,
                                ], JSON_UNESCAPED_UNICODE),
                        'active' => 1,
                        'field_view' => 'core::field.select-one.model-toggle-button',
                        'field_object_type' => $modelTypeId,
                        'field_object_tab' => $tabAdditionallyId,
                        'multilanguage' => 0,
                        'show_in_form' => 1,
                        'show_in_list' => 0,
                        'allow_search' => 0,
                        'allow_create' => 1,
                        'allow_update' => 1,
                        'field_order' => 12,
                    ]
            );

            DB::table('object_field')->insert(
                    [
                        'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\App\Model\Telenok\Object\Field']),
                        'title' => json_encode(SeedObjectFieldTableTranslation::get('field.allow_update'), JSON_UNESCAPED_UNICODE),
                        'title_list' => json_encode(SeedObjectFieldTableTranslation::get('field.allow_update'), JSON_UNESCAPED_UNICODE),
                        'key' => 'select-one',
                        'code' => 'allow_update',
                        'select_one_data' => json_encode([
                            'title' => \SeedCommonFields::llYesNo(),
                            'key' => [0, 1],
                            'default' => 0,
                                ], JSON_UNESCAPED_UNICODE),
                        'active' => 1,
                        'field_view' => 'core::field.select-one.model-toggle-button',
                        'field_object_type' => $modelTypeId,
                        'field_object_tab' => $tabAdditionallyId,
                        'multilanguage' => 0,
                        'show_in_form' => 1,
                        'show_in_list' => 0,
                        'allow_search' => 0,
                        'allow_create' => 1,
                        'allow_update' => 1,
                        'field_order' => 13,
                    ]
            );

            DB::table('object_field')->insert(
                    [
                        'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\App\Model\Telenok\Object\Field']),
                        'title' => json_encode(SeedObjectFieldTableTranslation::get('field.allow_sort'), JSON_UNESCAPED_UNICODE),
                        'title_list' => json_encode(SeedObjectFieldTableTranslation::get('field.allow_sort'), JSON_UNESCAPED_UNICODE),
                        'key' => 'select-one',
                        'code' => 'allow_sort',
                        'select_one_data' => json_encode([
                            'title' => \SeedCommonFields::llYesNo(),
                            'key' => [0, 1],
                            'default' => 0,
                                ], JSON_UNESCAPED_UNICODE),
                        'active' => 1,
                        'field_view' => 'core::field.select-one.model-toggle-button',
                        'field_object_type' => $modelTypeId,
                        'field_object_tab' => $tabAdditionallyId,
                        'multilanguage' => 0,
                        'show_in_form' => 1,
                        'show_in_list' => 0,
                        'allow_search' => 1,
                        'allow_create' => 1,
                        'allow_update' => 1,
                        'field_order' => 15,
                    ]
            );

            DB::table('object_field')->insert(
                    [
                        'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\App\Model\Telenok\Object\Field']),
                        'title' => json_encode(SeedObjectFieldTableTranslation::get('field.allow_search'), JSON_UNESCAPED_UNICODE),
                        'title_list' => json_encode(SeedObjectFieldTableTranslation::get('field.allow_search'), JSON_UNESCAPED_UNICODE),
                        'key' => 'select-one',
                        'code' => 'allow_search',
                        'select_one_data' => json_encode([
                            'title' => \SeedCommonFields::llYesNo(),
                            'key' => [0, 1],
                            'default' => 0,
                                ], JSON_UNESCAPED_UNICODE),
                        'active' => 1,
                        'field_view' => 'core::field.select-one.model-toggle-button',
                        'field_object_type' => $modelTypeId,
                        'field_object_tab' => $tabAdditionallyId,
                        'multilanguage' => 0,
                        'show_in_form' => 1,
                        'show_in_list' => 0,
                        'allow_search' => 1,
                        'allow_create' => 1,
                        'allow_update' => 1,
                        'field_order' => 16,
                    ]
            );

            DB::table('object_field')->insert(
                    [
                        'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\App\Model\Telenok\Object\Field']),
                        'title' => json_encode(SeedObjectFieldTableTranslation::get('field.show_in_list'), JSON_UNESCAPED_UNICODE),
                        'title_list' => json_encode(SeedObjectFieldTableTranslation::get('field.show_in_list'), JSON_UNESCAPED_UNICODE),
                        'key' => 'select-one',
                        'code' => 'show_in_list',
                        'select_one_data' => json_encode([
                            'title' => \SeedCommonFields::llYesNo(),
                            'key' => [0, 1],
                            'default' => 1,
                                ], JSON_UNESCAPED_UNICODE),
                        'active' => 1,
                        'field_view' => 'core::field.select-one.model-toggle-button',
                        'field_object_type' => $modelTypeId,
                        'field_object_tab' => $tabAdditionallyId,
                        'multilanguage' => 0,
                        'show_in_form' => 1,
                        'show_in_list' => 0,
                        'allow_search' => 1,
                        'allow_create' => 1,
                        'allow_update' => 1,
                        'field_order' => 17,
                    ]
            );

            DB::table('object_field')->insert(
                    [
                        'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\App\Model\Telenok\Object\Field']),
                        'title' => json_encode(SeedObjectFieldTableTranslation::get('field.show_in_form'), JSON_UNESCAPED_UNICODE),
                        'title_list' => json_encode(SeedObjectFieldTableTranslation::get('field.show_in_form'), JSON_UNESCAPED_UNICODE),
                        'key' => 'select-one',
                        'code' => 'show_in_form',
                        'select_one_data' => json_encode([
                            'title' => \SeedCommonFields::llYesNo(),
                            'key' => [0, 1],
                            'default' => 1,
                                ], JSON_UNESCAPED_UNICODE),
                        'active' => 1,
                        'field_view' => 'core::field.select-one.model-toggle-button',
                        'field_object_type' => $modelTypeId,
                        'field_object_tab' => $tabAdditionallyId,
                        'multilanguage' => 0,
                        'show_in_form' => 1,
                        'show_in_list' => 0,
                        'allow_search' => 1,
                        'allow_create' => 1,
                        'allow_update' => 1,
                        'checkbox_default' => 1,
                        'field_order' => 18,
                    ]
            );

            DB::table('object_field')->insert(
                    [
                        'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\App\Model\Telenok\Object\Field']),
                        'title' => json_encode(SeedObjectFieldTableTranslation::get('field.key'), JSON_UNESCAPED_UNICODE),
                        'title_list' => json_encode(SeedObjectFieldTableTranslation::get('field.key'), JSON_UNESCAPED_UNICODE),
                        'key' => 'string',
                        'code' => 'key',
                        'active' => 1,
                        'field_object_type' => $modelTypeId,
                        'field_object_tab' => $tabMainId,
                        'multilanguage' => 0,
                        'show_in_form' => 1,
                        'show_in_list' => 1,
                        'allow_search' => 0,
                        'allow_create' => 1,
                        'allow_update' => 0,
                        'field_order' => 19,
                    ]
            );

            DB::table('object_field')->insert(
                    [
                        'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\App\Model\Telenok\Object\Field']),
                        'title' => json_encode(SeedObjectFieldTableTranslation::get('field.description'), JSON_UNESCAPED_UNICODE),
                        'title_list' => json_encode(SeedObjectFieldTableTranslation::get('field.description'), JSON_UNESCAPED_UNICODE),
                        'key' => 'string',
                        'code' => 'description',
                        'active' => 1,
                        'field_object_type' => $modelTypeId,
                        'field_object_tab' => $tabAdditionallyId,
                        'multilanguage' => 1,
                        'show_in_form' => 1,
                        'show_in_list' => 0,
                        'allow_search' => 1,
                        'field_order' => 20,
                    ]
            );

            DB::table('object_field')->insert(
                    [
                        'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\App\Model\Telenok\Object\Field']),
                        'title' => json_encode(SeedObjectFieldTableTranslation::get('field.css_class'), JSON_UNESCAPED_UNICODE),
                        'title_list' => json_encode(SeedObjectFieldTableTranslation::get('field.css_class'), JSON_UNESCAPED_UNICODE),
                        'key' => 'string',
                        'code' => 'css_class',
                        'active' => 1,
                        'field_object_type' => $modelTypeId,
                        'field_object_tab' => $tabAdditionallyId,
                        'multilanguage' => 0,
                        'show_in_form' => 1,
                        'show_in_list' => 0,
                        'allow_search' => 1,
                        'field_order' => 21,
                    ]
            );

            DB::table('object_field')->insert(
                    [
                        'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\App\Model\Telenok\Object\Field']),
                        'title' => json_encode(SeedObjectFieldTableTranslation::get('field.icon_class'), JSON_UNESCAPED_UNICODE),
                        'title_list' => json_encode(SeedObjectFieldTableTranslation::get('field.icon_class'), JSON_UNESCAPED_UNICODE),
                        'key' => 'string',
                        'code' => 'icon_class',
                        'active' => 1,
                        'field_object_type' => $modelTypeId,
                        'field_object_tab' => $tabAdditionallyId,
                        'multilanguage' => 0,
                        'show_in_form' => 1,
                        'show_in_list' => 0,
                        'allow_search' => 1,
                        'field_order' => 22,
                    ]
            );
        }
    }

}

class SeedObjectFieldTableTranslation extends \Telenok\Core\Interfaces\Translation\Controller {

    public static $keys = [
        'field' => [
            'code' => [
                'ru' => "Код",
                'en' => "Code",
            ],
            'key' => [
                'ru' => "Тип поля",
                'en' => "Type of field",
            ],
            'field_object_type' => [
                'ru' => "Принадлежит типу",
                'en' => "Belong to type",
            ],
            'multilanguage' => [
                'ru' => "Мультиязычное",
                'en' => "Multilanguage",
            ],
            'field_view' => [
                'ru' => "Шаблон поля",
                'en' => "View of field",
            ],
            'rule' => [
                'ru' => "Правила проверки",
                'en' => "Validation rules",
            ],
            'show_in_list' => [
                'ru' => "Показывать в списке",
                'en' => "Show in list",
            ],
            'show_in_form' => [
                'ru' => "Показывать в форме",
                'en' => "Show in form",
            ],
            'allow_create' => [
                'ru' => "Доступно при создании объекта",
                'en' => "Available at object creation",
            ],
            'allow_search' => [
                'ru' => "Разрешить искать по полю",
                'en' => "Available search by field",
            ],
            'allow_update' => [
                'ru' => "Доступно при редактировании объекта",
                'en' => "Available at object editing",
            ],
            'allow_sort' => [
                'ru' => "Cортировка в списке",
                'en' => "Sorting",
            ],
            'description' => [
                'ru' => "Описание",
                'en' => "Description",
            ],
            'css_class' => [
                'ru' => "CSS класс",
                'en' => "CSS class",
            ],
            'icon_class' => [
                'ru' => "ICON класс",
                'en' => "ICON class",
            ],
            'required' => [
                'ru' => "Обязательно заполняется",
                'en' => "Required",
            ],
        ],
    ];

}
