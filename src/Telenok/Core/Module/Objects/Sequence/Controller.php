<?php

namespace Telenok\Core\Module\Objects\Sequence;

/**
 * @class Telenok.Core.Module.Objects.Sequence.Controller
 * @extends Telenok.Core.Abstraction.Presentation.TreeTabObject.Controller
 */
class Controller extends \Telenok\Core\Abstraction\Presentation\TreeTabObject\Controller {

    protected $key = 'objects-sequence';
    protected $parent = 'objects';
    protected $modelListClass = '\App\Telenok\Core\Model\Object\Sequence';
    protected $presentation = 'tree-tab-object';
    protected $presentationView = 'core::module.objects-sequence.presentation';

    public function getAdditionalViewParam()
    {
        $this->additionalViewParam['search'] = $this->getRequest()->input('search.value');

        return $this->additionalViewParam;
    }

}
