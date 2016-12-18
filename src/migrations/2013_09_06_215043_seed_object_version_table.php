<?php

class SeedObjectVersionTable extends \App\Vendor\Telenok\Core\Support\Migrations\Migration
{
    public function up()
    {
        parent::up();

        if (Schema::hasTable('object_version')) {
            $modelTypeId = DB::table('object_type')->where('code', 'object_version')->value('id');

            $tabMainId = \SeedCommonFields::createTabMain($modelTypeId);
            $tabVisibleId = \SeedCommonFields::createTabVisible($modelTypeId);
            $tabAdditionallyId = \SeedCommonFields::createTabAdditionally($modelTypeId);

            \SeedCommonFields::alterId($modelTypeId, $tabMainId);
            \SeedCommonFields::alterTitle($modelTypeId, $tabMainId);
            \SeedCommonFields::alterActive($modelTypeId, $tabVisibleId);
            \SeedCommonFields::alterCreateUpdateBy($modelTypeId, $tabAdditionallyId);

            DB::table('object_field')->insertGetId(
                    [
                        'id'                => DB::table('object_sequence')->insertGetId(['id' => null, 'model_class' => '\App\Vendor\Telenok\Core\Model\Object\Field']),
                        'title'             => json_encode(['ru' => 'ID объекта', 'en' => 'ID of object'], JSON_UNESCAPED_UNICODE),
                        'title_list'        => json_encode(['ru' => 'ID объекта', 'en' => 'ID of object'], JSON_UNESCAPED_UNICODE),
                        'key'               => 'integer-unsigned',
                        'code'              => 'object_id',
                        'active'            => 1,
                        'field_object_type' => $modelTypeId,
                        'field_object_tab'  => $tabMainId,
                        'multilanguage'     => 0,
                        'show_in_form'      => 1,
                        'show_in_list'      => 1,
                        'allow_search'      => 1,
                        'allow_update'      => 0,
                        'field_order'       => 3,
                    ]
            );

            DB::table('object_field')->insertGetId(
                    [
                        'id'                => DB::table('object_sequence')->insertGetId(['id' => null, 'model_class' => '\App\Vendor\Telenok\Core\Model\Object\Field']),
                        'title'             => json_encode(['ru' => 'ID типа объекта', 'en' => 'ID type of object'], JSON_UNESCAPED_UNICODE),
                        'title_list'        => json_encode(['ru' => 'ID типа объекта', 'en' => 'ID type of object'], JSON_UNESCAPED_UNICODE),
                        'key'               => 'integer-unsigned',
                        'code'              => 'object_type_id',
                        'active'            => 1,
                        'field_object_type' => $modelTypeId,
                        'field_object_tab'  => $tabMainId,
                        'multilanguage'     => 0,
                        'show_in_form'      => 0,
                        'show_in_list'      => 0,
                        'allow_search'      => 1,
                        'allow_update'      => 0,
                        'field_order'       => 3,
                    ]
            );

            DB::table('object_field')->insertGetId(
                    [
                        'id'                => DB::table('object_sequence')->insertGetId(['id' => null, 'model_class' => '\App\Vendor\Telenok\Core\Model\Object\Field']),
                        'title'             => json_encode(['ru' => 'Данные объекта', 'en' => 'Data of object'], JSON_UNESCAPED_UNICODE),
                        'title_list'        => json_encode(['ru' => 'Данные объекта', 'en' => 'Data of object'], JSON_UNESCAPED_UNICODE),
                        'key'               => 'complex-data',
                        'code'              => 'object_data',
                        'active'            => 1,
                        'field_object_type' => $modelTypeId,
                        'field_object_tab'  => $tabMainId,
                        'multilanguage'     => 0,
                        'show_in_form'      => 0,
                        'show_in_list'      => 0,
                        'allow_search'      => 1,
                        'allow_update'      => 0,
                        'field_order'       => 3,
                    ]
            );
        }
    }
}
