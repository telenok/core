<?php namespace Telenok\Core\Widget\Html;

class Controller extends \Telenok\Core\Interfaces\Widget\Controller {

    protected $key = 'html';
    protected $parent = 'standart';

	public function getNotCachedContent()
	{
        if ($t = $this->getFileTemplatePath())
        {
            return file_get_contents($t);
        }
	}
}