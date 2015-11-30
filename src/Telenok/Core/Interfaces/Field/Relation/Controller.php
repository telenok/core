<?php

namespace Telenok\Core\Interfaces\Field\Relation;

class Controller extends \Telenok\Core\Interfaces\Field\Controller {

	protected static $macroFile = 'Model/macro.php';

	public static function readMacroFile()
	{
		$path = app_path(static::$macroFile);
		
		if (!file_exists($path))
		{
			file_put_contents($path, '<?php ' . PHP_EOL . PHP_EOL, LOCK_EX);
		}
		
		require $path;
	}

	public function getLinkedField($field)
	{
	}

	public function getChooseTypeId($field)
	{
		return $field->{$this->getLinkedField($field)};
	}

	public function getModelAttribute($model, $key, $value, $field)
	{
		return $value;
	}

	public function validateExistsInputField($input, $param = [])
	{
		foreach ((array) $param as $p)
		{
			if ($input->get($p))
			{
				return;
			}
		}

		throw new \Exception('Please, define one or more keys "' . implode('", "', (array) $param) 
                . '" for object_field "' . $input->get('code') . '"'
                . ' and object_type "' . $input->get('field_object_type') . '"'
                );
	}

	public function getTitleList($id = null, $closure = null)
	{
		$term = trim($this->getRequest()->input('term'));
		$return = [];

		$class = \App\Telenok\Core\Model\Object\Sequence::getModel($id)->class_model;

		$model = app($class);

		$query = $model::withPermission()
			->join('object_translation', function($join) use ($model)
			{
				$join->on($model->getTable() . '.id', '=', 'object_translation.translation_object_model_id');
			})
			->where(function($query) use ($term, $model)
			{
				if (trim($term))
				{
					\Illuminate\Support\Collection::make(explode(' ', $term))
					->reject(function($i)
					{
						return !trim($i);
					})
					->each(function($i) use ($query)
					{
						$query->orWhere('object_translation.translation_object_string', 'like', "%{$i}%");
					});

					$query->orWhere($model->getTable() . '.id', (int) $term);
				}
			});

		if ($closure instanceof \Closure)
		{
			$closure($query);
		}
			
		$query->take(20)->groupBy($model->getTable() . '.id')->get()->each(function($item) use (&$return)
		{
			$return[] = ['value' => $item->id, 'text' => "[{$item->id}] " . $item->translate('title')];
		});

		return $return;
	}

	public function getListButtonExtended($item, $field, $type, $uniqueId, $canUpdate)
	{
		return '<div class="hidden-phone visible-lg btn-group">
                    <button class="btn btn-minier btn-info" title="' . $this->LL('list.btn.edit') . '" 
                        onclick="editTableRow' . $field->code . $uniqueId . '(this, \'' . route($this->getRouteWizardEdit(), ['id' => $item->getKey(), 'saveBtn' => 1, 'chooseBtn' => 0]) . '\'); return false;">
                        <i class="fa fa-pencil"></i>
                    </button>

                    <button class="btn btn-minier btn-light" onclick="return false;" title="' . $this->LL('list.btn.' . ($item->active ? 'active' : 'inactive')) . '">
                        <i class="fa fa-check ' . ($item->active ? 'green' : 'white') . '"></i>
                    </button>
                    ' .
				($canUpdate ? '
                    <button class="btn btn-minier btn-danger trash-it" title="' . $this->LL('list.btn.delete') . '" 
                        onclick="deleteTableRow' . $field->code . $uniqueId . '(this); return false;">
                        <i class="fa fa-trash-o"></i>
                    </button>' : ''
				) . '
                </div>';
	}

	public function getListFieldContent($field, $item, $type = null)
	{
		$items = [];
		$rows = \Illuminate\Support\Collection::make($this->getListFieldContentItems($field, $item, $type));

		if ($rows->count())
		{
			foreach ($rows->slice(0, 7, TRUE) as $row)
			{
				$items[] = \Str::limit($row->translate('title'), 20);
			}

			return e('"' . implode('", "', $items) . '"' . (count($rows) > 7 ? ', ...' : ''));
		}
	}

	public function getListFieldContentItems($field, $item, $type = null)
	{
		return $item->{camel_case($field->code)}()->take(8)->get();
	}

    public function schemeCreateExtraField($table, $p1 = null, $p2 = null, $p3 = null, $p4 = null, $p5 = null)
    {   
    }
}