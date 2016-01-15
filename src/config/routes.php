<?php

get('browser/file', ['as' => 'browse.file', 'uses' => "\App\Telenok\Core\Controller\Frontend\Controller@validateSession"]);

get('validate/session', array('as' => 'validate.session', 'uses' => "\App\Telenok\Core\Controller\Frontend\Controller@validateSession"));

get('telenok', array('as' => 'telenok.content', 'uses' => "\App\Telenok\Core\Controller\Backend\Controller@getContent"));
get('telenok/error', array('as' => 'error.access-denied', 'uses' => "\App\Telenok\Core\Controller\Backend\Controller@errorAccessDenied"));
get('telenok/validate/session', array('as' => 'telenok.validate.session', 'uses' => "\App\Telenok\Core\Controller\Backend\Controller@validateSession"));

// Update user's UI setting
post('telenok/user/update/ui-setting', array('as' => 'telenok.user.update.ui-setting', 'uses' => "\App\Telenok\Core\Controller\Backend\Controller@updateBackendUISetting"));


// Widget Form
post('widget/form/store/{typeId}', array('as' => 'telenok.widget.form.store', 'uses' => "\App\Telenok\Core\Widget\Model\Form\Controller@store"));
post('widget/form/update/{id}', array('as' => 'telenok.widget.form.update', 'uses' => "\App\Telenok\Core\Widget\Model\Form\Controller@update"));
post('widget/form/delete/{id}', array('as' => 'telenok.widget.form.delete', 'uses' => "\App\Telenok\Core\Widget\Model\Form\Controller@delete"));

// Widget Grid
get('widget/grid/{typeId}', array('as' => 'telenok.widget.grid.list', 'uses' => "\App\Telenok\Core\Widget\Model\Grid\Controller@getList"));

// Widget Menu
get('widget/menu/tree', array('as' => 'telenok.widget.menu.tree.list', 'uses' => "\App\Telenok\Core\Widget\Menu\Controller@getTreeList"));


// Object Field Upload
get('download/stream/{modelId}/{fieldId}', array('as' => 'telenok.download.stream.file', 'uses' => "\App\Telenok\Core\Field\Upload\Download@stream"));
get('download/image/{modelId}/{fieldId}/{width}/{height}/{action}', array('as' => 'telenok.download.image.file', 'uses' => "\App\Telenok\Core\Field\Upload\Download@image"));


// Module Objects\Lists
get('telenok/module/objects-lists/action-param', array('as' => 'telenok.module.objects-lists.action.param', 'uses' => "App\Telenok\Core\Module\Objects\Lists\Controller@getActionParam"));
get('telenok/module/objects-lists', array('as' => 'telenok.module.objects-lists', 'uses' => "App\Telenok\Core\Module\Objects\Lists\Controller@getContent"));
get('telenok/module/objects-lists/create/type', array('as' => 'telenok.module.objects-lists.create', 'uses' => "App\Telenok\Core\Module\Objects\Lists\Controller@create"));
get('telenok/module/objects-lists/edit', array('as' => 'telenok.module.objects-lists.edit', 'uses' => "App\Telenok\Core\Module\Objects\Lists\Controller@edit"));
post('telenok/module/objects-lists/store/type/{id}', array('as' => 'telenok.module.objects-lists.store', 'uses' => "App\Telenok\Core\Module\Objects\Lists\Controller@store"));
post('telenok/module/objects-lists/update/type/{id}', array('as' => 'telenok.module.objects-lists.update', 'uses' => "App\Telenok\Core\Module\Objects\Lists\Controller@update"));
post('telenok/module/objects-lists/delete/{id}', array('as' => 'telenok.module.objects-lists.delete', 'uses' => "App\Telenok\Core\Module\Objects\Lists\Controller@delete"));

