<?php

\Validator::resolver(function($translator, $data, $rules, $messages, $customAttributes)
{
    return new \App\Telenok\Core\Support\Validator\Validator($translator, $data, $rules, $messages, $customAttributes);
});

\Validator::extend('valid_regex', function($attribute, $value, $parameters)
{
    return (@preg_match($value, NULL) !== FALSE);
});

\Event::listen('telenok.setting.add', function($list)
{
    $list->push('App\Telenok\Core\Setting\AppLocaleDefault\Controller');
    $list->push('App\Telenok\Core\Setting\AppLocales\Controller');
});

\Event::listen('telenok.acl.filter.resource.add', function($list)
{
    $list->push('App\Telenok\Core\Filter\Acl\Resource\ObjectType\Controller');
    $list->push('App\Telenok\Core\Filter\Acl\Resource\ObjectTypeOwn\Controller');
    $list->push('App\Telenok\Core\Filter\Acl\Resource\DirectRight\Controller');
});

\Event::listen('telenok.module.menu.left', function($list)
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
    $list->put('package-manager', 3);

    $list->put('users', 1);
    $list->put('users-profile-edit', 2);
});

\Event::listen('telenok.module.menu.top', function($list)
{
    $list->push('users-profile-edit@topMenuMain');
    $list->push('users-profile-edit@topMenuProfileEdit');
    $list->push('users-profile-edit@topMenuLogout');
});

\Event::listen('telenok.objects-field.add', function($list)
{
    $list->push('App\Telenok\Core\Field\Integer\Controller');
    $list->push('App\Telenok\Core\Field\IntegerUnsigned\Controller');
    $list->push('App\Telenok\Core\Field\Text\Controller');
    $list->push('App\Telenok\Core\Field\String\Controller');
    $list->push('App\Telenok\Core\Field\ComplexArray\Controller');
    $list->push('App\Telenok\Core\Field\RelationOneToOne\Controller');
    $list->push('App\Telenok\Core\Field\RelationOneToMany\Controller');
    $list->push('App\Telenok\Core\Field\RelationManyToMany\Controller');
    $list->push('App\Telenok\Core\Field\System\Tree\Controller');
    $list->push('App\Telenok\Core\Field\MorphOneToOne\Controller');
    $list->push('App\Telenok\Core\Field\MorphOneToMany\Controller');
    $list->push('App\Telenok\Core\Field\MorphManyToMany\Controller');
    $list->push('App\Telenok\Core\Field\System\CreatedBy\Controller');
    $list->push('App\Telenok\Core\Field\System\UpdatedBy\Controller');
    $list->push('App\Telenok\Core\Field\System\DeletedBy\Controller');
    $list->push('App\Telenok\Core\Field\System\LockedBy\Controller');
    $list->push('App\Telenok\Core\Field\System\Permission\Controller');
    $list->push('App\Telenok\Core\Field\FileManyToMany\Controller');
    $list->push('App\Telenok\Core\Field\Upload\Controller');
    $list->push('App\Telenok\Core\Field\SelectOne\Controller');
    $list->push('App\Telenok\Core\Field\SelectMany\Controller');
    $list->push('App\Telenok\Core\Field\Time\Controller');
    $list->push('App\Telenok\Core\Field\DateTime\Controller');
    $list->push('App\Telenok\Core\Field\TimeRange\Controller');
    $list->push('App\Telenok\Core\Field\DateTimeRange\Controller');
});

\Event::listen('telenok.objects-field.view.model.add', function($list)
{
    $list->push('select-one#core::field.select-one.model-radio-button');
    $list->push('select-one#core::field.select-one.model-toggle-button');
    $list->push('select-one#core::field.select-one.model-select-box');

    $list->push('select-many#core::field.select-many.model-checkbox-button');
    $list->push('select-many#core::field.select-many.model-select-box');
    $list->push('select-many#core::field.select-many.model-toggle-button');
});

\Event::listen('telenok.compile.route', function()
{
    app('telenok.config.repository')->compileRouter();
});

\Event::listen('telenok.compile.setting', function()
{
    app('telenok.config.repository')->compileSetting();
});

\Event::listen('illuminate.query', function($sql, $bindings, $time)
{
    if (config('querylog'))
    {
        $sql = vsprintf(str_replace(array('%', '?'), array('%%', '"%s"'), $sql), $bindings);

        echo $sql . PHP_EOL;
    }
});