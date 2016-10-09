<?php namespace Telenok\Core\Widget\Menu;

/**
 * @class Telenok.Core.Widget.Menu.Controller
 * Class presents menu widget.
 * 
 * @extends Telenok.Core.Abstraction.Widget.Controller
 */
class Controller extends \App\Vendor\Telenok\Core\Abstraction\Widget\Controller {

    /**
     * @protected
     * @property {String} $key
     * Key of widget.
     * @member Telenok.Core.Widget.Menu.Controller
     */
    protected $key = 'menu';
    
    /**
     * @protected
     * @property {String} $parent
     * Parent's widget key.
     * @member Telenok.Core.Widget.Menu.Controller
     */
    protected $parent = 'standart';
    
    /**
     * @protected
     * @property {String} $defaultFrontendView
     * Name of view for fronend if user dont want to create own view.
     * @member Telenok.Core.Widget.Menu.Controller
     */
    protected $defaultFrontendView = "core::widget.menu.widget-frontend";

    /**
     * @protected
     * @property {String} $menuType
     * Menu type code.
     * 1 - tree from defined $nodeIds just one number like page Id
     * 2 - multilevel tree from defined $nodeIds {1,2{3,4,5{33}, 41},99}
     * @member Telenok.Core.Widget.Menu.Controller
     */
    protected $menuType = 1;
    
    /**
     * @protected
     * @property {String} $nodeIds
     * One Id or list Ids. Depend on $menuType.
     * @member Telenok.Core.Widget.Menu.Controller
     */
    protected $nodeIds;
    
    /**
     * @protected
     * @property {mixed} $objectType
     * Id or Code of Object Type for which we create tree.
     * @member Telenok.Core.Widget.Menu.Controller
     */
    protected $objectType = null;

    /**
     * @method setConfig
     * Set config of widget
     * @param {Array} $config
     * @member Telenok.Core.Widget.Menu.Controller
     * @return {Telenok.Core.Widget.Menu.Controller}
     */
    public function setConfig($config = [])
    {
        $config = collect($config)->all();

        parent::setConfig(array_merge($config, [
            'menu_type'     => array_get($config, 'menu_type', $this->menuType),
            'node_ids'      => array_get($config, 'node_ids', $this->nodeIds),
            'object_type'   => array_get($config, 'object_type', $this->objectType),
        ]));

        /*
         * We can restore widget config from cache by cache_key, so set object member value manually
         *
         */
        $this->menuType     = $this->getConfig('menu_type');
        $this->nodeIds      = $this->getConfig('node_ids');
        $this->objectType   = $this->getConfig('object_type');

        return $this;
    }

    /**
     * @method getMenuType
     * Set config of widget.
     * @param {Array} $config
     * @return {Integer}
     * @member Telenok.Core.Widget.Menu.Controller
     */
    public function getMenuType()
    {
        return $this->menuType;
    }

    /**
     * @method getNodeIds
     * Return value of ids to show.
     * @member Telenok.Core.Widget.Menu.Controller
     * @return {String}
     */
    public function getNodeIds()
    {
        return $this->nodeIds;
    }

    /**
     * @method getObjectType
     * Return object type Id or Code.
     * @return {mixed}
     * @member Telenok.Core.Widget.Menu.Controller
     */
    public function getObjectType()
    {
        return $this->objectType;
    }

    /**
     * @method getCacheKey
     * Return cache key and add to it new part of key.
     * @param {String} $additional
     * Additional part of key.
     * @return {String}
     * @member Telenok.Core.Widget.Menu.Controller
     */
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
	
    /**
     * @method getNotCachedContent
     * Return not cached content of widget.
     * @return {String}
     * @member Telenok.Core.Widget.Menu.Controller
     */
	public function getNotCachedContent()
	{
        $ids = [];

        if ($this->menuType == 1)
        {
            $ids = (array)json_decode($this->nodeIds);
        }
        else if ($this->menuType == 2)
        {
            $ids = (array)json_decode($this->nodeIds);
        }

        $class = \App\Vendor\Telenok\Core\Model\Object\Type::where(function($query)
        {
            $query->where('id', $this->objectType);
            $query->orWhere('code', (string)$this->objectType);
        })
        ->active()
        ->first()
        ->class_model;

        $model = app($class);

        $idsArray = array_flatten($ids); 

        array_walk($idsArray, 'trim');

        // replace router_name values in pageId
        $model->where(function($query) use ($idsArray, $model)
        {
            $query->whereIn($model->getTable() . '.id', $idsArray);
            $query->orWhereIn($model->getTable() . '.router_name', $idsArray);
        })->get()->each(function($item) use (&$idsArray)
        {
            $idsArray = array_map(function ($v) use ($item) {
                return $item->router_name === $v ? $item->getKey() : $v;
            }, $idsArray);
        });

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
                    ->get();

        return view($this->getFrontendView(), [
                        'controller' => $this, 
                        'items' => $items,
                        'nodeIds' => $ids,
                        'menu_type' => $this->menuType,
                    ])->render();
	}

    /**
     * @method getTreeList
     * Return array of items ordered by depth and order tree's values.
     * @return {Array}
     * @member Telenok.Core.Widget.Menu.Controller
     */
    public function getTreeList()
    {
        $typeId = $this->getRequest()->input('typeId');

		$term = trim($this->getRequest()->input('term'));

		$return = [];

		$model = app('\App\Vendor\Telenok\Core\Model\Object\Sequence');

        $objectFolderId = \App\Vendor\Telenok\Core\Model\Object\Type::where('code', 'folder')->active()->value('id');

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
                    $query->where(app('db')->raw(1), 0);

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

    /**
     * @method preProcess
     * Hook called before saving widget.
     * @param {Telenok.Core.Model.Web.WidgetOnPage} $model
     * @param {Telenok.Core.Model.Object.Type} $type
     * @param {Illuminate.Support.Collection} $input
     * @return {Telenok.Core.Widget.Menu.Controller}
     * @member Telenok.Core.Widget.Menu.Controller
     */
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

    /**
     * @method validate
     * validate structure data before saving.
     * @param {Telenok.Core.Model.Web.WidgetOnPage} $model
     * @param {Illuminate.Support.Collection} $input
     * @return {Telenok.Core.Widget.Menu.Controller}
     * @member Telenok.Core.Widget.Menu.Controller
     */
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
