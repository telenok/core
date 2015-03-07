<?php

namespace Telenok\Core\Interfaces\Field;

interface IField extends \Telenok\Core\Interfaces\IRequest {

    public function getName();

    public function getKey();

    public function getViewModel();

    public function setViewModel($field = null);

    public function getViewField();

    public function getRouteListTable();

    public function getRouteListTitle();

    public function getRouteWizardCreate();

    public function getRouteWizardEdit();

    public function getRouteWizardChoose();

    public function getSpecialField($model);

    public function getModelField($model, $field);

    public function getDateField($model, $field);

    public function getSpecialDateField($model);

    public function getRule($field = null);

    public function getModelAttribute($model, $key, $value, $field);

    public function setModelAttribute($model, $key, $value, $field);

    public function getModelSpecialAttribute($model, $key, $value);

    public function setModelSpecialAttribute($model, $key, $value);

    public function getFormModelContent($controller = null, $model = null, $field = null, $uniqueId = null);

    public function getLinkedModelType($field);

    public function getTableList($id = null, $fieldId = null, $uniqueId = null);

    public function getFormModelTableColumn($field, $model, $jsUnique);

    public function getFormFieldContent($model = null, $uniqueId = null);

    public function getFilterQuery($field = null, $model = null, $query = null, $name = null, $value = null);

    public function getFilterContent($field = null);

    public function getListFieldContent($field, $item, $type = null);

    public function validate($model = null, $input = [], $messages = []);

    public function validateMethodExists($object, $method);

    //!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! DELETE ?
    public function fill($field, $model, $input);

    public function saveModelField($field, $model, $input);

    public function updateModelFile($model, $param, $stubFile, $dir);

    public function validator($model = null, $input = [], $message = [], $customAttribute = []);

    public function validateException();

    public function preProcess($model, $type, $input);

    public function postProcess($model, $type, $input);

    public function processDeleting($model);

    public function allowMultilanguage();

    public function getMultilanguage($model, $field);

    public function getFieldTab($typeId, $tabCode);

    public function getFieldTabBelongTo($typeId, $tabBelongCode, $tabHasId);
}
