<?php namespace Telenok\Core\Interfaces\Presentation\Simple;

use \Telenok\Core\Interfaces\Presentation\IPresentation;

class Controller extends \Telenok\Core\Interfaces\Module\Controller implements IPresentation {

    protected $presentation = 'simple';
    protected $presentationView = '';
    protected $presentationContentView = '';
    protected $presentationModuleKey = '';
    protected $tabKey = '';
    protected $additionalViewParam = [];

    public function getPresentation()
    {
        return $this->presentation;
    } 
    
    public function setPresentation($key)
    {
        $this->presentation = $key;
        
        return $this;
    } 
    
    public function getTabKey()
    {
        return $this->tabKey ?: $this->getKey();
    }

    public function setTabKey($key)
    {
        $this->tabKey = $key;
        
        return $this;
    }
    
	public function getGridId($key = 'gridId')
    {
        return "{$this->getPresentation()}-{$this->getTabKey()}-{$key}";
	}
	
	public function getPresentationModuleKey()
    {
        return $this->presentationModuleKey ?: $this->presentation . '-' . $this->getKey();
    }

    public function setPresentationModuleKey($key)
    {
        $this->presentationModuleKey = $key;
        
        return $this;
    }

    public function getPresentationView()
    {
        return $this->presentationView ?: "core::presentation.simple.presentation";
    } 
    
    public function setPresentationView($key)
    {
        $this->presentationView = $key;
        
        return $this;
    } 

    public function getPresentationContentView()
    {
        return $this->presentationContentView ?: "{$this->getPackage()}::module.{$this->getKey()}.content";
    }
    
    public function setPresentationContentView($key)
    {
        $this->presentationContentView = $key;
        
        return $this;
    } 

    public function getAdditionalViewParam()
    {
        return $this->additionalViewParam;
    }    

    public function setAdditionalViewParam($param = [])
    {
		$this->additionalViewParam = $param;
		
		return $this;
    }    

    public function getActionParam()
    { 
        try
        {
            return [
                'presentation' => $this->getPresentation(),
                'presentationModuleKey' => $this->getPresentationModuleKey(),
                'presentationContent' => $this->getPresentationContent(),
                'key' => $this->getKey(),
                'breadcrumbs' => $this->getBreadcrumbs(),
                'pageHeader' => $this->getPageHeader(),
            ];
        }
        catch (\Exception $e)
        {
            return [
                'error' => $e->getMessage(),
            ];
        } 
    }

    public function getPresentationContent()
    {
        return view($this->getPresentationView(), array(
            'presentation' => $this->getPresentation(),
			'presentationModuleKey' => $this->getPresentationModuleKey(),
            'uniqueId' => str_random(),
			'controller' => $this,
            'content' => $this->getContent(),
            'key' => $this->getKey(),
            'breadcrumbs' => $this->getBreadcrumbs(),
            'pageHeader' => $this->getPageHeader(),
        ))->render();
    }

    public function getContent()
    { 
        return view($this->getPresentationContentView(), array_merge([
                'controller' => $this,  
                'uniqueId' => str_random(),
            ], $this->getAdditionalViewParam()))->render();
    }

    public function getModelFieldViewKey($field)
	{
	}
	
	public function getModelFieldView($field)
	{
	}
	
	public function getModelFieldViewVariable($fieldController = null, $model = null, $field = null, $uniqueId = null)
	{
	}
}