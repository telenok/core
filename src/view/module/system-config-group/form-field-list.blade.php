	@if (!in_array($field->code, ['value'], true))

            {!! app('telenok.repository')->getObjectFieldController($field->key)->getFormModelContent($controller, $model, $field, $uniqueId) !!}

	@elseif ($field->code=='value')

            <?php

            try
            {
                $w = app('telenok.repository')->getConfigGroup(strtolower($model->code));

                echo $w->getFormConfigContent($field, $model, $uniqueId);
            }
            catch (\Exception $e)
            {
                echo app('telenok.repository')->getObjectFieldController($field->key)->getFormModelContent($controller, $model, $field, $uniqueId);
            }

            ?>

	@endif
