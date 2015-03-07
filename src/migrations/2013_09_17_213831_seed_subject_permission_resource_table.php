<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SeedSubjectPermissionResourceTable extends Migration {

	public function up()
	{
		$modelTypeId = DB::table('object_type')->where('code', 'subject_permission_resource')->pluck('id');

        $tabMainId = \SeedCommonFields::createTabMain($modelTypeId);
        $tabVisibleId = \SeedCommonFields::createTabVisible($modelTypeId);
        $tabAdditionallyId = \SeedCommonFields::createTabAdditionally($modelTypeId);

        \SeedCommonFields::alterId($modelTypeId, $tabMainId);
        \SeedCommonFields::alterTitle($modelTypeId, $tabMainId);
        \SeedCommonFields::alterActive($modelTypeId, $tabVisibleId);
        \SeedCommonFields::alterCreateUpdateBy($modelTypeId, $tabAdditionallyId);

        $now = \Carbon\Carbon::now()->toDateTimeString();
        $plus15Year = \Carbon\Carbon::now()->addYears(15)->toDateTimeString();
        
        foreach(['object_type', 'object_field', 'object_tab', 'setting', 'object_sequence'] as $table)
        {
            \DB::table($table)->update([
                'active_at_start' => $now, 
                'active_at_end' => $plus15Year,
                'created_at' => $now,
                'updated_at' => $now, 
            ]);
        }
 
		(new \App\Model\Telenok\Object\Field())->storeOrUpdate(
				[
					'title' => SeedObjectPermissionResourceTableTranslation::get('field.code'),
					'title_list' => SeedObjectPermissionResourceTableTranslation::get('field.code'),
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
					'field_order' => 6,
				]
		);

		(new \App\Model\Telenok\Object\Field())->storeOrUpdate(
				[
					'title' => SeedObjectPermissionResourceTableTranslation::get('field.resource'),
					'title_list' => SeedObjectPermissionResourceTableTranslation::get('field.resource'),
					'key' => 'relation-one-to-many',
					'code' => 'acl_resource',
					'active' => 1,
					'field_object_type' => 'object_sequence',
					'relation_one_to_many_has' => $modelTypeId,
					'field_object_tab' => 'main',
					'multilanguage' => 0,
					'show_in_form' => 1,
					'show_in_list' => 0,
					'allow_search' => 1,
					'allow_create' => 1,
					'allow_update' => 1,
					'field_order' => 8,
				]
		);
		
		(new \App\Model\Telenok\Object\Field())->storeOrUpdate(
				[
					'title' => SeedObjectPermissionResourceTableTranslation::get('field.subject'),
					'title_list' => SeedObjectPermissionResourceTableTranslation::get('field.subject'),
					'key' => 'relation-one-to-many',
					'code' => 'acl_subject',
					'active' => 1,
					'field_object_type' => 'object_sequence',
					'relation_one_to_many_has' => $modelTypeId,
					'field_object_tab' => 'main',
					'multilanguage' => 0,
					'show_in_list' => 0,
					'allow_search' => 1,
					'allow_create' => 1,
					'allow_update' => 1,
					'field_order' => 9,
				]
		);
		
		(new \App\Model\Telenok\Object\Field())->storeOrUpdate(
				[
					'title' => SeedObjectPermissionResourceTableTranslation::get('field.permission'),
					'title_list' => SeedObjectPermissionResourceTableTranslation::get('field.permission'),
					'key' => 'relation-one-to-many',
					'code' => 'acl_permission',
					'active' => 1,
					'field_object_type' => 'object_sequence',
					'relation_one_to_many_has' => $modelTypeId,
					'field_object_tab' => 'main',
					'multilanguage' => 0,
					'show_in_list' => 0,
					'allow_search' => 1,
					'allow_create' => 1,
					'allow_update' => 1,
					'field_order' => 9,
				]
		);
	}
}

class SeedObjectPermissionResourceTableTranslation extends \Telenok\Core\Interfaces\Translation\Controller {

	public static $keys = [
        'field' => [
            'code' => ['ru' => 'Код', 'en' => 'Code'],
            'permission' => ['ru' => 'Разрешение', 'en' => 'Permission'],
            'resource' => ['ru' => 'Ресурс', 'en' => 'Resource'],
            'subject' => ['ru' => 'Владелец', 'en' => 'Owner'],
        ],
	];

}