get('telenok/module/objects-lists/list', array('as' => 'telenok.module.objects-lists.list', 'uses' => "App\Telenok\Core\Module\Objects\Lists\Controller@getList"));
get('telenok/module/objects-lists/list/json', array('as' => 'telenok.module.objects-lists.list.json', 'uses' => "App\Telenok\Core\Module\Objects\Lists\Controller@getListJson"));
get('telenok/module/objects-lists/list/edit/', array('as' => 'telenok.module.objects-lists.list.edit', 'uses' => "App\Telenok\Core\Module\Objects\Lists\Controller@editList"));
post('telenok/module/objects-lists/list/delete', array('as' => 'telenok.module.objects-lists.list.delete', 'uses' => "App\Telenok\Core\Module\Objects\Lists\Controller@deleteList"));
post('telenok/module/objects-lists/list/lock', array('as' => 'telenok.module.objects-lists.list.lock', 'uses' => "App\Telenok\Core\Module\Objects\Lists\Controller@lockList"));
post('telenok/module/objects-lists/lock', array('as' => 'telenok.module.objects-lists.lock', 'uses' => "App\Telenok\Core\Module\Objects\Lists\Controller@lock"));
post('telenok/module/objects-lists/list/unlock', array('as' => 'telenok.module.objects-lists.list.unlock', 'uses' => "App\Telenok\Core\Module\Objects\Lists\Controller@unlockList"));
get('telenok/module/objects-lists/list/tree', array('as' => 'telenok.module.objects-lists.list.tree', 'uses' => "App\Telenok\Core\Module\Objects\Lists\Controller@getTreeList"));

get('telenok/module/objects-lists/wizard/create/type', array('as' => 'telenok.module.objects-lists.wizard.create', 'uses' => "App\Telenok\Core\Module\Objects\Lists\Wizard\Controller@create"));
get('telenok/module/objects-lists/wizard/edit', array('as' => 'telenok.module.objects-lists.wizard.edit', 'uses' => "App\Telenok\Core\Module\Objects\Lists\Wizard\Controller@edit"));
post('telenok/module/objects-lists/wizard/store/type/{id}', array('as' => 'telenok.module.objects-lists.wizard.store', 'uses' => "App\Telenok\Core\Module\Objects\Lists\Wizard\Controller@store"));
post('telenok/module/objects-lists/wizard/update/type/{id}', array('as' => 'telenok.module.objects-lists.wizard.update', 'uses' => "App\Telenok\Core\Module\Objects\Lists\Wizard\Controller@update"));
post('telenok/module/objects-lists/wizard/delete/{id}', array('as' => 'telenok.module.objects-lists.wizard.delete', 'uses' => "App\Telenok\Core\Module\Objects\Lists\Wizard\Controller@delete"));
get('telenok/module/objects-lists/wizard/choose', array('as' => 'telenok.module.objects-lists.wizard.choose', 'uses' => "App\Telenok\Core\Module\Objects\Lists\Wizard\Controller@choose"));
get('telenok/module/objects-lists/wizard/list', array('as' => 'telenok.module.objects-lists.wizard.list', 'uses' => "App\Telenok\Core\Module\Objects\Lists\Wizard\Controller@getList"));


// Fields
get('field/relation-one-to-one/list/title/type/{id}', array('as' => 'telenok.field.relation-one-to-one.list.title', 'uses' => "\App\Telenok\Core\Field\RelationOneToOne\Controller@getTitleList"));
get('field/relation-one-to-one/list/table/model/{id}/field/{fieldId}/uniqueId/{uniqueId}', array('as' => 'telenok.field.relation-one-to-one.list.table', 'uses' => "\App\Telenok\Core\Field\RelationOneToOne\Controller@getTableList"));

get('field/relation-one-to-many/list/title/type/{id}', array('as' => 'telenok.field.relation-one-to-many.list.title', 'uses' => "\App\Telenok\Core\Field\RelationOneToMany\Controller@getTitleList"));
get('field/relation-one-to-many/list/table/model/{id}/field/{fieldId}/uniqueId/{uniqueId}', array('as' => 'telenok.field.relation-one-to-many.list.table', 'uses' => "\App\Telenok\Core\Field\RelationOneToMany\Controller@getTableList"));

get('field/relation-many-to-many/list/title/type/{id}', array('as' => 'telenok.field.relation-many-to-many.list.title', 'uses' => "\App\Telenok\Core\Field\RelationManyToMany\Controller@getTitleList"));
get('field/relation-many-to-many/list/table/model/{id}/field/{fieldId}/uniqueId/{uniqueId}', array('as' => 'telenok.field.relation-many-to-many.list.table', 'uses' => "\App\Telenok\Core\Field\RelationManyToMany\Controller@getTableList"));

get('field/tree/list/title/type/{id}', array('as' => 'telenok.field.tree.list.title', 'uses' => "\App\Telenok\Core\Field\System\Tree\Controller@getTitleList"));
get('field/tree/list/table/model/{id}/field/{fieldId}/uniqueId/{uniqueId}', array('as' => 'telenok.field.tree.list.table', 'uses' => "\App\Telenok\Core\Field\System\Tree\Controller@getTableList"));

