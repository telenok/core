<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SeedTypes extends Migration {

	public function up()
	{
		if (Schema::hasTable('object_type') && Schema::hasTable('object_field'))
		{
			DB::table('object_type')->insertGetId(
					[
						'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\App\Model\Telenok\Object\Type']),
						'title' => json_encode(['ru' => 'Тип объекта', 'en' => 'Type object'], JSON_UNESCAPED_UNICODE),
						'title_list' => json_encode(['ru' => 'Тип объекта', 'en' => 'Type object'], JSON_UNESCAPED_UNICODE),
						'code' => 'object_type',
						'active' => 1,
						'class_model' => '\App\Model\Telenok\Object\Type',
						'class_controller' => '\App\Http\Controllers\Module\Objects\Type\Controller',
						'treeable' => 1,
					]
			);

			DB::table('object_type')->insertGetId(
					[
						'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\App\Model\Telenok\Object\Type']),
						'title' => json_encode(['ru' => 'Поле объекта', 'en' => 'Field object'], JSON_UNESCAPED_UNICODE),
						'title_list' => json_encode(['ru' => 'Поле объекта', 'en' => 'Field object'], JSON_UNESCAPED_UNICODE),
						'code' => 'object_field',
						'active' => 1,
						'class_model' => '\App\Model\Telenok\Object\Field',
						'class_controller' => '\App\Http\Controllers\Module\Objects\Field\Controller',
					]
			);

			DB::table('object_type')->insertGetId(
					[
						'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\App\Model\Telenok\Object\Type']),
						'title' => json_encode(['ru' => 'Хранилище', 'en' => 'Repository'], JSON_UNESCAPED_UNICODE),
						'title_list' => json_encode(['ru' => 'Хранилище', 'en' => 'Repository'], JSON_UNESCAPED_UNICODE),
						'code' => 'object_sequence',
						'active' => 1,
						'class_model' => '\App\Model\Telenok\Object\Sequence',
						'class_controller' => '\App\Http\Controllers\Module\Objects\Sequence\Controller',
					]
			);

			DB::table('object_type')->insertGetId(
					[
						'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\App\Model\Telenok\Object\Type']),
						'title' => json_encode(['ru' => 'Вкладка', 'en' => 'Tab'], JSON_UNESCAPED_UNICODE),
						'title_list' => json_encode(['ru' => 'Вкладка', 'en' => 'Tab'], JSON_UNESCAPED_UNICODE),
						'code' => 'object_tab',
						'active' => 1,
						'class_model' => '\App\Model\Telenok\Object\Tab'
					]
			);

			DB::table('object_type')->insertGetId(
					[
						'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\App\Model\Telenok\Object\Type']),
						'title' => json_encode(['ru' => 'Пользователь', 'en' => 'User'], JSON_UNESCAPED_UNICODE),
						'title_list' => json_encode(['ru' => 'Пользователь', 'en' => 'User'], JSON_UNESCAPED_UNICODE),
						'code' => 'user',
						'active' => 1,
						'class_model' => '\App\Model\Telenok\User\User',
					]
			);

			DB::table('object_type')->insertGetId(
					[
						'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\App\Model\Telenok\Object\Type']),
						'title' => json_encode(['ru' => 'Версия', 'en' => 'Version'], JSON_UNESCAPED_UNICODE),
						'title_list' => json_encode(['ru' => 'Версия', 'en' => 'Version'], JSON_UNESCAPED_UNICODE),
						'code' => 'object_version',
						'active' => 1,
						'class_model' => '\App\Model\Telenok\Object\Version',
						'class_controller' => '\App\Http\Controllers\Module\Objects\Version\Controller',
					]
			);

			DB::table('object_type')->insertGetId(
					[
						'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\App\Model\Telenok\Object\Type']),
						'title' => json_encode(['ru' => 'Ресурс', 'en' => 'Resource'], JSON_UNESCAPED_UNICODE),
						'title_list' => json_encode(['ru' => 'Ресурс', 'en' => 'Resource'], JSON_UNESCAPED_UNICODE),
						'code' => 'resource',
						'active' => 1,
						'class_model' => '\App\Model\Telenok\Security\Resource',
					]
			);

			DB::table('object_type')->insertGetId(
					[
						'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\App\Model\Telenok\Object\Type']),
						'title' => json_encode(['ru' => 'Настройки', 'en' => 'Setting'], JSON_UNESCAPED_UNICODE),
						'title_list' => json_encode(['ru' => 'Настройки', 'en' => 'Setting'], JSON_UNESCAPED_UNICODE),
						'code' => 'setting',
						'active' => 1,
						'class_model' => '\App\Model\Telenok\System\Setting',
						'class_controller' => '\App\Http\Controllers\Module\System\Setting\Controller',
					]
			);

			DB::table('object_type')->insertGetId(
					[
						'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\App\Model\Telenok\Object\Type']),
						'title' => json_encode(['ru' => 'Роль', 'en' => 'Role'], JSON_UNESCAPED_UNICODE),
						'title_list' => json_encode(['ru' => 'Роли пользователей', 'en' => 'Role of user'], JSON_UNESCAPED_UNICODE),
						'code' => 'role',
						'active' => 1,
						'class_model' => '\App\Model\Telenok\Security\Role',
					]
			);

			DB::table('object_type')->insertGetId(
					[
						'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\App\Model\Telenok\Object\Type']),
						'title' => json_encode(['ru' => 'Группа', 'en' => 'Group'], JSON_UNESCAPED_UNICODE),
						'title_list' => json_encode(['ru' => 'Группа пользователей', 'en' => 'Group of user'], JSON_UNESCAPED_UNICODE),
						'code' => 'group',
						'active' => 1,
						'class_model' => '\App\Model\Telenok\User\Group',
					]
			);

			DB::table('object_type')->insertGetId(
					[
						'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\App\Model\Telenok\Object\Type']),
						'title' => json_encode(['ru' => 'Разрешение', 'en' => 'Permission'], JSON_UNESCAPED_UNICODE),
						'title_list' => json_encode(['ru' => 'Разрешение', 'en' => 'Permission'], JSON_UNESCAPED_UNICODE),
						'code' => 'permission',
						'active' => 1,
						'class_model' => '\App\Model\Telenok\Security\Permission',
					]
			);

			DB::table('object_type')->insertGetId(
					[
						'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\App\Model\Telenok\Object\Type']),
						'title' => json_encode(['ru' => 'Объект-разрешение-ресурс', 'en' => 'Object-permission-resource'], JSON_UNESCAPED_UNICODE),
						'title_list' => json_encode(['ru' => 'Объект-разрешение-ресурс', 'en' => 'Object-permission-resource'], JSON_UNESCAPED_UNICODE),
						'code' => 'subject_permission_resource',
						'active' => 1,
						'class_model' => '\App\Model\Telenok\Security\SubjectPermissionResource',
					]
			);

			DB::table('object_type')->insertGetId(
					[
						'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\App\Model\Telenok\Object\Type']),
						'title' => json_encode(['ru' => 'Сообщение', 'en' => 'Message'], JSON_UNESCAPED_UNICODE),
						'title_list' => json_encode(['ru' => 'Сообщение', 'en' => 'Message'], JSON_UNESCAPED_UNICODE),
						'code' => 'user_message',
						'active' => 1,
						'class_model' => '\App\Model\Telenok\User\UserMessage',
					]
			);

			DB::table('object_type')->insertGetId(
					[
						'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\App\Model\Telenok\Object\Type']),
						'title' => json_encode(['ru' => 'Язык', 'en' => 'Language'], JSON_UNESCAPED_UNICODE),
						'title_list' => json_encode(['ru' => 'Список языков', 'en' => 'List of languages'], JSON_UNESCAPED_UNICODE),
						'code' => 'language',
						'active' => 1,
						'class_model' => '\App\Model\Telenok\System\Language',
					]
			);

			DB::table('object_type')->insertGetId(
					[
						'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\App\Model\Telenok\Object\Type']),
						'title' => json_encode(['ru' => 'Папка', 'en' => 'Folder'], JSON_UNESCAPED_UNICODE),
						'title_list' => json_encode(['ru' => 'Папка', 'en' => 'Folder'], JSON_UNESCAPED_UNICODE),
						'code' => 'folder',
						'active' => 1,
						'class_model' => '\App\Model\Telenok\System\Folder',
						'treeable' => 1
					]
			);

			DB::table('object_type')->insertGetId(
					[
						'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\App\Model\Telenok\Object\Type']),
						'title' => json_encode(['ru' => 'Бизнес-процесс', 'en' => 'Business-process'], JSON_UNESCAPED_UNICODE),
						'title_list' => json_encode(['ru' => 'Бизнес-процесс', 'en' => 'Business-process'], JSON_UNESCAPED_UNICODE),
						'code' => 'workflow_process',
						'active' => 1,
						'class_model' => '\App\Model\Telenok\Workflow\Process',
						'class_controller' => '\App\Http\Controllers\Module\Workflow\Process\Controller',
					]
			);

			DB::table('object_type')->insertGetId(
					[
						'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\App\Model\Telenok\Object\Type']),
						'title' => json_encode(['ru' => 'Переменная', 'en' => 'Variable'], JSON_UNESCAPED_UNICODE),
						'title_list' => json_encode(['ru' => 'Переменная', 'en' => 'Variable'], JSON_UNESCAPED_UNICODE),
						'code' => 'workflow_process_variable',
						'active' => 1,
						'class_model' => '\App\Model\Telenok\Workflow\Variable',
						'class_controller' => '\App\Http\Controllers\Module\Workflow\Variable\Controller',
					]
			);

			DB::table('object_type')->insertGetId(
					[
						'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\App\Model\Telenok\Object\Type']),
						'title' => json_encode(['ru' => 'Параметр', 'en' => 'Parameter'], JSON_UNESCAPED_UNICODE),
						'title_list' => json_encode(['ru' => 'Параметр', 'en' => 'Parameter'], JSON_UNESCAPED_UNICODE),
						'code' => 'workflow_process_parameter',
						'active' => 1,
						'class_model' => '\App\Model\Telenok\Workflow\Parameter',
						'class_controller' => '\App\Http\Controllers\Module\Workflow\Parameter\Controller',
					]
			);

			DB::table('object_type')->insertGetId(
					[
						'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\App\Model\Telenok\Object\Type']),
						'title' => json_encode(['ru' => 'Выполняющийся бизнес-процесс', 'en' => 'Launched business-process'], JSON_UNESCAPED_UNICODE),
						'title_list' => json_encode(['ru' => 'Выполняющийся бизнес-процесс', 'en' => 'Launched business-process'], JSON_UNESCAPED_UNICODE),
						'code' => 'workflow_thread',
						'active' => 1,
						'class_model' => '\App\Model\Telenok\Workflow\Thread',
						'class_controller' => '\App\Http\Controllers\Module\Workflow\Thread\Controller',
					]
			);

			DB::table('object_type')->insertGetId(
					[
						'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\App\Model\Telenok\Object\Type']),
						'title' => json_encode(['ru' => 'Страница', 'en' => 'Page'], JSON_UNESCAPED_UNICODE),
						'title_list' => json_encode(['ru' => 'Страница', 'en' => 'Page'], JSON_UNESCAPED_UNICODE),
						'code' => 'page',
						'active' => 1,
						'class_model' => '\App\Model\Telenok\Web\Page',
						'class_controller' => '\App\Http\Controllers\Module\Web\Page\Controller',
						'treeable' => 1,
					]
			);

			DB::table('object_type')->insertGetId(
					[
						'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\App\Model\Telenok\Object\Type']),
						'title' => json_encode(['ru' => 'Группа модулей', 'en' => 'Module group'], JSON_UNESCAPED_UNICODE),
						'title_list' => json_encode(['ru' => 'Группа модулей', 'en' => 'Module group'], JSON_UNESCAPED_UNICODE),
						'code' => 'module_group',
						'active' => 1,
						'class_model' => '\App\Model\Telenok\Web\ModuleGroup'
					]
			);

			DB::table('object_type')->insertGetId(
					[
						'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\App\Model\Telenok\Object\Type']),
						'title' => json_encode(['ru' => 'Модуль', 'en' => 'Module'], JSON_UNESCAPED_UNICODE),
						'title_list' => json_encode(['ru' => 'Модуль', 'en' => 'Module'], JSON_UNESCAPED_UNICODE),
						'code' => 'module',
						'active' => 1,
						'class_model' => '\App\Model\Telenok\Web\Module'
					]
			);

			DB::table('object_type')->insertGetId(
					[
						'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\App\Model\Telenok\Object\Type']),
						'title' => json_encode(['ru' => 'Группа виджетов', 'en' => 'Widget group'], JSON_UNESCAPED_UNICODE),
						'title_list' => json_encode(['ru' => 'Группа виджетов', 'en' => 'Widget group'], JSON_UNESCAPED_UNICODE),
						'code' => 'widget_group',
						'active' => 1,
						'class_model' => '\App\Model\Telenok\Web\WidgetGroup'
					]
			);

			DB::table('object_type')->insertGetId(
					[
						'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\App\Model\Telenok\Object\Type']),
						'title' => json_encode(['ru' => 'Виджет', 'en' => 'Widget'], JSON_UNESCAPED_UNICODE),
						'title_list' => json_encode(['ru' => 'Виджет', 'en' => 'Widget'], JSON_UNESCAPED_UNICODE),
						'code' => 'widget',
						'active' => 1,
						'class_model' => '\App\Model\Telenok\Web\Widget'
					]
			);

			DB::table('object_type')->insertGetId(
					[
						'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\App\Model\Telenok\Object\Type']),
						'title' => json_encode(['ru' => 'Контроллер страницы', 'en' => 'Page controller'], JSON_UNESCAPED_UNICODE),
						'title_list' => json_encode(['ru' => 'Контроллер страницы', 'en' => 'Page controller'], JSON_UNESCAPED_UNICODE),
						'code' => 'page_controller',
						'active' => 1,
						'class_model' => '\App\Model\Telenok\Web\PageController',
						'class_controller' => '\App\Http\Controllers\Module\Web\PageController\Controller',
					]
			);

			DB::table('object_type')->insertGetId(
					[
						'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\App\Model\Telenok\Object\Type']),
						'title' => json_encode(['ru' => 'Виджет на странице', 'en' => 'Widget on page'], JSON_UNESCAPED_UNICODE),
						'title_list' => json_encode(['ru' => 'Виджет на странице', 'en' => 'Widget on page'], JSON_UNESCAPED_UNICODE),
						'code' => 'widget_on_page',
						'active' => 1,
						'class_model' => '\App\Model\Telenok\Web\WidgetOnPage',
						'class_controller' => '\App\Http\Controllers\Module\Web\WidgetOnPage\Controller',
					]
			);

			DB::table('object_type')->insertGetId(
					[
						'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\App\Model\Telenok\Object\Type']),
						'title' => json_encode(['ru' => 'Расширение файла', 'en' => 'File extension'], JSON_UNESCAPED_UNICODE),
						'title_list' => json_encode(['ru' => 'Расширение файла', 'en' => 'File extension'], JSON_UNESCAPED_UNICODE),
						'code' => 'file_extension',
						'active' => 1,
						'class_model' => '\App\Model\Telenok\File\FileExtension', 
					]
			);

			DB::table('object_type')->insertGetId(
					[
						'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\App\Model\Telenok\Object\Type']),
						'title' => json_encode(['ru' => 'Mime type файла', 'en' => 'File mime type'], JSON_UNESCAPED_UNICODE),
						'title_list' => json_encode(['ru' => 'Mime type файла', 'en' => 'File mime type'], JSON_UNESCAPED_UNICODE),
						'code' => 'file_mime_type',
						'active' => 1,
						'class_model' => '\App\Model\Telenok\File\FileMimeType', 
					]
			);

			DB::table('object_type')->insertGetId(
					[
						'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\App\Model\Telenok\Object\Type']),
						'title' => json_encode(['ru' => 'Файл', 'en' => 'File'], JSON_UNESCAPED_UNICODE),
						'title_list' => json_encode(['ru' => 'Файл', 'en' => 'File'], JSON_UNESCAPED_UNICODE),
						'code' => 'file',
						'active' => 1,
						'class_model' => '\App\Model\Telenok\File\File', 
					]
			);

			DB::table('object_type')->insertGetId(
					[
						'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\App\Model\Telenok\Object\Type']),
						'title' => json_encode(['ru' => 'Категория файлов', 'en' => 'File category'], JSON_UNESCAPED_UNICODE),
						'title_list' => json_encode(['ru' => 'Категория файлов', 'en' => 'File category'], JSON_UNESCAPED_UNICODE),
						'code' => 'file_category',
						'active' => 1,
						'class_model' => '\App\Model\Telenok\File\FileCategory', 
					]
			);

			DB::table('object_type')->insertGetId(
					[
						'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\App\Model\Telenok\Object\Type']),
						'title' => json_encode(['ru' => 'Домен', 'en' => 'Domain'], JSON_UNESCAPED_UNICODE),
						'title_list' => json_encode(['ru' => 'Домены', 'en' => 'Domains'], JSON_UNESCAPED_UNICODE),
						'code' => 'domain',
						'active' => 1,
						'class_model' => '\App\Model\Telenok\Web\Domain', 
						'class_controller' => '\App\Http\Controllers\Module\Web\Domain\Controller',
					]
			);
		}
	}

}
