<?php namespace Telenok\Core\Interfaces\Presentation\TreeTab;

use \Telenok\Core\Interfaces\Presentation\IPresentation;
use \Telenok\Core\Interfaces\Controller\IEloquentProcessController;

class Controller extends \Telenok\Core\Interfaces\Module\Controller implements IPresentation, IEloquentProcessController {

    protected $tabKey = '';
    protected $presentation = 'tree-tab';
    protected $presentationModuleKey = '';
    protected $presentationView = 'core::presentation.tree-tab.presentation';
    protected $presentationTreeView = 'core::presentation.tree-tab.tree';
    protected $presentationContentView = 'core::presentation.tree-tab.content';
    protected $presentationModelView = 'core::presentation.tree-tab.model';
    protected $presentationFormModelView = 'core::presentation.tree-tab.form';
    protected $presentationFormFieldListView = 'core::presentation.tree-tab.form-field-list';

    protected $routerActionParam = '';
    protected $routerList = '';
    protected $routerContent = '';
    protected $routerCreate = '';
    protected $routerEdit = '';
    protected $routerDelete = '';
    protected $routerStore = '';
    protected $routerUpdate = '';
    protected $routerListEdit = '';
    protected $routerListDelete = '';
    protected $routerLock = '';
    protected $routerListLock = '';
    protected $routerListUnlock = '';
    protected $routerListTree = '';

    protected $modelListClass = '';
    protected $modelTreeClass = ''; 

    protected $pageLength = 15;
    protected $additionalViewParam = [];
	
    protected $lockInListPeriod = 3600;
    protected $lockInFormPeriod = 300;

    protected $displayType = 1;
            
    public static $DISPLAY_TYPE_STANDART = 1;
    public static $DISPLAY_TYPE_WIZARD = 2;

    public function getLockInListPeriod()
    {
        return $this->lockInListPeriod;
    }
	
	public function setLockInListPeriod($param = 3600)
    {
        $this->lockInListPeriod = $param;
		
		return $this;
    }
	
	public function getLockInFormPeriod()
    {
        return $this->lockInFormPeriod;
    }
	
	public function setLockInFormPeriod($param = 300)
    {
        $this->lockInFormPeriod = $param;

		return $this;
    }
	
	public function getPresentation()
    {
        return $this->presentation;
    }

    public function setPresentation($key)
    {
        $this->presentation = $key;
        
        return $this;
    }
	
	public function getPresentationModuleKey()
    {
        return $this->presentationModuleKey ?: $this->presentation . '-' . $this->getKey();
    }

    public function setPresentationModuleKey($key)
    {
        $this->presentationModuleKey = $key;
        
        return $this;
    }
	
    public function getPresentationView()
    {
        return $this->presentationView;
    }

    public function setPresentationView($key)
    {
        $this->presentationView = $key;
        
        return $this;
    }

    public function getPresentationTreeView()
    {
        return $this->presentationTreeView;
    }    

    public function setPresentationTreeView($key)
    {
        $this->presentationTreeView = $key;
        
        return $this;
    }    

    public function getPresentationContentView()
    {
        return $this->presentationContentView;
    }

    public function setPresentationContentView($key)
    {
        $this->presentationContentView = $key;
        
        return $this;
    }

    public function getPresentationModelView()
    {
        return $this->presentationModelView;
    }

    public function setPresentationModelView($key)
    {
       $this->presentationModelView = $key;
        
        return $this;
    }

    public function getPresentationFormFieldListView()
    {
        return $this->presentationFormFieldListView;
    }

    public function setPresentationFormFieldListView($key)
    {
        $this->presentationFormFieldListView = $key;

        return $this;
    }

    public function getPresentationFormModelView()
    {
        return $this->presentationFormModelView;
    }
    
    public function setPresentationFormModelView($key)
    {
        $this->presentationFormModelView = $key;

        return $this;
    }
    
