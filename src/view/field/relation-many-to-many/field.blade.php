
@include('core::field.common-view.field-view')

<?php
    if (!$model->exists || $model->relation_many_to_many_has || !$model->relation_many_to_many_belong_to)
    {
        $linkedField = 'relation_many_to_many_has';
    }
    else
    {
        $linkedField = 'relation_many_to_many_belong_to';
    }
?>

<div class="form-group">
	{!! Form::label("required", $controller->LL('property.required'), array('class'=>'col-sm-3 control-label no-padding-right')) !!}
	<div class="col-sm-9">
        <div data-toggle="buttons" class="btn-group btn-overlap">
            <label class="btn btn-white btn-sm btn-primary @if (!$model->required) active @endif">
                <input type="radio" value="0" name="required" @if (!$model->required) checked="checked" @endif> {{$controller->LL('btn.no')}}
            </label>

            <label class="btn btn-white btn-sm btn-primary @if ($model->required) active @endif">
                <input type="radio" value="1" name="required" @if ($model->required) checked="checked" @endif> {{$controller->LL('btn.yes')}}
            </label>
        </div>
    </div>
</div>

<div class="form-group">
    {!! Form::label($linkedField, $controller->LL('entity.'.$linkedField), array('class'=>'col-sm-3 control-label no-padding-right')) !!}
    <div class="col-sm-9">
        <?php 

            $domAttr = [];

			if ($model->{$linkedField})
            {
                $domAttr['disabled'] = 'disabled';
            }

        ?>
        {!! Form::hidden($linkedField, $model->{$linkedField}) !!}
        {!! Form::select($linkedField, \App\Telenok\Core\Model\Object\Type::get(['title', 'id'])->transform(function($item) { return ['title' => $item->translate('title'), 'id' => $item->id]; })->sortBy('title')->lists('title', 'id'), $model->{$linkedField}, $domAttr) !!}
    </div>
</div> 