get('field/morph-many-to-many/list/title/type/{id}', array('as' => 'telenok.field.morph-many-to-many.list.title', 'uses' => "\App\Telenok\Core\Field\MorphManyToMany\Controller@getTitleList"));
get('field/morph-many-to-many/list/table/model/{id}/field/{fieldId}/uniqueId/{uniqueId}', array('as' => 'telenok.field.morph-many-to-many.list.table', 'uses' => "\App\Telenok\Core\Field\MorphManyToMany\Controller@getTableList"));

get('field/morph-one-to-many/list/title/type/{id}', array('as' => 'telenok.field.morph-one-to-many.list.title', 'uses' => "\App\Telenok\Core\Field\MorphOneToMany\Controller@getTitleList"));
get('field/morph-one-to-many/list/table/model/{id}/field/{fieldId}/uniqueId/{uniqueId}', array('as' => 'telenok.field.morph-one-to-many.list.table', 'uses' => "\App\Telenok\Core\Field\MorphOneToMany\Controller@getTableList"));

get('field/morph-one-to-one/list/title/type/{id}', array('as' => 'telenok.field.morph-one-to-one.list.title', 'uses' => "\App\Telenok\Core\Field\MorphOneToOne\Controller@getTitleList"));

get('field/permission/list/title', array('as' => 'telenok.field.permission.list.title', 'uses' => "\App\Telenok\Core\Field\System\Permission\Controller@getTitleList"));

post('field/file-many-to-many/upload', array('as' => 'telenok.field.file-many-to-many.upload', 'uses' => "\App\Telenok\Core\Field\FileManyToMany\Controller@upload"));
get('field/file-many-to-many/list/title', array('as' => 'telenok.field.file-many-to-many.list.title', 'uses' => "\App\Telenok\Core\Field\FileManyToMany\Controller@getTitleList"));

get('field/upload/modal-cropper', array('as' => 'telenok.field.upload.modal-cropper', 'uses' => "\App\Telenok\Core\Field\Upload\Controller@modalCropperContent"));

get('telenok/ckeditor.custom.config.js', array('as' => 'telenok.ckeditor.config', 'uses' => "App\Telenok\Core\Support\Config\CKEditor@getCKEditorConfig"));
get('telenok/ckeditor/browser/file', array('as' => 'telenok.ckeditor.file', 'uses' => "App\Telenok\Core\Support\Config\CKEditor@browseFile"));
get('telenok/ckeditor/browser/image', array('as' => 'telenok.ckeditor.image', 'uses' => "App\Telenok\Core\Support\Config\CKEditor@browseImage"));
get('telenok/ckeditor/browser/file/list', array('as' => 'telenok.ckeditor.storage.list', 'uses' => "App\Telenok\Core\Support\Config\CKEditor@storageFileList"));
get('telenok/ckeditor/browser/model/list', array('as' => 'telenok.ckeditor.model.list', 'uses' => "App\Telenok\Core\Support\Config\CKEditor@modelFileList"));
get('telenok/packages/telenok/core/js/ckeditor_addons/plugins/widget_inline/plugin.js', array('as' => 'telenok.ckeditor.plugin.inline-widget.config', 'uses' => "App\Telenok\Core\Support\Config\CKEditor@getCKEditorPluginWidgetInline"));
get('telenok/ckeditor/modal-cropper', array('as' => 'telenok.ckeditor.modal-cropper', 'uses' => "App\Telenok\Core\Support\Config\CKEditor@modalCropperContent"));
post('telenok/ckeditor/image/create', array('as' => 'telenok.ckeditor.image.create', 'uses' => "App\Telenok\Core\Support\Config\CKEditor@imageCreate"));
post('telenok/ckeditor/directory/create', array('as' => 'telenok.ckeditor.directory.create', 'uses' => "App\Telenok\Core\Support\Config\CKEditor@directoryCreate"));
post('telenok/ckeditor/file/upload', array('as' => 'telenok.ckeditor.file.upload', 'uses' => "App\Telenok\Core\Support\Config\CKEditor@uploadFile"));


// Module Dashboard 
get('telenok/module/dashboard', array('as' => 'telenok.module.dashboard', 'uses' => "App\Telenok\Core\Module\Dashboard\Controller@getContent"));

