<?php 
    
    $domAttr = ['id' => $field->code . '-' . $uniqueId, 'class' => $field->css_class?: 'col-sm-3'];
    $disabled = false;
     
	
    if ((!$model->exists && (!$field->allow_create || !$permissionCreate)) || ($model->exists && (!$field->allow_update || !$permissionUpdate)))
    {
        $domAttr['disabled'] = 'disabled';
        $disabled = true; 
    }
    
    if ($model->exists && $model->{$field->code})
    {
        $value = $model->{$field->code}->setTimezone(config('app.timezone'));
    }
    else
    {
        $value = $field->time_default->setTimezone(config('app.timezone'));
    }

    $domAttr['placeholder'] = ($placeholder = $field->time_default->toTimeString()) ? $placeholder : $field->translate('title');
    
    $random = str_random();
?>

<div class="form-group" data-field-key='{{ $field->code }}'>
	{!! Form::label("{$field->code}", $field->translate('title'), array('class' => 'col-sm-3 control-label no-padding-right')) !!}
	<div class="col-sm-5">
        <div class="input-group" id="datetime-picker-time-{{$random}}">
            @if ($field->icon_class)
            <span class="input-group-addon datepickerbutton">
                <i class="{{ $field->icon_class }}"></i>
            </span>
            @else
			<span class="input-group-addon datepickerbutton">
				<i class="fa fa-clock-o bigger-110"></i>
			</span>
            @endif
			{!! Form::text($field->code, $value ? $value->format('H:i:s') : '', $domAttr) !!}
            @if ($field->translate('description'))
            <span title="" data-content="{{ $field->translate('description') }}" data-placement="right" data-trigger="hover" data-rel="popover" 
                  class="help-button" data-original-title="{{trans('core::default.tooltip.description')}}">?</span>
            @endif
		</div>
	</div>
</div>

<script type="text/javascript">
	jQuery("#datetime-picker-time-{{$random}}").not('.datetime-picker-added').addClass('datetime-picker-added').datetimepicker(
	{
        format: 'HH:mm:ss',
        useSeconds: true,
		pick12HourFormat: false,
		autoclose: true,
		minuteStep: 1,
        pickDate: false,
        useCurrent: true
	});
</script>
 