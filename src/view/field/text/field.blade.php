
@include('core::field.common-view.field-view')

<?php

    $textUnique = str_random();

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

<div class="form-group">
    {!! Form::label('text_rte', $controller->LL('property.rte'), array('class'=>'col-sm-3 control-label no-padding-right')) !!}
    {!! Form::hidden('text_rte', 0) !!}
    <div class="col-sm-9">
        {!! Form::checkbox('text_rte', 1, $model->text_rte) !!}
    </div>
</div>


<div id="string_default{{$textUnique}}">

</div>

<script>

	var $form{{$uniqueId}} = jQuery('#model-ajax-{{$uniqueId}}');
	var $multilanguage{{$uniqueId}} = jQuery('input[name=multilanguage]', $form{{$uniqueId}});

	var string_default_multilanguage{{$uniqueId}} = '';
	var string_default{{$uniqueId}} = '';

	@foreach(config('app.locales') as $locale)
		string_default_multilanguage{{$uniqueId}} += '<div class="form-group">';
		string_default_multilanguage{{$uniqueId}} += {!! json_encode(Form::label("text_default[{$locale}]", $controller->LL("property.default") . " [{$locale}]", array('class'=>'col-sm-3 control-label no-padding-right'))->toHtml()) !!};
		string_default_multilanguage{{$uniqueId}} += '<div class="col-sm-9">';
		string_default_multilanguage{{$uniqueId}} += {!! json_encode(Form::text("text_default[{$locale}]", $model->translate("text_default", $locale) )->toHtml()) !!};
		string_default_multilanguage{{$uniqueId}} += '</div></div>';
	@endforeach

	string_default{{$uniqueId}} += '<div class="form-group">';
	string_default{{$uniqueId}} += {!! json_encode(Form::label("text_default", $controller->LL("property.default"), array('class'=>'col-sm-3 control-label no-padding-right'))->toHtml()) !!};
	string_default{{$uniqueId}} += '<div class="col-sm-9">';
	string_default{{$uniqueId}} += {!! json_encode(Form::text("text_default", $model->text_default)->toHtml()) !!};
	string_default{{$uniqueId}} += '</div></div>';

	var closure{{$uniqueId}} = function()
	{
		if (jQuery('input:checked[name=multilanguage]', $form{{$uniqueId}}).val() == 1)
		{
			jQuery('#string_default{{$textUnique}}').html(string_default_multilanguage{{$uniqueId}});
		}
		else
		{
			jQuery('#string_default{{$textUnique}}').html(string_default{{$uniqueId}});
		}
	};

	closure{{$uniqueId}}();

	$multilanguage{{$uniqueId}}.change(function()
	{
		closure{{$uniqueId}}();
	});

</script>