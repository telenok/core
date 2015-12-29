<?php namespace Telenok\Core\Widget\Menu;

class Controller extends \App\Telenok\Core\Interfaces\Widget\Controller {

    protected $key = 'menu';
    protected $parent = 'standart';
	protected $defaultFrontendView = "core::widget.menu.widget-frontend";

    protected $menuType = 'root';
    protected $nodeIds = [];
    protected $objectType = null;

    public function setConfig($config = [])
    {
        parent::setConfig($config);

        if ($m = $this->getWidgetModel())
        {
            $structure = $m->structure;

            $this->menuType = array_get($structure, 'menu_type');
            $this->nodeIds = array_get($structure, 'node_ids');
            $this->objectType = array_get($structure, 'object_type');
        }
        else 
        {
            $this->menuType = $this->getConfig('menu_type', $this->cacheTime);
            $this->nodeIds = $this->getConfig('node_ids', $this->nodeIds);
            $this->objectType = $this->getConfig('object_type', $this->objectType);
        }

        return $this;
    }

    public function getMenuType()
    {
        return $this->menuType;
    }

    public function getNodeIds()
    {
        return $this->nodeIds;
    }

    public function getObjectType()
    {
        return $this->objectType;
    }

	public function getCacheKey($additional = '')
	{
        if ($key = parent::getCacheKey($additional))
        {
            return $key . $this->getMenuType() . $this->getNodeIds();
        }
        else
        {
            return false;
        }
	}

	public function getNotCachedContent()
	{
        $ids = [];

        if ($this->menuType == 1)
        {
            $ids = (array)json_decode('[' . $this->nodeIds . ']'); 
        }
        else if ($this->menuType == 2)
        {
            $ids = str_replace('{', ',[', $this->nodeIds);
            $ids = str_replace('}', ']', $ids);
            $ids = (array)json_decode('[' . $ids . ']');
        }

        $class = \App\Telenok\Core\Model\Object\Type::where(function($query)
        {
            $query->where('id', $this->objectType);
            $query->orWhere('code', $this->objectType);
        })
        ->active()
        ->first()
        ->class_model;

        $model = app($class);

        $idsArray = array_flatten($ids); 

        array_walk($idsArray, 'trim');
        array_walk($idsArray, 'intval');

        $items = $model::withTreeAttr()
                    ->withPermission()
                    ->withChildren(100)
                    ->active()
                    ->where(function($query) use ($idsArray, $model)
                    {
                        if ($this->menuType == 1)
                        {
                            $query->whereIn($model->getTable() . '.id', $idsArray);
                            $query->orWhereIn('pivot_tree_children.tree_pid', $idsArray);
                        }
                        else
                        {
                            $query->whereIn($model->getTable() . '.id', $idsArray);
                        }
                    })
                    ->orderBy(app('db')->raw('FIELD(' . $model->getTable() . '.id, "' . implode('", "', $idsArray) . '")'))
                    ->orderBy('pivot_tree_children.tree_depth')
                    ->orderBy('pivot_tree_children.tree_depth')
                    ->get();

        return view($this->getFrontendView(), [
                        'controller' => $this, 
                        'items' => $items,
                        'nodeIds' => $ids,
                        'menu_type' => $this->menuType,
                    ])->render();
	}

    public function getTreeList()
    {
        $typeId = $this->getRequest()->input('typeId');

		$term = trim($this->getRequest()->input('term'));

		$return = [];

		$model = app('\App\Telenok\Core\Model\Object\Sequence');

        $objectFolderId = \App\Telenok\Core\Model\Object\Type::where('code', 'folder')->active()->pluck('id');

        $query = $model::whereIn('sequences_object_type', [$objectFolderId, $typeId])
            ->withPermission()
            ->withTreeAttr()
			->join('object_translation', function($join) use ($model)
			{
				$join->on($model->getTable() . '.id', '=', 'object_translation.translation_object_model_id');
			})
			->where(function($query) use ($term, $model)
			{
				if (trim($term))
				{
					collect(explode(' ', $term))
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

		$query->groupBy($model->getTable() . '.id')
                ->orderBy('tree_depth')
                ->orderBy('tree_order')->get()->each(function($item) use (&$return)
		{
			$return[] = [
                            'id' => $item->id, 
                            'title'  => $item->translate('title'),
                            'pid' => $item->tree_pid,
                            'depth' => $item->tree_depth
                        ];
		});

		return $return;
    }

    public function preProcess($model, $type, $input)
    { 
        $structure = $input->get('structure');

        $ids = trim(array_get($structure, 'node_ids', ''));

        if ($ids)
        {
            $ids = preg_replace('/\s+/', '', $ids);

            $structure['node_ids'] = $ids;
        }

        $input->put('structure', $structure);

        return parent::preProcess($model, $type, $input);
    }

	public function validate($model = null, $input = [])
	{
        if (!$model->exists)
        {
            return;
        }

        $structure = $input->get('structure');
        
        $ids = trim(array_get($structure, 'node_ids'));
        $type = trim(array_get($structure, 'menu_type'));

        if ($ids)
        {
            if ($type == 1)
            {
                $ids = preg_replace('/\s+/', '', $ids);

                if (preg_match('/[^\d,]+/', $ids) !== 0)
                {
                    throw new \Exception($this->LL('error.menu.type.1.node_ids'));
                }
            }
            else if ($type == 2)
            {
                $ids = preg_replace('/\s+/', '', $ids);
                $ids = str_replace('{', ',[', $ids);
                $ids = str_replace('}', ']', $ids);

                if (json_decode('[' . $ids . ']') === null)
                {
                    throw new \Exception($this->LL('error.menu.type.2.node_ids'));
                }
            }
        }
	}
}