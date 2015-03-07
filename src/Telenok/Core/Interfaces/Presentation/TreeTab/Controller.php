<?php 

namespace Telenok\Core\Interfaces\Presentation\TreeTab;

use \Telenok\Core\Interfaces\Module\Controller as Module;
use \Telenok\Core\Interfaces\Presentation\IPresentation;

abstract class Controller extends Module implements IPresentation {

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

    protected $displayLength = 15;
    protected $additionalViewParam = [];
	
    protected $lockInListPeriod = 3600;
    protected $lockInFormPeriod = 300;

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
		return \URL::route($this->routerActionParam ?: "cmf.module.{$this->getKey()}.action.param", $param);
    } 
	
    public function setRouterList($param)
    {
		$this->routerList = $param;
		
		return $this;
    } 

    public function getRouterList($param = [])
    {
        return \URL::route($this->routerList ?: "cmf.module.{$this->getKey()}.list", $param);
    }	
	
    public function setRouterContent($param)
    {
		$this->routerContent = $param;
		
		return $this;
    }  
	
    public function getRouterContent($param = [])
    {
        return \URL::route($this->routerContent ?: "cmf.module.{$this->getKey()}", $param);
    }
	
    public function setRouterCreate($param)
    {
		$this->routerCreate = $param;
		
		return $this;
    }   
    
    public function getRouterCreate($param = [])
    {
        return \URL::route($this->routerCreate ?: "cmf.module.{$this->getKey()}.create", $param);
    }
	
    public function setRouterEdit($param)
    {
		$this->routerEdit = $param;
		
		return $this;
    }       

    public function getRouterEdit($param = [])
    {
        return \URL::route($this->routerEdit ?: "cmf.module.{$this->getKey()}.edit", $param);
    }
	
    public function setRouterDelete($param)
    {
		$this->routerDelete = $param;
		
		return $this;
    }       

    public function getRouterDelete($param = [])
    {
        return \URL::route($this->routerDelete ?: "cmf.module.{$this->getKey()}.delete", $param);
    }
	
    public function setRouterStore($param)
    {
		$this->routerStore = $param;
		
		return $this;
    }       

    public function getRouterStore($param = [])
    {
        return \URL::route($this->routerStore ?: "cmf.module.{$this->getKey()}.store", $param);
    }
	
    public function setRouterUpdate($param)
    {
		$this->routerUpdate = $param;
		
		return $this;
    }       

    public function getRouterUpdate($param = [])
    {
        return \URL::route($this->routerUpdate ?: "cmf.module.{$this->getKey()}.update", $param);
    }
	
    public function setRouterListEdit($param)
    {
		$this->routerListEdit = $param;
		
		return $this;
    }       

    public function getRouterListEdit($param = [])
    {
		return \URL::route($this->routerListEdit ?: "cmf.module.{$this->getKey()}.list.edit", $param);
    }
	
    public function setRouterListDelete($param)
    {
		$this->routerListDelete = $param;
		
		return $this;
    }       

    public function getRouterListDelete($param = [])
    {
		return \URL::route($this->routerListDelete ?: "cmf.module.{$this->getKey()}.list.delete", $param);
    }
	
    public function setRouterListLock($param)
    {
		$this->routerListLock = $param;
		
		return $this;
    }       

    public function getRouterLock($param = [])
    {
		return \URL::route($this->routerLock ?: "cmf.module.{$this->getKey()}.lock", $param);
    }

    public function getRouterListLock($param = [])
    {
		return \URL::route($this->routerListLock ?: "cmf.module.{$this->getKey()}.list.lock", $param);
    }
	
    public function setRouterListUnlock($param)
    {
		$this->routerListUnlock = $param;
		
		return $this;
    }       

    public function getRouterListUnlock($param = [])
    {
		return \URL::route($this->routerListUnlock ?: "cmf.module.{$this->getKey()}.list.unlock", $param);
    }
	
    public function setRouterListTree($param)
    {
		$this->routerListTree = $param;
		
		return $this;
    }       

    public function getRouterListTree($param = [])
    {
		return \URL::route($this->routerListTree ?: "cmf.module.{$this->getKey()}.list.tree", $param);
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
        return \App\Model\Telenok\Object\Sequence::getModel($id);
    }
 
    public function getType($id)
    {
        return \App\Model\Telenok\Object\Type::where('id', $id)->orWhere('code', $id)->active()->firstOrFail();
    } 

    public function getTypeByModelId($id)
    {
        return \App\Model\Telenok\Object\Sequence::findOrFail($id)->sequencesObjectType;
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
        return app('\Telenok\Core\Interfaces\Exception\Validate');
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
            'iDisplayLength' => $this->displayLength
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
            \Illuminate\Support\Collection::make(explode(' ', $value))
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
        $input = \Illuminate\Support\Collection::make($this->getRequest()->input()); 
        
        if ($str = trim($input->get('sSearch')))
        {
            $this->getFilterQueryLike($str, $query, $model, 'title');
        } 

		if ($input->get('multifield_search', false))
		{
			$this->getFilterSubQuery($input->get('filter', []), $model, $query);
		}
        
        $orderByField = $input->get('mDataProp_' . $input->get('iSortCol_0'));
        
        if ($input->get('iSortCol_0', 0))
        {
            $query->orderBy($model->getTable() . '.' . $orderByField, $input->get('sSortDir_0'));
        }
    }

    public function getFilterSubQuery($input, $model, $query)
    {
        foreach ($input as $name => $value)
        {
			$query->where(function($query) use ($value, $name, $model)
			{
				\Illuminate\Support\Collection::make(explode(' ', $value))
						->reject(function($i) { return !trim($i); })
						->each(function($i) use ($query, $name, $model)
				{
					$query->where($model->getTable().'.'.$name, 'like', '%'.trim($i).'%');
				});
			});

        } 
    }

    public function getListItem($model)
    {
        $id = $this->getRequest()->input('treeId', 0);

        $query = $model->newQuery();

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
        
        return $query->groupBy($model->getTable() . '.id')->orderBy($model->getTable() . '.updated_at', 'desc')->skip($this->getRequest()->input('iDisplayStart', 0))->take($this->displayLength + 1);
    }

    public function getListItemProcessed($field, $item)
    {
        return $item->translate($field->code);
    }

    public function getTreeList($id = null)
    {
        $tree = \Illuminate\Support\Collection::make();
        $input = \Illuminate\Support\Collection::make($this->getRequest()->input()); 

        $id = $id === null ? $input->get('treeId', 0) : $id;
        $searchStr = $input->get('search_string');
            
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

        $types[] = \App\Model\Telenok\Object\Type::where('code', 'folder')->active()->pluck('id');

        if ($this->getModelTreeClass())
        {
            $types[] = $this->getTypeTree()->getKey();
        }
        
        return $types;
    }

    public function getTreeListModel($treeId = 0, $str = '')
    { 
        $sequence = app('\App\Model\Telenok\Object\Sequence');

        if ($str)
        {
            $query = $sequence->withTreeAttr()->active();

            $this->getFilterQueryLike($str, $query, $sequence, 'title');
        }
        else
        {
            $types = $this->getTreeListTypes(); 

            if ($treeId == 0)
            {
                $query = \App\Model\Telenok\Object\Sequence::withChildren(2)->orderBy('pivot_tree_children.tree_order')->active();
            }
            else
            {
                $query = \App\Model\Telenok\Object\Sequence::find($treeId)->children(2)->orderBy('pivot_tree_attr.tree_order')->active();
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
        $collection = \Illuminate\Support\Collection::make();
        
        $collection->put('open', ['order' => 0 , 'content' => '<div class="hidden-phone visible-lg btn-group">']);
        $collection->put('close', ['order' => PHP_INT_MAX, 'content' => '</div>']);
        
        $collection->put('edit', ['order' => 1000, 'content' => '<button class="btn btn-minier btn-info disable" title="'.$this->LL('list.btn.edit').'" 
                        onclick="telenok.getPresentation(\''.$this->getPresentationModuleKey().'\').addTabByURL({url : \'' 
                        . $this->getRouterEdit(['id' => $item->getKey()]) . '\'});">
                        <i class="fa fa-pencil"></i>
                    </button>']);
        
        $collection->put('active', ['order' => 2000, 'content' => '<button class="btn btn-minier btn-light" onclick="return false;" title="' . $this->LL('list.btn.' . ($item->active ? 'active' : 'inactive')) . '">
                        <i class="fa fa-check ' . ($item->active ? 'green' : 'white'). '"></i>
                    </button>']);
        
        $collection->put('locked', ['order' => 3000, 'content' => '<button class="btn btn-minier btn-light" onclick="return false;" title="' . $this->LL('list.btn.' . ($item->locked() ? 'locked' : 'unlocked')) . '">
                        <i class="fa fa-' . ($item->locked() ? 'lock ' . (\Auth::user()->id == $item->locked_by_user ? 'green' : 'red') : 'unlock green'). '"></i>
                    </button>']);
        
        $collection->put('deleted', ['order' => 4000, 'content' => '<button class="btn btn-minier btn-danger" title="'.$this->LL('list.btn.delete').'" 
                        onclick="if (confirm(\'' . $this->LL('notice.sure') . '\')) telenok.getPresentation(\''.$this->getPresentationModuleKey().'\').deleteByURL(this, \'' 
                        . $this->getRouterDelete(['id' => $item->getKey()]) . '\');">
                        <i class="fa fa-trash-o"></i>
                    </button>']); 
        
        
        return $this->getAdditionalListButton($item, $collection)->sort(function($a, $b)
                    {
                        return array_get($a, 'order', 0) > array_get($b, 'order', 0) ? 1 : -1;
                    })->implode('content');
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
        return \Illuminate\Support\Collection::make();
    }

    public function getList()
    {
        $content = [];

        $input = \Illuminate\Support\Collection::make($this->getRequest()->input()); 

        $total = $input->get('iDisplayLength', $this->displayLength);
        $sEcho = $input->get('sEcho');
        $iDisplayStart = $input->get('iDisplayStart', 0);

        $model = $this->getModelList();
        $items = $this->getListItem($model)->get();

        foreach ($items->slice(0, $this->displayLength, true) as $k => $item)
        {
            $put = ['tableCheckAll' => '<label><input type="checkbox" class="ace ace-switch ace-switch-6" name="tableCheckAll[]" value="'.$item->getKey().'" /><span class="lbl"></span></label>'];

            foreach ($model->getFieldList() as $field)
            { 
                $put[$field->code] = $this->getListItemProcessed($field, $item);
            }

            $put['tableManageItem'] = $this->getListButton($item); 

            $content[] = $put;
        }

        return [
            'gridId' => $this->getGridId(),
            'sEcho' => $sEcho,
            'iTotalRecords' => ($iDisplayStart + $items->count()),
            'iTotalDisplayRecords' => ($iDisplayStart + $items->count()),
            'aaData' => $content
        ];
    } 

	public function getRouterParam($action = '', $model = null)
	{
		switch ($action)
		{
			case 'create':
				return [ $this->getRouterStore(['id' => $model->getKey(), 'saveBtn' => $this->getRequest()->input('saveBtn', true), 'chooseBtn' => $this->getRequest()->input('chooseBtn', false), 'chooseSequence' => $this->getRequest()->input('chooseSequence', false)]) ];
				break;

			case 'edit':
				return [ $this->getRouterUpdate(['id' => $model->getKey(), 'saveBtn' => $this->getRequest()->input('saveBtn', true), 'chooseBtn' => $this->getRequest()->input('chooseBtn', true), 'chooseSequence' => $this->getRequest()->input('chooseSequence', false)]) ];
				break;

			case 'store':
				return [ $this->getRouterUpdate(['id' => $model->getKey(), 'saveBtn' => $this->getRequest()->input('saveBtn', true), 'chooseBtn' => $this->getRequest()->input('chooseBtn', true), 'chooseSequence' => $this->getRequest()->input('chooseSequence', false)]) ];
				break;

			case 'update':
				return [ $this->getRouterUpdate(['id' => $model->getKey(), 'saveBtn' => $this->getRequest()->input('saveBtn', true), 'chooseBtn' => $this->getRequest()->input('chooseBtn', true), 'chooseSequence' => $this->getRequest()->input('chooseSequence', false)]) ];
				break;

			default:
				return [];
				break;
		}
	} 

    public function create()
    {  
		$id = $this->getRequest()->input('id');
		
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
	        $model = $this->getModelList()->findOrFail($id);
			
			\DB::transaction(function() use ($model, $force)
			{
				if ($force)
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
		
		\DB::transaction(function() use ($ids, &$error)
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
			$model = \App\Model\Telenok\Object\Sequence::find($id)->model;

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
				$model = \App\Model\Telenok\Object\Sequence::find($id)->model;
				
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
			$userId = \Auth::user()->id;
			
			foreach($tableCheckAll as $id)
			{
				$model = \App\Model\Telenok\Object\Sequence::find($id)->model;

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
        try 
		{
            $input = \Illuminate\Support\Collection::make($this->getRequest()->input()); 

			$model = null;

            \DB::transaction(function() use (&$model, $input)
            { 
                $model = $this->save($input); 
            });
        } 
        catch (\Exception $e) 
        {   
			throw $e;
        } 

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
        try 
        {
            $input = \Illuminate\Support\Collection::make($this->getRequest()->input());  

            $model = null;

            \DB::transaction(function() use (&$model, $input)
            { 
                $model = $this->save($input); 
            });
        } 
        catch (\Exception $e) 
        {   
			throw $e;
        }
 
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
        $input = $input instanceof  \Illuminate\Support\Collection ? $input : \Illuminate\Support\Collection::make((array)$input);
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
                $model->makeLastChildOf(\App\Model\Telenok\System\Folder::findOrFail($input->get('tree_pid'))->sequence);
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
	
	
	
}

