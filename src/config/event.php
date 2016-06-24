<?php
/*
app('events')->listen('telenok.repository.package', function($list)
{
    $list->push('Telenok\Core\PackageInfo');
});


app('events')->listen('telenok.repository.setting', function($list)
{

});

app('events')->listen('telenok.acl.filter.resource', function($list)
{
    $list->push('App\Vendor\Telenok\Core\Security\Filter\Acl\Resource\ObjectType\Controller');
    $list->push('App\Vendor\Telenok\Core\Security\Filter\Acl\Resource\ObjectTypeOwn\Controller');
    $list->push('App\Vendor\Telenok\Core\Security\Filter\Acl\Resource\DirectRight\Controller');
});

app('events')->listen('telenok.module.menu.left', function($list)
{
    $list->put('web', 1);
    $list->put('objects', 2);
    $list->put('system', 3);

    $list->put('dashboard', 0);
    $list->put('objects-field', 0);
    $list->put('objects-lists', 0);
    $list->put('objects-type', 0);
    $list->put('objects-version', 0);
    $list->put('system-setting', 0);
    $list->put('web-page-constructor', 10);
    $list->put('web-page', 11);
    $list->put('web-page-controller', 12);
    $list->put('web-domain', 13);

    $list->put('files', 4);
    $list->put('files-browser', 5);

    $list->put('tools', 5);
    $list->put('database-console', 1);
    $list->put('php-console', 2);
	
    $list->put('packages', 3);
    $list->put('composer-manager', 1);
    $list->put('installer-manager', 2);

    $list->put('users', 1);
    $list->put('users-profile-edit', 2);
});

app('events')->listen('telenok.module.menu.top', function($list)
{
    $list->push('users-profile-edit@topMenuMain');
    $list->push('users-profile-edit@topMenuProfileEdit');
    $list->push('users-profile-edit@topMenuLogout');
});

app('events')->listen('telenok.repository.objects-field', function($list)
{
    $list->push('App\Vendor\Telenok\Core\Field\Integer\Controller');
    $list->push('App\Vendor\Telenok\Core\Field\IntegerUnsigned\Controller');
    $list->push('App\Vendor\Telenok\Core\Field\Decimal\Controller');
    $list->push('App\Vendor\Telenok\Core\Field\Text\Controller');
    $list->push('App\Vendor\Telenok\Core\Field\String\Controller');
    $list->push('App\Vendor\Telenok\Core\Field\ComplexArray\Controller');
    $list->push('App\Vendor\Telenok\Core\Field\RelationOneToOne\Controller');
    $list->push('App\Vendor\Telenok\Core\Field\RelationOneToMany\Controller');
    $list->push('App\Vendor\Telenok\Core\Field\RelationManyToMany\Controller');
    $list->push('App\Vendor\Telenok\Core\Field\System\Tree\Controller');
    $list->push('App\Vendor\Telenok\Core\Field\MorphOneToOne\Controller');
    $list->push('App\Vendor\Telenok\Core\Field\MorphOneToMany\Controller');
    $list->push('App\Vendor\Telenok\Core\Field\MorphManyToMany\Controller');
    $list->push('App\Vendor\Telenok\Core\Field\System\CreatedBy\Controller');
    $list->push('App\Vendor\Telenok\Core\Field\System\UpdatedBy\Controller');
    $list->push('App\Vendor\Telenok\Core\Field\System\DeletedBy\Controller');
    $list->push('App\Vendor\Telenok\Core\Field\System\LockedBy\Controller');
    $list->push('App\Vendor\Telenok\Core\Field\System\Permission\Controller');
    $list->push('App\Vendor\Telenok\Core\Field\FileManyToMany\Controller');
    $list->push('App\Vendor\Telenok\Core\Field\Upload\Controller');
    $list->push('App\Vendor\Telenok\Core\Field\SelectOne\Controller');
    $list->push('App\Vendor\Telenok\Core\Field\SelectMany\Controller');
    $list->push('App\Vendor\Telenok\Core\Field\Time\Controller');
    $list->push('App\Vendor\Telenok\Core\Field\DateTime\Controller');
    $list->push('App\Vendor\Telenok\Core\Field\TimeRange\Controller');
    $list->push('App\Vendor\Telenok\Core\Field\DateTimeRange\Controller');
});

app('events')->listen('telenok.repository.objects-field.view.model', function($list)
{
    $list->push('select-one#core::field.select-one.model-radio-button');
    $list->push('select-one#core::field.select-one.model-toggle-button');
    $list->push('select-one#core::field.select-one.model-select-box');

    $list->push('select-many#core::field.select-many.model-checkbox-button');
    $list->push('select-many#core::field.select-many.model-select-box');
    $list->push('select-many#core::field.select-many.model-toggle-button');
});

app('events')->listen('telenok.compile.route', function()
{
    app('telenok.config.repository')->compileRoute();
});

app('events')->listen('telenok.compile.setting', function()
{
    app('telenok.config.repository')->compileSetting();
});

app('events')->listen('telenok.backend.external', function($controller)
{
    app('\App\Vendor\Telenok\Core\Module\Packages\InstallerManager\Controller')->processExternalEvent($controller);
});
*/