    public function getTabKey()
    {
        return $this->tabKey ?: $this->getKey();
    }

    public function setTabKey($key)
    {
        $this->tabKey = $key;
        
        return $this;
    }
	
    public function setRouterActionParam($param)
    {
		$this->routerActionParam = $param;
		
		return $this;
    } 

    public function getRouterActionParam($param = [])
    {
		return route($this->routerActionParam ?: $this->getVendorName() . ".module.{$this->getKey()}.action.param", $param);
    } 
	
    public function setRouterList($param)
    {
		$this->routerList = $param;
		
		return $this;
    } 

    public function getRouterList($param = [])
    {
        return route($this->routerList ?: $this->getVendorName() . ".module.{$this->getKey()}.list", $param);
    }	
	
    public function setRouterContent($param)
    {
		$this->routerContent = $param;
		
		return $this;
    }  
	
    public function getRouterContent($param = [])
    {
        return route($this->routerContent ?: $this->getVendorName() . ".module.{$this->getKey()}", $param);
    }
	
    public function setRouterCreate($param)
    {
		$this->routerCreate = $param;
		
		return $this;
    }   
    
    public function getRouterCreate($param = [])
    {
        return route($this->routerCreate ?: $this->getVendorName() . ".module.{$this->getKey()}." . ($this->isDisplayTypeWizard() ? "wizard." : "") . "create", $param);
    }
	
    public function setRouterEdit($param)
    {
		$this->routerEdit = $param;
		
		return $this;
    }       

    public function getRouterEdit($param = [])
    {
        return route($this->routerEdit ?: $this->getVendorName() . ".module.{$this->getKey()}." . ($this->isDisplayTypeWizard() ? "wizard." : "") . "edit", $param);
    }
	
    public function setRouterDelete($param)
    {
		$this->routerDelete = $param;
		
		return $this;
    }       

    public function getRouterDelete($param = [])
    {
        return route($this->routerDelete ?: $this->getVendorName() . ".module.{$this->getKey()}." . ($this->isDisplayTypeWizard() ? "wizard." : "") . "delete", $param);
    }
	
    public function setRouterStore($param)
    {
		$this->routerStore = $param;
		
		return $this;
    }       

    public function getRouterStore($param = [])
    {
        return route($this->routerStore ?: $this->getVendorName() . ".module.{$this->getKey()}." . ($this->isDisplayTypeWizard() ? "wizard." : "") . "store", $param);
    }
	
    public function setRouterUpdate($param)
    {
		$this->routerUpdate = $param;
		
		return $this;
    }       

    public function getRouterUpdate($param = [])
    {
        return route($this->routerUpdate ?: $this->getVendorName() . ".module.{$this->getKey()}." . ($this->isDisplayTypeWizard() ? "wizard." : "") . "update", $param);
    }
	
    public function setRouterListEdit($param)
    {
		$this->routerListEdit = $param;
		
		return $this;
    }       

    public function getRouterListEdit($param = [])
    {
		return route($this->routerListEdit ?: $this->getVendorName() . ".module.{$this->getKey()}.list.edit", $param);
    }
	
    public function setRouterListDelete($param)
    {
		$this->routerListDelete = $param;
		
		return $this;
    }       

    public function getRouterListDelete($param = [])
    {
		return route($this->routerListDelete ?: $this->getVendorName() . ".module.{$this->getKey()}.list.delete", $param);
    }
	
    public function setRouterListLock($param)
    {
		$this->routerListLock = $param;
		
		return $this;
    }       

    public function getRouterLock($param = [])
    {
		return route($this->routerLock ?: $this->getVendorName() . ".module.{$this->getKey()}.lock", $param);
    }

    public function getRouterListLock($param = [])
    {
		return route($this->routerListLock ?: $this->getVendorName() . ".module.{$this->getKey()}.list.lock", $param);
    }
	
