<?php namespace Telenok\Core\Module\Objects\Lists\Wizard;

class Controller extends \App\Telenok\Core\Module\Objects\Lists\Controller {

    protected $presentation = 'wizard-model';
    protected $presentationModelView = 'core::module.objects-lists.wizard-model'; 
    protected $presentationListWizardView = 'core::module.objects-lists.wizard-list'; 
    
    protected $displayType = 2;

    public function getRouterCreate($param = [])
    {
        return route($this->getVendorName() . ".module.{$this->getKey()}.wizard.create", $param);
    }

    public function getRouterEdit($param = [])
    {
        return route($this->getVendorName() . ".module.{$this->getKey()}.wizard.edit", $param);
    }

    public function getRouterStore($param = [])
    {
        return route($this->getVendorName() . ".module.{$this->getKey()}.wizard.store", $param);
    }

    public function getRouterUpdate($param = [])
    {
        return route($this->getVendorName() . ".module.{$this->getKey()}.wizard.update", $param);
    }

    public function getRouterChooseTypeId($param = [])
    {
        return route($this->getVendorName() . ".module.{$this->getKey()}.wizard.choose.type", $param);
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
        $this->additionalViewParam['pageLength'] = $this->pageLength;

        return $this->additionalViewParam;
    }
    
	public function typeForm($type)
    { 
		return parent::typeForm($type)
				->setPresentationModelView($this->getPresentationModelView())
				->setRouterStore($this->getVendorName() . ".module.{$this->getKey()}.wizard.store")
				->setRouterUpdate($this->getVendorName() . ".module.{$this->getKey()}.wizard.update");
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
        $input = $this->getRequest(); 
		$id = $input->input('typeId', 0);
		
		try
		{
			if (is_array($id))
			{
				$typeList = $id;
				$id = \App\Telenok\Core\Model\Object\Type::where('code', 'object_sequence')->pluck('id');
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
				'saveBtn' => $input->input('saveBtn', true), 
				'chooseBtn' => $input->input('chooseBtn', true),  
                'contentForm' => ( 
                    ($type->classController() && ($controllerProcessing = $this->typeForm($type)) instanceof IPresentation)
                        ? $controllerProcessing->getFormContent($model, $type, $fields, $uniqueId) : FALSE),
            ))->render()
        );
    }    

    public function fillListItem($item = null, \Illuminate\Support\Collection $put = null, $model = null, $type = null)
    {        
        $config = app('telenok.config.repository')->getObjectFieldController();

        foreach ($model->getFieldList() as $field)
        { 
            $put->put($field->code, $config->get($field->key)->getListFieldContent($field, $item, $type));
        }

        $put->put('choose', $this->getChooseButton($item, $type, $put));
        
        return $this;
    }
    
    public function getChooseButton($item, $type, $put)
    {
		$uniq = str_random();

        return '
				<script type="text/javascript">
					$(document).on("click", "#btnfield' . $uniq . '", function() {
						var $modal = jQuery(this).closest(".modal");
                        if ($modal)
                        {
                            $modal.data("model-data")(' . $put->toJson() . '); 
                            return false;
                        }
					});
				</script>
				<button id="btnfield' . $uniq . '" type="button" data-model-id="' . $item->id . '" class="btn btn-xs btn-success">'.$this->LL('btn.choose').'</button> 
		';
    } 
}