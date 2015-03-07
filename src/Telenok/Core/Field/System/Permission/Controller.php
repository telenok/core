<?php

namespace Telenok\Core\Field\System\Permission;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Controller extends \Telenok\Core\Interfaces\Field\Controller {

	protected $key = 'permission';
    protected $allowMultilanguage = false; 
	
	public function getTitleList($id = null)
	{
		$term = trim($this->getRequest()->input('term'));
		$return = [];

		$sequence = new \App\Model\Telenok\Object\Sequence();

		$sequenceTable = $sequence->getTable();
		$typeTable = (new \App\Model\Telenok\Object\Type())->getTable();

		$sequence->addMultilanguage('title_type');

		try
		{
			\App\Model\Telenok\Object\Sequence::select($sequenceTable . '.id', $sequenceTable . '.title', $typeTable . '.title AS title_type')
					->join($typeTable, function($join) use ($sequenceTable, $typeTable)
					{
						$join->on($sequenceTable . '.sequences_object_type', '=', $typeTable . '.id');
					})
					->where(function ($query) use ($sequenceTable, $typeTable, $term)
					{
						$query->where($sequenceTable . '.id', $term);

						$query->orWhere(function ($query) use ($sequenceTable, $term)
						{
							\Illuminate\Support\Collection::make(explode(' ', $term))
									->reject(function($i) { return !trim($i); })
									->each(function($i) use ($query, $sequenceTable)
							{
								$query->where($sequenceTable . '.title', 'like', "%{$i}%");
							});
						});

						$query->orWhere(function ($query) use ($typeTable, $term)
						{
							\Illuminate\Support\Collection::make(explode(' ', $term))
									->reject(function($i) { return !trim($i); })
									->each(function($i) use ($query, $typeTable)
							{
								$query->where($typeTable . '.title', 'like', "%{$i}%");
							});
						});
					})
					->take(20)->get()->each(function($item) use (&$return)
			{
				$return[] = ['value' => $item->id, 'text' => "[{$item->translate('title_type')}#{$item->id}] " . $item->translate('title')];
			});
		}
		catch (\Exception $e)
		{
			echo $e;
		}

		return $return;
	}

	public function preProcess($model, $type, $input)
	{  
		$input->put('title', ['en' => 'Permission']);
		$input->put('title_list', ['en' => 'Permission']);
		$input->put('code', 'permission');
		$input->put('active', 1);
		$input->put('multilanguage', 0);
		$input->put('show_in_list', 0);
		$input->put('show_in_form', 1);
		$input->put('allow_search', 1);
		$input->put('allow_create', 1);
		$input->put('allow_update', 1); 
		$input->put('field_order', $input->get('field_order', 3)); 

		if (!$input->get('field_object_tab'))
		{
			$input->put('field_object_tab', 'additionally');
		}
		
		$tab = $this->getFieldTab($input->get('field_object_type'), $input->get('field_object_tab'));

		$input->put('field_object_tab', $tab->getKey());  

		return parent::preProcess($model, $type, $input);
	}

	public function getFormModelContent($controller = null, $model = null, $field = null, $uniqueId = null)
	{
		$permissions = \App\Model\Telenok\Security\Permission::active()->get();
        $this->setViewModel($field);

		return view($this->getViewModel(), array(
					'parentController' => $controller,
					'controller' => $this,
					'model' => $model,
					'field' => $field,
					'uniqueId' => $uniqueId,
					'permissions' => $permissions,
					'permissionCreate' => \Auth::can('create', 'object_field.' . $model->getTable() . '.' . $field->code),
					'permissionUpdate' => \Auth::can('update', 'object_field.' . $model->getTable() . '.' . $field->code),
				))->render();
	}

    public function setModelAttribute($model, $key, $value, $field) {}
	public function getModelAttribute($model, $key, $value, $field) {}
	
	public function saveModelField($field, $model, $input)
	{ 
		$permissionList = (array)$input->get('permission', []);

		\Telenok\Core\Security\Acl::resource($model)->unsetPermission();
        
		foreach($permissionList as $permissionCode => $persmissionIds)
		{
			if (!empty($persmissionIds))
			{
				foreach($persmissionIds as $id)
				{
					\Telenok\Core\Security\Acl::subject($id)->setPermission($permissionCode, $model);
				}
			}
		}
		
		return $model;
	}

	public function getListFieldContent($field, $item, $type = null)
	{
		$items = [];
		$rows = \Illuminate\Support\Collection::make(\App\Model\Telenok\Security\Permission::take(8)->get());

		if ($rows->count())
		{
			foreach ($rows->slice(0, 7, TRUE) as $row)
			{
				$items[] = $row->translate('title');
			}

			return '"' . implode('", "', $items) . '"' . (count($rows) > 7 ? ', ...' : '');
		}
	}
    
    public function getFilterContent($field = null)
    {
        return view($this->getViewFilter(), [
            'controller' => $this,
            'field' => $field,
            'permissions' => \App\Model\Telenok\Security\Permission::active()->get(),
        ]);
    }

    public function getFilterQuery($field = null, $model = null, $query = null, $name = null, $value = null) 
    {
		if ($value !== null)
		{
            $sequence = new \App\Model\Telenok\Object\Sequence();
            $spr = new \App\Model\Telenok\Security\SubjectPermissionResource();
            $type = new \App\Model\Telenok\Object\Type();

            foreach((array)$value as $permissionId => $ids)
            {
                $query->join($sequence->getTable() . ' AS sequence_filter_' . $permissionId, function($query) use ($permissionId, $model) 
                {
                    $query->on($model->getTable() . '.id', '=', 'sequence_filter_' . $permissionId . '.id');
                })
                ->join($spr->getTable() . ' AS spr_filter_' . $permissionId, function($query) use ($permissionId) 
                {
                    $query->on('sequence_filter_' . $permissionId . '.id', '=', 'spr_filter_' . $permissionId . '.acl_resource_object_sequence');
                })
                ->join($type->getTable() . ' AS type_filter_' . $permissionId, function($query) use ($permissionId) 
                {
                    $query->on('sequence_filter_' . $permissionId . '.sequences_object_type', '=', 'type_filter_' . $permissionId . '.id');
                })
                ->active('spr_filter_' . $permissionId)
                ->active('type_filter_' . $permissionId)
                ->whereIn('spr_filter_' . $permissionId . '.acl_subject_object_sequence', (array)$ids)
                ->where('spr_filter_' . $permissionId . '.acl_permission_object_sequence', $permissionId);
            }
		}
    } 
}