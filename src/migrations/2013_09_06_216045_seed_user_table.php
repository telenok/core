<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SeedUserTable extends Migration {

	public function up()
	{
		$modelTypeId = DB::table('object_type')->where('code', 'user')->pluck('id');

        $tabMainId = \SeedCommonFields::createTabMain($modelTypeId);
        $tabVisibleId = \SeedCommonFields::createTabVisible($modelTypeId);
        $tabAdditionallyId = \SeedCommonFields::createTabAdditionally($modelTypeId);

        \SeedCommonFields::alterId($modelTypeId, $tabMainId);
        \SeedCommonFields::alterTitle($modelTypeId, $tabMainId, 0);
        \SeedCommonFields::alterActive($modelTypeId, $tabVisibleId);
        \SeedCommonFields::alterCreateUpdateBy($modelTypeId, $tabAdditionallyId);
  
		DB::table('object_field')->insertGetId(
				[
					'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\App\Model\Telenok\Object\Field']),
					'title' => json_encode(SeedUserTableTranslation::get('field.username'), JSON_UNESCAPED_UNICODE),
					'title_list' => json_encode(SeedUserTableTranslation::get('field.username'), JSON_UNESCAPED_UNICODE),
					'key' => 'string',
					'code' => 'username',
					'active' => 1,
					'field_object_type' => $modelTypeId,
					'field_object_tab' => $tabMainId,
					'multilanguage' => 0,
					'show_in_form' => 1,
					'show_in_list' => 1,
					'allow_search' => 1,
					'allow_create' => 1,
					'allow_update' => 1,
					'field_order' => 3,
				]
		);

		DB::table('object_field')->insertGetId(
				[
					'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\App\Model\Telenok\Object\Field']),
					'title' => json_encode(SeedUserTableTranslation::get('field.usernick'), JSON_UNESCAPED_UNICODE),
					'title_list' => json_encode(SeedUserTableTranslation::get('field.usernick'), JSON_UNESCAPED_UNICODE),
					'key' => 'string',
					'code' => 'usernick',
					'active' => 1,
					'field_object_type' => $modelTypeId,
					'field_object_tab' => $tabMainId,
					'multilanguage' => 0,
					'show_in_form' => 1,
					'show_in_list' => 1,
					'allow_search' => 1,
					'allow_create' => 1,
					'allow_update' => 1,
					'field_order' => 4,
				]
		);

		DB::table('object_field')->insertGetId(
				[
					'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\App\Model\Telenok\Object\Field']),
					'title' => json_encode(SeedUserTableTranslation::get('field.email'), JSON_UNESCAPED_UNICODE),
					'title_list' => json_encode(SeedUserTableTranslation::get('field.email'), JSON_UNESCAPED_UNICODE),
					'key' => 'string',
					'code' => 'email',
					'active' => 1,
					'field_object_type' => $modelTypeId,
					'field_object_tab' => $tabMainId,
					'multilanguage' => 0,
					'show_in_form' => 1,
					'show_in_list' => 1,
					'allow_search' => 1,
					'allow_create' => 1,
					'allow_update' => 1,
					'field_order' => 5,
                    'icon_class' => 'ace-icon fa fa-envelope',
				]
		);

		DB::table('object_field')->insertGetId(
				[
					'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\App\Model\Telenok\Object\Field']),
					'title' => json_encode(SeedUserTableTranslation::get('field.password'), JSON_UNESCAPED_UNICODE),
					'title_list' => json_encode(SeedUserTableTranslation::get('field.password'), JSON_UNESCAPED_UNICODE),
					'key' => 'string',
					'code' => 'password',
					'active' => 1,
					'field_object_type' => $modelTypeId,
					'field_object_tab' => $tabMainId,
					'multilanguage' => 0,
					'show_in_form' => 1,
					'show_in_list' => 0,
					'allow_search' => 1,
					'allow_create' => 1,
					'allow_update' => 1,
					'field_order' => 6,
					'string_password' => 1,
				]
		); 

		DB::table('object_field')->insertGetId(
				[
					'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\App\Model\Telenok\Object\Field']),
					'title' => json_encode(SeedUserTableTranslation::get('field.configuration'), JSON_UNESCAPED_UNICODE),
					'title_list' => json_encode(SeedUserTableTranslation::get('field.configuration'), JSON_UNESCAPED_UNICODE),
					'key' => 'complex-array',
					'code' => 'configuration',
					'active' => 1,
					'field_object_type' => $modelTypeId,
					'field_object_tab' => $tabMainId,
					'multilanguage' => 0,
					'show_in_form' => 0,
					'show_in_list' => 0,
					'allow_search' => 1,
					'allow_create' => 1,
					'allow_update' => 1,
					'field_order' => 7,
				]
		); 

