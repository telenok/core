<?php 
    
    $domAttr = ['id' => $field->code . '-' . $uniqueId, 'class' => $field->css_class?: ''];
    $disabled = false;

	if ( (!$model->exists && (!$field->allow_create || !$permissionCreate)) || ($model->exists && (!$field->allow_update || !$permissionUpdate)) )
    {
        $domAttr['disabled'] = 'disabled';
        $disabled = true; 
    }

    $localeDefault = config('app.localeDefault');
    $locale = config('app.locale');

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
	{!! Form::label("{$field->code}", $field->translate('title'), array('class' => 'col-xs-2 control-label text-right')) !!}
	<div class="col-sm-10">
        <div>
            <div class="btn-group btn-overlap" data-toggle="buttons">
                @foreach($values as $k => $v)
                <?php

                    $checked = ($model->exists && strcmp($k, $model->{$field->code}) === 0) || (!$model->exists && strcmp($k, $default) === 0) ? 1 : 0;
					
					$domAttr['id'] .= '-' . $k;

                ?>
                <label class="btn btn-white btn-sm btn-primary @if ($checked) active @endif" @if ($disabled) disabled="disabled" @endif>
					   
                    {!! Form::radio($field->code, $k, $checked, $domAttr) !!}
                       
                    <input type="radio" @if ($checked) checked="checked" @endif name="{{$field->code}}" value="{{$k}}" @if ($disabled) disabled="disabled" @endif /> {{$v}}
                </label>
                @endforeach
            </div>
            @if ($field->translate('description'))
            <span title="" data-content="{{ $field->translate('description') }}" data-placement="right" data-trigger="hover" data-rel="popover" 
                  class="help-button" data-original-title="{{trans('core::default.tooltip.description')}}">?</span>
            @endif
        </div>
    </div>
</div>