// Module Profile
get('telenok/module/users-profile-edit/action-param', array('as' => 'telenok.module.users-profile-edit.action.param', 'uses' => "App\Telenok\Core\Module\Users\ProfileEdit\Controller@getActionParam"));
get('telenok/module/users-profile-edit', array('as' => 'telenok.module.users-profile-edit', 'uses' => "App\Telenok\Core\Module\Users\ProfileEdit\Controller@getContent"));
post('telenok/module/users-profile-edit/update', array('as' => 'telenok.module.users-profile-edit.update', 'uses' => "App\Telenok\Core\Module\Users\ProfileEdit\Controller@update"));

// Module Objects\Type
get('telenok/module/objects-type/action-param', array('as' => 'telenok.module.objects-type.action.param', 'uses' => "App\Telenok\Core\Module\Objects\Type\Controller@getActionParam"));

// Module Objects\Field
get('telenok/module/objects-field/field-form/{fieldKey}/{modelId}/{uniqueId}', array('as' => 'telenok.module.objects-field.field.form', 'uses' => "App\Telenok\Core\Module\Objects\Field\Controller@getFormFieldContent"));


// Module Objects\Sequence
get('telenok/module/objects-sequence/action-param', array('as' => 'telenok.module.objects-sequence.action.param', 'uses' => "App\Telenok\Core\Module\Objects\Sequence\Controller@getActionParam"));
get('telenok/module/objects-sequence', array('as' => 'telenok.module.objects-sequence', 'uses' => "App\Telenok\Core\Module\Objects\Sequence\Controller@getContent"));
get('telenok/module/objects-sequence/list', array('as' => 'telenok.module.objects-sequence.list', 'uses' => "App\Telenok\Core\Module\Objects\Sequence\Controller@getList"));

// Module Objects\Version
get('telenok/module/objects-version/action-param', array('as' => 'telenok.module.objects-version.action.param', 'uses' => "App\Telenok\Core\Module\Objects\Version\Controller@getActionParam"));
get('telenok/module/objects-version', array('as' => 'telenok.module.objects-version', 'uses' => "App\Telenok\Core\Module\Objects\Version\Controller@getContent"));
get('telenok/module/objects-version/list', array('as' => 'telenok.module.objects-version.list', 'uses' => "App\Telenok\Core\Module\Objects\Version\Controller@getList"));

// Module Objects\Sequence
get('telenok/module/objects-sequence/list', array('as' => 'telenok.module.objects-sequence.list', 'uses' => "App\Telenok\Core\Module\Objects\Sequence\Controller@getList"));

// Module Web Domain
get('telenok/module/web-domain/action-param', array('as' => 'telenok.module.web-domain.action.param', 'uses' => "App\Telenok\Core\Module\Web\Domain\Controller@getActionParam"));

// Module Page Controller
get('telenok/module/web-page-controller/action-param', array('as' => 'telenok.module.web-page-controller.action.param', 'uses' => "App\Telenok\Core\Module\Web\PageController\Controller@getActionParam"));

// Module Files
get('telenok/module/files/browser/action-param', array('as' => 'telenok.module.files-browser.action.param', 'uses' => "App\Telenok\Core\Module\Files\Browser\Controller@getActionParam"));
get('telenok/module/files/browser', array('as' => 'telenok.module.files-browser', 'uses' => "App\Telenok\Core\Module\Files\Browser\Controller@getContent"));
get('telenok/module/files/browser/list', array('as' => 'telenok.module.files-browser.list', 'uses' => "App\Telenok\Core\Module\Files\Browser\Controller@getList"));
get('telenok/module/files/browser/create', array('as' => 'telenok.module.files-browser.create', 'uses' => "App\Telenok\Core\Module\Files\Browser\Controller@create"));
get('telenok/module/files/browser/edit', array('as' => 'telenok.module.files-browser.edit', 'uses' => "App\Telenok\Core\Module\Files\Browser\Controller@edit"));
post('telenok/module/files/browser/store', array('as' => 'telenok.module.files-browser.store', 'uses' => "App\Telenok\Core\Module\Files\Browser\Controller@store"));
post('telenok/module/files/browser/update', array('as' => 'telenok.module.files-browser.update', 'uses' => "App\Telenok\Core\Module\Files\Browser\Controller@update"));
post('telenok/module/files/browser/delete', array('as' => 'telenok.module.files-browser.delete', 'uses' => "App\Telenok\Core\Module\Files\Browser\Controller@delete"));
get('telenok/module/files/browser/list/edit', array('as' => 'telenok.module.files-browser.list.edit', 'uses' => "App\Telenok\Core\Module\Files\Browser\Controller@editList"));
get('telenok/module/files/browser/list/delete', array('as' => 'telenok.module.files-browser.list.delete', 'uses' => "App\Telenok\Core\Module\Files\Browser\Controller@deleteList"));
post('telenok/module/files/browser/upload', array('as' => 'telenok.module.files-browser.upload', 'uses' => "App\Telenok\Core\Module\Files\Browser\Controller@uploadFile"));

