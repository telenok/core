<?php

namespace Telenok\Core\Interfaces\Presentation;

interface IPresentation extends \Telenok\Core\Interfaces\Module\IModule {
    
    public function getPresentation();
    
    public function setPresentation($key);

    public function getPresentationView();
    
    public function setPresentationView($key);

    public function getPresentationContentView();
    
    public function setPresentationContentView($key);

    public function getPresentationContent();

    public function getContent();
}

