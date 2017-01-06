<?php

namespace Telenok\Core\Model\Web;

/**
 * @class Telenok.Core.Model.Web.WidgetOnPage
 * @extends Telenok.Core.Abstraction.Eloquent.Object.Model
 */
class WidgetOnPage extends \App\Vendor\Telenok\Core\Abstraction\Eloquent\Object\Model {

    protected $ruleList = ['title' => ['required', 'min:1']];
    protected $table = 'widget_on_page';

    public function isWidgetLink()
    {
        return !!$this->widget_link_widget_on_page;
    }

    public function widgetPage()
    {
        return $this->belongsTo('\App\Vendor\Telenok\Core\Model\Web\Page', 'widget_page');
    }

    public function widgetLink()
    {
        return $this->hasMany('\App\Vendor\Telenok\Core\Model\Web\WidgetOnPage', 'widget_link_widget_on_page');
    }

    public function widgetLinkWidgetOnPage()
    {
        return $this->belongsTo('\App\Vendor\Telenok\Core\Model\Web\WidgetOnPage', 'widget_link_widget_on_page');
    }

    public function widgetLanguageLanguage()
    {
        return $this->belongsTo('\App\Vendor\Telenok\Core\Model\System\Language', 'widget_language_language');
    }

    public function preProcess($type, $input)
    {
        if ($widget = app('telenok.repository')->getWidget()->get($input->get('key')))
        {
            $widget->validate($this, $input);
        }
        else
        {
            throw new \Telenok\Core\Support\Exception\Validator(['Please, set linked widget']);
        }

        return parent::preProcess($type, $input);
    }

    public function delete()
    {
        $key = $this->key;

        parent::delete();

        if ($this->forceDeleting)
        {
            app('telenok.repository')->getWidget()->get($key)->delete($this);
        }
    }
}
