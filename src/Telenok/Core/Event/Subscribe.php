<?php namespace Telenok\Core\Event;

class Subscribe {

    public function onRepositoryPackage(RepositoryPackage $event)
    {
        $event->getList()->push('Telenok\Core\PackageInfo');
    }

    public function onAclFilterResource(AclFilterResource $event)
    {
        $event->getList()->push('App\Vendor\Telenok\Core\Security\Filter\Acl\Resource\ObjectType\Controller');
        $event->getList()->push('App\Vendor\Telenok\Core\Security\Filter\Acl\Resource\ObjectTypeOwn\Controller');
        $event->getList()->push('App\Vendor\Telenok\Core\Security\Filter\Acl\Resource\DirectRight\Controller');
    }

    public function onWidgetGroup(WidgetGroup $event)
    {
        $event->getList()->push('App\Vendor\Telenok\Core\WidgetGroup\Standart\Controller');
        $event->getList()->push('App\Vendor\Telenok\News\WidgetGroup\News\Controller');
    }

    public function onWidget(Widget $event)
    {
        $list = $event->getList();

        $list->push('App\Vendor\Telenok\Core\Widget\Html\Controller');
        $list->push('App\Vendor\Telenok\Core\Widget\Table\Controller');
        $list->push('App\Vendor\Telenok\Core\Widget\Menu\Controller');
        $list->push('App\Vendor\Telenok\Core\Widget\Rte\Controller');
        $list->push('App\Vendor\Telenok\Core\Widget\Php\Controller');
        $list->push('App\Vendor\Telenok\Core\Widget\Table\Controller');
    }

    public function onModuleGroup(ModuleGroup $event)
    {
        $list = $event->getList();

        $list->push('App\Vendor\Telenok\Core\ModuleGroup\Content\Controller');
        $list->push('App\Vendor\Telenok\Core\ModuleGroup\User\Controller');
        $list->push('App\Vendor\Telenok\Core\ModuleGroup\Web\Controller');
        $list->push('App\Vendor\Telenok\Core\ModuleGroup\Setting\Controller');
    }

    public function onModule(Module $event)
    {
        $list = $event->getList();

        $list->push('App\Vendor\Telenok\Core\Module\Users\Controller');
        $list->push('App\Vendor\Telenok\Core\Module\Users\ProfileEdit\Controller');
        $list->push('App\Vendor\Telenok\Core\Module\Objects\Controller');
        $list->push('App\Vendor\Telenok\Core\Module\Objects\Type\Controller');
        $list->push('App\Vendor\Telenok\Core\Module\Objects\Field\Controller');
        $list->push('App\Vendor\Telenok\Core\Module\Objects\Lists\Controller');
        $list->push('App\Vendor\Telenok\Core\Module\Objects\Version\Controller');
        $list->push('App\Vendor\Telenok\Core\Module\Web\Controller');
        $list->push('App\Vendor\Telenok\Core\Module\Files\Controller');
        $list->push('App\Vendor\Telenok\Core\Module\Files\Browser\Controller');
        $list->push('App\Vendor\Telenok\Core\Module\Web\Page\Controller');
        $list->push('App\Vendor\Telenok\Core\Module\Web\PageConstructor\Controller');
        $list->push('App\Vendor\Telenok\Core\Module\Web\Domain\Controller');
        $list->push('App\Vendor\Telenok\Core\Module\Tools\Controller');
        $list->push('App\Vendor\Telenok\Core\Module\Tools\PhpConsole\Controller');
        $list->push('App\Vendor\Telenok\Core\Module\Tools\DatabaseConsole\Controller');
        $list->push('App\Vendor\Telenok\Core\Module\Packages\Controller');
        $list->push('App\Vendor\Telenok\Core\Module\Packages\ComposerManager\Controller');
        $list->push('App\Vendor\Telenok\Core\Module\Packages\InstallerManager\Controller');
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
        $list->put('system-config', 0);
        $list->put('web-page-constructor', 10);
        $list->put('web-page', 11);
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

        $list->push('App\Vendor\Telenok\Core\Field\IntegerSigned\Controller');
        $list->push('App\Vendor\Telenok\Core\Field\IntegerUnsigned\Controller');
        $list->push('App\Vendor\Telenok\Core\Field\Decimal\Controller');
        $list->push('App\Vendor\Telenok\Core\Field\Text\Controller');
        $list->push('App\Vendor\Telenok\Core\Field\String\Controller');
        $list->push('App\Vendor\Telenok\Core\Field\ComplexArray\Controller');
        $list->push('App\Vendor\Telenok\Core\Field\ComplexData\Controller');
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
        $list->push('App\Vendor\Telenok\Core\Field\Date\Controller');
        $list->push('App\Vendor\Telenok\Core\Field\DateTime\Controller');
        $list->push('App\Vendor\Telenok\Core\Field\TimeRange\Controller');
        $list->push('App\Vendor\Telenok\Core\Field\DateTimeRange\Controller');
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
        app('telenok.repository')->compileRoute();
    }

