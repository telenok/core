<?php 
    
    $domAttr = ['id' => $field->code . '-' . $uniqueId, 'class' => 'ace ' . ($field->css_class?: '')];
    $disabled = false;

	if ( (!$model->exists && (!$field->allow_create || !$permissionCreate)) || ($model->exists && (!$field->allow_update || !$permissionUpdate)) )
    {
        $domAttr['disabled'] = 'disabled';
        $disabled = true; 
    }

    $localeDefault = config('app.localeDefault');
    $locale = config('app.locale');

    $title = $field->select_many_data->get('title', []);
    $keys = $field->select_many_data->get('key', []);
    $default = $field->select_many_data->get('default', []);

    if ($field->multilanguage)
    {
        $titleLocale = array_get($title, $locale, []);

        if (empty($titleLocale))
        {
            $titleLocale = array_get($title, $localeDefault, []);
        }

        $values = array_combine($keys, $titleLocale);
    }
    else
    {
        $values = array_combine($keys, $title);
    }
?>

<div class="form-group" data-field-key='{{ $field->code }}'>

    {!! Form::label("{$field->code}", $field->translate('title'), array('class' => 'col-sm-3 control-label no-padding-right')) !!}

    <div class="col-sm-9">
        <div>
            <div class="control-group">

                @foreach($values as $k => $v)

                <?php

                    $checked = ($model->exists && $model->{$field->code}->search($k) !== false) || (!$model->exists && in_array($k, $default)) ? 1 : 0;

                ?>

                <div class="checkbox">
                    <label>
                        {!! Form::checkbox($field->code . '[]', $k, $checked, $domAttr) !!}
                        <span class="lbl"> {{$v}}</span>
                    </label>
                </div>

                @endforeach

            </div>

            @if ($field->translate('description'))
            <span title="" data-content="{{ $field->translate('description') }}" data-placement="right" data-trigger="hover" data-rel="popover" 
                  class="help-button" data-original-title="{{trans('core::default.tooltip.description')}}">?</span>
            @endif

        </div>
    </div>
</div>

