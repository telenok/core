<?php

namespace Telenok\Core\Contract\Module;

/**
 * @class Telenok.Core.Contract.Module.Module
 */
interface Module extends \Telenok\Core\Contract\Injection\Request {

    public function getName();

    public function getOrder();

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

    public function getNavigoRouterCode();
}
