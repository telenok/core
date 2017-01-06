<?php

class SeedModuleGroupTable extends \App\Vendor\Telenok\Core\Support\Migrations\Migration {

    public function up()
    {
        parent::up();
/*
        $modelTypeId = DB::table('object_type')->where('code', 'module_group')->value('id');

        $tabMainId = \SeedCommonFields::createTabMain($modelTypeId);
        $tabVisibleId = \SeedCommonFields::createTabVisible($modelTypeId);
        $tabAdditionallyId = \SeedCommonFields::createTabAdditionally($modelTypeId);

        \SeedCommonFields::alterId($modelTypeId, $tabMainId);
        \SeedCommonFields::alterTitle($modelTypeId, $tabMainId);
        \SeedCommonFields::alterActive($modelTypeId, $tabVisibleId);
        \SeedCommonFields::alterCreateUpdateBy($modelTypeId, $tabAdditionallyId);
*/
        (new \App\Vendor\Telenok\Core\Model\Object\Type())->storeOrUpdate([
            'title'       => ['ru' => 'Группа модулей', 'en' => 'Module group'],
            'title_list'  => ['ru' => 'Группа модулей', 'en' => 'Module group'],
            'code'        => 'module_group',
            'active'      => 1,
            'model_class' => '\App\Vendor\Telenok\Core\Model\Web\ModuleGroup',
            'multilanguage' => 1,
        ]);

		(new \App\Vendor\Telenok\Core\Model\Object\Field())->storeOrUpdate([
            'title' => ['en' => 'Controller class', 'ru' => 'Класс контроллера'],
            'title_list' => ['en' => 'Controller class', 'ru' => 'Класс контроллера'],
            'key' => 'string',
            'code' => 'controller_class',
            'active' => 1,
            'field_object_type' => 'module_group',
            'field_object_tab' => 'main',
            'multilanguage' => 0,
            'show_in_form' => 1,
            'show_in_list' => 1,
            'allow_search' => 1,
            'allow_create' => 1,
            'allow_update' => 1,
            'field_order' => 4,
        ]);
    }
}
