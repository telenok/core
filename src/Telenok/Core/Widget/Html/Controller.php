<?php namespace Telenok\Core\Widget\Html;

class Controller extends \Telenok\Core\Interfaces\Widget\Controller {

    protected $key = 'html';
    protected $parent = 'standart';

	public function getContent($structure = null)
	{
        if (!($model = $this->getWidgetModel()))
        {
            return;
        }
        
        $structure = $structure === null ? $model->structure : $structure;
        
        $this->setCacheTime($model->cache_time);

        if (($content = $this->getCachedContent()) !== false)
        {
            return $content;
        }

        $content = view('widget.' . $model->getKey(), ['controller' => $this, 'frontendController' => $this->getFrontendController()])->render();

        $this->setCachedContent($content);

        return $content;
	}
}