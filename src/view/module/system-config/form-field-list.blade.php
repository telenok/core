	@if (!in_array($field->code, ['value'], true))

            {!! app('telenok.repository')->getObjectFieldController($field->key)->getFormModelContent($controller, $model, $field, $uniqueId) !!}

	@elseif ($field->code=='value')

            <?php

            try
            {
                if ($model->controller_class && class_exists($model->controller_class))
                {
                    echo with(new $model->controller_class)->getValueContent($controller, $model, $field, $uniqueId);
                }
                else
                {
                    throw new \Exception();
                }
            }
            catch (\Exception $e)
            {
                echo app('telenok.repository')->getObjectFieldController($field->key)->getFormModelContent($controller, $model, $field, $uniqueId);
            }

            ?>

	@endif
