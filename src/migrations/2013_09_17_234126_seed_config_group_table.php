<?php

class SeedConfigGroupTable extends \App\Vendor\Telenok\Core\Support\Migrations\Migration {

    public function up()
    {
        parent::up();
/*
        $modelTypeId = DB::table('object_type')->where('code', 'config_group')->value('id');

        $tabMainId = \SeedCommonFields::createTabMain($modelTypeId);
        $tabVisibleId = \SeedCommonFields::createTabVisible($modelTypeId);
        $tabAdditionallyId = \SeedCommonFields::createTabAdditionally($modelTypeId);

        \SeedCommonFields::alterId($modelTypeId, $tabMainId);
        \SeedCommonFields::alterTitle($modelTypeId, $tabMainId);
        \SeedCommonFields::alterActive($modelTypeId, $tabVisibleId);
        \SeedCommonFields::alterCreateUpdateBy($modelTypeId, $tabAdditionallyId);
*/

        (new \App\Vendor\Telenok\Core\Model\Object\Type())->storeOrUpdate([
            'title'            => ['ru' => 'Группа конфигураций', 'en' => 'Configuration group'],
            'title_list'       => ['ru' => 'Группа конфигураций', 'en' => 'Configuration group'],
            'code'             => 'config_group',
            'active'           => 1,
            'multilanguage'    => 1,
            'model_class'      => '\App\Vendor\Telenok\Core\Model\System\ConfigGroup',
            'controller_class' => '\App\Vendor\Telenok\Core\Module\System\ConfigGroup\Controller',
        ]);

        (new \App\Vendor\Telenok\Core\Model\Object\Field())->storeOrUpdate([
            'title' => ['ru' => "Код", 'en' => "Code"],
            'title_list' => ['ru' => "Код", 'en' => "Code"],
            'key' => 'string',
            'code' => 'code',
            'active' => 1,
            'field_object_type' => 'config_group',
            'field_object_tab' => 'main',
            'multilanguage' => 0,
            'show_in_form' => 1,
            'show_in_list' => 0,
            'allow_create' => 1,
            'allow_update' => 1,
            'allow_search' => 0,
            'field_order' => 4,
            'string_list_size' => 150,
            'string_unique' => 1,
            'required' => 1,
        ]);

        (new \App\Vendor\Telenok\Core\Model\Object\Field())->storeOrUpdate([
            'title' => ['en' => 'Configuration', 'ru' => 'Конфигурация'],
            'title_list' => ['en' => 'Configuration', 'ru' => 'Конфигурация'],
            'key' => 'relation-one-to-many',
            'code' => 'config',
            'active' => 1,
            'field_object_type' => 'config_group',
            'field_object_tab' => 'main',
            'relation_one_to_many_has' => 'config',
            'show_in_form' => 1,
            'show_in_list' => 0,
            'allow_search' => 0,
            'multilanguage' => 0,
            'allow_create' => 1,
            'allow_update' => 1,
            'field_order' => 5,
        ]);
    }
}
