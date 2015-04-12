<?php namespace Telenok\Core\Model\Web;

class Domain extends \Telenok\Core\Interfaces\Eloquent\Object\Model {

	protected $ruleList = ['title' => ['required', 'min:1'], 'domain' => ['required', 'min:1']];
	protected $table = 'domain';

    public function page()
    {
        return $this->hasMany('\App\Telenok\Core\Model\Web\Page', 'page_domain');
    }
}

