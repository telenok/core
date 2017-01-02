<?php

namespace Telenok\Core\Contract\Presentation;

/**
 * @class Telenok.Core.Contract.Presentation.Presentation
 * @extends Telenok.Core.Contract.Module.Module
 */
interface Presentation extends \Telenok\Core\Contract\Module\Module {

    public function getActionParam();

    public function getPresentation();

    public function setPresentation($key);

    public function getPresentationView();

    public function setPresentationView($key);

    public function getPresentationContentView();

    public function setPresentationContentView($key);

    public function getPresentationContent();

    public function getContent();

    public function getModelFieldViewKey($field);

    public function getModelFieldView($field);

    public function getFormModelViewVariable($fieldController = null, $model = null, $field = null, $uniqueId = null);

    public function setDisplayType($type);

    public function create();

    public function edit($id = null);

    public function store($id = null);

    public function update($id = null);

    public function save($input = [], $type = null);

    public function getListItem($model = null);
}
