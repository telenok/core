<?php namespace Telenok\Core\Event;

class Listener {

    public function onRepositoryPackage(RepositoryPackage $event)
    {
        $event->getList()->push('Telenok\Core\PackageInfo');
    }

    public function onRepositorySetting(RepositorySetting $event)
    {
        $event->getList()->push('App\Telenok\Core\Setting\Basic\Controller');
        $event->getList()->push('App\Telenok\Core\Setting\Secure\Controller');
        $event->getList()->push('App\Telenok\Core\Setting\License\Controller');
    }

    public function onAclFilterResource(AclFilterResource $event)
    {
        $event->getList()->push('App\Telenok\Core\Security\Filter\Acl\Resource\ObjectType\Controller');
        $event->getList()->push('App\Telenok\Core\Security\Filter\Acl\Resource\ObjectTypeOwn\Controller');
        $event->getList()->push('App\Telenok\Core\Security\Filter\Acl\Resource\DirectRight\Controller');
    }

    public function onModuleMenuLeft(ModuleMenuLeft $event)
    {
        $list = $event->getList();

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
    }

    public function onModuleMenuTop(ModuleMenuTop $event)
    {
        $event->getList()->push('users-profile-edit@topMenuMain');
        $event->getList()->push('users-profile-edit@topMenuProfileEdit');
        $event->getList()->push('users-profile-edit@topMenuLogout');
    }

    public function onRepositoryObjectField(RepositoryObjectField $event)
    {
        $list = $event->getList();

        $list->push('App\Telenok\Core\Field\Integer\Controller');
        $list->push('App\Telenok\Core\Field\IntegerUnsigned\Controller');
        $list->push('App\Telenok\Core\Field\Decimal\Controller');
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
    }

    public function onRepositoryObjectFieldViewModel(RepositoryObjectFieldViewModel $event)
    {
        $list = $event->getList();

        $list->push('select-one#core::field.select-one.model-radio-button');
        $list->push('select-one#core::field.select-one.model-toggle-button');
        $list->push('select-one#core::field.select-one.model-select-box');

        $list->push('select-many#core::field.select-many.model-checkbox-button');
        $list->push('select-many#core::field.select-many.model-select-box');
        $list->push('select-many#core::field.select-many.model-toggle-button');
    }

    public function onCompileRoute(CompileRoute $event)
    {
        app('telenok.config.repository')->compileRouter();
    }

    public function subscribe($events)
    {
        $this->addListenerRepositoryPackage($events);
        $this->addListenerRepositorySetting($events);
        $this->addListenerAclFilterResource($events);
        $this->addListenerModuleMenuLeft($events);
        $this->addListenerModuleMenuTop($events);
        $this->addListenerRepositoryObjectField($events);
        $this->addListenerRepositoryObjectFieldViewModel($events);
        $this->addListenerCompileRoute($events);
    }

    public function addListenerRepositoryPackage($events)
    {
        $events->listen(
            'Telenok\Core\Event\RepositoryPackage',
            'App\Telenok\Core\Event\Listener@onRepositoryPackage'
        );
    }

    public function addListenerRepositorySetting($events)
    {
        $events->listen(
            'Telenok\Core\Event\RepositorySetting',
            'App\Telenok\Core\Event\Listener@onRepositorySetting'
        );
    }

    public function addListenerAclFilterResource($events)
    {
        $events->listen(
            'Telenok\Core\Event\AclFilterResource',
            'App\Telenok\Core\Event\Listener@onAclFilterResource'
        );
    }

    public function addListenerModuleMenuLeft($events)
    {
        $events->listen(
            'Telenok\Core\Event\ModuleMenuLeft',
            'App\Telenok\Core\Event\Listener@onModuleMenuLeft'
        );
    }

    public function addListenerModuleMenuTop($events)
    {
        $events->listen(
            'Telenok\Core\Event\ModuleMenuTop',
            'App\Telenok\Core\Event\Listener@onModuleMenuTop'
        );
    }

    public function addListenerRepositoryObjectField($events)
    {
        $events->listen(
            'Telenok\Core\Event\RepositoryObjectField',
            'App\Telenok\Core\Event\Listener@onRepositoryObjectField'
        );
    }

    public function addListenerRepositoryObjectFieldViewModel($events)
    {
        $events->listen(
            'Telenok\Core\Event\RepositoryObjectFieldViewModel',
            'App\Telenok\Core\Event\Listener@onRepositoryObjectFieldViewModel'
        );
    }

    public function addListenerCompileRoute($events)
    {
        $events->listen(
            'Telenok\Core\Event\CompileRoute',
            'App\Telenok\Core\Event\Listener@onCompileRoute'
        );
    }















}
