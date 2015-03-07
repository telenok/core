<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SeedFileTable extends Migration {

    public function up()
    {
        $modelTypeId = DB::table('object_type')->where('code', 'file')->pluck('id');

        $tabMainId = \SeedCommonFields::createTabMain($modelTypeId);
        $tabVisibleId = \SeedCommonFields::createTabVisible($modelTypeId);
        $tabAdditionallyId = \SeedCommonFields::createTabAdditionally($modelTypeId);

        \SeedCommonFields::alterId($modelTypeId, $tabMainId);
        \SeedCommonFields::alterTitle($modelTypeId, $tabMainId);
        \SeedCommonFields::alterActive($modelTypeId, $tabVisibleId);
        \SeedCommonFields::alterCreateUpdateBy($modelTypeId, $tabAdditionallyId);

        (new \App\Model\Telenok\Object\Field())->storeOrUpdate(
                [
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
                    'allow_create' => 0,
                    'allow_update' => 0,
                    'field_order' => 21,
                ]
        );
 
        (new \App\Model\Telenok\Object\Field())->storeOrUpdate(
                [
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
                ]
        ); 
    }
}