    public function setRouterListUnlock($param)
    {
		$this->routerListUnlock = $param;
		
		return $this;
    }       

    public function getRouterListUnlock($param = [])
    {
		return route($this->routerListUnlock ?: $this->getVendorName() . ".module.{$this->getKey()}.list.unlock", $param);
    }
	
    public function setRouterListTree($param)
    {
		$this->routerListTree = $param;
		
		return $this;
    }       

    public function getRouterListTree($param = [])
    {
		return route($this->routerListTree ?: $this->getVendorName() . ".module.{$this->getKey()}.list.tree", $param);
    }

    public function setModelListClass($param)
    {
        $this->modelListClass = $param;
        
        return $this;
    }

    public function getModelListClass()
    {
        return $this->modelListClass;
    }
    
    public function setModelTreeClass($param)
    {
        $this->modelTreeClass = $param;
        
        return $this;
    }

    public function getModelTreeClass()
    {
        return $this->modelTreeClass;
    }
    
    public function getModelList()
    {
        return app($this->getModelListClass());
    }

    public function getModelTree()
    {
        return app($this->getModelTreeClass());
    }

    public function getTypeList()
    {
        return $this->getModelList()->type();
    } 

    public function getTypeTree()
    {
        return $this->getModelTree()->type();
    } 
    
    public function getModel($id)
    {
        return \App\Telenok\Core\Model\Object\Sequence::getModel($id);
    }
    
    public function getModelTrashed($id)
    {
        return \App\Telenok\Core\Model\Object\Sequence::getModelTrashed($id);
    }
 
    public function getType($id)
    {
        return \App\Telenok\Core\Model\Object\Type::where('id', $id)->orWhere('code', $id)->active()->firstOrFail();
    } 

    public function getTypeByModelId($id)
    {
        return \App\Telenok\Core\Model\Object\Sequence::withTrashed()->findOrFail($id)->sequencesObjectType;
    }
    
    public function getModelByTypeId($id)
    {
        return app($this->getType($id)->class_model);
    }

    public function validate($model = null, $input = null, $message = [])
    { 
        return $this;
    }

    public function validator($model = null, $input = [], $message = [], $customAttribute = [])
    {
        return app('\Telenok\Core\Interfaces\Validator\Model')
                    ->setModel($model ?: $this->getModelList())
                    ->setInput($input)
                    ->setMessage($message)
                    ->setCustomAttribute($customAttribute);
    }

    public function validateException()
    {
        return new \Telenok\Core\Support\Exception\Validator;
    } 
    
    public function getActionParam()
    { 
        return json_encode([
            'presentation' => $this->getPresentation(),
			'presentationModuleKey' => $this->getPresentationModuleKey(),
            'presentationContent' => $this->getPresentationContent(),
            'key' => $this->getKey(),
            'treeContent' => $this->getTreeContent(),
            'url' => $this->getRouterContent(),
            'breadcrumbs' => $this->getBreadcrumbs(),
            'pageHeader' => $this->getPageHeader(),
            'uniqueId' => str_random(), 
        ]);
    }
    
    public function getPresentationContent()
    {
        return view($this->getPresentationView(), [
            'presentation' => $this->getPresentation(),
			'presentationModuleKey' => $this->getPresentationModuleKey(),
            'controller' => $this,
            'uniqueId' => str_random(),
            'pageLength' => $this->pageLength
        ])->render();
    } 

    public function getContent()
    {
        $model = $this->getModelList();

        return [
            'tabKey' => $this->getTabKey(),
            'tabLabel' => $this->LL('list.name'),
            'tabContent' => view($this->getPresentationContentView(), array_merge([
                'controller' => $this, 
                'fields' => $model->getFieldList(),
                'fieldsFilter' => $this->getModelFieldFilter(),
                'gridId' => $this->getGridId(), 
                'uniqueId' => str_random(),
            ], $this->getAdditionalViewParam()))->render()
        ];
    }
    
