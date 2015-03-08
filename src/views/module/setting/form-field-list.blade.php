							@if (!in_array($field->code, ['value'], true))

								{!! app('telenok.config.repository')->getObjectFieldController()->get($field->key)->getFormModelContent($controller, $model, $field, $uniqueId) !!}

							@elseif ($field->code=='value')

								<?php

								$w = app('telenok.config.repository')->getSetting()->get(strtolower($model->code));

								?>

								@if ($w)

									{!! $w->getFormSettingContent($field, $model, $uniqueId) !!}

								@else

									{!! app('telenok.config.repository')->getObjectFieldController()->get($field->key)->getFormModelContent($controller, $model, $field, $uniqueId) !!}

								@endif

							@endif
