<div class="form-group">
    {!! Form::label('text_width', $controller->LL('property.width'), array('class'=>'col-sm-3 control-label no-padding-right')) !!}
    <div class="col-sm-9">
        {!! Form::text('text_width', $model->text_width) !!}
    </div>
</div>
<div class="form-group">
    {!! Form::label('text_height', $controller->LL('property.height'), array('class'=>'col-sm-3 control-label no-padding-right')) !!}
    <div class="col-sm-9">
        {!! Form::text('text_height', $model->text_height) !!}
    </div>
</div>

@if ($model->multilanguage)
	@foreach(config('app.locales')->all() as $locale)
		<div class="form-group">
			{!! Form::label("text_default[{$locale}]", $controller->LL("property.default") . " [{$locale}]", array('class'=>'col-sm-3 control-label no-padding-right')) !!}
			<div class="col-sm-9">
				{!! Form::text("text_default[{$locale}]", $model->translate("text_default", $locale) ) !!}
			</div>
		</div>
	@endforeach
@else
	<div class="form-group">
		{!! Form::label("text_default", $controller->LL("property.default"), array('class'=>'col-sm-3 control-label no-padding-right')) !!}
		<div class="col-sm-9">
			{!! Form::text("text_default", $model->text_default) !!}
		</div>
	</div>
@endif