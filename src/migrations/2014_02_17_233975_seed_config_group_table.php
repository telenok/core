<?php

class SeedConfigGroupTable extends \App\Vendor\Telenok\Core\Support\Migrations\Migration {

    public function up()
    {
        parent::up();

        $modelTypeId = DB::table('object_type')->where('code', 'config_group')->value('id');

        $tabMainId = \SeedCommonFields::createTabMain($modelTypeId);
        $tabVisibleId = \SeedCommonFields::createTabVisible($modelTypeId);
        $tabAdditionallyId = \SeedCommonFields::createTabAdditionally($modelTypeId);

        \SeedCommonFields::alterId($modelTypeId, $tabMainId);
        \SeedCommonFields::alterTitle($modelTypeId, $tabMainId);
        \SeedCommonFields::alterActive($modelTypeId, $tabVisibleId);
        \SeedCommonFields::alterCreateUpdateBy($modelTypeId, $tabAdditionallyId);

        (new \App\Vendor\Telenok\Core\Model\Object\Field())->storeOrUpdate([
            'title'             => ['ru' => 'Код', 'en' => 'Code'],
            'title_list'        => ['ru' => 'Код', 'en' => 'Code'],
            'key'               => 'string',
            'code'              => 'code',
            'active'            => 1,
            'field_object_type' => $modelTypeId,
            'field_object_tab'  => $tabMainId,
            'multilanguage'     => 1,
            'show_in_form'      => 1,
            'show_in_list'      => 0,
            'allow_search'      => 1,
            'allow_create'      => 1,
            'allow_update'      => 1,
            'required'          => 0,
            'field_order'       => 3,
        ]);

        (new \App\Vendor\Telenok\Core\Model\Object\Field())->storeOrUpdate([
            'title'             => ['ru' => 'Класс контроллера', 'en' => 'Controller class'],
            'title_list'        => ['ru' => 'Класс контроллера', 'en' => 'Controller class'],
            'key'               => 'string',
            'code'              => 'controller_class',
            'active'            => 1,
            'field_object_type' => $modelTypeId,
            'field_object_tab'  => $tabMainId,
            'multilanguage'     => 0,
            'show_in_form'      => 1,
            'show_in_list'      => 0,
            'allow_search'      => 1,
            'allow_create'      => 1,
            'allow_update'      => 1,
            'required'          => 0,
            'field_order'       => 4,
        ]);

        (new \App\Vendor\Telenok\Core\Model\Object\Field())->storeOrUpdate([
            'title'                    => ['en' => 'Configuration', 'ru' => 'Конфигурации'],
            'title_list'               => ['en' => 'Configuration', 'ru' => 'Конфигурации'],
            'key'                      => 'relation-one-to-many',
            'code'                     => 'config',
            'active'                   => 1,
            'field_object_type'        => $modelTypeId,
            'field_object_tab'         => $tabMainId,
            'relation_one_to_many_has' => 'config',
            'show_in_form'             => 1,
            'show_in_list'             => 0,
            'allow_search'             => 1,
            'multilanguage'            => 1,
            'allow_create'             => 1,
            'allow_update'             => 1,
            'field_order'              => 5,
        ]);
    }
}
