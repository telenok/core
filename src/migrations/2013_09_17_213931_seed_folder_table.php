<?php

class SeedFolderTable extends \App\Vendor\Telenok\Core\Support\Migrations\Migration {

    public function up()
    {
        parent::up();
/*
        $modelTypeId = DB::table('object_type')->where('code', 'folder')->value('id');

        $tabMainId = \SeedCommonFields::createTabMain($modelTypeId);
        $tabVisibleId = \SeedCommonFields::createTabVisible($modelTypeId);
        $tabAdditionallyId = \SeedCommonFields::createTabAdditionally($modelTypeId);

        \SeedCommonFields::alterId($modelTypeId, $tabMainId);
        \SeedCommonFields::alterTitle($modelTypeId, $tabMainId);
        \SeedCommonFields::alterActive($modelTypeId, $tabVisibleId);
        \SeedCommonFields::alterCreateUpdateBy($modelTypeId, $tabAdditionallyId);
*/

        (new \App\Vendor\Telenok\Core\Model\Object\Type())->storeOrUpdate([
            'title'       => ['ru' => 'Папка', 'en' => 'Folder'],
            'title_list'  => ['ru' => 'Папка', 'en' => 'Folder'],
            'code'        => 'folder',
            'active'      => 1,
            'multilanguage' => 1,
            'model_class' => '\App\Vendor\Telenok\Core\Model\System\Folder',
            'treeable'    => 1,
        ]);

		(new \App\Vendor\Telenok\Core\Model\Object\Field())->storeOrUpdate([
            'title' => SeedFolderTableTranslation::get('field.code'),
            'title_list' => SeedFolderTableTranslation::get('field.code'),
            'key' => 'string',
            'code' => 'code',
            'active' => 1,
            'field_object_type' => 'folder',
            'field_object_tab' => 'main',
            'multilanguage' => 0,
            'show_in_form' => 1,
            'show_in_list' => 1,
            'allow_search' => 1,
            'allow_create' => 1,
            'allow_update' => 0,
            'field_order' => 3,
        ]);
    }
}


class SeedFolderTableTranslation extends \Telenok\Core\Abstraction\Translation\Controller {

	public static $keys = [
        'field' => [
            'code' => [
                'ru' => "Код",
                'en' => "Code",
            ],
        ],
	];

}
