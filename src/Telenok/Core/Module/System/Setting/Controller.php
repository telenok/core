<?php

namespace Telenok\Core\Module\System\Setting;

class Controller extends \Telenok\Core\Interfaces\Presentation\TreeTabObject\Controller {

	protected $key = 'system-setting';
    protected $presentation = 'tree-tab-object';
    protected $presentationFormFieldListView = 'core::module.setting.form-field-list'; 
    protected $modelListClass = '\App\Model\Telenok\System\Setting';

}

