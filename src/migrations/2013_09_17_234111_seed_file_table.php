<?php

class SeedFileTable extends \App\Vendor\Telenok\Core\Support\Migrations\Migration {

    public function up()
    {
        parent::up();
/*
        $modelTypeId = DB::table('object_type')->where('code', 'file')->value('id');

        $tabMainId = \SeedCommonFields::createTabMain($modelTypeId);
        $tabVisibleId = \SeedCommonFields::createTabVisible($modelTypeId);
        $tabAdditionallyId = \SeedCommonFields::createTabAdditionally($modelTypeId);

        \SeedCommonFields::alterId($modelTypeId, $tabMainId);
        \SeedCommonFields::alterTitle($modelTypeId, $tabMainId);
        \SeedCommonFields::alterActive($modelTypeId, $tabVisibleId);
        \SeedCommonFields::alterCreateUpdateBy($modelTypeId, $tabAdditionallyId);
*/
        (new \App\Vendor\Telenok\Core\Model\Object\Type())->storeOrUpdate([
            'title'       => ['ru' => 'Файл', 'en' => 'File'],
            'title_list'  => ['ru' => 'Файл', 'en' => 'File'],
            'code'        => 'file',
            'active'      => 1,
            'model_class' => '\App\Vendor\Telenok\Core\Model\File\File',
            'multilanguage' => 1,
        ]);

        (new \App\Vendor\Telenok\Core\Model\Object\Field())->storeOrUpdate([
            'title' => ['en' => 'Description', 'ru' => 'Описание'],
            'title_list' => ['en' => 'Description', 'ru' => 'Описание'],
            'key' => 'text',
            'code' => 'description',
            'active' => 1,
            'field_object_type' => 'file',
            'field_object_tab' => 'main',
            'multilanguage' => 1,
            'show_in_form' => 1,
            'show_in_list' => 1,
            'allow_search' => 1,
            'allow_create' => 1,
            'allow_update' => 1,
            'field_order' => 9,
            'text_rte' => 1,
        ]);

        (new \App\Vendor\Telenok\Core\Model\Object\Field())->storeOrUpdate([
            'title' => ['en' => 'File category'],
            'title_list' => ['en' => 'File category'],
            'key' => 'relation-many-to-many',
            'code' => 'category',
            'active' => 1,
            'field_object_type' => 'file',
            'field_object_tab' => 'main',
            'relation_many_to_many_has' => 'file_category',
            'show_in_form' => 1,
            'show_in_list' => 0,
            'allow_search' => 1,
            'multilanguage' => 0,
            'allow_create' => 1,
            'allow_update' => 1,
            'field_order' => 21,
        ]);
 
        (new \App\Vendor\Telenok\Core\Model\Object\Field())->storeOrUpdate([
            'title' => ['en' => 'File upload'],
            'title_list' => ['en' => 'File upload'],
            'key' => 'upload',
            'code' => 'upload',
            'active' => 1,
            'field_object_type' => 'file',
            'field_object_tab' => 'main',
            'show_in_form' => 1,
            'show_in_list' => 1,
            'allow_search' => 1,
            'multilanguage' => 0,
            'allow_create' => 1,
            'allow_update' => 1,
            'field_order' => 33,
            'upload_allow_ext' => ['jpg', 'jpeg', 'png', 'txt', 'doc', 'gif'],
            'upload_allow_mime' => ['image/jpeg', 'image/pjpeg', 'image/gif', 'image/png', 'text/plain'],
        ]); 
    }
}
