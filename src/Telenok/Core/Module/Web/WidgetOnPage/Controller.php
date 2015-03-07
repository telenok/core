<?php

namespace Telenok\Core\Module\Web\WidgetOnPage;

class Controller extends \Telenok\Core\Interfaces\Presentation\TreeTabObject\Controller {

	protected $key = 'web-page-wop';
    protected $presentation = 'tree-tab-object';
    protected $modelListClass = '\App\Model\Telenok\Web\WidgetOnPage';
    protected $presentationFormFieldListView = 'core::module.web-page-wop.form-field-list';
    protected $presentationModuleKey = 'web-page-widget-web-page-constructor';

    public function getGridId($key = 'gridId')
    {
        return "{$this->getPresentation()}-{$this->getTabKey()}-{$this->getTypeList()->code}";
    }  
        
    public function preProcess($model, $type, $input)
    { 
        if ($input->get('key'))
        {
            app('telenok.config')->getWidget()->get($input->get('key'))->preProcess($model, $type, $input);
        }
        
        return parent::postProcess($model, $type, $input);
    }
	
    public function postProcess($model, $type, $input)
    { 
        if ($input->get('key'))
        {
            \File::makeDirectory(base_path("resources/views/widget/"), 0777, true, true);

            $templateFile = base_path("resources/views/widget/") . $model->getKey() . '.blade.php';

            if ($t = trim($input->get('template_content')))
            {
                $viewContent = $t;
            }
            else
            {
                $viewContent = app('telenok.config')->getWidget()->get($input->get('key'))->getViewContent();
            }
             
            \File::put($templateFile, $viewContent);
            
            app('telenok.config')->getWidget()->get($input->get('key'))->postProcess($model, $type, $input);
        }
        
        return parent::postProcess($model, $type, $input);
    }
    
}

