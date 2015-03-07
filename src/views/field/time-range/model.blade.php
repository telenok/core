<?php 
    
    $domAttrStart = ['id' => $field->code . '-start-' . $uniqueId, 'class' => $field->css_class?: ''];
    $disabled = false;
     
	
    if ((!$model->exists && (!$field->allow_create || !$permissionCreate)) || ($model->exists && (!$field->allow_update || !$permissionUpdate)))
    {
        $domAttr['disabled'] = 'disabled';
        $disabled = true; 
    }
    
    if ($model->exists && $model->{$field->code . '_start'})
    {
        $valueStart = $model->{$field->code . '_start'}->setTimezone(\Config::get('app.timezone'));
    }
    else 
    {
        $valueStart = $field->time_range_default_start->setTimezone(\Config::get('app.timezone'));
    }
    
    if ($model->exists && $model->{$field->code . '_end'})
    {
        $valueEnd = $model->{$field->code . '_end'}->setTimezone(\Config::get('app.timezone'));
    }
    else 
    {
        $valueEnd = $field->time_range_default_end->setTimezone(\Config::get('app.timezone'));
    }

    $domAttrStart['placeholder'] = ($placeholder = $field->time_range_default_start->toTimeString()) ? $placeholder : $field->translate('title');
    $domAttrEnd = $domAttrStart;
    $domAttrEnd['placeholder'] = ($placeholder = $field->time_range_default_end->toTimeString()) ? $placeholder : $field->translate('title');
    $domAttrEnd['id'] = $field->code . '-end-' . $uniqueId;
    
    $random = str_random();
?>



<div class="form-group">
	{!! Form::label("{$field->code}_start", $field->translate('title'), array('class' => 'col-sm-3 control-label no-padding-right')) !!}
	<div class="col-sm-3">
        <div class="input-daterange input-group">
            <div class="input-group" id="datetime-picker-time-start-{{$random}}">
                @if ($field->icon_class)
                <span class="input-group-addon datepickerbutton">
                    <i class="{{ $field->icon_class }}"></i>
                </span>
                @else
                <span class="input-group-addon datepickerbutton">
                    <i class="fa fa-clock-o bigger-110"></i>
                </span>
                @endif
                {!! Form::text($field->code . '_start', $valueStart ? $valueStart->toTimeString() : '', $domAttrStart) !!}
            </div>           
            <span class="input-group-addon">
                <i class="fa fa-arrow-right"></i>
            </span>
            <div class="input-group" id="datetime-picker-time-end-{{$random}}">
                {!! Form::text($field->code . '_end', $valueEnd ? $valueEnd->toTimeString() : '', $domAttrEnd) !!}
                @if ($field->icon_class)
                <span class="input-group-addon datepickerbutton">
                    <i class="{{ $field->icon_class }}"></i>
                </span>
                @else
                <span class="input-group-addon datepickerbutton">
                    <i class="fa fa-clock-o bigger-110"></i>
                </span>
                @endif
            </div>
            @if ($field->translate('description'))
            <span title="" data-content="{{ $field->translate('description') }}" data-placement="right" data-trigger="hover" data-rel="popover" 
                  class="help-button" data-original-title="{{\Lang::get('core::default.tooltip.description')}}">?</span>
            @endif
        </div>
    </div>
</div>

<script type="text/javascript">
	jQuery("#datetime-picker-time-start-{{$random}}, #datetime-picker-time-end-{{$random}}")
    .not('.datetime-picker-added').addClass('datetime-picker-added')
    .datetimepicker(
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
 