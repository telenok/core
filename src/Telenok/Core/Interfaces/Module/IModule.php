<?php

namespace Telenok\Core\Interfaces\Module;

/**
 * @class Telenok.Core.Interfaces.Module.IModule
 */
interface IModule extends \Telenok\Core\Interfaces\Support\IRequest {

    public function getName();

    public function getHeader();

    public function getHeaderDescription();

    public function setKey($key);

    public function getKey();

    public function setPermissionKey($key = '');

    public function getPermissionKey();

    public function getParent();

    public function getIcon();

    public function getActionParam();

    public function getBreadcrumbs();

    public function getPageHeader();
}