    public function getTreeContent()
    {
        return view($this->getPresentationTreeView(), [
                'controller' => $this, 
                'treeChoose' => $this->LL('title.tree'), 
                'id' => str_random(),
            ])->render();
    }
    
    public function getFilterQueryLike($value, $query, $model, $field)
    {     
        $query->where(function($query) use ($value, $model, $field)
        {
            collect(explode(' ', $value))
                ->filter(function($i) 
                { 
                    return trim($i);
                }) 
                ->each(function($i) use ($query, $model, $field)
                {
                    $query->orWhere($model->getTable() . '.' . $field, 'like', '%' . trim($i) . '%');
                });

            $query->orWhere($model->getTable() . '.id', intval($value));
        }); 
    }
    
    public function getFilterQuery($model, $query)
    {
        $input = $this->getRequest(); 

        if (($str = trim($input->input('search.value'))) || ($str = trim($input->input('term'))))
        {
            $this->getFilterQueryLike($str, $query, $model, 'title');
        } 

		if ($input->input('multifield_search', false))
		{
			$this->getFilterSubQuery($input->input('filter', []), $model, $query);
		}
        else
        {
			$this->getFilterSubQuery(null, $model, $query);
        }
        
        if ($input->input('order', 0) 
                && ($orderByField = $input->input("columns.{$input->input('order.0.column')}.data")))
        {
            if (($model instanceof \Telenok\Core\Interfaces\Eloquent\Object\Model
                    && $model->getFieldList()->filter(function($item) use ($orderByField)
                    {
                        return $orderByField === $item->code;
                    })->count()) || !($model instanceof \Telenok\Core\Interfaces\Eloquent\Object\Model))
            {
                $query->orderBy($model->getTable() . '.' . $orderByField, $input->input('order.0.dir') == 'asc' ? 'asc' : 'desc');
            }
        }
    }

    public function getFilterSubQuery($input, $model, $query)
    {
        foreach ($input as $name => $value)
        {
			$query->where(function($query) use ($value, $name, $model)
			{
				collect(explode(' ', $value))
						->reject(function($i) { return !trim($i); })
						->each(function($i) use ($query, $name, $model)
				{
					$query->where($model->getTable().'.'.$name, 'like', '%'.trim($i).'%');
				});
			});

        } 
    }

    public function getListItem($model = null)
    {
        $id = $this->getRequest()->input('treeId', 0);

        $query = $model->withTrashed();

        if ($model->treeForming())
        {
            $query->withTreeAttr();
            
            if ($id)
            {
                $query->where(function($query) use ($model, $id)
                {
                    $query->where('pivot_relation_m2m_tree.tree_pid', $id)
                            ->orWhere($model->getTable() . '.id', $id);
                });
            }
        }
        else
        {
            $query->where($model->getTable() . '.id', $id); 
        }

        $query->withPermission();

        $this->getFilterQuery($model, $query); 
        
        return $query->groupBy($model->getTable() . '.id')
                ->orderBy($model->getTable() . '.updated_at', 'desc')
                ->skip($this->getRequest()->input('start', 0))
                ->take($this->getRequest()->input('length', $this->pageLength) + 1)
                ->get();
    }

    public function fillListItem($item = null, \Illuminate\Support\Collection $put = null, $model = null)
    {
        $put->put('tableCheckAll', 
                '<input type="checkbox" class="ace ace-checkbox-2" '
                . 'name="tableCheckAll[]" value="' . $item->getKey() . '"><span class="lbl"></span>');

        foreach ($model->getFieldList() as $field)
        { 
            $put->put($field->code, $this->getListItemProcessed($field, $item));
        }

        $put->put('tableManageItem', $this->getListButton($item));
        
        return $this;
    }
    
    public function getListItemProcessed($field, $item)
    {
        return $item->translate($field->code);
    }

