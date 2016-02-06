<?php

namespace Telenok\Core\Model\Web;

/**
 * @class Telenok.Core.Model.Web.WidgetOnPage
 * @extends Telenok.Core.Interfaces.Eloquent.Object.Model
 */
class WidgetOnPage extends \App\Telenok\Core\Interfaces\Eloquent\Object\Model {

    protected $ruleList = ['title' => ['required', 'min:1']];
    protected $table = 'widget_on_page';

    public function isWidgetLink()
    {
        return !!$this->widget_link_widget_on_page;
    }

    public function widgetPage()
    {
        return $this->belongsTo('\App\Telenok\Core\Model\Web\Page', 'widget_page');
    }

    public function widgetLink()
    {
        return $this->hasMany('\App\Telenok\Core\Model\Web\WidgetOnPage', 'widget_link_widget_on_page');
    }

    public function widgetLinkWidgetOnPage()
    {
        return $this->belongsTo('\App\Telenok\Core\Model\Web\WidgetOnPage', 'widget_link_widget_on_page');
    }

    public function widgetLanguageLanguage()
    {
        return $this->belongsTo('\App\Telenok\Core\Model\System\Language', 'widget_language_language');
    }

    public function preProcess($type, $input)
    {
        app('telenok.config.repository')->getWidget()->get($input->get('key'))->validate($this, $input);

        return parent::preProcess($type, $input);
    }

    public function delete()
    {
        $key = $this->key;

        parent::delete();

        if ($this->forceDeleting)
        {
            app('telenok.config.repository')->getWidget()->get($key)->delete($this);
        }
    }

}
