<?php

namespace Telenok\Core\Model\Web;

class Page extends \Telenok\Core\Interfaces\Eloquent\Object\Model {

	protected $ruleList = ['title' => ['required', 'min:1']];
	protected $table = 'page';  
    
	public function pagePageController()
	{
		return $this->belongsTo('\App\Model\Telenok\Web\PageController', 'page_page_controller');
	}

	public function widget()
	{
		return $this->hasMany('\App\Model\Telenok\Web\WidgetOnPage', 'widget_page');
	}
	
    public function pageDomain()
    {
        return $this->belongsTo('\App\Model\Telenok\Web\Domain', 'page_domain');
    } 
     
} 