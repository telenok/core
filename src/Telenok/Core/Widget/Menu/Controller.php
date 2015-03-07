<?php

namespace Telenok\Core\Widget\Menu;

class Controller extends \Telenok\Core\Interfaces\Widget\Controller {

    protected $key = 'menu';
    protected $parent = 'standart';
	protected $frontendView = "core::widget.menu.widget-frontend";

	public function getContent($structure = null)
	{
        if (!($model = $this->getWidgetModel()))
        {
            return;
        }
        
        $structure = $structure === null ? $model->structure : $structure;
        
        $this->setCacheTime($model->cache_time);
        
        if (($content = $this->getCachedContent()) !== false)
        {
            return $content;
        }

        $menuType = array_get($structure, 'menu_type');
        $nodeIds = array_get($structure, 'node_ids');
        $ids = [];

        if ($menuType == 1)
        {
            $ids = json_decode('[' . $nodeIds . ']'); 
        }
        else if ($menuType == 2)
        {
            $ids = str_replace('{', ',[', $nodeIds);
            $ids = str_replace('}', ']', $ids);
            $ids = json_decode('[' . $ids . ']');
        }

        $pages = \App\Model\Telenok\Web\Page::withTreeAttr()->whereIn('page.id', array_flatten($ids))->active()->withPermission()
                    ->orderBy(\DB::raw('CONCAT(pivot_tree_attr.tree_path, pivot_tree_attr.tree_id)'))
                    ->orderBy('pivot_tree_attr.tree_order')
                    ->get();
        
        $content = view('widget.' . $model->getKey(), [
                        'controller' => $this, 
                        'frontendController' => $this->getFrontendController(),
                        'pages' => $pages,
                        'nodeIds' => $ids,
                        'menu_type' => $menuType,
                    ])->render();

        $this->setCachedContent($content);

        return $content;
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