/*
		DB::table('object_field')->insertGetId(
				[
					'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\App\Model\Telenok\Object\Field']),
					'title' => json_encode(['ru' => "Автор", 'en' => "Author"], JSON_UNESCAPED_UNICODE),
					'title_list' => json_encode(['ru' => "Автор", 'en' => "Author"], JSON_UNESCAPED_UNICODE),
					'key' => 'relation-one-to-one',
					'code' => 'author_user_message',
					'active' => 1,
					'field_object_type' => $modelTypeId,
					'field_object_tab' => $tabAdditionallyId,
					'relation_one_to_one_belong_to' => DB::table('object_type')->where('code', 'user_message')->pluck('id'),
					'show_in_form' => 1,
					'show_in_list' => 0,
					'allow_search' => 1,
					'multilanguage' => 0,
					'allow_create' => 1,
					'allow_update' => 1,
					'field_order' => 8,
				]
		);

		DB::table('object_field')->insertGetId(
				[
					'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\App\Model\Telenok\Object\Field']),
					'title' => json_encode(['ru' => "Получатель", 'en' => "Recepient"], JSON_UNESCAPED_UNICODE),
					'title_list' => json_encode(['ru' => "Получатель", 'en' => "Recepient"], JSON_UNESCAPED_UNICODE),
					'key' => 'relation-many-to-many',
					'code' => 'recepient_user_message',
					'active' => 1,
					'field_object_type' => $modelTypeId,
					'field_object_tab' => $tabAdditionallyId,
					'relation_many_to_many_belong_to' => DB::table('object_type')->where('code', 'user_message')->pluck('id'),
					'show_in_form' => 1,
					'show_in_list' => 0,
					'allow_search' => 1,
					'multilanguage' => 0,
					'allow_create' => 1,
					'allow_update' => 1,
					'field_order' => 9,
				]
		);
*/
		DB::table('object_field')->insertGetId(
				[
					'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\App\Model\Telenok\Object\Field']),
					'title' => json_encode(SeedUserTableTranslation::get('field.group'), JSON_UNESCAPED_UNICODE),
					'title_list' => json_encode(SeedUserTableTranslation::get('field.group'), JSON_UNESCAPED_UNICODE),
					'key' => 'relation-many-to-many',
					'code' => 'group',
					'active' => 1,
					'field_object_type' => $modelTypeId,
					'field_object_tab' => $tabAdditionallyId,
					'relation_many_to_many_has' => DB::table('object_type')->where('code', 'group')->pluck('id'),
					'multilanguage' => 0,
					'show_in_form' => 1,
					'show_in_list' => 0,
					'allow_search' => 1,
					'allow_create' => 1,
					'allow_update' => 1,
					'field_order' => 7,
				]
		);
		
		DB::table('object_field')->insert(
				[
					'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\App\Model\Telenok\Object\Field']),
					'title' => json_encode(['en' => 'Created by'], JSON_UNESCAPED_UNICODE),
					'title_list' => json_encode(['en' => 'Created by'], JSON_UNESCAPED_UNICODE),
					'key' => 'relation-one-to-many',
					'code' => 'created_by',
					'field_object_type' => $modelTypeId,
					'relation_one_to_many_has' => DB::table('object_type')->where('code', 'object_sequence')->pluck('id'),
					'field_object_tab' => $tabAdditionallyId,
					'active' => 1,
					'multilanguage' => 0,
					'show_in_list' => 0,
					'show_in_form' => 1,
					'allow_search' => 1,
				]
		);

		DB::table('object_field')->insert(
				[
					'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\App\Model\Telenok\Object\Field']),
					'title' => json_encode(['en' => 'Updated by'], JSON_UNESCAPED_UNICODE),
					'title_list' => json_encode(['en' => 'Updated by'], JSON_UNESCAPED_UNICODE),
					'key' => 'relation-one-to-many',
					'code' => 'updated_by',
					'field_object_type' => $modelTypeId,
					'relation_one_to_many_has' => DB::table('object_type')->where('code', 'object_sequence')->pluck('id'),
					'field_object_tab' => $tabAdditionallyId,
					'active' => 1,
					'multilanguage' => 0,
					'show_in_list' => 0,
					'show_in_form' => 1,
					'allow_search' => 1,
				]
		);

		DB::table('object_field')->insert(
				[
					'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\App\Model\Telenok\Object\Field']),
					'title' => json_encode(['en' => 'Locked by'], JSON_UNESCAPED_UNICODE),
					'title_list' => json_encode(['en' => 'Locked by'], JSON_UNESCAPED_UNICODE),
					'key' => 'relation-one-to-many',
					'code' => 'locked_by',
					'field_object_type' => $modelTypeId,
					'relation_one_to_many_has' => DB::table('object_type')->where('code', 'object_sequence')->pluck('id'),
					'field_object_tab' => $tabAdditionallyId,
					'active' => 1,
					'multilanguage' => 0,
					'show_in_list' => 0,
					'show_in_form' => 1,
					'allow_search' => 1,
				]
		);

		DB::table('object_field')->insert(
				[
					'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\App\Model\Telenok\Object\Field']),
					'title' => json_encode(['en' => 'Deleted by'], JSON_UNESCAPED_UNICODE),
					'title_list' => json_encode(['en' => 'Deleted by'], JSON_UNESCAPED_UNICODE),
					'key' => 'relation-one-to-many',
					'code' => 'deleted_by',
					'field_object_type' => DB::table('object_type')->where('code', 'user')->pluck('id'),
					'relation_one_to_many_has' => DB::table('object_type')->where('code', 'object_sequence')->pluck('id'),
					'field_object_tab' => $tabAdditionallyId,
					'active' => 1,
					'multilanguage' => 0,
					'show_in_list' => 0,
					'show_in_form' => 1,
					'allow_search' => 1,
				]
		);

		if (!Schema::hasTable('pivot_relation_m2m_group_user'))
		{
			Schema::create('pivot_relation_m2m_group_user', function(Blueprint $table)
			{
				$table->increments('id');
				$table->nullableTimestamps();
				$table->integer('group')->unsigned()->default(0);
				$table->integer('group_user')->unsigned()->default(0);
				
				$table->unique(['group', 'group_user'], 'uniq_key');
				
				$table->foreign('group')->references('id')->on('group')->onDelete('cascade');
				$table->foreign('group_user')->references('id')->on('user')->onDelete('cascade');
			});
		}
	}

}

class SeedUserTableTranslation extends \Telenok\Core\Interfaces\Translation\Controller {

	public static $keys = [
        'field' => [
            'username' => [
                'ru' => "Логин",
                'en' => "Login",
            ],
            'usernick' => [
                'ru' => "Ник",
                'en' => "Nick",
            ],
            'email' => [
                'ru' => "Email",
                'en' => "Email",
            ],
            'password' => [
                'ru' => "Пароль",
                'en' => "Password",
            ],
            'group' => [
                'ru' => "Группа",
                'en' => "Group",
            ], 
            'configuration' => [
                'ru' => "Конфигурация",
                'en' => "Configuration",
            ], 
        ],
	];

}
