<?php namespace Telenok\Core\Interfaces\Presentation;

interface IPresentation extends \Telenok\Core\Interfaces\Module\IModule {
    
    public function getPresentation();
    
    public function setPresentation($key);

    public function getPresentationView();
    
    public function setPresentationView($key);

    public function getPresentationContentView();
    
    public function setPresentationContentView($key);

    public function getPresentationContent();

    public function getContent();
	
    public function getModelFieldViewKey($field);
	
	public function getModelFieldView($field);

	public function getModelFieldViewVariable($fieldController = null, $model = null, $field = null, $uniqueId = null);
}

