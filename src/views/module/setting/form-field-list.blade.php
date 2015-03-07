							@if (!in_array($field->code, ['value'], true))

								{!! app('telenok.config')->getObjectFieldController()->get($field->key)->getFormModelContent($controller, $model, $field, $uniqueId) !!}

							@elseif ($field->code=='value')

								<?php

								$w = app('telenok.config')->getSetting()->get(strtolower($model->code));

								?>

								@if ($w)

									{!! $w->getFormSettingContent($field, $model, $uniqueId) !!}

								@else

									{!! app('telenok.config')->getObjectFieldController()->get($field->key)->getFormModelContent($controller, $model, $field, $uniqueId) !!}

								@endif

							@endif
