<?php namespace Telenok\Core\Widget\Html;

class Controller extends \Telenok\Core\Interfaces\Widget\Controller {

    protected $key = 'html';
    protected $parent = 'standart';

    public function getFileTemplatePath($model = null)
    {
        return base_path($this->widgetTemplateDirectory) . $model->getKey() . '.html';
    }

	public function getNotCachedContent($model, $structure = null)
	{
        return file_get_contents($this->getFileTemplatePath($model));
	}
}