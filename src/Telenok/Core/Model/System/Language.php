<?php

namespace Telenok\Core\Model\System;

/**
 * @class Telenok.Core.Model.System.Language
 * @extends Telenok.Core.Abstraction.Eloquent.Object.Model
 */
class Language extends \App\Telenok\Core\Abstraction\Eloquent\Object\Model {

    protected $guarded = [];
    protected $table = 'language';
    protected $ruleList = ['title' => ['required', 'min:1'], 'locale' => ['required', 'unique:language,locale,:id:,id']];

    public function widgetLanguage()
    {
        return $this->hasMany('\App\Telenok\Core\Model\Web\WidgetOnPage', 'widget_language_language');
    }

}
