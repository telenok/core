<?php

	\Route::get('telenok', array('as' => 'telenok.content', 'uses' => "\App\Telenok\Core\Controller\Backend\Controller@getContent"));
	\Route::get('telenok/error', array('as' => 'error.access-denied', 'uses' => "\App\Telenok\Core\Controller\Backend\Controller@errorAccessDenied"));
	\Route::get('telenok/validate/session', array('as' => 'telenok.validate.session', 'uses' => "\App\Telenok\Core\Controller\Backend\Controller@validateSession"));

	// Update user's UI setting
	\Route::post('telenok/user/update/ui-setting', array('as' => 'telenok.user.update.ui-setting', 'uses' => "\App\Telenok\Core\Controller\Backend\Controller@updateBackendUISetting"));



	\Route::post('widget/form/store/{typeId}', array('as' => 'telenok.widget.form.store', 'uses' => "Telenok\Core\Widget\Model\Form\Controller@store"));
	\Route::post('widget/form/update/{id}', array('as' => 'telenok.widget.form.update', 'uses' => "Telenok\Core\Widget\Model\Form\Controller@update"));
	\Route::post('widget/form/delete/{id}', array('as' => 'telenok.widget.form.delete', 'uses' => "Telenok\Core\Widget\Model\Form\Controller@delete"));
	
	\Route::get('widget/grid/{typeId}', array('as' => 'telenok.widget.grid.list', 'uses' => "Telenok\Core\Widget\Model\Grid\Controller@getList"));

	
	\Route::get('download/stream/{modelId}/{fieldId}', array('as' => 'telenok.download.stream.file', 'uses' => "\App\Telenok\Core\Field\Upload\Download@stream"));
	\Route::get('download/image/{modelId}/{fieldId}/{toDo}/{width}/{height}/{secureKey}', array('as' => 'telenok.download.image.file', 'uses' => "\App\Telenok\Core\Field\Upload\Download@image"));

	

	// Module Objects\Lists
	\Route::get('telenok/module/objects-lists/action-param', array('as' => 'telenok.module.objects-lists.action.param', 'uses' => "App\Telenok\Core\Module\Objects\Lists\Controller@getActionParam"));
	\Route::get('telenok/module/objects-lists', array('as' => 'telenok.module.objects-lists', 'uses' => "App\Telenok\Core\Module\Objects\Lists\Controller@getContent"));
	\Route::get('telenok/module/objects-lists/create/type', array('as' => 'telenok.module.objects-lists.create', 'uses' => "App\Telenok\Core\Module\Objects\Lists\Controller@create"));
	\Route::get('telenok/module/objects-lists/edit', array('as' => 'telenok.module.objects-lists.edit', 'uses' => "App\Telenok\Core\Module\Objects\Lists\Controller@edit"));
	\Route::post('telenok/module/objects-lists/store/type/{id}', array('as' => 'telenok.module.objects-lists.store', 'uses' => "App\Telenok\Core\Module\Objects\Lists\Controller@store"));
	\Route::post('telenok/module/objects-lists/update/type/{id}', array('as' => 'telenok.module.objects-lists.update', 'uses' => "App\Telenok\Core\Module\Objects\Lists\Controller@update"));
	\Route::post('telenok/module/objects-lists/delete/{id}', array('as' => 'telenok.module.objects-lists.delete', 'uses' => "App\Telenok\Core\Module\Objects\Lists\Controller@delete"));

    \Route::get('telenok/module/objects-lists/list', array('as' => 'telenok.module.objects-lists.list', 'uses' => "App\Telenok\Core\Module\Objects\Lists\Controller@getList"));
	\Route::get('telenok/module/objects-lists/list/json', array('as' => 'telenok.module.objects-lists.list.json', 'uses' => "App\Telenok\Core\Module\Objects\Lists\Controller@getListJson"));
	\Route::get('telenok/module/objects-lists/list/edit/', array('as' => 'telenok.module.objects-lists.list.edit', 'uses' => "App\Telenok\Core\Module\Objects\Lists\Controller@editList"));
	\Route::post('telenok/module/objects-lists/list/delete', array('as' => 'telenok.module.objects-lists.list.delete', 'uses' => "App\Telenok\Core\Module\Objects\Lists\Controller@deleteList"));
	\Route::post('telenok/module/objects-lists/list/lock', array('as' => 'telenok.module.objects-lists.list.lock', 'uses' => "App\Telenok\Core\Module\Objects\Lists\Controller@lockList"));
	\Route::post('telenok/module/objects-lists/lock', array('as' => 'telenok.module.objects-lists.lock', 'uses' => "App\Telenok\Core\Module\Objects\Lists\Controller@lock"));
	\Route::post('telenok/module/objects-lists/list/unlock', array('as' => 'telenok.module.objects-lists.list.unlock', 'uses' => "App\Telenok\Core\Module\Objects\Lists\Controller@unlockList"));
	\Route::get('telenok/module/objects-lists/list/tree', array('as' => 'telenok.module.objects-lists.list.tree', 'uses' => "App\Telenok\Core\Module\Objects\Lists\Controller@getTreeList"));
	
    \Route::get('telenok/module/objects-lists/wizard/create/type', array('as' => 'telenok.module.objects-lists.wizard.create', 'uses' => "App\Telenok\Core\Module\Objects\Lists\Wizard\Controller@create"));
	\Route::get('telenok/module/objects-lists/wizard/edit', array('as' => 'telenok.module.objects-lists.wizard.edit', 'uses' => "App\Telenok\Core\Module\Objects\Lists\Wizard\Controller@edit"));
	\Route::post('telenok/module/objects-lists/wizard/store/type/{id}', array('as' => 'telenok.module.objects-lists.wizard.store', 'uses' => "App\Telenok\Core\Module\Objects\Lists\Wizard\Controller@store"));
	\Route::post('telenok/module/objects-lists/wizard/update/type/{id}', array('as' => 'telenok.module.objects-lists.wizard.update', 'uses' => "App\Telenok\Core\Module\Objects\Lists\Wizard\Controller@update"));
	\Route::post('telenok/module/objects-lists/wizard/delete/{id}', array('as' => 'telenok.module.objects-lists.wizard.delete', 'uses' => "App\Telenok\Core\Module\Objects\Lists\Wizard\Controller@delete"));
	\Route::get('telenok/module/objects-lists/wizard/choose', array('as' => 'telenok.module.objects-lists.wizard.choose', 'uses' => "App\Telenok\Core\Module\Objects\Lists\Wizard\Controller@choose"));
	\Route::get('telenok/module/objects-lists/wizard/list', array('as' => 'telenok.module.objects-lists.wizard.list', 'uses' => "App\Telenok\Core\Module\Objects\Lists\Wizard\Controller@getWizardList"));


	// Fields
	\Route::get('field/relation-one-to-one/list/title/type/{id}', array('as' => 'telenok.field.relation-one-to-one.list.title', 'uses' => "\App\Telenok\Core\Field\RelationOneToOne\Controller@getTitleList"));
	\Route::get('field/relation-one-to-one/list/table/model/{id}/field/{fieldId}/uniqueId/{uniqueId}', array('as' => 'telenok.field.relation-one-to-one.list.table', 'uses' => "\App\Telenok\Core\Field\RelationOneToOne\Controller@getTableList"));

	\Route::get('field/relation-one-to-many/list/title/type/{id}', array('as' => 'telenok.field.relation-one-to-many.list.title', 'uses' => "\App\Telenok\Core\Field\RelationOneToMany\Controller@getTitleList"));
	\Route::get('field/relation-one-to-many/list/table/model/{id}/field/{fieldId}/uniqueId/{uniqueId}', array('as' => 'telenok.field.relation-one-to-many.list.table', 'uses' => "\App\Telenok\Core\Field\RelationOneToMany\Controller@getTableList"));

	\Route::get('field/relation-many-to-many/list/title/type/{id}', array('as' => 'telenok.field.relation-many-to-many.list.title', 'uses' => "\App\Telenok\Core\Field\RelationManyToMany\Controller@getTitleList"));
	\Route::get('field/relation-many-to-many/list/table/model/{id}/field/{fieldId}/uniqueId/{uniqueId}', array('as' => 'telenok.field.relation-many-to-many.list.table', 'uses' => "\App\Telenok\Core\Field\RelationManyToMany\Controller@getTableList"));

	\Route::get('field/tree/list/title/type/{id}', array('as' => 'telenok.field.tree.list.title', 'uses' => "\App\Telenok\Core\Field\System\Tree\Controller@getTitleList"));
	\Route::get('field/tree/list/table/model/{id}/field/{fieldId}/uniqueId/{uniqueId}', array('as' => 'telenok.field.tree.list.table', 'uses' => "\App\Telenok\Core\Field\System\Tree\Controller@getTableList"));

	\Route::get('field/morph-many-to-many/list/title/type/{id}', array('as' => 'telenok.field.morph-many-to-many.list.title', 'uses' => "\App\Telenok\Core\Field\MorphManyToMany\Controller@getTitleList"));
	\Route::get('field/morph-many-to-many/list/table/model/{id}/field/{fieldId}/uniqueId/{uniqueId}', array('as' => 'telenok.field.morph-many-to-many.list.table', 'uses' => "\App\Telenok\Core\Field\MorphManyToMany\Controller@getTableList"));

	\Route::get('field/morph-one-to-many/list/title/type/{id}', array('as' => 'telenok.field.morph-one-to-many.list.title', 'uses' => "\App\Telenok\Core\Field\MorphOneToMany\Controller@getTitleList"));
	\Route::get('field/morph-one-to-many/list/table/model/{id}/field/{fieldId}/uniqueId/{uniqueId}', array('as' => 'telenok.field.morph-one-to-many.list.table', 'uses' => "\App\Telenok\Core\Field\MorphOneToMany\Controller@getTableList"));

	\Route::get('field/morph-one-to-one/list/title/type/{id}', array('as' => 'telenok.field.morph-one-to-one.list.title', 'uses' => "\App\Telenok\Core\Field\MorphOneToOne\Controller@getTitleList"));

	\Route::get('field/permission/list/title', array('as' => 'telenok.field.permission.list.title', 'uses' => "\App\Telenok\Core\Field\System\Permission\Controller@getTitleList"));

	\Route::post('field/file-many-to-many/upload', array('as' => 'telenok.field.file-many-to-many.upload', 'uses' => "\App\Telenok\Core\Field\FileManyToMany\Controller@upload"));
 

	// Module Dashboard 
	\Route::get('telenok/module/dashboard', array('as' => 'telenok.module.dashboard', 'uses' => "App\Telenok\Core\Module\Dashboard\Controller@getContent"));

	// Module Profile
	\Route::get('telenok/module/users-profile-edit/action-param', array('as' => 'telenok.module.users-profile-edit.action.param', 'uses' => "App\Telenok\Core\Module\Users\ProfileEdit\Controller@getActionParam"));
	\Route::get('telenok/module/users-profile-edit', array('as' => 'telenok.module.users-profile-edit', 'uses' => "App\Telenok\Core\Module\Users\ProfileEdit\Controller@getContent"));
	\Route::post('telenok/module/users-profile-edit/update', array('as' => 'telenok.module.users-profile-edit.update', 'uses' => "App\Telenok\Core\Module\Users\ProfileEdit\Controller@update"));

	// Module Objects\Type
	\Route::get('telenok/module/objects-type/action-param', array('as' => 'telenok.module.objects-type.action.param', 'uses' => "App\Telenok\Core\Module\Objects\Type\Controller@getActionParam"));

	// Module Objects\Field
	\Route::get('telenok/module/objects-field/field-form/{fieldKey}/{modelId}/{uniqueId}', array('as' => 'telenok.module.objects-field.field.form', 'uses' => "App\Telenok\Core\Module\Objects\Field\Controller@getFormFieldContent"));

		
	// Module Objects\Sequence
	\Route::get('telenok/module/objects-sequence/action-param', array('as' => 'telenok.module.objects-sequence.action.param', 'uses' => "App\Telenok\Core\Module\Objects\Sequence\Controller@getActionParam"));
	\Route::get('telenok/module/objects-sequence', array('as' => 'telenok.module.objects-sequence', 'uses' => "App\Telenok\Core\Module\Objects\Sequence\Controller@getContent"));
	\Route::get('telenok/module/objects-sequence/list', array('as' => 'telenok.module.objects-sequence.list', 'uses' => "App\Telenok\Core\Module\Objects\Sequence\Controller@getList"));

	// Module Objects\Version
	\Route::get('telenok/module/objects-version/action-param', array('as' => 'telenok.module.objects-version.action.param', 'uses' => "App\Telenok\Core\Module\Objects\Version\Controller@getActionParam"));
	\Route::get('telenok/module/objects-version', array('as' => 'telenok.module.objects-version', 'uses' => "App\Telenok\Core\Module\Objects\Version\Controller@getContent"));
	\Route::get('telenok/module/objects-version/list', array('as' => 'telenok.module.objects-version.list', 'uses' => "App\Telenok\Core\Module\Objects\Version\Controller@getList"));

	// Module Objects\Sequence
	\Route::get('telenok/module/objects-sequence/list', array('as' => 'telenok.module.objects-sequence.list', 'uses' => "App\Telenok\Core\Module\Objects\Sequence\Controller@getList"));

	// Module Web Domain
	\Route::get('telenok/module/web-domain/action-param', array('as' => 'telenok.module.web-domain.action.param', 'uses' => "App\Telenok\Core\Module\Web\Domain\Controller@getActionParam"));

	// Module Page Controller
	\Route::get('telenok/module/web-page-controller/action-param', array('as' => 'telenok.module.web-page-controller.action.param', 'uses' => "App\Telenok\Core\Module\Web\PageController\Controller@getActionParam"));

	// Module Files
	\Route::get('telenok/module/files/browser/action-param', array('as' => 'telenok.module.files-browser.action.param', 'uses' => "App\Telenok\Core\Module\Files\Browser\Controller@getActionParam"));
	\Route::get('telenok/module/files/browser', array('as' => 'telenok.module.files-browser', 'uses' => "App\Telenok\Core\Module\Files\Browser\Controller@getContent"));
	\Route::get('telenok/module/files/browser/list', array('as' => 'telenok.module.files-browser.list', 'uses' => "App\Telenok\Core\Module\Files\Browser\Controller@getList"));
	\Route::get('telenok/module/files/browser/create', array('as' => 'telenok.module.files-browser.create', 'uses' => "App\Telenok\Core\Module\Files\Browser\Controller@create"));
	\Route::get('telenok/module/files/browser/edit', array('as' => 'telenok.module.files-browser.edit', 'uses' => "App\Telenok\Core\Module\Files\Browser\Controller@edit"));
	\Route::post('telenok/module/files/browser/store', array('as' => 'telenok.module.files-browser.store', 'uses' => "App\Telenok\Core\Module\Files\Browser\Controller@store"));
	\Route::post('telenok/module/files/browser/update', array('as' => 'telenok.module.files-browser.update', 'uses' => "App\Telenok\Core\Module\Files\Browser\Controller@update"));
	\Route::post('telenok/module/files/browser/delete', array('as' => 'telenok.module.files-browser.delete', 'uses' => "App\Telenok\Core\Module\Files\Browser\Controller@delete"));
	\Route::get('telenok/module/files/browser/list/edit', array('as' => 'telenok.module.files-browser.list.edit', 'uses' => "App\Telenok\Core\Module\Files\Browser\Controller@editList"));
	\Route::get('telenok/module/files/browser/list/delete', array('as' => 'telenok.module.files-browser.list.delete', 'uses' => "App\Telenok\Core\Module\Files\Browser\Controller@deleteList"));

	// Module System\Setting
	\Route::get('telenok/module/system-setting/action-param', array('as' => 'telenok.module.system-setting.action.param', 'uses' => "App\Telenok\Core\Module\System\Setting\Controller@getActionParam"));
	\Route::post('telenok/module/system-setting/save', array('as' => 'telenok.module.system-setting.save', 'uses' => "App\Telenok\Core\Module\System\Setting\Controller@save"));

	// Module Web\PageConstructor
	\Route::get('telenok/module/web-page-constructor/action-param', array('as' => 'telenok.module.web-page-constructor.action.param', 'uses' => "App\Telenok\Core\Module\Web\PageConstructor\Controller@getActionParam"));
	\Route::get('telenok/module/web-page-constructor/list/page', array('as' => 'telenok.module.web-page-constructor.list.page', 'uses' => "App\Telenok\Core\Module\Web\PageConstructor\Controller@getListPage"));
	\Route::get('telenok/module/web-page-constructor/view/page/container/{id}/language-id/{languageId}', array('as' => 'telenok.module.web-page-constructor.view.page.container', 'uses' => "App\Telenok\Core\Module\Web\PageConstructor\Controller@viewPageContainer"));
	\Route::get('telenok/module/web-page-constructor/view/page/insert/language-id/{languageId}/page-id/{pageId}/widget-key/{key}/widget-id/{id}/container/{container}/bufferId/{bufferId}/order/{order}/', array('as' => 'telenok.module.web-page-constructor.view.page.insert.widget', 'uses' => "App\Telenok\Core\Module\Web\PageConstructor\Controller@insertWidget"));
	\Route::get('telenok/module/web-page-constructor/view/page/remove/widget-id/{id}/', array('as' => 'telenok.module.web-page-constructor.view.page.remove.widget', 'uses' => "App\Telenok\Core\Module\Web\PageConstructor\Controller@removeWidget"));
	\Route::post('telenok/module/web-page-constructor/view/page/widget/buffer/add/{id}/key/{key}', array('as' => 'telenok.module.web-page-constructor.view.buffer.add.widget', 'uses' => "App\Telenok\Core\Module\Web\PageConstructor\Controller@addBufferWidget"));
	\Route::post('telenok/module/web-page-constructor/view/page/widget/buffer/delete/{id}', array('as' => 'telenok.module.web-page-constructor.view.buffer.delete.widget', 'uses' => "App\Telenok\Core\Module\Web\PageConstructor\Controller@deleteBufferWidget"));

	\Route::get('telenok/login', array('as' => 'telenok.login.control-panel', 'uses' => "\App\Telenok\Core\Controller\Auth\AuthController@getLogin"));
	\Route::post('telenok/process/login', array('as' => 'telenok.login.process', 'uses' => "\App\Telenok\Core\Controller\Auth\AuthController@postLogin"));
	\Route::get('telenok/logout', array('as' => 'telenok.logout', 'uses' => "\App\Telenok\Core\Controller\Auth\AuthController@logout"));
	\Route::post('telenok/password/reset/email', array('as' => 'telenok.password.reset.email.process', 'uses' => "\App\Telenok\Core\Controller\Auth\PasswordController@postEmail"));
	\Route::get('telenok/password/reset/{token}', array('as' => 'telenok.password.reset.token', 'uses' => "\App\Telenok\Core\Controller\Auth\PasswordController@getReset"));
	\Route::post('telenok/password/reset/process', array('as' => 'telenok.password.reset.token.process', 'uses' => "\App\Telenok\Core\Controller\Auth\PasswordController@postReset"));

	// Module Setting\Tools
	\Route::get('telenok/module/php-console/action-param', array('as' => 'telenok.module.php-console.action.param', 'uses' => "App\Telenok\Core\Module\Tools\PhpConsole\Controller@getActionParam"));
	\Route::post('telenok/module/php-console/process-code', array('as' => 'telenok.module.php-console.process-code', 'uses' => "App\Telenok\Core\Module\Tools\PhpConsole\Controller@processCode"));
	
    \Route::get('telenok/module/database-console/action-param', array('as' => 'telenok.module.database-console.action.param', 'uses' => "App\Telenok\Core\Module\Tools\DatabaseConsole\Controller@getActionParam"));
	\Route::post('telenok/module/database-console/process-select', array('as' => 'telenok.module.database-console.process-select', 'uses' => "App\Telenok\Core\Module\Tools\DatabaseConsole\Controller@processSelect"));
	\Route::post('telenok/module/database-console/process-statement', array('as' => 'telenok.module.database-console.process-statement', 'uses' => "App\Telenok\Core\Module\Tools\DatabaseConsole\Controller@processStatement"));

    // Module Packages\ComposerManager
	\Route::get('telenok/module/packages/composer-manager/action-param', array('as' => 'telenok.module.composer-manager.action.param', 'uses' => "App\Telenok\Core\Module\Packages\ComposerManager\Controller@getActionParam"));
	\Route::get('telenok/module/packages/composer-manager', array('as' => 'telenok.module.composer-manager', 'uses' => "App\Telenok\Core\Module\Packages\ComposerManager\Controller@getContent"));
	\Route::get('telenok/module/packages/composer-manager/list', array('as' => 'telenok.module.composer-manager.list', 'uses' => "App\Telenok\Core\Module\Packages\ComposerManager\Controller@getList"));

	\Route::get('telenok/module/packages/composer-manager/edit', array('as' => 'telenok.module.composer-manager.edit', 'uses' => "App\Telenok\Core\Module\Packages\ComposerManager\Controller@edit"));
	\Route::get('telenok/module/packages/composer-manager/update', array('as' => 'telenok.module.composer-manager.update', 'uses' => "App\Telenok\Core\Module\Packages\ComposerManager\Controller@update"));
	\Route::get('telenok/module/packages/composer-manager/delete', array('as' => 'telenok.module.composer-manager.delete', 'uses' => "App\Telenok\Core\Module\Packages\ComposerManager\Controller@delete"));

    \Route::get('telenok/module/packages/composer-manager/composer-json/edit', array('as' => 'telenok.module.composer-manager.composer-json.edit', 'uses' => "App\Telenok\Core\Module\Packages\ComposerManager\Controller@getComposerJsonContent"));
	\Route::post('telenok/module/packages/composer-manager/composer-json/update', array('as' => 'telenok.module.composer-manager.composer-json.update', 'uses' => "App\Telenok\Core\Module\Packages\ComposerManager\Controller@composerJsonUpdate"));

	// Module Packages\InstallerManager
	\Route::get('telenok/module/packages/installer-manager/action-param', array('as' => 'telenok.module.installer-manager.action.param', 'uses' => "App\Telenok\Core\Module\Packages\InstallerManager\Controller@getActionParam"));
	\Route::get('telenok/module/packages/installer-manager', array('as' => 'telenok.module.installer-manager', 'uses' => "App\Telenok\Core\Module\Packages\InstallerManager\Controller@getContent"));
	\Route::get('telenok/module/packages/installer-manager/list', array('as' => 'telenok.module.installer-manager.list', 'uses' => "App\Telenok\Core\Module\Packages\InstallerManager\Controller@getList"));
	\Route::get('telenok/module/packages/installer-manager/view/{id}', array('as' => 'telenok.module.installer-manager.view', 'uses' => "App\Telenok\Core\Module\Packages\InstallerManager\Controller@view"));

	\Route::any('telenok/module/packages/installer-manager/install-package/{packageId}/{versionId}', array('as' => 'telenok.module.installer-manager.install-package', 'uses' => "App\Telenok\Core\Module\Packages\InstallerManager\Controller@installPackage"));	
	\Route::get('telenok/module/packages/installer-manager/install-package/{packageId}/{versionId}/status', array('as' => 'telenok.module.installer-manager.install-package.status', 'uses' => "App\Telenok\Core\Module\Packages\InstallerManager\Controller@installPackageStatus"));	
	
	\Route::post('telenok/module/packages/installer-manager/update', array('as' => 'telenok.module.installer-manager.update', 'uses' => "App\Telenok\Core\Module\Packages\InstallerManager\Controller@update"));
	\Route::post('telenok/module/packages/installer-manager/delete', array('as' => 'telenok.module.installer-manager.delete', 'uses' => "App\Telenok\Core\Module\Packages\InstallerManager\Controller@delete"));
