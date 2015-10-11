<?php

    $jsUnique = str_random();

?>

<div class="input-group col-sm-1">
    <div class="input-group" id="datetime-picker-datetime-start-{{$jsUnique}}">
        <span class="input-group-addon datepickerbutton">
            <i class="fa fa-clock-o bigger-110"></i>
        </span>
        {!! Form::text("filter[" . $field->code. "][start]", "") !!}
    </div>           
    <span class="input-group-addon">
        <i class="fa fa-arrow-right"></i>
    </span>
    <div class="input-group" id="datetime-picker-datetime-end-{{$jsUnique}}">
        {!! Form::text("filter[" . $field->code. "][end]", "") !!}
        <span class="input-group-addon datepickerbutton">
            <i class="fa fa-clock-o bigger-110"></i>
        </span>
    </div>
</div>

<script type="text/javascript">
	jQuery("#datetime-picker-datetime-start-{{$jsUnique}}, #datetime-picker-datetime-end-{{$jsUnique}}").datetimepicker(
	{
        format: "YYYY-MM-DD HH:mm:ss",
        useSeconds: true,
		pick12HourFormat: false,
		autoclose: true,
		minuteStep: 1,
        useCurrent: true
	});
</script>