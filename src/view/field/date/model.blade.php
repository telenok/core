<?php

    $disabled = false;
    
    $domAttr = ['id' => $field->code . '-' . $uniqueId, 'class' => $field->css_class?: 'col-sm-4'];

    if ($model->exists && $model->{$field->code})
    {
        $value = $model->{$field->code}->setTimezone(config('app.timezone'));
    }
    else
    {
        $value = $field->date_default->setTimezone(config('app.timezone'));
    }

	if ((!$model->exists && (!$field->allow_create || !$permissionCreate)) || ($model->exists && (!$field->allow_update || !$permissionUpdate)))
    {
        $domAttr['disabled'] = 'disabled';
        $disabled = true; 
    }

    $domAttr['placeholder'] = ($placeholder = $field->datetime_default) ? $placeholder : $field->translate('title');
    $random = str_random();
?>

<div class="form-group" data-field-key='{{ $field->code }}'>
	{!! Form::label("{$field->code}", $field->translate('title'), array('class' => 'col-sm-3 control-label no-padding-right')) !!}
	<div class="col-sm-8">
        <div class="input-group" id="datetime-picker-date-{{$random}}">
            @if ($field->icon_class)
            <span class="input-group-addon datepickerbutton">
                <i class="{{ $field->icon_class }}"></i>
            </span>
            @else
			<span class="input-group-addon datepickerbutton">
				<i class="fa fa-clock-o bigger-110"></i>
			</span>
            @endif
			{!! Form::text($field->code, $value ? $value->format('Y-m-d') : '', $domAttr) !!}
            @if ($field->translate('description'))
            <span title="" data-content="{{ $field->translate('description') }}" data-placement="right" data-trigger="hover" data-rel="popover" 
                  class="help-button" data-original-title="{{trans('core::default.tooltip.description')}}">?</span>
            @endif
		</div>
	</div>
</div>

<script type="text/javascript">
	jQuery("#datetime-picker-date-{{$random}}").not('.datetime-picker-added').addClass('datetime-picker-added').datetimepicker(
	{
        format: "YYYY-MM-DD",
		autoclose: true,
        pickTime: false
	});
</script>
 