<?php

namespace Telenok\Core\Model\Web;

/**
 * @class Telenok.Core.Model.Web.WidgetGroup
 * @extends Telenok.Core.Abstraction.Eloquent.Object.Model
 */
class WidgetGroup extends \App\Vendor\Telenok\Core\Abstraction\Eloquent\Object\Model {

    protected $ruleList = ['title' => ['required', 'min:1']];
    protected $table = 'widget_group';

}
