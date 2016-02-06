<?php

namespace Telenok\Core\Support\Image;

/**
 * @class Telenok.Core.Support.Image.Cropper
 * Class for https://github.com/fengyuanchen/cropper.
 */
class Cropper extends \App\Telenok\Core\Controller\Backend\Controller {

    protected $key = 'cropper';
    protected $path;
    protected $allowNew = true;
    protected $allowBlob = true;
    protected $view = 'core::special.cropper.modal';
    protected $languageDirectory = 'support';
    protected $jsUnique;

    public function setView($param)
    {
        $this->view = $param;

        return $this;
    }

    public function getView()
    {
        return $this->view;
    }

    public function setJsUnique($param)
    {
        $this->jsUnique = $param;

        return $this;
    }

    public function getJsUnique()
    {
        return $this->jsUnique;
    }

    public function setPath($param)
    {
        $this->path = $param;

        return $this;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function setAllowNew($param)
    {
        $this->allowNew = $param;

        return $this;
    }

    public function getAllowNew()
    {
        return $this->allowNew;
    }

    public function setAllowBlob($param)
    {
        $this->allowBlob = $param;

        return $this;
    }

    public function getAllowBlob()
    {
        return $this->allowBlob;
    }

    public function getContent()
    {
        $url = $this->getPath() ? : 'clear.gif';
        $jsUnique = $this->getJsUnique();

        return view($this->getView(), [
            'controller' => $this,
            'path' => $url,
            'allowNew' => $this->getAllowNew(),
            'allowBlob' => $this->getAllowBlob(),
            'jsUnique' => $jsUnique,
        ]);
    }

}
