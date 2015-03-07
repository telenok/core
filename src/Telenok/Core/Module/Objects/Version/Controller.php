<?php

namespace Telenok\Core\Module\Objects\Version;

class Controller extends \Telenok\Core\Interfaces\Presentation\TreeTabObject\Controller { 

    protected $key = 'objects-version';
    protected $parent = 'objects';

    protected $modelListClass = '\App\Model\Telenok\Object\Version';

    protected $presentation = 'tree-tab-object';
    protected $presentationModelView = 'core::module.objects-version.model';
    protected $presentationView = 'core::module.objects-version.presentation';

    public function save($input = null, $type = null)
    {   
        $input = $input instanceof  \Illuminate\Support\Collection ? $input : \Illuminate\Support\Collection::make((array)$input);
		$model = \App\Model\Telenok\Object\Version::findOrFail($input->get('id'));
		
		try
		{
			return \App\Model\Telenok\Object\Version::toRestore($model);
		} 
		catch (\Telenok\Core\Interfaces\Exception\ObjectTypeNotFound $ex) 
		{
			throw new \Exception($this->LL('error.restore.type.first', ['id' => $model->object_type_id]));
		}
		
		return $model;
	}

}