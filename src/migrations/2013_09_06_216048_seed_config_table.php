<?php

class SeedConfigTable extends \App\Vendor\Telenok\Core\Support\Migrations\Migration
{
    public function up()
    {
        parent::up();

        $modelTypeId = DB::table('object_type')->where('code', 'config')->value('id');

        $tabMainId = \SeedCommonFields::createTabMain($modelTypeId);
        $tabVisibleId = \SeedCommonFields::createTabVisible($modelTypeId);
        $tabAdditionallyId = \SeedCommonFields::createTabAdditionally($modelTypeId);

        \SeedCommonFields::alterId($modelTypeId, $tabMainId);
        \SeedCommonFields::alterTitle($modelTypeId, $tabMainId);
        \SeedCommonFields::alterActive($modelTypeId, $tabVisibleId);
        \SeedCommonFields::alterCreateUpdateBy($modelTypeId, $tabAdditionallyId);

        DB::table('object_field')->insertGetId([
            'id'                => DB::table('object_sequence')->insertGetId(['id' => null, 'model_class' => '\App\Vendor\Telenok\Core\Model\Object\Field']),
            'title'             => json_encode(SeedConfigTableTranslation::get('field.code'), JSON_UNESCAPED_UNICODE),
            'title_list'        => json_encode(SeedConfigTableTranslation::get('field.code'), JSON_UNESCAPED_UNICODE),
            'key'               => 'string',
            'code'              => 'code',
            'active'            => 1,
            'field_object_type' => $modelTypeId,
            'field_object_tab'  => $tabMainId,
            'multilanguage'     => 0,
            'show_in_form'      => 1,
            'show_in_list'      => 1,
            'allow_search'      => 1,
            'allow_create'      => 1,
            'allow_update'      => 1,
            'required'          => 1,
            'field_order'       => 7,
        ]);

        DB::table('object_field')->insertGetId([
            'id'                => DB::table('object_sequence')->insertGetId(['id' => null, 'model_class' => '\App\Vendor\Telenok\Core\Model\Object\Field']),
            'title'             => json_encode(SeedConfigTableTranslation::get('field.controller_class'), JSON_UNESCAPED_UNICODE),
            'title_list'        => json_encode(SeedConfigTableTranslation::get('field.controller_class'), JSON_UNESCAPED_UNICODE),
            'key'               => 'string',
            'code'              => 'controller_class',
            'active'            => 1,
            'field_object_type' => $modelTypeId,
            'field_object_tab'  => $tabMainId,
            'multilanguage'     => 0,
            'show_in_form'      => 1,
            'show_in_list'      => 1,
            'allow_search'      => 1,
            'allow_create'      => 1,
            'allow_update'      => 1,
            'required'          => 0,
            'field_order'       => 7,
        ]);

        DB::table('object_field')->insertGetId([
            'id'                => DB::table('object_sequence')->insertGetId(['id' => null, 'model_class' => '\App\Vendor\Telenok\Core\Model\Object\Field']),
            'title'             => json_encode(SeedConfigTableTranslation::get('field.value'), JSON_UNESCAPED_UNICODE),
            'title_list'        => json_encode(SeedConfigTableTranslation::get('field.value'), JSON_UNESCAPED_UNICODE),
            'key'               => 'complex-data',
            'code'              => 'value',
            'active'            => 1,
            'field_object_type' => $modelTypeId,
            'field_object_tab'  => $tabMainId,
            'multilanguage'     => 0,
            'show_in_form'      => 1,
            'show_in_list'      => 1,
            'allow_search'      => 0,
            'allow_create'      => 1,
            'allow_update'      => 1,
            'required'          => 0,
            'field_order'       => 8,
        ]);

        app('config')->set('app.localeDefault', 'en');
        app('config')->set('app.locales', ['en', 'ru']);
        app('config')->set('app.timezone', 'UTC');
    }
}

class SeedConfigTableTranslation extends \Telenok\Core\Abstraction\Translation\Controller
{
    public static $keys = [
        'field' => [
            'code' => [
                'ru' => 'Код',
                'en' => 'Code',
            ],
            'value' => [
                'ru' => 'Значение',
                'en' => 'Value',
            ],
            'controller_class' => [
                'ru' => 'Класс контроллера',
                'en' => 'Class controller',
            ],
        ],
    ];
}
