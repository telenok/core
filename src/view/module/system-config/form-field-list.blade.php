	@if (!in_array($field->code, ['value'], true))

            {!! app('telenok.repository')->getObjectFieldController($field->key)->getFormModelContent($controller, $model, $field, $uniqueId) !!}

	@elseif ($field->code=='value')

            <?php

            try
            {
                if ($model->controller_class && class_exists($model->controller_class))
                {
                    echo app($model->controller_class)->getValueContent($controller, $model, $field, $uniqueId);
                }
                else
                {
                    throw new \Exception();
                }
            }
            catch (\Exception $e)
            {

                $domAttr = ['id' => $field->code . '-' . $uniqueId, 'class' => $field->css_class?: 'col-xs-5 col-sm-5'];
                $disabled = false;

                $value = $model->{$field->code};

                if ( (!$model->exists && !$field->allow_create) || ($model->exists && !$field->allow_update) )
                {
                    $domAttr['disabled'] = 'disabled';
                    $disabled = true;
                }
                ?>

                <div class="form-group" data-field-key='{{ $field->code }}'>
                    {!! Form::label($disabled ? str_random() : "{$field->code}[{$model->code}]", $field->translate('title'), array('class'=>'col-sm-3 control-label no-padding-right')) !!}
                    <div class="col-sm-9">

                        @if ((is_string($value) && mb_strlen($value) < 250) || is_numeric($value))
                            {!! Form::text("{$field->code}[{$model->code}]", $value, $domAttr) !!}
                        @elseif (is_string($value) && mb_strlen($value) >= 250)
                            <?php
                            $domAttr['class'] = $field->css_class ?: 'form-control';
                            ?>
                            {!! Form::textarea("{$field->code}[{$model->code}]", $value, $domAttr ) !!}

                        @elseif ($value instanceof \Illuminate\Support\Collection)

                            <?php
                            $domAttr['disabled'] = 'disabled';
                            ?>

                            {!! Form::text("", 'Not editable Object Collection', $domAttr) !!}

                        @else

                            <?php
                            $domAttr['disabled'] = 'disabled';
                            ?>

                            {!! Form::text("", 'Empty data', $domAttr) !!}

                        @endif

                    </div>
                </div>

            <?php

            }

            ?>

	@endif
