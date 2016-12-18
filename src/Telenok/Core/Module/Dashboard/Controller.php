<?php

namespace Telenok\Core\Module\Dashboard;

/**
 * @class Telenok.Core.Module.Dashboard.Controller
 * @extends Telenok.Core.Abstraction.Presentation.Simple.Controller
 */
class Controller extends \Telenok\Core\Abstraction\Presentation\Simple\Controller
{
    protected $key = 'dashboard';
    protected $parent = false;
    protected $group = 'content';
    protected $icon = 'fa fa-tachometer';
}
