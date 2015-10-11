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

<div class="form-group" data-field-key='{{ $field->code }}'>

	{!! Form::label("{$field->code}", $field->translate('title'), array('class' => 'col-xs-2 control-label text-right')) !!}

	<div class="col-sm-10">

            @if ($field->icon_class)
		<div class="input-group">
            <span class="input-group-addon">
                <i class="{{ $field->icon_class }}"></i>
            </span>
            @else
		<div>
            @endif	
            
            {!! Form::select($field->code, $values, $model->exists ? $model->{$field->code} : $default, $domAttr) !!}

            @if ($field->translate('description'))
            <span title="" data-content="{{ $field->translate('description') }}" data-placement="right" data-trigger="hover" data-rel="popover" 
                  class="help-button" data-original-title="{{trans('core::default.tooltip.description')}}">?</span>
            @endif
            
		</div>
	</div> 
</div>


