<?php

namespace Telenok\Core\Abstraction\Presentation\Simple;

use \Telenok\Core\Contract\Presentation\Presentation;

/**
 * @class Telenok.Core.Abstraction.Presentation.Simple.Controller
 * Base controller for presentation "simple".
 * @extends Telenok.Core.Abstraction.Module.Controller
 */
abstract class Controller extends \Telenok\Core\Abstraction\Module\Controller implements Presentation {

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
        return $this->tabKey ? : $this->getKey();
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
        return $this->presentationModuleKey ? : $this->presentation . '-' . $this->getKey();
    }

    public function setPresentationModuleKey($key)
    {
        $this->presentationModuleKey = $key;

        return $this;
    }

    public function getPresentationView()
    {
        return $this->presentationView ? : "core::presentation.simple.presentation";
    }

    public function setPresentationView($key)
    {
        $this->presentationView = $key;

        return $this;
    }

    public function getPresentationContentView()
    {
        return $this->presentationContentView ? : "{$this->getPackage()}::module.{$this->getKey()}.content";
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
                'content' => $this->getContent(),
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

    public function getFormModelViewVariable($fieldController = null, $model = null, $field = null, $uniqueId = null)
    {
        
    }

    public function setDisplayType($type)
    {
        
    }

    public function create()
    {
        
    }

    public function edit($id = null)
    {
        
    }

    public function store($id = null)
    {
        
    }

    public function update($id = null)
    {
        
    }

    public function save($input = [], $type = null)
    {
        
    }

    public function getListItem($model = null)
    {
        
    }

}