    public function getTreeList($id = null)
    {
        $tree = collect();
        $input = $this->getRequest(); 

        $id = $id === null ? $input->input('treeId', 0) : $id;
        $searchStr = $input->input('search_string');
            
        try
        {
            $list = $this->getTreeListModel($id, $searchStr);

            if ($searchStr)
            {
                foreach ($list->all() as $l)
                {
                    foreach($l->parents()->get()->all() as $l_)
                    {
                       $tree->push("#{$l_->getKey()}");
                    }

                    $tree->push("#{$l->getKey()}");
                }
            } 
            else
            {
                $parents = $list->lists('id', 'tree_pid');

                foreach ($list as $key => $item)
                {
                    if ($item->tree_pid == $id)
                    {
                        $tree->push([
                            "data" => $item->translate('title'), 
                            'attr' => ['id' => $item->getKey(), 'rel' => '', 'title' => 'ID: ' . $item->getKey()],
                            "state" => (isset($parents[$item->getKey()]) ? 'closed' : ''),
                            "metadata" => array_merge( ['id' => $item->getKey(), 'gridId' => $this->getGridId() ], $this->getTreeListItemProcessed($item)),
                        ]);
                    }
                }                
            }
        }
        catch (\Exception $e)
        {     
            return $e->getMessage(); 
        }

        return $tree->all();
    } 

    public function getTreeListTypes()
    { 
        $types = [];

        $types[] = \App\Telenok\Core\Model\Object\Type::where('code', 'folder')->active()->pluck('id');

        if ($this->getModelTreeClass())
        {
            $types[] = $this->getTypeTree()->getKey();
        }
        
        return $types;
    }

    public function getTreeListModel($treeId = 0, $str = '')
    { 
        $sequence = app('\App\Telenok\Core\Model\Object\Sequence');

        if ($str)
        {
            $query = $sequence->withTreeAttr();

            $this->getFilterQueryLike($str, $query, $sequence, 'title');
        }
        else
        {
            $types = $this->getTreeListTypes(); 

            if ($treeId == 0)
            {
                $query = \App\Telenok\Core\Model\Object\Sequence::withChildren(2)->orderBy('pivot_tree_children.tree_order');
            }
            else
            {
                $query = \App\Telenok\Core\Model\Object\Sequence::find($treeId)->children(2)->orderBy('pivot_tree_attr.tree_order')->active();
            }

            $query->whereIn('object_sequence.sequences_object_type', $types);
        }

        $query->where('object_sequence.treeable', 1);
        $query->groupBy('object_sequence.id');
		$query->withPermission('read', null, ['direct-right']);

        return $query->get();
    } 

    public function getTreeListItemProcessed($item)
    {
        return [];
    } 

