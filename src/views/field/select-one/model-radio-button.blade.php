<?php 

    $domAttr = ['id' => $field->code . '-' . $uniqueId, 'class' => 'ace ' . ($field->css_class?: '')];
    $disabled = false;

	if ((!$model->exists && (!$field->allow_create || !$permissionCreate)) || ($model->exists && (!$field->allow_update || !$permissionUpdate)))
    {
        $domAttr['disabled'] = 'disabled';
        $disabled = true; 
    }

    $localeDefault = \Config::get('app.localeDefault');
    $locale = \Config::get('app.locale');

    $title = $field->select_one_data->get('title', []);
    $keys = $field->select_one_data->get('key', []);
    $default = $field->select_one_data->get('default');
    $titleLocale = array_get($title, $locale, []);

    if (empty($titleLocale))
    {
        $titleLocale = array_get($title, $localeDefault, []);
    }

    $values = array_combine($keys, $titleLocale);

?>

<div class="form-group">
	{!! Form::label("{$field->code}", $field->translate('title'), array('class' => 'col-sm-3 control-label no-padding-right')) !!}
	<div class="col-sm-9">
        <div>
            <div class="control-group">

                @foreach($values as $k => $v)

                <?php

                    $checked = ($model->exists && strcmp($k, $model->{$field->code}) === 0) || (!$model->exists && strcmp($k, $default) === 0) ? 1 : 0;

                ?>

                <div class="radio">
                    <label>

                        {!! Form::radio($field->code, $k, $checked, $domAttr) !!}

                        <span class="lbl"> {{$v}}</span>
                    </label>
                </div>
                
                @endforeach
                
            </div>
            
            @if ($field->translate('description'))
            <span title="" data-content="{{ $field->translate('description') }}" data-placement="right" data-trigger="hover" data-rel="popover" 
                  class="help-button" data-original-title="{{\Lang::get('core::default.tooltip.description')}}">?</span>
            @endif
            
        </div>
    </div>
</div>

