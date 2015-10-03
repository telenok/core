
@include('core::field.common-view.field-view')

<div class="form-group">
    {!! Form::label("decimal_default", $controller->LL('property.default'), array('class'=>'col-sm-3 control-label no-padding-right')) !!}
    <div class="col-sm-9">
        {!! Form::text("decimal_default", $model->decimal_default) !!}
    </div>
</div>


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
	{!! Form::label('decimal_min', $controller->LL('property.decimal_min'), array('class'=>'col-sm-3 control-label no-padding-right')) !!}
	<div class="col-sm-9">
		{!! Form::text('decimal_min', $model->decimal_min) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('decimal_max', $controller->LL('property.decimal_max'), array('class'=>'col-sm-3 control-label no-padding-right')) !!}
	<div class="col-sm-9">
		{!! Form::text('decimal_max', $model->decimal_max) !!}
	</div>
</div>

<div class="form-group">
	{!! Form::label('decimal_precision', $controller->LL('property.decimal_precision'), array('class'=>'col-sm-3 control-label no-padding-right')) !!}
	<div class="col-sm-9">
		{!! Form::text('decimal_precision', $model->decimal_precision) !!}
        
        <span title="" data-content="{{ $field->translate('decimal_precision_description') }}" data-placement="right" data-trigger="hover" data-rel="popover" 
              class="help-button" data-original-title="{{trans('core::default.tooltip.description')}}">?</span>

	</div>
</div>


<div class="form-group">
	{!! Form::label('decimal_scale', $controller->LL('property.decimal_scale'), array('class'=>'col-sm-3 control-label no-padding-right')) !!}
	<div class="col-sm-9">
		{!! Form::text('decimal_scale', $model->decimal_scale) !!}
        
        <span title="" data-content="{{ $field->translate('decimal_scale_description') }}" data-placement="right" data-trigger="hover" data-rel="popover" 
              class="help-button" data-original-title="{{trans('core::default.tooltip.description')}}">?</span>

	</div>
</div>