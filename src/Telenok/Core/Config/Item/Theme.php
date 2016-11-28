<?php

namespace Telenok\Core\Config\Item;

class Theme extends \Telenok\Core\Abstraction\Config\Controller
{
    protected $key = 'telenok-view-theme';

    public function getValueContent($controller, $model, $field, $uniqueId)
    {
        return view($this->getValueContentView(), [
            'parentController' => $controller,
            'controller' => $this,
            'model' => $model,
            'field' => $field,
            'uniqueId' => $uniqueId
        ])->render();
    }
}
