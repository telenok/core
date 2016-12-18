<?php

namespace Telenok\Core\WidgetGroup\Standart;

/**
 * @class Telenok.Core.WidgetGroup.Standart.Controller
 * Standart widget's group.
 * @extends Telenok.Core.Abstraction.Widget.Group.Controller
 */
class Controller extends \Telenok\Core\Abstraction\Widget\Group\Controller
{
    /**
     * @protected
     *
     * @property {String} $key
     * Key for widget group.
     * @member Telenok.Core.WidgetGroup.Standart.Controller
     */
    protected $key = 'standart';

    /**
     * @protected
     *
     * @property {String} $icon
     * Icon for widget group.
     * @member Telenok.Core.WidgetGroup.Standart.Controller
     */
    protected $icon = 'fa fa-signal';
}