    public function getListButton($item)
    {
        $random = str_random();
        
        $collection = collect();
        
        $collection->put('open', ['order' => 0 , 'content' => 
            '<div class="dropdown">
                <a class="btn btn-white no-hover btn-transparent btn-xs dropdown-toggle" href="#" role="button" style="border:none;"
                        type="button" id="' . $random . '" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                    <span class="glyphicon glyphicon-menu-hamburger text-muted"></span>
                </a>
                <ul class="dropdown-menu" aria-labelledby="' . $random . '">
            ']);
        
        $collection->put('close', ['order' => PHP_INT_MAX, 'content' => 
                '</ul>
            </div>']);
        
        $collection->put('edit', ['order' => 1000, 'content' => 
                '<li><a href="#" onclick="telenok.getPresentation(\''
                    . $this->getPresentationModuleKey().'\').addTabByURL({url : \'' . $this->getRouterEdit(['id' => $item->getKey()]) . '\'}); return false;">' 
                    . ' <i class="fa fa-pencil"></i> ' . $this->LL('list.btn.edit') . '</a>
                </li>']);
        
        $collection->put('delete', ['order' => 2000, 'content' => 
                '<li><a href="#" onclick="if (confirm(\'' . $this->LL('notice.sure.delete') . '\')) telenok.getPresentation(\''.$this->getPresentationModuleKey().'\').deleteByURL(this, \''
                    . $this->getRouterDelete(['id' => $item->getKey()]) . '\'); return false;">'
                    . ' <i class="fa fa-trash-o"></i> ' . $this->LL('list.btn.delete') . '</a>
                </li>']);

        app('events')->fire($this->getListButtonEventKey(), $collection);

        return $this->getAdditionalListButton($item, $collection)->sort(function($a, $b)
                    {
                        return array_get($a, 'order', 0) > array_get($b, 'order', 0) ? 1 : -1;
                    })->implode('content');
    }

    public function getListButtonEventKey($param = null)
    {
        return 'telenok.module.' . $this->getKey();
    }
    
    public function getAdditionalListButton($item, $collection)
    {
        return $collection;
    }

    public function getAdditionalViewParam()
    {
        return $this->additionalViewParam;
    }    

    public function setAdditionalViewParam($param = [])
    {
		$this->additionalViewParam = $param;
		
		return $this;
    }    

    public function getGridId($key = 'gridId')
    {
        return "{$this->getPresentation()}-{$this->getTabKey()}-{$key}";
    }

    public function getModelFieldFilter($model = null)
    {
        return collect();
    }

    public function getList()
    {
        $content = [];

        $input = $this->getRequest(); 

        $draw = $input->input('draw');
        $start = $input->input('start', 0);
        $length = $input->input('length', $this->pageLength);

        $model = $this->getModelList();
        $items = $this->getListItem($model);

        foreach ($items->slice(0, $length, true) as $item)
        {
            $put = collect();

            $this->fillListItem($item, $put, $model);

            $content[] = $put->all();
        }

        return [
            'draw' => $draw,
            'data' => $content,
            'gridId' => $this->getGridId(),
            'recordsTotal' => ($start + $items->count()),
            'recordsFiltered' => ($start + $items->count()),
        ];
    } 

	public function getRouterParam($action = '', $model = null)
	{
		switch ($action)
		{
			case 'create':
				return [ 
                    $this->getRouterStore(
                        [
                            'id' => $model->getKey(), 
                            'saveBtn' => $this->getRequest()->input('saveBtn', true), 
                            'chooseBtn' => $this->getRequest()->input('chooseBtn', false), 
                            'chooseSequence' => $this->getRequest()->input('chooseSequence', false)
                        ])];
				break;

			case 'edit':
				return [ 
                    $this->getRouterUpdate(
                        [
                            'id' => $model->getKey(), 
                            'saveBtn' => $this->getRequest()->input('saveBtn', true), 
                            'chooseBtn' => $this->getRequest()->input('chooseBtn', true), 
                            'chooseSequence' => $this->getRequest()->input('chooseSequence', false)
                        ])];
				break;

			case 'store':
				return [ 
                    $this->getRouterUpdate(
                        [
                            'id' => $model->getKey(),
                            'saveBtn' => $this->getRequest()->input('saveBtn', true),
                            'chooseBtn' => $this->getRequest()->input('chooseBtn', true),
                            'chooseSequence' => $this->getRequest()->input('chooseSequence', false)
                        ])];
				break;

			case 'update':
				return [ 
                    $this->getRouterUpdate(
                        [
                            'id' => $model->getKey(), 
                            'saveBtn' => $this->getRequest()->input('saveBtn', true), 
                            'chooseBtn' => $this->getRequest()->input('chooseBtn', true), 
                            'chooseSequence' => $this->getRequest()->input('chooseSequence', false)
                        ])];
				break;

			default:
				return [];
		}
	} 

    public function create()
    {  
        return [
            'tabKey' => $this->getTabKey().'-new-'.str_random(),
            'tabLabel' => $this->LL('list.create'),
            'tabContent' => view("{$this->getPackage()}::module.{$this->getKey()}.model", array_merge(array( 
                'controller' => $this,
                'model' => $this->getModelList(), 
				'routerParam' => $this->getRouterParam('create'),
                'uniqueId' => str_random(),  
            ), $this->getAdditionalViewParam()))->render()
        ];
    }

    public function edit($id = 0)
    { 
		$id = $id ?: $this->getRequest()->input('id');
		
        return [
            'tabKey' => $this->getTabKey() . '-edit-' . $id,
            'tabLabel' => $this->LL('list.edit'),
            'tabContent' => view("{$this->getPackage()}::module.{$this->getKey()}.model", array_merge(array( 
                'controller' => $this,
                'model' => $this->getModelList()->find($id), 
				'routerParam' => $this->getRouterParam('edit'),
                'uniqueId' => str_random(),  
            ), $this->getAdditionalViewParam()))->render()
        ];
    }

    public function editList()
    {
        $content = [];

        $ids = (array)$this->getRequest()->input('tableCheckAll');

        if (empty($ids)) 
        {
            return \Response::json(['message' => 'Expectation Failed'], 417 /* Expectation Failed */);
        }
        
        foreach ($ids as $id)
        {
            $content[] = view("{$this->getPackage()}::module.{$this->getKey()}.model", array_merge(array( 
                'controller' => $this,
                'model' => $this->getModelList()->find($id), 
				'routerParam' => $this->getRouterParam('edit'),
                'uniqueId' => str_random(),  
            ), $this->getAdditionalViewParam()))->render();
        }

        return [
            'tabKey' => $this->getTabKey() . '-edit-' . implode('', $ids),
            'tabLabel' => $this->LL('list.edit'),
            'tabContent' => implode('<div class="hr hr-double hr-dotted hr18"></div>', $content)
        ];
    }

    public function delete($id = null, $force = false)
    { 
        try
        {
	        $model = $this->getModelTrashed($id);
		
			if (!app('auth')->can('delete', $id))
			{
				throw new \LogicException($this->LL('error.access'));
			}
			
			app('db')->transaction(function() use ($model, $force)
			{
				if ($force || $model->trashed())
				{
					$model->forceDelete();
				}
				else 
				{
					$model->delete();
				}
			});

            return ['success' => 1];
        }
        catch (\Exception $e)
        {
            return ['exception' => 1];
        }
    }

    public function deleteList($id = null, $ids = [])
    {
        $ids = !empty($ids) ? $ids : (array)$this->getRequest()->input('tableCheckAll');

        if (empty($ids)) 
        {
            return \Response::json(['message' => 'Expectation Failed'], 417 /* Expectation Failed */);
        }
         
		$error = false;
		
		app('db')->transaction(function() use ($ids, &$error)
		{ 
			try
			{
				$model = $this->getModelList();
				
				foreach ($ids as $id_)
				{
					$model::findOrFail($id_)->delete();
				}
			}
			catch (\Exception $e)
			{
			   $error = true;
			}
		});
		
		if ($error)
		{
			return \Response::json(['message' => 'Expectation Failed'], 417 /* Expectation Failed */);
		}
		else
		{
			return \Response::json(['success' => 1]);
		}
    }

    public function lock()
    {
		$id = $this->getRequest()->input('id');

		try
		{
			$model = \App\Telenok\Core\Model\Object\Sequence::find($id)->model;

			if (!$model->locked())
			{
				$model->lock($this->getLockInFormPeriod());
			}
		}
		catch (\Exception $ex) 
		{
            return \Response::json(['message' => 'Expectation Failed'], 417 /* Expectation Failed */);
		} 
		
		return \Response::json(['success' => 1]);
	}

    public function lockList()
    {
		$tableCheckAll = $this->getRequest()->input('tableCheckAll', []);
		
		try
		{
			foreach($tableCheckAll as $id)
			{
				$model = \App\Telenok\Core\Model\Object\Sequence::find($id)->model;
				
				if (!$model->locked())
				{
					$model->lock($this->getLockInListPeriod());
				}
			}
		} 
		catch (\Exception $ex) 
		{
            return \Response::json(['message' => 'Expectation Failed'], 417 /* Expectation Failed */);
		} 
		
		return \Response::json(['success' => 1]);
	}

	public function unlockList()
    {
		$tableCheckAll = $this->getRequest()->input('tableCheckAll', []);
		
		try
		{
			$userId = app('auth')->user()->id;
			
			foreach($tableCheckAll as $id)
			{
				$model = \App\Telenok\Core\Model\Object\Sequence::find($id)->model;

				if ($model && $model->locked_by_user == $userId)
				{
					$model->unLock();
				}
			}
		} 
		catch (\Exception $ex) 
		{
            return \Response::json(['message' => 'Expectation Failed'], 417 /* Expectation Failed */);
		} 
		
		return \Response::json(['success' => 1]);
	}

    public function store($id = null)
    {
        $input = $this->getRequestCollected(); 

        $model = null;

        app('db')->transaction(function() use (&$model, $input)
        { 
            $model = $this->save($input); 
        });

        $return = [];
		
        $return['content'] = view("{$this->getPackage()}::module.{$this->getKey()}.model", array_merge([
                    'controller' => $this,
                    'model' => $model,
					'routerParam' => $this->getRouterParam('store'),
                    'uniqueId' => str_random(), 
                    'success' => true,
                    'warning' => \Session::get('warning'),
                ], $this->getAdditionalViewParam()))->render();

        return $return;
    }
    
    public function update($id = null)
    {
        $input = $this->getRequestCollected();  

        $model = null;

        app('db')->transaction(function() use (&$model, $input)
        { 
            $model = $this->save($input); 
        });

        $return = []; 
		
        $return['content'] = view("{$this->getPackage()}::module.{$this->getKey()}.model", array_merge([
                    'controller' => $this,
                    'model' => $model,
					'routerParam' => $this->getRouterParam('update'),
                    'uniqueId' => str_random(),                 
                    'success' => true,
                    'warning' => \Session::get('warning'), 
                ], $this->getAdditionalViewParam()))->render();

        return $return;
    }

    public function save($input = [], $type = null)
    {   
        $input = collect($input);
        $model = $this->getModelList();

        $validator = $this->validator($model, $input->all(), $this->LL('error'), ['table' => $model->getTable()]);

        if ($validator->fails()) 
        {
            throw $this->validateException()->setMessageError($validator->messages());
        } 
        
        $this->preProcess($model, $type, $input);

        $this->validate($model, $input);

        if ($model->exists && $model->getKey() == $input->get('id'))
        {
            $model->update($input->all()); 
        }
        else
        {
            $model->fill($input->all())->save();  
        } 
        
        if ($input->get('tree_pid') && $model->treeForming())
        {
            try
            {
                $model->makeLastChildOf(\App\Telenok\Core\Model\System\Folder::findOrFail($input->get('tree_pid'))->sequence);
            }
            catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) 
            { 
                $model->makeRoot();  
            } 
        }
        
        $this->postProcess($model, $type, $input);

        return $model;
    }
    
    public function preProcess($model, $type, $input)
    { 
        return $this;
    }

    public function postProcess($model, $type, $input)
    {  
        return $this;
    }

    public function getModelFieldViewKey($field)
	{
	}
	
	public function getModelFieldView($field)
	{
	}
	
	public function getModelFieldViewVariable($fieldController = null, $model = null, $field = null, $uniqueId = null)
	{
	}
    
    public function getDisplayType()
    {
        return $this->displayType;
    }
    
    public function setDisplayType($type)
    {
        $this->displayType = $type;
        
        return $this;
    }
    
    public function isDisplayTypeWizard()
    {
        return $this->displayType == static::$DISPLAY_TYPE_WIZARD;
    }
}

