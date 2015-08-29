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



<div id="div_string_default">

</div>

<script>

	var $form{{$uniqueId}} = jQuery('#model-ajax-{{$uniqueId}}');
	var $multilanguage{{$uniqueId}} = jQuery('input[name=multilanguage]', $form{{$uniqueId}}); 

	var string_default_multilanguage{{$uniqueId}} = '';
	var string_default{{$uniqueId}} = '';
			 
	@foreach(config('app.locales')->all() as $locale)
		string_default_multilanguage{{$uniqueId}} += '<div class="form-group">';
		string_default_multilanguage{{$uniqueId}} += {!! json_encode(Form::label("text_default[{$locale}]", $controller->LL("property.default") . " [{$locale}]", array('class'=>'col-sm-3 control-label no-padding-right'))) !!};
		string_default_multilanguage{{$uniqueId}} += '<div class="col-sm-9">';
		string_default_multilanguage{{$uniqueId}} += {!! json_encode(Form::text("text_default[{$locale}]", $model->translate("text_default", $locale) )) !!};
		string_default_multilanguage{{$uniqueId}} += '</div></div>';
	@endforeach
	
	string_default{{$uniqueId}} += '<div class="form-group">';
	string_default{{$uniqueId}} += {!! json_encode(Form::label("text_default", $controller->LL("property.default"), array('class'=>'col-sm-3 control-label no-padding-right'))) !!};
	string_default{{$uniqueId}} += '<div class="col-sm-9">';
	string_default{{$uniqueId}} += {!! json_encode(Form::text("text_default", $model->text_default)) !!};
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