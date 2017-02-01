<?php

class SeedSubjectPermissionResourceTable extends \App\Vendor\Telenok\Core\Support\Migrations\Migration {

    public function up()
    {
        parent::up();

        $modelTypeId = DB::table('object_type')->where('code', 'subject_permission_resource')->value('id');

        $tabMainId = \SeedCommonFields::createTabMain($modelTypeId);
        $tabVisibleId = \SeedCommonFields::createTabVisible($modelTypeId);
        $tabAdditionallyId = \SeedCommonFields::createTabAdditionally($modelTypeId);

        \SeedCommonFields::alterId($modelTypeId, $tabMainId);
        \SeedCommonFields::alterTitle($modelTypeId, $tabMainId);
        \SeedCommonFields::alterActive($modelTypeId, $tabVisibleId);
        \SeedCommonFields::alterCreateUpdateBy($modelTypeId, $tabAdditionallyId);

        $userId = DB::table('user')->where('username', 'admin')->value('id');

        foreach(DB::table('object_type')->get() as $type)
        {
            if ($type->code != 'object_sequence')
            {
                foreach(DB::table($type->code)->get() as $item)
                {
                    $ins = [
                        'id' => $item->id,
                        'title' => $item->title,
                        'sequences_object_type' => $type->id,
                        'treeable' => $type->treeable,
                        'model_class' => $type->model_class,
                    ];
                    
                    if ($itemSequence = DB::table('object_sequence')->where('id', $item->id)->first()) {
                        DB::table('object_sequence')->where('id', $item->id)->update($ins);
                    }
                    else
                    {
                        DB::table('object_sequence')->insert($ins);
                    }
                }
            }

            app('db')->table($type->code)->update([
                'active'            => 1,
                'active_at_start'   => '2017-01-01 00:01:01',
                'active_at_end'     => '2032-01-01 00:01:01',
                'created_at'        => '2017-01-01 00:01:01',
                'updated_at'        => '2017-01-01 00:01:01',
                'created_by_user'   => $userId,
                'updated_by_user'   => $userId,
            ]);
        }

        (new \Symfony\Component\Console\Output\ConsoleOutput(
            \Symfony\Component\Console\Output\ConsoleOutput::VERBOSITY_NORMAL
        ))->writeln('Super Administrator created');

        //Login User
        Auth::loginUsingId($userId);

        (new \Symfony\Component\Console\Output\ConsoleOutput(
            \Symfony\Component\Console\Output\ConsoleOutput::VERBOSITY_NORMAL
        ))->writeln('Super Administrator logined');


        app('config')->set('app.localeDefault', 'en');
        app('config')->set('app.locales', ['en', 'ru']);
        app('config')->set('app.timezone', 'UTC');

        (new \Symfony\Component\Console\Output\ConsoleOutput(
            \Symfony\Component\Console\Output\ConsoleOutput::VERBOSITY_NORMAL
        ))->writeln('Set initial config data (locale, timezone)');

        (new \App\Vendor\Telenok\Core\Model\Object\Field())->storeOrUpdate(
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

		(new \App\Vendor\Telenok\Core\Model\Object\Field())->storeOrUpdate(
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

		(new \App\Vendor\Telenok\Core\Model\Object\Field())->storeOrUpdate(
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

		(new \App\Vendor\Telenok\Core\Model\Object\Field())->storeOrUpdate(
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

class SeedObjectPermissionResourceTableTranslation extends \Telenok\Core\Abstraction\Translation\Controller {

	public static $keys = [
        'field' => [
            'code' => ['ru' => 'Код', 'en' => 'Code'],
            'permission' => ['ru' => 'Разрешение', 'en' => 'Permission'],
            'resource' => ['ru' => 'Ресурс', 'en' => 'Resource'],
            'subject' => ['ru' => 'Владелец', 'en' => 'Owner'],
        ],
	];
}