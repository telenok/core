<?php

namespace Telenok\Core\Model\Web;

/**
 * @class Telenok.Core.Model.Web.PageController
 * @extends Telenok.Core.Abstraction.Eloquent.Object.Model
 */
class PageController extends \App\Vendor\Telenok\Core\Abstraction\Eloquent\Object\Model {

    protected $ruleList = ['title' => ['required', 'min:1']];
    protected $table = 'page_controller';

    public function page()
    {
        return $this->hasMany('\App\Vendor\Telenok\Core\Model\Web\Page', 'page_page_controller');
    }

}
