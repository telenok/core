<div class="form-group">
    {!! Form::label("string_regex", $controller->LL("property.string_regex"), array('class'=>'col-sm-3 control-label no-padding-right')) !!}
    <div class="col-sm-9">
        {!! Form::text("string_regex", $model->translate("string_regex") ) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label("string_list_size", $controller->LL("property.string_list_size"), array('class'=>'col-sm-3 control-label no-padding-right')) !!}
    <div class="col-sm-9">
        {!! Form::text("string_list_size", $model->translate("string_list_size") ) !!}
    </div>
</div> 

@if ($model->multilanguage)
	@foreach(\Config::get('app.locales')->all() as $locale)
		<div class="form-group">
			{!! Form::label("string_default[{$locale}]", $controller->LL("property.string_default") . " [{$locale}]", array('class'=>'col-sm-3 control-label no-padding-right')) !!}
			<div class="col-sm-9">
				{!! Form::text("string_default[{$locale}]", $model->translate("string_default", $locale) ) !!}
			</div>
		</div>
	@endforeach
@else
	<div class="form-group">
		{!! Form::label("string_default", $controller->LL("property.string_default"), array('class'=>'col-sm-3 control-label no-padding-right')) !!}
		<div class="col-sm-9">
			{!! Form::text("string_default", $model->string_default) !!}
		</div>
	</div>
@endif

<div class="form-group">
	{!! Form::label("string_password", $controller->LL('property.string_password'), array('class'=>'col-sm-3 control-label no-padding-right')) !!}
	<div class="col-sm-9">
        <div data-toggle="buttons" class="btn-group btn-overlap">
            <label class="btn btn-white btn-sm btn-primary @if (!$model->string_password) active @endif">
                <input type="radio" value="0" name="string_password" @if (!$model->string_password) checked="checked" @endif> {{$controller->LL('btn.no')}}
            </label>

            <label class="btn btn-white btn-sm btn-primary @if ($model->string_password) active @endif">
                <input type="radio" value="1" name="string_password" @if ($model->string_password) checked="checked" @endif> {{$controller->LL('btn.yes')}}
            </label>
        </div>
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
	{!! Form::label('string_min', $controller->LL('property.string_min'), array('class'=>'col-sm-3 control-label no-padding-right')) !!}
	<div class="col-sm-9">
		{!! Form::text('string_min', $model->string_min) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('string_max', $controller->LL('property.string_max'), array('class'=>'col-sm-3 control-label no-padding-right')) !!}
	<div class="col-sm-9">
		{!! Form::text('string_max', $model->string_max) !!}
	</div>
</div> 