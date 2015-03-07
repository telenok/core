
<div class="form-group">
    {!! Form::label("integer_unsigned_default", $controller->LL('property.default'), array('class'=>'col-sm-3 control-label no-padding-right')) !!}
    <div class="col-sm-9">
        {!! Form::text("integer_unsigned_default", $model->integer_unsigned_default) !!}
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
	{!! Form::label('integer_unsigned_min', $controller->LL('property.integer_unsigned_min'), array('class'=>'col-sm-3 control-label no-padding-right')) !!}
	<div class="col-sm-9">
		{!! Form::text('integer_unsigned_min', $model->integer_unsigned_min) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('integer_unsigned_max', $controller->LL('property.integer_unsigned_max'), array('class'=>'col-sm-3 control-label no-padding-right')) !!}
	<div class="col-sm-9">
		{!! Form::text('integer_unsigned_max', $model->integer_unsigned_max) !!}
	</div>
</div>