    public function onCompileConfig(CompileConfig $event)
    {
        app('telenok.repository')->compileConfig();
    }

    public function onNavigoRouter(NavigoRouter $event)
    {
        $list = $event->getList();

        $list->push('Telenok\Core\Event\Subscribers\NavigoRouter@onEventFire');
    }

    public function subscribe($events)
    {
        $this->addListenerRepositoryPackage($events);
        $this->addListenerAclFilterResource($events);
        $this->addListenerModule($events);
        $this->addListenerModuleMenuLeft($events);
        $this->addListenerModuleMenuTop($events);
        $this->addListenerModuleGroup($events);
        $this->addListenerWidget($events);
        $this->addListenerWidgetGroup($events);
        $this->addListenerRepositoryObjectField($events);
        $this->addListenerRepositoryObjectFieldViewModel($events);
        $this->addListenerCompileRoute($events);
        $this->addListenerCompileConfig($events);
        $this->addListenerNavigoRouter($events);
    }

    public function addListenerRepositoryPackage($events)
    {
        $events->listen(
            'Telenok\Core\Event\RepositoryPackage',
            'App\Vendor\Telenok\Core\Event\Subscribe@onRepositoryPackage'
        );
    }

    public function addListenerAclFilterResource($events)
    {
        $events->listen(
            'Telenok\Core\Event\AclFilterResource',
            'App\Vendor\Telenok\Core\Event\Subscribe@onAclFilterResource'
        );
    }

    public function addListenerModule($events)
    {
        $events->listen(
            'Telenok\Core\Event\Module',
            'App\Vendor\Telenok\Core\Event\Subscribe@onModule'
        );
    }

    public function addListenerModuleGroup($events)
    {
        $events->listen(
            'Telenok\Core\Event\ModuleGroup',
            'App\Vendor\Telenok\Core\Event\Subscribe@onModuleGroup'
        );
    }

    public function addListenerModuleMenuLeft($events)
    {
        $events->listen(
            'Telenok\Core\Event\ModuleMenuLeft',
            'App\Vendor\Telenok\Core\Event\Subscribe@onModuleMenuLeft'
        );
    }

    public function addListenerModuleMenuTop($events)
    {
        $events->listen(
            'Telenok\Core\Event\ModuleMenuTop',
            'App\Vendor\Telenok\Core\Event\Subscribe@onModuleMenuTop'
        );
    }

    public function addListenerWidget($events)
    {
        $events->listen(
            'Telenok\Core\Event\Widget',
            'App\Vendor\Telenok\Core\Event\Subscribe@onWidget'
        );
    }

    public function addListenerWidgetGroup($events)
    {
        $events->listen(
            'Telenok\Core\Event\WidgetGroup',
            'App\Vendor\Telenok\Core\Event\Subscribe@onWidgetGroup'
        );
    }

    public function addListenerRepositoryObjectField($events)
    {
        $events->listen(
            'Telenok\Core\Event\RepositoryObjectField',
            'App\Vendor\Telenok\Core\Event\Subscribe@onRepositoryObjectField'
        );
    }

    public function addListenerRepositoryObjectFieldViewModel($events)
    {
        $events->listen(
            'Telenok\Core\Event\RepositoryObjectFieldViewModel',
            'App\Vendor\Telenok\Core\Event\Subscribe@onRepositoryObjectFieldViewModel'
        );
    }

    public function addListenerCompileRoute($events)
    {
        $events->listen(
            'Telenok\Core\Event\CompileRoute',
            'App\Vendor\Telenok\Core\Event\Subscribe@onCompileRoute'
        );
    }

    public function addListenerCompileConfig($events)
    {
        $events->listen(
            'Telenok\Core\Event\CompileConfig',
            'App\Vendor\Telenok\Core\Event\Subscribe@onCompileConfig'
        );
    }

    public function addListenerNavigoRouter($events)
    {
        $events->listen(
            'Telenok\Core\Event\NavigoRouter',
            'App\Vendor\Telenok\Core\Event\Subscribe@onNavigoRouter'
        );
    }
}