// Module System\Setting
get('telenok/module/system-setting/action-param', array('as' => 'telenok.module.system-setting.action.param', 'uses' => "App\Telenok\Core\Module\System\Setting\Controller@getActionParam"));
post('telenok/module/system-setting/save', array('as' => 'telenok.module.system-setting.save', 'uses' => "App\Telenok\Core\Module\System\Setting\Controller@save"));

// Module Web\PageConstructor
get('telenok/module/web-page-constructor/action-param', array('as' => 'telenok.module.web-page-constructor.action.param', 'uses' => "App\Telenok\Core\Module\Web\PageConstructor\Controller@getActionParam"));
get('telenok/module/web-page-constructor/list/page', array('as' => 'telenok.module.web-page-constructor.list.page', 'uses' => "App\Telenok\Core\Module\Web\PageConstructor\Controller@getListPage"));
get('telenok/module/web-page-constructor/view/page/container/{id}/language-id/{languageId}', array('as' => 'telenok.module.web-page-constructor.view.page.container', 'uses' => "App\Telenok\Core\Module\Web\PageConstructor\Controller@viewPageContainer"));
get('telenok/module/web-page-constructor/view/page/insert/language-id/{languageId}/page-id/{pageId}/widget-key/{key}/widget-id/{id}/container/{container}/bufferId/{bufferId}/order/{order}/', array('as' => 'telenok.module.web-page-constructor.view.page.insert.widget', 'uses' => "App\Telenok\Core\Module\Web\PageConstructor\Controller@insertWidget"));
get('telenok/module/web-page-constructor/view/page/remove/widget-id/{id}/', array('as' => 'telenok.module.web-page-constructor.view.page.remove.widget', 'uses' => "App\Telenok\Core\Module\Web\PageConstructor\Controller@removeWidget"));
post('telenok/module/web-page-constructor/view/page/widget/buffer/add/{id}/key/{key}', array('as' => 'telenok.module.web-page-constructor.view.buffer.add.widget', 'uses' => "App\Telenok\Core\Module\Web\PageConstructor\Controller@addBufferWidget"));
post('telenok/module/web-page-constructor/view/page/widget/buffer/delete/{id}', array('as' => 'telenok.module.web-page-constructor.view.buffer.delete.widget', 'uses' => "App\Telenok\Core\Module\Web\PageConstructor\Controller@deleteBufferWidget"));

get('telenok/login', array('as' => 'telenok.login.control-panel', 'uses' => "\App\Telenok\Core\Controller\Auth\AuthController@getLogin"));
post('telenok/process/login', array('as' => 'telenok.login.process', 'uses' => "\App\Telenok\Core\Controller\Auth\AuthController@postLogin"));
get('telenok/logout', array('as' => 'telenok.logout', 'uses' => "\App\Telenok\Core\Controller\Auth\AuthController@logout"));
post('telenok/password/reset/email', array('as' => 'telenok.password.reset.email.process', 'uses' => "\App\Telenok\Core\Controller\Auth\PasswordController@postEmail"));
get('telenok/password/reset/{token}', array('as' => 'telenok.password.reset.token', 'uses' => "\App\Telenok\Core\Controller\Auth\PasswordController@getReset"));
post('telenok/password/reset/process', array('as' => 'telenok.password.reset.token.process', 'uses' => "\App\Telenok\Core\Controller\Auth\PasswordController@postReset"));

// Module Setting\Tools
get('telenok/module/php-console/action-param', array('as' => 'telenok.module.php-console.action.param', 'uses' => "App\Telenok\Core\Module\Tools\PhpConsole\Controller@getActionParam"));
post('telenok/module/php-console/process-code', array('as' => 'telenok.module.php-console.process-code', 'uses' => "App\Telenok\Core\Module\Tools\PhpConsole\Controller@processCode"));

