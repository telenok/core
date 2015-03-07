<?php

    $jsUnique = str_random();

?>

<div class="input-group col-sm-1">
    <div class="input-group" id="time-picker-time-start-{{$jsUnique}}">
        <span class="input-group-addon datepickerbutton">
            <i class="fa fa-clock-o bigger-110"></i>
        </span>
        {!! Form::text("filter[" . $field->code. "][start]", "") !!}
    </div>           
    <span class="input-group-addon">
        <i class="fa fa-arrow-right"></i>
    </span>
    <div class="input-group" id="time-picker-time-end-{{$jsUnique}}">
        {!! Form::text("filter[" . $field->code. "][end]", "") !!}
        <span class="input-group-addon datepickerbutton">
            <i class="fa fa-clock-o bigger-110"></i>
        </span>
    </div>
</div>

<script type="text/javascript">
	jQuery("#time-picker-time-start-{{$jsUnique}}, #time-picker-time-end-{{$jsUnique}}").datetimepicker(
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