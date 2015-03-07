<?php

namespace Telenok\Core\Module\Web\Page;

class Controller extends \Telenok\Core\Interfaces\Presentation\TreeTabObject\Controller {

	protected $key = 'web-page';
	protected $parent = 'web';

    protected $modelListClass = '\App\Model\Telenok\Web\Page';
    protected $modelTreeClass = '\App\Model\Telenok\Web\Page';
    
    protected $presentation = 'tree-tab-object';

    public function getListItem($model)
    {
        $query = $model::withTreeAttr()->withPermission()->where(function($query) use ($model)
        {
            if (!$this->getRequest()->input('multifield_search', false) && ($treeId = $this->getRequest()->input('treeId', 0)))
            { 
                $query->where(function($query) use ($model, $treeId)
                    {
                        $query->where('pivot_tree_attr.tree_id', $treeId);
                        $query->orWhere('pivot_tree_attr.tree_pid', $treeId);
                    });
            }
        })->select($model->getTable() . '.*');

        $this->getFilterQuery($model, $query); 

        return $query->groupBy($model->getTable() . '.id')->orderBy($model->getTable() . '.updated_at', 'desc')->skip($this->getRequest()->input('iDisplayStart', 0))->take($this->displayLength + 1);
    }

    public function postProcess($model, $type, $input)
    { 
        \Event::fire('telenok.compile.route');

        return $this;
    }
}