get('telenok/module/database-console/action-param', array('as' => 'telenok.module.database-console.action.param', 'uses' => "App\Telenok\Core\Module\Tools\DatabaseConsole\Controller@getActionParam"));
post('telenok/module/database-console/process-select', array('as' => 'telenok.module.database-console.process-select', 'uses' => "App\Telenok\Core\Module\Tools\DatabaseConsole\Controller@processSelect"));
post('telenok/module/database-console/process-statement', array('as' => 'telenok.module.database-console.process-statement', 'uses' => "App\Telenok\Core\Module\Tools\DatabaseConsole\Controller@processStatement"));

// Module Packages\ComposerManager
get('telenok/module/packages/composer-manager/action-param', array('as' => 'telenok.module.composer-manager.action.param', 'uses' => "App\Telenok\Core\Module\Packages\ComposerManager\Controller@getActionParam"));
get('telenok/module/packages/composer-manager', array('as' => 'telenok.module.composer-manager', 'uses' => "App\Telenok\Core\Module\Packages\ComposerManager\Controller@getContent"));
get('telenok/module/packages/composer-manager/list', array('as' => 'telenok.module.composer-manager.list', 'uses' => "App\Telenok\Core\Module\Packages\ComposerManager\Controller@getList"));


get('telenok/module/packages/composer-manager/edit', array('as' => 'telenok.module.composer-manager.edit', 'uses' => "App\Telenok\Core\Module\Packages\ComposerManager\Controller@edit"));
post('telenok/module/packages/composer-manager/update', array('as' => 'telenok.module.composer-manager.update', 'uses' => "App\Telenok\Core\Module\Packages\ComposerManager\Controller@update"));
post('telenok/module/packages/composer-manager/delete', array('as' => 'telenok.module.composer-manager.delete', 'uses' => "App\Telenok\Core\Module\Packages\ComposerManager\Controller@delete"));

get('telenok/module/packages/composer-manager/composer-json/edit', array('as' => 'telenok.module.composer-manager.composer-json.edit', 'uses' => "App\Telenok\Core\Module\Packages\ComposerManager\Controller@getComposerJsonContent"));
post('telenok/module/packages/composer-manager/composer-json/update', array('as' => 'telenok.module.composer-manager.composer-json.update', 'uses' => "App\Telenok\Core\Module\Packages\ComposerManager\Controller@composerJsonUpdate"));

// Module Packages\InstallerManager
get('telenok/module/packages/installer-manager/action-param', array('as' => 'telenok.module.installer-manager.action.param', 'uses' => "App\Telenok\Core\Module\Packages\InstallerManager\Controller@getActionParam"));
get('telenok/module/packages/installer-manager', array('as' => 'telenok.module.installer-manager', 'uses' => "App\Telenok\Core\Module\Packages\InstallerManager\Controller@getContent"));
get('telenok/module/packages/installer-manager/list', array('as' => 'telenok.module.installer-manager.list', 'uses' => "App\Telenok\Core\Module\Packages\InstallerManager\Controller@getList"));
get('telenok/module/packages/installer-manager/view/{id}', array('as' => 'telenok.module.installer-manager.view', 'uses' => "App\Telenok\Core\Module\Packages\InstallerManager\Controller@view"));

\Route::any('telenok/module/packages/installer-manager/install-package/{packageId}/{versionId}', array('as' => 'telenok.module.installer-manager.install-package', 'uses' => "App\Telenok\Core\Module\Packages\InstallerManager\Controller@installPackage"));
get('telenok/module/packages/installer-manager/install-package/{packageId}/{versionId}/status', array('as' => 'telenok.module.installer-manager.install-package.status', 'uses' => "App\Telenok\Core\Module\Packages\InstallerManager\Controller@installPackageStatus"));

post('telenok/module/packages/installer-manager/update', array('as' => 'telenok.module.installer-manager.update', 'uses' => "App\Telenok\Core\Module\Packages\InstallerManager\Controller@update"));
post('telenok/module/packages/installer-manager/delete', array('as' => 'telenok.module.installer-manager.delete', 'uses' => "App\Telenok\Core\Module\Packages\InstallerManager\Controller@delete"));

