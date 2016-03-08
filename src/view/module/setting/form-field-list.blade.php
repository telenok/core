	@if (!in_array($field->code, ['value'], true))

		{!! app('telenok.config.repository')->getObjectFieldController($field->key)->getFormModelContent($controller, $model, $field, $uniqueId) !!}

	@elseif ($field->code=='value')

		<?php

		try
		{
                    $w = app('telenok.config.repository')->getSetting(strtolower($model->code));
			
                    echo $w->getFormSettingContent($field, $model, $uniqueId);
		}
		catch (\Exception $e)
		{
                    echo app('telenok.config.repository')->getObjectFieldController($field->key)->getFormModelContent($controller, $model, $field, $uniqueId);
		}
		
		?>

	@endif
