<?php

namespace Telenok\Core\Module\Objects\Lists\Wizard;

/**
 * @class Telenok.Core.Module.Objects.Lists.Wizard.Controller
 * @extends Telenok.Core.Module.Objects.Lists.Controller
 */
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
                'core::module.objects-lists.wizard-choose-type', [
                'controller' => $this,
                'typeId' => (array) $id,
                'uniqueId' => str_random(),
            ])->render()
        ];
    }

    public function choose()
    {        
        $input = $this->getRequest();
        $typeId = $input->input('typeId', 0);

        try
        {
            $model = $this->getModelByTypeId($typeId);
            $type = $this->getType($typeId);
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
                'typeList' => $typeId,
                'fields' => $fields,
                'uniqueId' => ($uniqueId = str_random()),
                'gridId' => str_random(),
                'saveBtn' => $input->input('saveBtn', true),
                'chooseBtn' => $input->input('chooseBtn', true),
                'contentForm' =>
                    (($type->classController() && ($controllerProcessing = $this->typeForm($type)) instanceof IPresentation) 
                        ? $controllerProcessing->getFormContent($model, $type, $fields, $uniqueId) : FALSE),
            ))->render()
        );
    }

    /**
     * @method getFilterQuery
     * Return filtered query.
     * @param {Telenok.Core.Interfaces.Eloquent.Object.Model} $model
     * @param {Illuminate.Database.Query.Builder} $query
     * @return {void}
     * @member Telenok.Core.Module.Objects.Lists.Wizard.Controller
     */
    public function getFilterQuery($model, $query)
    {
        $typeId = $this->getRequest()->input('typeId', 0);
        
        if (is_array($typeId))
        {
            $query->whereIn($model->getTable() . '.sequences_object_type', $typeId);
        }

        return parent::getFilterQuery($model, $query);
    }

    public function fillListItemProcessed($item = null, \Illuminate\Support\Collection $put = null, $model = null, $type = null)
    {
        $put->put('choose', $this->getChooseButton($item, $type, $put));

        return $this;
    }

    public function getChooseButton($item, $type, $put)
    {
        $uniq = str_random();

        return '
            <script type="text/javascript">
                jQuery(document).on("click", "#btnfield' . $uniq . '", function() {
                    var $modal = jQuery(this).closest(".modal");
                    if ($modal)
                    {
                        $modal.data("model-data")(' . $put->toJson() . '); 
                        return false;
                    }
                });
            </script>
            <button id="btnfield' . $uniq . '" type="button" data-model-id="' . $item->id . '" class="btn btn-xs btn-success">' . $this->LL('btn.choose') . '</button> 
        ';
    }
}
