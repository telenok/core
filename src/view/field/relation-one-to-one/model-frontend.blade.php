<?php 
    
    $jsUnique = str_random();
    $disabled = false;
    $method = camel_case($field->code);
	$values = [];

    $domAttr = [
		'id' => 'relation-one-to-one-' . $jsUnique,
		'class' => 'chosen',
		'data-placeholder' => e($controller->LL('notice.choose')),
	];

	if ( (!$model->exists && (!$field->allow_create || !$permissionCreate)) || ($model->exists && (!$field->allow_update || !$permissionUpdate)) )
    {
        $domAttr['disabled'] = 'disabled';
        $disabled = true; 
    }

	if ($model->exists && ($v = \App\Vendor\Telenok\Core\Model\Object\Sequence::where('id', $model->{$field->code})->get()))
	{
		$value = $v->transform(function($item)
		{
			return ['id' => $item->id, 'value' => $item->translate('title')];
		})->pluck('value', 'id')->toArray();
	}

	$values = ['&nbsp;'] + (array)$values;

?>

<div class="form-group" data-field-key='{{ $field->code }}'>

	{!! Form::label("{$field->code}", $field->translate('title'), array('class' => 'col-sm-3 control-label no-padding-right')) !!}

	<div class="col-sm-5">

            @if ($field->icon_class)
		<div class="input-group">
            <span class="input-group-addon">
                <i class="{{ $field->icon_class }}"></i>
            </span>
            @else
		<div>
            @endif	

            {!! Form::select($field->code, $values, $model->exists ? $model->{$field->code} : 0, $domAttr) !!}

			<?php

			$controllerRequest->addCssFile(asset('packages/telenok/core/js/jquery.chosen/chosen.css'), 'chosen', 20);
			$controllerRequest->addJsFile(asset('packages/telenok/core/js/jquery.chosen/chosen.js'), 'chosen', 20);

			ob_start();

			?>

			<script type="text/javascript">

				jQuery(function()
				{
					jQuery("#relation-one-to-one-{{ $jsUnique }}").on("chosen:showing_dropdown", function()
                                        {
                                            telenok.maxZ("*", jQuery(this).parent().find("div.chosen-drop"));
                                        })
                                        .ajaxChosen({ 
                                            keepTypingMsg: "{{ $controller->LL('notice.typing') }}",
                                            lookingForMsg: "{{ $controller->LL('notice.looking-for') }}",
                                            type: "GET",
                                            url: "{!! $urlListTitle !!}", 
                                            dataType: "json",
                                            minTermLength: 1
					}, 
					function (data) 
					{
						var results = [];

						jQuery.each(data, function (i, val) {
							results.push({ value: val.value, text: val.text });
						});

						return results;
					},
					{
						width: "100%",
						no_results_text: "{{ $controller->LL('notice.not-found') }}"
					});

				});

			</script>

			<?php

			$jsCode = ob_get_contents();

			ob_end_clean();

			$controllerRequest->addJsCode($jsCode); 

			?>
			
            @if ($field->translate('description'))
            <span title="" data-content="{{ $field->translate('description') }}" data-placement="right" data-trigger="hover" data-rel="popover" 
                  class="help-button" data-original-title="{{trans('core::default.tooltip.description')}}">?</span>
            @endif
            
		</div>
	</div> 
</div>