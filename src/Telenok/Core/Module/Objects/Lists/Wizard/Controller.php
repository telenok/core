<?php

namespace Telenok\Core\Module\Objects\Lists\Wizard;

class Controller extends \App\Http\Controllers\Module\Objects\Lists\Controller {

    protected $presentation = 'wizard-model';
    protected $presentationModelView = 'core::module.objects-lists.wizard-model'; 
    protected $presentationListWizardView = 'core::module.objects-lists.wizard-list'; 

    public function getRouterCreate($param = [])
    {
        return \URL::route("cmf.module.{$this->getKey()}.wizard.create", $param);
    }

    public function getRouterEdit($param = [])
    {
        return \URL::route("cmf.module.{$this->getKey()}.wizard.edit", $param);
    }

    public function getRouterStore($param = [])
    {
        return \URL::route("cmf.module.{$this->getKey()}.wizard.store", $param);
    }

    public function getRouterUpdate($param = [])
    {
        return \URL::route("cmf.module.{$this->getKey()}.wizard.update", $param);
    }

    public function getRouterChooseTypeId($param = [])
    {
        return \URL::route("cmf.module.{$this->getKey()}.wizard.choose.type", $param);
    }

    public function getPresentationListWizardView()
    {
        return $this->presentationListWizardView;
    }

    public function getAdditionalViewParam()
    {
        $this->additionalViewParam = parent::getAdditionalViewParam();
		$this->additionalViewParam['presentation'] = $this->getPresentation();
        $this->additionalViewParam['presentationModuleKey'] = $this->getPresentationModuleKey();
        $this->additionalViewParam['iDisplayLength'] = $this->displayLength;

        return $this->additionalViewParam;
    }
    
	public function typeForm($type)
    { 
		return parent::typeForm($type)
				->setPresentationModelView($this->getPresentationModelView())
				->setRouterStore("cmf.module.{$this->getKey()}.wizard.store")
				->setRouterUpdate("cmf.module.{$this->getKey()}.wizard.update");
    }    
	
    public function create()
    { 
		$id = $this->getRequest()->input('id');
  
		if (is_array($id))
		{
			return $this->chooseType($id);
		}

        return parent::create();
    }
	
    public function chooseType($id = [])
    { 
		return [
				'tabContent' => view(
                    'core::module.objects-lists.wizard-choose-type', 
					[
						'controller' => $this,
						'typeId' => (array)$id,
						'uniqueId' => str_random(),
					])->render()
            ];
    }
    
    public function choose()
    {
		$typeList = [];
        $input = \Illuminate\Support\Collection::make($this->getRequest()->input()); 
		$id = $input->get('id', 0);
		
		try
		{
			if (is_array($id))
			{
				$typeList = $id;
				$id = \App\Model\Telenok\Object\Type::where('code', 'object_sequence')->pluck('id');
			}
			
            $model = $this->getModelByTypeId($id);
            $type = $this->getType($id); 
            $fields = $model->getFieldList(); 
        } 
        catch (\Exception $exc) 
        {
            return;
        } 
        
        return array(
            'tabKey' => "{$this->getTabKey()}-{$model->getTable()}",
            'tabLabel' => $type->translate('title'),
            'tabContent' => view($this->getPresentationListWizardView(), array(
                'controller' => $this,  
				'presentation' => $this->getPresentation(),
                'model' => $model,
                'type' => $type,
				'typeList' => $typeList,
                'fields' => $fields,
                'uniqueId' => ($uniqueId = str_random()),
                'gridId' => str_random(),
				'saveBtn' => $input->get('saveBtn', true), 
				'chooseBtn' => $input->get('chooseBtn', true),  
                'contentForm' => ( $model->classController() ? $this->typeForm($model)->getFormContent($model, $type, $fields, $uniqueId) : FALSE),
            ))->render()
        );
    }    
	
    public function getFilterQuery($model, $query) {}
	
    public function getWizardList()
    {
        $content = [];
		$typeList = [];

        $input = \Illuminate\Support\Collection::make($this->getRequest()->input());  
        
        $iDisplayStart = intval($input->get('iDisplayStart', 10));
        $sEcho = $input->get('sEcho');
		$id = $input->get('id', 0);
		$sSearch = trim($input->get('sSearch', 0));
		
        try
        {
			if (is_array($id))
			{
				$typeList = $id;
				$id = \App\Model\Telenok\Object\Type::where('code', 'object_sequence')->pluck('id');
			}

            $type = $this->getType($id);
            $model = $this->getModelByTypeId($id);  
			$query = $this->getListItem($model);

			if (!empty($typeList))
			{
				$query->join('object_sequence as osequence_wizard_list', function($join) use ($model, $typeList)
				{
					$join->on($model->getTable() . '.id', '=', 'osequence_wizard_list.id');
				}); 

				$query->whereIn('osequence_wizard_list.sequences_object_type', $typeList);
			}

			if ($sSearch)
			{
				$query->join('object_translation as object_translation_list', function($join) use ($model, $typeList)
				{
					$join->on($model->getTable() . '.id', '=', 'object_translation_list.translation_object_model_id');
				}); 
				
				$query->groupBy($model->getTable() . '.id');
				
				$query->where(function($query) use ($sSearch, $model)
				{
					$query->orWhere('object_translation_list.translation_object_string', 'like', '%' . $sSearch . '%');
					$query->orWhere($model->getTable() . '.id', (int)$sSearch);
				});
			}
			
			$items = $query->get();
			
			$config = app('telenok.config')->getObjectFieldController();

            foreach ($items->slice(0, $this->displayLength, true) as $k => $item)
            {
                $put = \Illuminate\Support\Collection::make(); 

                foreach ($model->getFieldList() as $field)
                { 
					$put->put($field->code, $config->get($field->key)->getListFieldContent($field, $item, $type));
                }

                $put->put('choose', $this->getChooseButton($item, $type, $put));

                $content[] = $put->toArray();
            }
        }
        catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) 
        {
            return [
                'gridId' => $this->getGridId(), 
                'sEcho' => $sEcho,
                'iTotalRecords' => 0,
                'iTotalDisplayRecords' => 0,
                'aaData' => []
            ];
        }

        return [
            'gridId' => $this->getGridId($model->getTable()), 
            'sEcho' => $sEcho,
            'iTotalRecords' => ($iDisplayStart + $items->count()),
            'iTotalDisplayRecords' => ($iDisplayStart + $items->count()),
            'aaData' => $content
        ];
    }

    public function getChooseButton($item, $type, $put)
    {
		$uniq = str_random();

        return '
				<script type="text/javascript">
					$(document).on("click", "#btnfield' . $uniq . '", function() {
						var $modal = jQuery(this).closest(".modal"); 
                            $modal.data("model-data")(' . $put->toJson() . '); 
                            return false;
					});
				</script>
				<button id="btnfield' . $uniq . '" type="button" class="btn btn-xs btn-success">'.$this->LL('btn.choose').'</button> 
		';
    } 
}