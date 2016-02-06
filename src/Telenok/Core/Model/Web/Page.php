<?php

namespace Telenok\Core\Model\Web;

/**
 * @class Telenok.Core.Model.Web.Page
 * @extends Telenok.Core.Interfaces.Eloquent.Object.Model
 */
class Page extends \App\Telenok\Core\Interfaces\Eloquent\Object\Model {

    protected $ruleList = ['title' => ['required', 'min:1']];
    protected $table = 'page';

    public function pagePageController()
    {
        return $this->belongsTo('\App\Telenok\Core\Model\Web\PageController', 'page_page_controller');
    }

    public function widget()
    {
        return $this->hasMany('\App\Telenok\Core\Model\Web\WidgetOnPage', 'widget_page');
    }

    public function pageDomain()
    {
        return $this->belongsToMany('\App\Telenok\Core\Model\Web\Domain', 'pivot_relation_m2m_page_domain', 'page', 'page_domain')->withTimestamps();
    }

}
