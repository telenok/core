<?php

    app('router')->get('browser/file', ['as' => 'browse.file', 'uses' => '\App\Vendor\Telenok\Core\Controller\Frontend\Controller@validateSession']);

    app('router')->get('validate/session', ['as' => 'validate.session', 'uses' => '\App\Vendor\Telenok\Core\Controller\Frontend\Controller@validateSession']);

    app('router')->get('telenok', ['as' => 'telenok.content', 'uses' => '\App\Vendor\Telenok\Core\Controller\Backend\Controller@getContent']);
    app('router')->get('telenok/error', ['as' => 'error.access-denied', 'uses' => '\App\Vendor\Telenok\Core\Controller\Backend\Controller@errorAccessDenied']);
    app('router')->get('telenok/validate/session', ['as' => 'telenok.validate.session', 'uses' => '\App\Vendor\Telenok\Core\Controller\Backend\Controller@validateSession']);

    // Update user's UI setting
    app('router')->post('telenok/user/update/ui-setting', ['as' => 'telenok.user.update.ui-setting', 'uses' => '\App\Vendor\Telenok\Core\Controller\Backend\Controller@updateBackendUISetting']);

    // Widget Form
    app('router')->post('widget/form/store/{typeId}', ['as' => 'telenok.widget.form.store', 'uses' => '\App\Vendor\Telenok\Core\Widget\Model\Form\Controller@store']);
    app('router')->post('widget/form/update/{id}', ['as' => 'telenok.widget.form.update', 'uses' => '\App\Vendor\Telenok\Core\Widget\Model\Form\Controller@update']);
    app('router')->post('widget/form/delete/{id}', ['as' => 'telenok.widget.form.delete', 'uses' => '\App\Vendor\Telenok\Core\Widget\Model\Form\Controller@delete']);

    // Widget Grid
    app('router')->get('widget/grid/{typeId}', ['as' => 'telenok.widget.grid.list', 'uses' => '\App\Vendor\Telenok\Core\Widget\Model\Grid\Controller@getList']);

    // Widget Menu
    app('router')->get('widget/menu/tree', ['as' => 'telenok.widget.menu.tree.list', 'uses' => '\App\Vendor\Telenok\Core\Widget\Menu\Controller@getTreeList']);

    // Object Field Upload
    app('router')->get('download/stream/{modelId}/{fieldId}', ['as' => 'telenok.download.stream.file', 'uses' => '\App\Vendor\Telenok\Core\Field\Upload\Download@stream']);
    app('router')->get('download/image/{modelId}/{fieldId}/{width}/{height}/{action}', ['as' => 'telenok.download.image.file', 'uses' => '\App\Vendor\Telenok\Core\Field\Upload\Download@image']);

    // Module Objects\Lists
    app('router')->get('telenok/module/objects-lists/action-param', ['as' => 'telenok.module.objects-lists.action.param', 'uses' => 'App\Vendor\Telenok\Core\Module\Objects\Lists\Controller@getActionParam']);
    app('router')->get('telenok/module/objects-lists', ['as' => 'telenok.module.objects-lists', 'uses' => 'App\Vendor\Telenok\Core\Module\Objects\Lists\Controller@getContent']);
    app('router')->get('telenok/module/objects-lists/create/type', ['as' => 'telenok.module.objects-lists.create', 'uses' => 'App\Vendor\Telenok\Core\Module\Objects\Lists\Controller@create']);
    app('router')->get('telenok/module/objects-lists/edit', ['as' => 'telenok.module.objects-lists.edit', 'uses' => 'App\Vendor\Telenok\Core\Module\Objects\Lists\Controller@edit']);
    app('router')->post('telenok/module/objects-lists/store/type/{id}', ['as' => 'telenok.module.objects-lists.store', 'uses' => 'App\Vendor\Telenok\Core\Module\Objects\Lists\Controller@store']);
    app('router')->post('telenok/module/objects-lists/update/type/{id}', ['as' => 'telenok.module.objects-lists.update', 'uses' => 'App\Vendor\Telenok\Core\Module\Objects\Lists\Controller@update']);
    app('router')->post('telenok/module/objects-lists/delete/{id}', ['as' => 'telenok.module.objects-lists.delete', 'uses' => 'App\Vendor\Telenok\Core\Module\Objects\Lists\Controller@delete']);

    app('router')->get('telenok/module/objects-lists/list', ['as' => 'telenok.module.objects-lists.list', 'uses' => 'App\Vendor\Telenok\Core\Module\Objects\Lists\Controller@getList']);
    app('router')->get('telenok/module/objects-lists/list/json', ['as' => 'telenok.module.objects-lists.list.json', 'uses' => 'App\Vendor\Telenok\Core\Module\Objects\Lists\Controller@getListJson']);
    app('router')->get('telenok/module/objects-lists/list/edit/', ['as' => 'telenok.module.objects-lists.list.edit', 'uses' => 'App\Vendor\Telenok\Core\Module\Objects\Lists\Controller@editList']);
    app('router')->post('telenok/module/objects-lists/list/delete', ['as' => 'telenok.module.objects-lists.list.delete', 'uses' => 'App\Vendor\Telenok\Core\Module\Objects\Lists\Controller@deleteList']);
    app('router')->post('telenok/module/objects-lists/list/lock', ['as' => 'telenok.module.objects-lists.list.lock', 'uses' => 'App\Vendor\Telenok\Core\Module\Objects\Lists\Controller@lockList']);
    app('router')->post('telenok/module/objects-lists/lock', ['as' => 'telenok.module.objects-lists.lock', 'uses' => 'App\Vendor\Telenok\Core\Module\Objects\Lists\Controller@lock']);
    app('router')->post('telenok/module/objects-lists/list/unlock', ['as' => 'telenok.module.objects-lists.list.unlock', 'uses' => 'App\Vendor\Telenok\Core\Module\Objects\Lists\Controller@unlockList']);
    app('router')->get('telenok/module/objects-lists/list/tree', ['as' => 'telenok.module.objects-lists.list.tree', 'uses' => 'App\Vendor\Telenok\Core\Module\Objects\Lists\Controller@getTreeList']);

    app('router')->get('telenok/module/objects-lists/wizard/create/type', ['as' => 'telenok.module.objects-lists.wizard.create', 'uses' => 'App\Vendor\Telenok\Core\Module\Objects\Lists\Wizard\Controller@create']);
    app('router')->get('telenok/module/objects-lists/wizard/edit', ['as' => 'telenok.module.objects-lists.wizard.edit', 'uses' => 'App\Vendor\Telenok\Core\Module\Objects\Lists\Wizard\Controller@edit']);
    app('router')->post('telenok/module/objects-lists/wizard/store/type/{id}', ['as' => 'telenok.module.objects-lists.wizard.store', 'uses' => 'App\Vendor\Telenok\Core\Module\Objects\Lists\Wizard\Controller@store']);
    app('router')->post('telenok/module/objects-lists/wizard/update/type/{id}', ['as' => 'telenok.module.objects-lists.wizard.update', 'uses' => 'App\Vendor\Telenok\Core\Module\Objects\Lists\Wizard\Controller@update']);
    app('router')->post('telenok/module/objects-lists/wizard/delete/{id}', ['as' => 'telenok.module.objects-lists.wizard.delete', 'uses' => 'App\Vendor\Telenok\Core\Module\Objects\Lists\Wizard\Controller@delete']);
    app('router')->get('telenok/module/objects-lists/wizard/choose', ['as' => 'telenok.module.objects-lists.wizard.choose', 'uses' => 'App\Vendor\Telenok\Core\Module\Objects\Lists\Wizard\Controller@choose']);
    app('router')->get('telenok/module/objects-lists/wizard/list', ['as' => 'telenok.module.objects-lists.wizard.list', 'uses' => 'App\Vendor\Telenok\Core\Module\Objects\Lists\Wizard\Controller@getList']);

    // Fields
    app('router')->get('field/relation-one-to-one/list/title/type/{id}', ['as' => 'telenok.field.relation-one-to-one.list.title', 'uses' => '\App\Vendor\Telenok\Core\Field\RelationOneToOne\Controller@getTitleList']);
    app('router')->get('field/relation-one-to-one/list/table/model/{id}/field/{fieldId}/uniqueId/{uniqueId}', ['as' => 'telenok.field.relation-one-to-one.list.table', 'uses' => '\App\Vendor\Telenok\Core\Field\RelationOneToOne\Controller@getTableList']);

    app('router')->get('field/relation-one-to-many/list/title/type/{id}', ['as' => 'telenok.field.relation-one-to-many.list.title', 'uses' => '\App\Vendor\Telenok\Core\Field\RelationOneToMany\Controller@getTitleList']);
    app('router')->get('field/relation-one-to-many/list/table/model/{id}/field/{fieldId}/uniqueId/{uniqueId}', ['as' => 'telenok.field.relation-one-to-many.list.table', 'uses' => '\App\Vendor\Telenok\Core\Field\RelationOneToMany\Controller@getTableList']);

    app('router')->get('field/relation-many-to-many/list/title/type/{id}', ['as' => 'telenok.field.relation-many-to-many.list.title', 'uses' => '\App\Vendor\Telenok\Core\Field\RelationManyToMany\Controller@getTitleList']);
    app('router')->get('field/relation-many-to-many/list/table/model/{id}/field/{fieldId}/uniqueId/{uniqueId}', ['as' => 'telenok.field.relation-many-to-many.list.table', 'uses' => '\App\Vendor\Telenok\Core\Field\RelationManyToMany\Controller@getTableList']);

    app('router')->get('field/tree/list/title/type/{id}', ['as' => 'telenok.field.tree.list.title', 'uses' => '\App\Vendor\Telenok\Core\Field\System\Tree\Controller@getTitleList']);
    app('router')->get('field/tree/list/table/model/{id}/field/{fieldId}/uniqueId/{uniqueId}', ['as' => 'telenok.field.tree.list.table', 'uses' => '\App\Vendor\Telenok\Core\Field\System\Tree\Controller@getTableList']);

    app('router')->get('field/morph-many-to-many/list/title/type/{id}', ['as' => 'telenok.field.morph-many-to-many.list.title', 'uses' => '\App\Vendor\Telenok\Core\Field\MorphManyToMany\Controller@getTitleList']);
    app('router')->get('field/morph-many-to-many/list/table/model/{id}/field/{fieldId}/uniqueId/{uniqueId}', ['as' => 'telenok.field.morph-many-to-many.list.table', 'uses' => '\App\Vendor\Telenok\Core\Field\MorphManyToMany\Controller@getTableList']);

    app('router')->get('field/morph-one-to-many/list/title/type/{id}', ['as' => 'telenok.field.morph-one-to-many.list.title', 'uses' => '\App\Vendor\Telenok\Core\Field\MorphOneToMany\Controller@getTitleList']);
    app('router')->get('field/morph-one-to-many/list/table/model/{id}/field/{fieldId}/uniqueId/{uniqueId}', ['as' => 'telenok.field.morph-one-to-many.list.table', 'uses' => '\App\Vendor\Telenok\Core\Field\MorphOneToMany\Controller@getTableList']);

    app('router')->get('field/morph-one-to-one/list/title/type/{id}', ['as' => 'telenok.field.morph-one-to-one.list.title', 'uses' => '\App\Vendor\Telenok\Core\Field\MorphOneToOne\Controller@getTitleList']);
    app('router')->get('field/morph-one-to-one/list/table/model/{id}/field/{fieldId}/uniqueId/{uniqueId}', ['as' => 'telenok.field.morph-one-to-one.list.table', 'uses' => '\App\Vendor\Telenok\Core\Field\MorphOneToOne\Controller@getTableList']);

    app('router')->get('field/permission/list/title', ['as' => 'telenok.field.permission.list.title', 'uses' => '\App\Vendor\Telenok\Core\Field\System\Permission\Controller@getTitleList']);

    app('router')->post('field/file-many-to-many/upload', ['as' => 'telenok.field.file-many-to-many.upload', 'uses' => '\App\Vendor\Telenok\Core\Field\FileManyToMany\Controller@upload']);
    app('router')->get('field/file-many-to-many/list/title', ['as' => 'telenok.field.file-many-to-many.list.title', 'uses' => '\App\Vendor\Telenok\Core\Field\FileManyToMany\Controller@getTitleList']);

    app('router')->get('field/upload/modal-cropper', ['as' => 'telenok.field.upload.modal-cropper', 'uses' => '\App\Vendor\Telenok\Core\Field\Upload\Controller@modalCropperContent']);

    app('router')->get('telenok/ckeditor.custom.config.js', ['as' => 'telenok.ckeditor.config', 'uses' => 'App\Vendor\Telenok\Core\Support\CKEditor\Controller@getCKEditorConfig']);
    app('router')->get('telenok/ckeditor/browser/file', ['as' => 'telenok.ckeditor.file', 'uses' => 'App\Vendor\Telenok\Core\Support\CKEditor\Controller@browseFile']);
    app('router')->get('telenok/ckeditor/browser/image', ['as' => 'telenok.ckeditor.image', 'uses' => 'App\Vendor\Telenok\Core\Support\CKEditor\Controller@browseImage']);
    app('router')->get('telenok/ckeditor/browser/file/list', ['as' => 'telenok.ckeditor.storage.list', 'uses' => 'App\Vendor\Telenok\Core\Support\CKEditor\Controller@storageFileList']);
    app('router')->get('telenok/ckeditor/browser/model/list', ['as' => 'telenok.ckeditor.model.list', 'uses' => 'App\Vendor\Telenok\Core\Support\CKEditor\Controller@modelFileList']);
    app('router')->get('telenok/packages/telenok/core/js/ckeditor_addons/plugins/widget_inline/plugin.js', ['as' => 'telenok.ckeditor.plugin.inline-widget.config', 'uses' => 'App\Vendor\Telenok\Core\Support\CKEditor\Controller@getCKEditorPluginWidgetInline']);
    app('router')->get('telenok/ckeditor/modal-cropper', ['as' => 'telenok.ckeditor.modal-cropper', 'uses' => 'App\Vendor\Telenok\Core\Support\CKEditor\Controller@modalCropperContent']);
    app('router')->post('telenok/ckeditor/image/create', ['as' => 'telenok.ckeditor.image.create', 'uses' => 'App\Vendor\Telenok\Core\Support\CKEditor\Controller@imageCreate']);
    app('router')->post('telenok/ckeditor/directory/create', ['as' => 'telenok.ckeditor.directory.create', 'uses' => 'App\Vendor\Telenok\Core\Support\CKEditor\Controller@directoryCreate']);
    app('router')->post('telenok/ckeditor/file/upload', ['as' => 'telenok.ckeditor.file.upload', 'uses' => 'App\Vendor\Telenok\Core\Support\CKEditor\Controller@uploadFile']);

    // Module Dashboard
    app('router')->get('telenok/module/dashboard', ['as' => 'telenok.module.dashboard', 'uses' => 'App\Vendor\Telenok\Core\Module\Dashboard\Controller@getContent']);

    // Module Profile
    app('router')->get('telenok/module/users-profile-edit/action-param', ['as' => 'telenok.module.users-profile-edit.action.param', 'uses' => 'App\Vendor\Telenok\Core\Module\Users\ProfileEdit\Controller@getActionParam']);
    app('router')->get('telenok/module/users-profile-edit', ['as' => 'telenok.module.users-profile-edit', 'uses' => 'App\Vendor\Telenok\Core\Module\Users\ProfileEdit\Controller@getContent']);
    app('router')->post('telenok/module/users-profile-edit/update', ['as' => 'telenok.module.users-profile-edit.update', 'uses' => 'App\Vendor\Telenok\Core\Module\Users\ProfileEdit\Controller@update']);

    // Module Objects\Type
    app('router')->get('telenok/module/objects-type/action-param', ['as' => 'telenok.module.objects-type.action.param', 'uses' => 'App\Vendor\Telenok\Core\Module\Objects\Type\Controller@getActionParam']);

    // Module Objects\Field
    app('router')->get('telenok/module/objects-field/field-form/{fieldKey}/{modelId}/{uniqueId}', ['as' => 'telenok.module.objects-field.field.form', 'uses' => 'App\Vendor\Telenok\Core\Module\Objects\Field\Controller@getFormFieldContent']);

    // Module Objects\Sequence
    app('router')->get('telenok/module/objects-sequence/action-param', ['as' => 'telenok.module.objects-sequence.action.param', 'uses' => 'App\Vendor\Telenok\Core\Module\Objects\Sequence\Controller@getActionParam']);
    app('router')->get('telenok/module/objects-sequence', ['as' => 'telenok.module.objects-sequence', 'uses' => 'App\Vendor\Telenok\Core\Module\Objects\Sequence\Controller@getContent']);
    app('router')->get('telenok/module/objects-sequence/list', ['as' => 'telenok.module.objects-sequence.list', 'uses' => 'App\Vendor\Telenok\Core\Module\Objects\Sequence\Controller@getList']);

    // Module Objects\Version
    app('router')->get('telenok/module/objects-version/action-param', ['as' => 'telenok.module.objects-version.action.param', 'uses' => 'App\Vendor\Telenok\Core\Module\Objects\Version\Controller@getActionParam']);
    app('router')->get('telenok/module/objects-version', ['as' => 'telenok.module.objects-version', 'uses' => 'App\Vendor\Telenok\Core\Module\Objects\Version\Controller@getContent']);
    app('router')->get('telenok/module/objects-version/list', ['as' => 'telenok.module.objects-version.list', 'uses' => 'App\Vendor\Telenok\Core\Module\Objects\Version\Controller@getList']);

    // Module Objects\Sequence
    app('router')->get('telenok/module/objects-sequence/list', ['as' => 'telenok.module.objects-sequence.list', 'uses' => 'App\Vendor\Telenok\Core\Module\Objects\Sequence\Controller@getList']);

    // Module Web Domain
    app('router')->get('telenok/module/web-domain/action-param', ['as' => 'telenok.module.web-domain.action.param', 'uses' => 'App\Vendor\Telenok\Core\Module\Web\Domain\Controller@getActionParam']);

    // Module Files
    app('router')->get('telenok/module/files/browser/action-param', ['as' => 'telenok.module.files-browser.action.param', 'uses' => 'App\Vendor\Telenok\Core\Module\Files\Browser\Controller@getActionParam']);
    app('router')->get('telenok/module/files/browser', ['as' => 'telenok.module.files-browser', 'uses' => 'App\Vendor\Telenok\Core\Module\Files\Browser\Controller@getContent']);
    app('router')->get('telenok/module/files/browser/list', ['as' => 'telenok.module.files-browser.list', 'uses' => 'App\Vendor\Telenok\Core\Module\Files\Browser\Controller@getList']);
    app('router')->get('telenok/module/files/browser/create', ['as' => 'telenok.module.files-browser.create', 'uses' => 'App\Vendor\Telenok\Core\Module\Files\Browser\Controller@create']);
    app('router')->get('telenok/module/files/browser/edit', ['as' => 'telenok.module.files-browser.edit', 'uses' => 'App\Vendor\Telenok\Core\Module\Files\Browser\Controller@edit']);
    app('router')->post('telenok/module/files/browser/store', ['as' => 'telenok.module.files-browser.store', 'uses' => 'App\Vendor\Telenok\Core\Module\Files\Browser\Controller@store']);
    app('router')->post('telenok/module/files/browser/update', ['as' => 'telenok.module.files-browser.update', 'uses' => 'App\Vendor\Telenok\Core\Module\Files\Browser\Controller@update']);
    app('router')->post('telenok/module/files/browser/delete', ['as' => 'telenok.module.files-browser.delete', 'uses' => 'App\Vendor\Telenok\Core\Module\Files\Browser\Controller@delete']);
    app('router')->get('telenok/module/files/browser/list/edit', ['as' => 'telenok.module.files-browser.list.edit', 'uses' => 'App\Vendor\Telenok\Core\Module\Files\Browser\Controller@editList']);
    app('router')->get('telenok/module/files/browser/list/delete', ['as' => 'telenok.module.files-browser.list.delete', 'uses' => 'App\Vendor\Telenok\Core\Module\Files\Browser\Controller@deleteList']);
    app('router')->post('telenok/module/files/browser/upload', ['as' => 'telenok.module.files-browser.upload', 'uses' => 'App\Vendor\Telenok\Core\Module\Files\Browser\Controller@uploadFile']);

    // Module System\Config
    app('router')->get('telenok/module/system-config/action-param', ['as' => 'telenok.module.system-config.action.param', 'uses' => 'App\Vendor\Telenok\Core\Module\System\Config\Controller@getActionParam']);
    app('router')->post('telenok/module/system-config/save', ['as' => 'telenok.module.system-config.save', 'uses' => 'App\Vendor\Telenok\Core\Module\System\Config\Controller@save']);

    // Module Web\PageConstructor
    app('router')->get('telenok/module/web-page-constructor/action-param', ['as' => 'telenok.module.web-page-constructor.action.param', 'uses' => 'App\Vendor\Telenok\Core\Module\Web\PageConstructor\Controller@getActionParam']);
    app('router')->get('telenok/module/web-page-constructor/list/page', ['as' => 'telenok.module.web-page-constructor.list.page', 'uses' => 'App\Vendor\Telenok\Core\Module\Web\PageConstructor\Controller@getListPage']);
    app('router')->get('telenok/module/web-page-constructor/view/page/container/{id}/language-id/{languageId}', ['as' => 'telenok.module.web-page-constructor.view.page.container', 'uses' => 'App\Vendor\Telenok\Core\Module\Web\PageConstructor\Controller@viewPageContainer']);
    app('router')->get('telenok/module/web-page-constructor/view/page/insert/language-id/{languageId}/page-id/{pageId}/widget-key/{key}/widget-id/{id}/container/{container}/bufferId/{bufferId}/order/{order}/', ['as' => 'telenok.module.web-page-constructor.view.page.insert.widget', 'uses' => 'App\Vendor\Telenok\Core\Module\Web\PageConstructor\Controller@insertWidget']);
    app('router')->get('telenok/module/web-page-constructor/view/page/remove/widget-id/{id}/', ['as' => 'telenok.module.web-page-constructor.view.page.remove.widget', 'uses' => 'App\Vendor\Telenok\Core\Module\Web\PageConstructor\Controller@removeWidget']);
    app('router')->post('telenok/module/web-page-constructor/view/page/widget/buffer/add/{id}/key/{key}', ['as' => 'telenok.module.web-page-constructor.view.buffer.add.widget', 'uses' => 'App\Vendor\Telenok\Core\Module\Web\PageConstructor\Controller@addBufferWidget']);
    app('router')->post('telenok/module/web-page-constructor/view/page/widget/buffer/delete/{id}', ['as' => 'telenok.module.web-page-constructor.view.buffer.delete.widget', 'uses' => 'App\Vendor\Telenok\Core\Module\Web\PageConstructor\Controller@deleteBufferWidget']);

    app('router')->get('telenok/login', ['as' => 'telenok.login.control-panel', 'uses' => '\App\Vendor\Telenok\Core\Controller\Auth\AuthController@getLogin']);
    app('router')->post('telenok/process/login', ['as' => 'telenok.login.process', 'uses' => '\App\Vendor\Telenok\Core\Controller\Auth\AuthController@postLogin']);
    app('router')->post('telenok/logout', ['as' => 'telenok.logout', 'uses' => '\App\Vendor\Telenok\Core\Controller\Auth\AuthController@logout']);
    app('router')->post('telenok/password/reset/email', ['as' => 'telenok.password.reset.email.process', 'uses' => '\App\Vendor\Telenok\Core\Controller\Auth\PasswordController@postEmail']);
    app('router')->get('telenok/password/reset/{token}', ['as' => 'telenok.password.reset.token', 'uses' => '\App\Vendor\Telenok\Core\Controller\Auth\PasswordController@getReset']);
    app('router')->post('telenok/password/reset/process', ['as' => 'telenok.password.reset.token.process', 'uses' => '\App\Vendor\Telenok\Core\Controller\Auth\PasswordController@postReset']);

    // Module Setting\Tools
    app('router')->get('telenok/module/php-console/action-param', ['as' => 'telenok.module.php-console.action.param', 'uses' => 'App\Vendor\Telenok\Core\Module\Tools\PhpConsole\Controller@getActionParam']);
    app('router')->post('telenok/module/php-console/process-code', ['as' => 'telenok.module.php-console.process-code', 'uses' => 'App\Vendor\Telenok\Core\Module\Tools\PhpConsole\Controller@processCode']);

    app('router')->get('telenok/module/database-console/action-param', ['as' => 'telenok.module.database-console.action.param', 'uses' => 'App\Vendor\Telenok\Core\Module\Tools\DatabaseConsole\Controller@getActionParam']);
    app('router')->post('telenok/module/database-console/process-select', ['as' => 'telenok.module.database-console.process-select', 'uses' => 'App\Vendor\Telenok\Core\Module\Tools\DatabaseConsole\Controller@processSelect']);
    app('router')->post('telenok/module/database-console/process-statement', ['as' => 'telenok.module.database-console.process-statement', 'uses' => 'App\Vendor\Telenok\Core\Module\Tools\DatabaseConsole\Controller@processStatement']);

    // Module Packages\ComposerManager
    app('router')->get('telenok/module/packages/composer-manager/action-param', ['as' => 'telenok.module.composer-manager.action.param', 'uses' => 'App\Vendor\Telenok\Core\Module\Packages\ComposerManager\Controller@getActionParam']);
    app('router')->get('telenok/module/packages/composer-manager', ['as' => 'telenok.module.composer-manager', 'uses' => 'App\Vendor\Telenok\Core\Module\Packages\ComposerManager\Controller@getContent']);
    app('router')->get('telenok/module/packages/composer-manager/list', ['as' => 'telenok.module.composer-manager.list', 'uses' => 'App\Vendor\Telenok\Core\Module\Packages\ComposerManager\Controller@getList']);

    app('router')->get('telenok/module/packages/composer-manager/edit', ['as' => 'telenok.module.composer-manager.edit', 'uses' => 'App\Vendor\Telenok\Core\Module\Packages\ComposerManager\Controller@edit']);
    app('router')->post('telenok/module/packages/composer-manager/update', ['as' => 'telenok.module.composer-manager.update', 'uses' => 'App\Vendor\Telenok\Core\Module\Packages\ComposerManager\Controller@update']);
    app('router')->post('telenok/module/packages/composer-manager/delete', ['as' => 'telenok.module.composer-manager.delete', 'uses' => 'App\Vendor\Telenok\Core\Module\Packages\ComposerManager\Controller@delete']);

    app('router')->get('telenok/module/packages/composer-manager/composer-json/edit', ['as' => 'telenok.module.composer-manager.composer-json.edit', 'uses' => 'App\Vendor\Telenok\Core\Module\Packages\ComposerManager\Controller@getComposerJsonContent']);
    app('router')->post('telenok/module/packages/composer-manager/composer-json/update', ['as' => 'telenok.module.composer-manager.composer-json.update', 'uses' => 'App\Vendor\Telenok\Core\Module\Packages\ComposerManager\Controller@composerJsonUpdate']);

    // Module Packages\InstallerManager
    app('router')->get('telenok/module/packages/installer-manager/action-param', ['as' => 'telenok.module.installer-manager.action.param', 'uses' => 'App\Vendor\Telenok\Core\Module\Packages\InstallerManager\Controller@getActionParam']);
    app('router')->get('telenok/module/packages/installer-manager', ['as' => 'telenok.module.installer-manager', 'uses' => 'App\Vendor\Telenok\Core\Module\Packages\InstallerManager\Controller@getContent']);
    app('router')->get('telenok/module/packages/installer-manager/list', ['as' => 'telenok.module.installer-manager.list', 'uses' => 'App\Vendor\Telenok\Core\Module\Packages\InstallerManager\Controller@getList']);
    app('router')->get('telenok/module/packages/installer-manager/view/{id}', ['as' => 'telenok.module.installer-manager.view', 'uses' => 'App\Vendor\Telenok\Core\Module\Packages\InstallerManager\Controller@view']);

    app('router')->any('telenok/module/packages/installer-manager/install-package/{packageId}/{versionId}', ['as' => 'telenok.module.installer-manager.install-package', 'uses' => 'App\Vendor\Telenok\Core\Module\Packages\InstallerManager\Controller@installPackage']);
    app('router')->get('telenok/module/packages/installer-manager/install-package/{packageId}/{versionId}/status', ['as' => 'telenok.module.installer-manager.install-package.status', 'uses' => 'App\Vendor\Telenok\Core\Module\Packages\InstallerManager\Controller@installPackageStatus']);

    app('router')->post('telenok/module/packages/installer-manager/update', ['as' => 'telenok.module.installer-manager.update', 'uses' => 'App\Vendor\Telenok\Core\Module\Packages\InstallerManager\Controller@update']);
    app('router')->post('telenok/module/packages/installer-manager/delete', ['as' => 'telenok.module.installer-manager.delete', 'uses' => 'App\Vendor\Telenok\Core\Module\Packages\InstallerManager\Controller@delete']);

    // Cross-domain auth
    app('router')->get('cross-domain/auth', ['as' => 'telenok.account.cross-domain.auth', 'uses' => 'App\Vendor\Telenok\Core\Controller\Auth\AuthController@setCrossDomainAuth']);
