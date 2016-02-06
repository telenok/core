<?php

namespace Telenok\Core\Model\Web;

/**
 * @class Telenok.Core.Model.Web.WidgetGroup
 * @extends Telenok.Core.Interfaces.Eloquent.Object.Model
 */
class WidgetGroup extends \App\Telenok\Core\Interfaces\Eloquent\Object\Model {

    protected $ruleList = ['title' => ['required', 'min:1']];
    protected $table = 'widget_group';

}
