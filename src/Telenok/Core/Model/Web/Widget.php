<?php

namespace Telenok\Core\Model\Web;

/**
 * @class Telenok.Core.Model.Web.Widget
 * @extends Telenok.Core.Abstraction.Eloquent.Object.Model
 */
class Widget extends \App\Telenok\Core\Abstraction\Eloquent\Object\Model {

    protected $ruleList = ['title' => ['required', 'min:1']];
    protected $table = 'widget';

}
