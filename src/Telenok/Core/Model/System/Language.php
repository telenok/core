<?php

namespace Telenok\Core\Model\System;

class Language extends \Telenok\Core\Interfaces\Eloquent\Object\Model {

	protected $guarded = [];
	protected $table = 'language';
	protected $ruleList = ['title' => ['required', 'min:1'], 'locale' => ['required', 'unique:language,locale,:id:,id']];



    public function widgetLanguage()
    {
        return $this->hasMany('\App\Model\Telenok\Web\WidgetOnPage', 'widget_language_language');
    } 
} 