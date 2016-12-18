<?php

namespace Telenok\Core\Module\Web\Page;

use Telenok\Core\Event\CompileRoute;

/**
 * @class Telenok.Core.Module.Web.Page.Controller
 * @extends Telenok.Core.Abstraction.Presentation.TreeTabObject.Controller
 */
class Controller extends \Telenok\Core\Abstraction\Presentation\TreeTabObject\Controller
{
    protected $key = 'web-page';
    protected $parent = 'web';
    protected $modelListClass = '\App\Vendor\Telenok\Core\Model\Web\Page';
    protected $modelTreeClass = '\App\Vendor\Telenok\Core\Model\Web\Page';
    protected $presentation = 'tree-tab-object';

    public function getListItem($model = null)
    {
        $model = $model ?: $this->getModelList();

        $query = $model::withTrashed()->withTreeAttr()->withPermission()->where(function ($query) use ($model) {
            if (!$this->getRequest()->input('multifield_search', false) && ($treeId = $this->getRequest()->input('treeId', 0))) {
                $query->where(function ($query) use ($model, $treeId) {
                    $query->where('pivot_tree_attr.tree_id', $treeId);
                    $query->orWhere('pivot_tree_attr.tree_pid', $treeId);
                });
            }
        })->select($model->getTable().'.*');

        $this->getFilterQuery($model, $query);

        return $query->groupBy($model->getTable().'.id')
                        ->orderBy($model->getTable().'.updated_at', 'desc')
                        ->skip($this->getRequest()->input('start', 0))
                        ->take($this->getRequest()->input('length', $this->pageLength) + 1)
                        ->get();
    }

    public function postProcess($model, $type, $input)
    {
        app('events')->fire(new CompileRoute());

        return $this;
    }
}
