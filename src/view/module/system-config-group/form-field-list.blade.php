{!! app('telenok.repository')->getObjectFieldController($field->key)->getFormModelContent($controller, $model, $field, $uniqueId) !!}

@if ($field->code == 'code')

    <div class="widget-box transparent" data-field-key='{{ $field->code }}'>
        <div class="widget-header widget-header-small">
            <h4 class="row">
                <span class="col-sm-12">
                    <i class="ace-icon fa fa-list-ul"></i>
                    Settings
                </span>
            </h4>
        </div>
        <div class="widget-body">
            <div class="widget-main form-group field-list">

    <?php

    if ($model->exists && $model->config()->active()->count())
    {
        $field_  = \App\Vendor\Telenok\Core\Model\Object\Field::where(function($query)
        {
            $query->where('field_object_type', \App\Vendor\Telenok\Core\Model\Object\Type::where('code', 'config')->first()->getKey());
            $query->where('code', 'value');
        })->first();

        $model->config()->active()->get()->each(function($item) use ($controller, $model, $field_, $uniqueId)
        {
            $field_->title = $item->title;

            try
            {
                if ($item->controller_class && class_exists($item->controller_class))
                {
                    echo app($item->controller_class)->getValueContent($controller, $item, $field_, $uniqueId);
                }
                else
                {
                    throw new \Exception();
                }
            }
            catch (\Exception $e)
            {
                $domAttr = ['id' => $field_->code . '-' . $uniqueId, 'class' => $field_->css_class?: 'col-xs-5 col-sm-5'];
                $disabled = false;

                $value = $item->{$field_->code};

                if ( (!$item->exists && !$field_->allow_create) || ($item->exists && !$field_->allow_update) )
                {
                    $domAttr['disabled'] = 'disabled';
                    $disabled = true;
                }
                ?>

                <div class="form-group" data-field-key='{{ $field_->code }}'>
                    {!! Form::label($disabled ? str_random() : "{$field_->code}[{$item->code}]",
                        $field_->translate('title'), array('class'=>'col-sm-3 control-label no-padding-right')) !!}
                    <div class="col-sm-9">

                        @if ((is_string($value) && mb_strlen($value) < 250) || is_numeric($value))
                            {!! Form::text("{$field_->code}[{$item->code}]", $value, $domAttr) !!}
                        @elseif (is_string($value) && mb_strlen($value) >= 250)
                            <?php
                            $domAttr['class'] = $field_->css_class ?: 'form-control';
                            ?>
                            {!! Form::textarea("{$field_->code}[{$item->code}]", $value, $domAttr ) !!}

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
        });
    }

    ?>

            </div>
        </div>
    </div>

@endif
