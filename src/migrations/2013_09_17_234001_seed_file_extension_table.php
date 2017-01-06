<?php

class SeedFileExtensionTable extends \App\Vendor\Telenok\Core\Support\Migrations\Migration {

    public function up()
    {
        parent::up();
/*
        $modelTypeId = DB::table('object_type')->where('code', 'file_extension')->value('id');

        $tabMainId = \SeedCommonFields::createTabMain($modelTypeId);
        $tabVisibleId = \SeedCommonFields::createTabVisible($modelTypeId);
        $tabAdditionallyId = \SeedCommonFields::createTabAdditionally($modelTypeId);

        \SeedCommonFields::alterId($modelTypeId, $tabMainId);
        \SeedCommonFields::alterTitle($modelTypeId, $tabMainId, 0);
        \SeedCommonFields::alterActive($modelTypeId, $tabVisibleId);
        \SeedCommonFields::alterCreateUpdateBy($modelTypeId, $tabAdditionallyId);
*/
        (new \App\Vendor\Telenok\Core\Model\Object\Type())->storeOrUpdate([
            'title'       => ['ru' => 'Расширение файла', 'en' => 'File extension'],
            'title_list'  => ['ru' => 'Расширение файла', 'en' => 'File extension'],
            'code'        => 'file_extension',
            'active'      => 1,
            'model_class' => '\App\Vendor\Telenok\Core\Model\File\FileExtension',
        ]);

		(new \App\Vendor\Telenok\Core\Model\Object\Field())->storeOrUpdate([
            'title' => ['ru' => "Расширение", 'en' => "Extension"],
            'title_list' => ['ru' => "Расширение", 'en' => "Extension"],
            'key' => 'string',
            'code' => 'extension',
            'active' => 1,
            'field_object_type' => 'file_extension',
            'field_object_tab' => 'main',
            'multilanguage' => 0,
            'show_in_form' => 1,
            'show_in_list' => 1,
            'allow_search' => 1,
            'required' => 1,
            'field_order' => 3,
        ]);
    }

}
