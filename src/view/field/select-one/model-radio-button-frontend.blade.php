<?php 

    $domAttr = ['id' => $field->code . '-' . $uniqueId, 'class' => 'ace ' . ($field->css_class?: '')];
    $disabled = false;

	if ((!$model->exists && (!$field->allow_create || !$permissionCreate)) || ($model->exists && (!$field->allow_update || !$permissionUpdate)))
    {
        $domAttr['disabled'] = 'disabled';
        $disabled = true; 
    }

    $localeDefault = config('app.localeDefault');
    $locale = config('app.locale');

    $title = $field->select_one_data->get('title', []);
    $keys = $field->select_one_data->get('key', []);
    $default = $field->select_one_data->get('default');

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
	{!! Form::label("{$field->code}", $field->translate('title'), array('class' => 'col-xs-2 control-label text-right')) !!}
	<div class="col-sm-10">
        <div>
            <div class="control-group">

                @foreach($values as $k => $v)

                <?php

                    $checked = ($model->exists && strcmp($k, $model->{$field->code}) === 0) || (!$model->exists && strcmp($k, $default) === 0) ? 1 : 0;

                ?>

                <div class="radio">
                    <label>

						<?php

						$domAttr['id'] .= '-' . $k;

						?>
						
                        {!! Form::radio($field->code, $k, $checked, $domAttr) !!}

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

