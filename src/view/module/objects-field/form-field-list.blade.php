<?php

	$jsUnique = str_random();

?>

	@if (!in_array($field->code, ['key', 'field_view'], true)) 

		{!! app('telenok.config.repository')->getObjectFieldController($field->key)->getFormModelContent($controller, $model, $field, $uniqueId) !!}
		 
	@elseif ($field->code=='key')

		{!! Form::hidden('key', $model->{$field->code}) !!}

		<div class="form-group">
			{!! Form::label('key', $field->translate('title'), array('class' => 'col-sm-3 control-label no-padding-right')) !!}
			<div class="col-sm-9">

				<?php 

				$key = ['onchange' => "onChangeType{$uniqueId}()"];

				if ($model->exists)
				{
					$key['disabled'] = 'disabled';
				}

				$selectFields = [];
				$multilanguageFields = [];

				app('telenok.config.repository')->getObjectFieldController()
                        ->reject(function($i) use ($model) { return !$model->exists && in_array($i->getKey(), ['locked-by', 'deleted-by', 'created-by', 'active', 'permission', 'updated-by'], true); })
                        ->each(function($field) use (&$selectFields, &$multilanguageFields) 
				{  
					$selectFields[$field->getKey()] = $field->getName(); 

					if ($field->allowMultilanguage())
					{
						$multilanguageFields[] = $field->getKey();
					}
				});

				?>
			{!! Form::select('key', $selectFields, $model->{$field->code}, $key) !!}
			</div>
		</div>

		<script type="text/javascript">

			function onChangeType{{$uniqueId}}()
			{
				var $form = jQuery('#model-ajax-{{$uniqueId}}');

				var $key = jQuery('select[name="key"]', $form);

				if ( ["{!! implode('","', $multilanguageFields) !!}"].join(',').indexOf($key.val()) > -1)
				{
					jQuery("div", $form).find("[data-field-key='multilanguage']").show();
				}
				else
				{
					jQuery("div", $form).find("[data-field-key='multilanguage']").hide();
				}

				jQuery.ajax({
						type: "GET",
						url: "{!! 
							route(
									'telenok.module.objects-field.field.form', 
									[
										'fieldKey' => '--fieldKey--', 
										'modelId' => '--modelId--',
										'uniqueId' => '--uniqueId--',
									]) 
							!!}"
								.replace('--fieldKey--', $key.val())
								.replace('--modelId--', '{{ (int)$model->getKey() }}')
								.replace('--uniqueId--', '{{$uniqueId}}'),
						dataType: 'html',
						success: function(data)
						{
							jQuery('#field-form-content-{{$uniqueId}}').html(data).show();
                            
                            jQuery('#field-form-content-{{$uniqueId}} .help-button').popover();
						}
					});

			} 
            
			jQuery(function()
			{
				onChangeType{{$uniqueId}}();
			});
		</script>

		<div id='field-form-content-{{$uniqueId}}' style="display: none;"></div>
		

	@endif 