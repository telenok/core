<?php

	\Route::post('widget/form/store/{typeId}', array('as' => 'cmf.widget.form.store', 'uses' => "Telenok\Core\Widget\Model\Form\Controller@store"));
	\Route::post('widget/form/update/{id}', array('as' => 'cmf.widget.form.update', 'uses' => "Telenok\Core\Widget\Model\Form\Controller@update"));
	\Route::post('widget/form/delete/{id}', array('as' => 'cmf.widget.form.delete', 'uses' => "Telenok\Core\Widget\Model\Form\Controller@delete"));
	
	\Route::get('widget/form/grid/{typeId}', array('as' => 'cmf.widget.grid.list', 'uses' => "Telenok\Core\Widget\Model\Grid\Controller@getList"));


	// Module Objects\Lists
	\Route::get('telenok/module/objects-lists/action-param', array('as' => 'cmf.module.objects-lists.action.param', 'uses' => "App\Telenok\Core\Module\Objects\Lists\Controller@getActionParam"));
	\Route::get('telenok/module/objects-lists', array('as' => 'cmf.module.objects-lists', 'uses' => "App\Telenok\Core\Module\Objects\Lists\Controller@getContent"));
	\Route::get('telenok/module/objects-lists/create/type', array('as' => 'cmf.module.objects-lists.create', 'uses' => "App\Telenok\Core\Module\Objects\Lists\Controller@create"));
	\Route::get('telenok/module/objects-lists/edit', array('as' => 'cmf.module.objects-lists.edit', 'uses' => "App\Telenok\Core\Module\Objects\Lists\Controller@edit"));
	\Route::post('telenok/module/objects-lists/store/type/{id}', array('as' => 'cmf.module.objects-lists.store', 'uses' => "App\Telenok\Core\Module\Objects\Lists\Controller@store"));
	\Route::post('telenok/module/objects-lists/update/type/{id}', array('as' => 'cmf.module.objects-lists.update', 'uses' => "App\Telenok\Core\Module\Objects\Lists\Controller@update"));
	\Route::post('telenok/module/objects-lists/delete/{id}', array('as' => 'cmf.module.objects-lists.delete', 'uses' => "App\Telenok\Core\Module\Objects\Lists\Controller@delete"));
	\Route::get('telenok/module/objects-lists/list', array('as' => 'cmf.module.objects-lists.list', 'uses' => "App\Telenok\Core\Module\Objects\Lists\Controller@getList"));
	\Route::get('telenok/module/objects-lists/list/json', array('as' => 'cmf.module.objects-lists.list.json', 'uses' => "App\Telenok\Core\Module\Objects\Lists\Controller@getListJson"));
	\Route::get('telenok/module/objects-lists/list/edit/', array('as' => 'cmf.module.objects-lists.list.edit', 'uses' => "App\Telenok\Core\Module\Objects\Lists\Controller@editList"));
	\Route::post('telenok/module/objects-lists/list/delete', array('as' => 'cmf.module.objects-lists.list.delete', 'uses' => "App\Telenok\Core\Module\Objects\Lists\Controller@deleteList"));
	\Route::post('telenok/module/objects-lists/list/lock', array('as' => 'cmf.module.objects-lists.list.lock', 'uses' => "App\Telenok\Core\Module\Objects\Lists\Controller@lockList"));
	\Route::post('telenok/module/objects-lists/lock', array('as' => 'cmf.module.objects-lists.lock', 'uses' => "App\Telenok\Core\Module\Objects\Lists\Controller@lock"));
	\Route::post('telenok/module/objects-lists/list/unlock', array('as' => 'cmf.module.objects-lists.list.unlock', 'uses' => "App\Telenok\Core\Module\Objects\Lists\Controller@unlockList"));
	\Route::get('telenok/module/objects-lists/list/tree', array('as' => 'cmf.module.objects-lists.list.tree', 'uses' => "App\Telenok\Core\Module\Objects\Lists\Controller@getTreeList"));
	\Route::get('telenok/module/objects-lists/wizard/create/type', array('as' => 'cmf.module.objects-lists.wizard.create', 'uses' => "App\Telenok\Core\Module\Objects\Lists\Wizard\Controller@create"));
	\Route::get('telenok/module/objects-lists/wizard/edit', array('as' => 'cmf.module.objects-lists.wizard.edit', 'uses' => "App\Telenok\Core\Module\Objects\Lists\Wizard\Controller@edit"));
	\Route::post('telenok/module/objects-lists/wizard/store/type/{id}', array('as' => 'cmf.module.objects-lists.wizard.store', 'uses' => "App\Telenok\Core\Module\Objects\Lists\Wizard\Controller@store"));
	\Route::post('telenok/module/objects-lists/wizard/update/type/{id}', array('as' => 'cmf.module.objects-lists.wizard.update', 'uses' => "App\Telenok\Core\Module\Objects\Lists\Wizard\Controller@update"));
	\Route::post('telenok/module/objects-lists/wizard/delete/{id}', array('as' => 'cmf.module.objects-lists.wizard.delete', 'uses' => "App\Telenok\Core\Module\Objects\Lists\Wizard\Controller@delete"));
	\Route::get('telenok/module/objects-lists/wizard/choose', array('as' => 'cmf.module.objects-lists.wizard.choose', 'uses' => "App\Telenok\Core\Module\Objects\Lists\Wizard\Controller@choose"));
	\Route::get('telenok/module/objects-lists/wizard/list', array('as' => 'cmf.module.objects-lists.wizard.list', 'uses' => "App\Telenok\Core\Module\Objects\Lists\Wizard\Controller@getWizardList"));


	// Fields
	\Route::get('telenok/field/relation-one-to-one/list/title/type/{id}', array('as' => 'cmf.field.relation-one-to-one.list.title', 'uses' => "\App\Telenok\Core\Field\RelationOneToOne\Controller@getTitleList"));

	\Route::get('telenok/field/relation-one-to-many/list/title/type/{id}', array('as' => 'cmf.field.relation-one-to-many.list.title', 'uses' => "\App\Telenok\Core\Field\RelationOneToMany\Controller@getTitleList"));
	\Route::get('telenok/field/relation-one-to-many/list/table/model/{id}/field/{fieldId}/uniqueId/{uniqueId}', array('as' => 'cmf.field.relation-one-to-many.list.table', 'uses' => "\App\Telenok\Core\Field\RelationOneToMany\Controller@getTableList"));

	\Route::get('telenok/field/relation-many-to-many/list/title/type/{id}', array('as' => 'cmf.field.relation-many-to-many.list.title', 'uses' => "\App\Telenok\Core\Field\RelationManyToMany\Controller@getTitleList"));
	\Route::get('telenok/field/relation-many-to-many/list/table/model/{id}/field/{fieldId}/uniqueId/{uniqueId}', array('as' => 'cmf.field.relation-many-to-many.list.table', 'uses' => "\App\Telenok\Core\Field\RelationManyToMany\Controller@getTableList"));

	\Route::get('telenok/field/tree/list/title/type/{id}', array('as' => 'cmf.field.tree.list.title', 'uses' => "\App\Telenok\Core\Field\System\Tree\Controller@getTitleList"));
	\Route::get('telenok/field/tree/list/table/model/{id}/field/{fieldId}/uniqueId/{uniqueId}', array('as' => 'cmf.field.tree.list.table', 'uses' => "\App\Telenok\Core\Field\System\Tree\Controller@getTableList"));

	\Route::get('telenok/field/morph-many-to-many/list/title/type/{id}', array('as' => 'cmf.field.morph-many-to-many.list.title', 'uses' => "\App\Telenok\Core\Field\MorphManyToMany\Controller@getTitleList"));
	\Route::get('telenok/field/morph-many-to-many/list/table/model/{id}/field/{fieldId}/uniqueId/{uniqueId}', array('as' => 'cmf.field.morph-many-to-many.list.table', 'uses' => "\App\Telenok\Core\Field\MorphManyToMany\Controller@getTableList"));

	\Route::get('telenok/field/morph-one-to-many/list/title/type/{id}', array('as' => 'cmf.field.morph-one-to-many.list.title', 'uses' => "\App\Telenok\Core\Field\MorphOneToMany\Controller@getTitleList"));
	\Route::get('telenok/field/morph-one-to-many/list/table/model/{id}/field/{fieldId}/uniqueId/{uniqueId}', array('as' => 'cmf.field.morph-one-to-many.list.table', 'uses' => "\App\Telenok\Core\Field\MorphOneToMany\Controller@getTableList"));

	\Route::get('telenok/field/morph-one-to-one/list/title/type/{id}', array('as' => 'cmf.field.morph-one-to-one.list.title', 'uses' => "\App\Telenok\Core\Field\MorphOneToOne\Controller@getTitleList"));

	\Route::get('telenok/field/permission/list/title', array('as' => 'cmf.field.permission.list.title', 'uses' => "\App\Telenok\Core\Field\System\Permission\Controller@getTitleList"));

	\Route::post('telenok/field/file-many-to-many/upload', array('as' => 'cmf.field.file-many-to-many.upload', 'uses' => "\App\Telenok\Core\Field\FileManyToMany\Controller@upload"));
 
	\Route::filter('control-panel', '\App\Telenok\Core\Filter\Router\Backend\ControlPanel@filter');
	\Route::whenRegex('/telenok.*/', 'control-panel');

	\Route::get('telenok', array('as' => 'cmf.content', 'uses' => "\App\Telenok\Core\Controller\Backend\Controller@getContent"));
	\Route::get('telenok/error', array('as' => 'error.access-denied', 'uses' => "\App\Telenok\Core\Controller\Backend\Controller@errorAccessDenied"));
	\Route::get('telenok/update/csrf', array('as' => 'cmf.update.csrf', 'uses' => "\App\Telenok\Core\Controller\Backend\Controller@updateCsrf"));

	// Update user's UI setting
	\Route::post('telenok/user/update/ui-setting', array('as' => 'cmf.user.update.ui-setting', 'uses' => "\App\Telenok\Core\Controller\Backend\Controller@updateBackendUISetting"));

	// Module Dashboard 
	\Route::get('telenok/module/dashboard', array('as' => 'cmf.module.dashboard', 'uses' => "App\Telenok\Core\Module\Dashboard\Controller@getContent"));

	// Module Profile
	\Route::get('telenok/module/users-profile-edit/action-param', array('as' => 'cmf.module.users-profile-edit.action.param', 'uses' => "App\Telenok\Core\Module\Users\ProfileEdit\Controller@getActionParam"));
	\Route::get('telenok/module/users-profile-edit', array('as' => 'cmf.module.users-profile-edit', 'uses' => "App\Telenok\Core\Module\Users\ProfileEdit\Controller@getContent"));
	\Route::post('telenok/module/users-profile-edit/update', array('as' => 'cmf.module.users-profile-edit.update', 'uses' => "App\Telenok\Core\Module\Users\ProfileEdit\Controller@update"));

	// Module Objects\Type
	\Route::get('telenok/module/objects-type/action-param', array('as' => 'cmf.module.objects-type.action.param', 'uses' => "App\Telenok\Core\Module\Objects\Type\Controller@getActionParam"));

	// Module Objects\Sequence
	\Route::get('telenok/module/objects-sequence/action-param', array('as' => 'cmf.module.objects-sequence.action.param', 'uses' => "App\Telenok\Core\Module\Objects\Sequence\Controller@getActionParam"));
	\Route::get('telenok/module/objects-sequence', array('as' => 'cmf.module.objects-sequence', 'uses' => "App\Telenok\Core\Module\Objects\Sequence\Controller@getContent"));
	\Route::get('telenok/module/objects-sequence/list', array('as' => 'cmf.module.objects-sequence.list', 'uses' => "App\Telenok\Core\Module\Objects\Sequence\Controller@getList"));

	// Module Objects\Version
	\Route::get('telenok/module/objects-version/action-param', array('as' => 'cmf.module.objects-version.action.param', 'uses' => "App\Telenok\Core\Module\Objects\Version\Controller@getActionParam"));
	\Route::get('telenok/module/objects-version', array('as' => 'cmf.module.objects-version', 'uses' => "App\Telenok\Core\Module\Objects\Version\Controller@getContent"));
	\Route::get('telenok/module/objects-version/list', array('as' => 'cmf.module.objects-version.list', 'uses' => "App\Telenok\Core\Module\Objects\Version\Controller@getList"));

	// Module Objects\Sequence
	\Route::get('telenok/module/objects-sequence/list', array('as' => 'cmf.module.objects-sequence.list', 'uses' => "App\Telenok\Core\Module\Objects\Sequence\Controller@getList"));

	// Module Web Domain
	\Route::get('telenok/module/web-domain/action-param', array('as' => 'cmf.module.web-domain.action.param', 'uses' => "App\Telenok\Core\Module\Web\Domain\Controller@getActionParam"));

	// Module Page Controller
	\Route::get('telenok/module/web-page-controller/action-param', array('as' => 'cmf.module.web-page-controller.action.param', 'uses' => "App\Telenok\Core\Module\Web\PageController\Controller@getActionParam"));

	// Module Files
	\Route::get('telenok/module/files/browser/action-param', array('as' => 'cmf.module.files-browser.action.param', 'uses' => "App\Telenok\Core\Module\Files\Browser\Controller@getActionParam"));
	\Route::get('telenok/module/files/browser', array('as' => 'cmf.module.files-browser', 'uses' => "App\Telenok\Core\Module\Files\Browser\Controller@getContent"));
	\Route::get('telenok/module/files/browser/list', array('as' => 'cmf.module.files-browser.list', 'uses' => "App\Telenok\Core\Module\Files\Browser\Controller@getList"));
	\Route::get('telenok/module/files/browser/create', array('as' => 'cmf.module.files-browser.create', 'uses' => "App\Telenok\Core\Module\Files\Browser\Controller@create"));
	\Route::get('telenok/module/files/browser/edit', array('as' => 'cmf.module.files-browser.edit', 'uses' => "App\Telenok\Core\Module\Files\Browser\Controller@edit"));
	\Route::post('telenok/module/files/browser/store', array('as' => 'cmf.module.files-browser.store', 'uses' => "App\Telenok\Core\Module\Files\Browser\Controller@store"));
	\Route::post('telenok/module/files/browser/update', array('as' => 'cmf.module.files-browser.update', 'uses' => "App\Telenok\Core\Module\Files\Browser\Controller@update"));
	\Route::post('telenok/module/files/browser/delete', array('as' => 'cmf.module.files-browser.delete', 'uses' => "App\Telenok\Core\Module\Files\Browser\Controller@delete"));
	\Route::get('telenok/module/files/browser/list/edit', array('as' => 'cmf.module.files-browser.list.edit', 'uses' => "App\Telenok\Core\Module\Files\Browser\Controller@editList"));
	\Route::get('telenok/module/files/browser/list/delete', array('as' => 'cmf.module.files-browser.list.delete', 'uses' => "App\Telenok\Core\Module\Files\Browser\Controller@deleteList"));

	// Module System\Setting
	\Route::get('telenok/module/system-setting/action-param', array('as' => 'cmf.module.system-setting.action.param', 'uses' => "App\Telenok\Core\Module\System\Setting\Controller@getActionParam"));
	\Route::post('telenok/module/system-setting/save', array('as' => 'cmf.module.system-setting.save', 'uses' => "App\Telenok\Core\Module\System\Setting\Controller@save"));

	// Module Web\PageConstructor
	\Route::get('telenok/module/web-page-constructor/action-param', array('as' => 'cmf.module.web-page-constructor.action.param', 'uses' => "App\Telenok\Core\Module\Web\PageConstructor\Controller@getActionParam"));
	\Route::get('telenok/module/web-page-constructor/list/page', array('as' => 'cmf.module.web-page-constructor.list.page', 'uses' => "App\Telenok\Core\Module\Web\PageConstructor\Controller@getListPage"));
	\Route::get('telenok/module/web-page-constructor/view/page/container/{id}/language-id/{languageId}', array('as' => 'cmf.module.web-page-constructor.view.page.container', 'uses' => "App\Telenok\Core\Module\Web\PageConstructor\Controller@viewPageContainer"));
	\Route::get('telenok/module/web-page-constructor/view/page/insert/language-id/{languageId}/page-id/{pageId}/widget-key/{key}/widget-id/{id}/container/{container}/bufferId/{bufferId}/order/{order}/', array('as' => 'cmf.module.web-page-constructor.view.page.insert.widget', 'uses' => "App\Telenok\Core\Module\Web\PageConstructor\Controller@insertWidget"));
	\Route::get('telenok/module/web-page-constructor/view/page/remove/widget-id/{id}/', array('as' => 'cmf.module.web-page-constructor.view.page.remove.widget', 'uses' => "App\Telenok\Core\Module\Web\PageConstructor\Controller@removeWidget"));
	\Route::post('telenok/module/web-page-constructor/view/page/widget/buffer/add/{id}/key/{key}', array('as' => 'cmf.module.web-page-constructor.view.buffer.add.widget', 'uses' => "App\Telenok\Core\Module\Web\PageConstructor\Controller@addBufferWidget"));
	\Route::post('telenok/module/web-page-constructor/view/page/widget/buffer/delete/{id}', array('as' => 'cmf.module.web-page-constructor.view.buffer.delete.widget', 'uses' => "App\Telenok\Core\Module\Web\PageConstructor\Controller@deleteBufferWidget"));

	\Route::get('telenok/login', array('as' => 'cmf.login.content', 'uses' => "\App\Telenok\Core\Controller\Auth\AuthController@getLogin"));
	\Route::post('telenok/process/login', array('as' => 'cmf.login.process', 'uses' => "\App\Telenok\Core\Controller\Auth\AuthController@postLogin"));
	\Route::get('telenok/logout', array('as' => 'cmf.logout', 'uses' => "\App\Telenok\Core\Controller\Backend\Controller@logout"));
	\Route::post('telenok/password/reset/email', array('as' => 'cmf.password.reset.email.process', 'uses' => "\App\Telenok\Core\Controller\Auth\PasswordController@postEmail"));
	\Route::get('telenok/password/reset/{token}', array('as' => 'cmf.password.reset.token', 'uses' => "\App\Telenok\Core\Controller\Auth\PasswordController@getReset"));
	\Route::post('telenok/password/reset/process', array('as' => 'cmf.password.reset.token.process', 'uses' => "\App\Telenok\Core\Controller\Auth\PasswordController@postReset"));

	// Module System\Setting
	\Route::get('telenok/module/php-console/action-param', array('as' => 'cmf.module.php-console.action.param', 'uses' => "App\Telenok\Core\Module\Tools\PhpConsole\Controller@getActionParam"));
	\Route::get('telenok/module/database-console/action-param', array('as' => 'cmf.module.database-console.action.param', 'uses' => "App\Telenok\Core\Module\Tools\DatabaseConsole\Controller@getActionParam"));
	
	// Module Packages\ComposerManager
	\Route::get('telenok/module/packages/composer-manager/action-param', array('as' => 'cmf.module.composer-manager.action.param', 'uses' => "App\Telenok\Core\Module\Packages\ComposerManager\Controller@getActionParam"));
	\Route::get('telenok/module/packages/composer-manager', array('as' => 'cmf.module.composer-manager', 'uses' => "App\Telenok\Core\Module\Packages\ComposerManager\Controller@getContent"));
	\Route::get('telenok/module/packages/composer-manager/list', array('as' => 'cmf.module.composer-manager.list', 'uses' => "App\Telenok\Core\Module\Packages\ComposerManager\Controller@getList"));
	\Route::get('telenok/module/packages/composer-manager/composer-json/edit', array('as' => 'cmf.module.composer-manager.composer-json.edit', 'uses' => "App\Telenok\Core\Module\Packages\ComposerManager\Controller@getComposerJsonContent"));
	\Route::post('telenok/module/packages/composer-manager/composer-json/update', array('as' => 'cmf.module.composer-manager.composer-json.update', 'uses' => "App\Telenok\Core\Module\Packages\ComposerManager\Controller@composerJsonUpdate"));

	// Module Packages\InstallerManager
	\Route::get('telenok/module/packages/installer-manager/action-param', array('as' => 'cmf.module.installer-manager.action.param', 'uses' => "App\Telenok\Core\Module\Packages\InstallerManager\Controller@getActionParam"));
	\Route::get('telenok/module/packages/installer-manager', array('as' => 'cmf.module.installer-manager', 'uses' => "App\Telenok\Core\Module\Packages\InstallerManager\Controller@getContent"));
	\Route::get('telenok/module/packages/installer-manager/list', array('as' => 'cmf.module.installer-manager.list', 'uses' => "App\Telenok\Core\Module\Packages\InstallerManager\Controller@getList"));
	\Route::any('telenok/module/packages/installer-manager/install', array('as' => 'cmf.module.installer-manager.install', 'uses' => "App\Telenok\Core\Module\Packages\InstallerManager\Controller@install"));
	\Route::post('telenok/module/packages/installer-manager/update', array('as' => 'cmf.module.installer-manager.update', 'uses' => "App\Telenok\Core\Module\Packages\InstallerManager\Controller@update"));
	\Route::post('telenok/module/packages/installer-manager/delete', array('as' => 'cmf.module.installer-manager.delete', 'uses' => "App\Telenok\Core\Module\Packages\InstallerManager\Controller@delete"));