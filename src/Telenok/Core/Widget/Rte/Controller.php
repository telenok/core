<?php namespace Telenok\Core\Widget\Rte;

class Controller extends \Telenok\Core\Interfaces\Widget\Controller {

    protected $key = 'rte';
    protected $parent = 'standart';

	public function getNotCachedContent()
	{
        if ($t = $this->getFileTemplatePath())
        {
            return \File::get($t);
        }
	}
}