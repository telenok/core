@include('core::field.common-view.field-view')

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


<div id="div_string_default"></div>

<script>

	var $form{{$uniqueId}} = jQuery('#model-ajax-{{$uniqueId}}');
	var $multilanguage{{$uniqueId}} = jQuery('input[name=multilanguage]', $form{{$uniqueId}}); 

	var string_default_multilanguage{{$uniqueId}} = '';
	var string_default{{$uniqueId}} = '';

	@foreach(config('app.locales')->all() as $locale)
		string_default_multilanguage{{$uniqueId}} += '<div class="form-group">';
		string_default_multilanguage{{$uniqueId}} += {!! json_encode((string)Form::label("string_default[{$locale}]", $controller->LL("property.string_default") . " [{$locale}]", array("class" => "col-sm-3 control-label no-padding-right")), JSON_UNESCAPED_UNICODE) !!};
		string_default_multilanguage{{$uniqueId}} += '<div class="col-sm-9">';
		string_default_multilanguage{{$uniqueId}} += {!! json_encode((string)Form::text("string_default[{$locale}]", $model->translate("string_default", $locale)), JSON_UNESCAPED_UNICODE) !!};
		string_default_multilanguage{{$uniqueId}} += '</div></div>';
	@endforeach

	string_default{{$uniqueId}} += '<div class="form-group">';
	string_default{{$uniqueId}} += {!! json_encode((string)Form::label("string_default", $controller->LL("property.string_default"), array("class" => "col-sm-3 control-label no-padding-right")), JSON_UNESCAPED_UNICODE) !!};
	string_default{{$uniqueId}} += '<div class="col-sm-9">';
	string_default{{$uniqueId}} += {!! json_encode((string)Form::text("string_default", $model->string_default), JSON_UNESCAPED_UNICODE) !!};
	string_default{{$uniqueId}} += '</div></div>';

	var closure{{$uniqueId}} = function()
	{
		if (jQuery('input:checked[name=multilanguage]', $form{{$uniqueId}}).val() == 1)
		{
			jQuery('#div_string_default', $form{{$uniqueId}}).html(string_default_multilanguage{{$uniqueId}});
		}
		else
		{
			jQuery('#div_string_default', $form{{$uniqueId}}).html(string_default{{$uniqueId}});
		}
	};

	closure{{$uniqueId}}();

	$multilanguage{{$uniqueId}}.change(function()
	{
		closure{{$uniqueId}}();
	});

</script>

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

<div class="form-group">
	{!! Form::label('string_min', $controller->LL('property.string_unique'), array('class'=>'col-sm-3 control-label no-padding-right')) !!}
	<div class="col-sm-9">
        <div>
            <div data-toggle="buttons" class="btn-group btn-overlap">
				<label class="btn btn-white btn-sm btn-primary @if ($model->string_unique == 0) active @endif">

                   {!! Form::radio('string_unique', 0, $model->string_unique == 0, []) !!} {{ $controller->LL('btn.no') }}

                </label>
				<label class="btn btn-white btn-sm btn-primary @if ($model->string_unique == 1) active @endif">

                   {!! Form::radio('string_unique', 1, $model->string_unique == 1, []) !!} {{ $controller->LL('btn.yes') }}

                </label>
			</div>
		</div>
    </div>
</div>