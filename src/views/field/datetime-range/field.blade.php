
<div class="form-group">
	{!! Form::label("required", $controller->LL('property.required'), array('class'=>'col-sm-3 control-label no-padding-right')) !!}
	<div class="col-sm-9">
        <div data-toggle="buttons" class="btn-group btn-overlap">
            <label class="btn btn-white btn-sm btn-primary @if (!$model->required) active @endif">
                <input type="radio" value="0" name="required" @if (!$model->required) checked="checked" @endif> {{$controller->LL('btn.no')}}
            </label>

            <label class="btn btn-white btn-sm btn-primary @if ($model->required) active @endif">
                <input type="radio" value="1" name="required" @if ($model->required) checked="checked" @endif> {{$controller->LL('btn.yes')}}
            </label>
        </div>
    </div>
</div>
 
<div class="row">
    {!! Form::label("datetime_range_default_start", $controller->LL('property.datetimerange_default'), array('class'=>'col-sm-3 control-label no-padding-right')) !!}
	<div class="col-sm-3">
        <div class="input-daterange input-group">
            <div class="input-group" id="datetime-picker-time-start-{{$uniqueId}}">
                <span class="input-group-addon datepickerbutton">
                    <i class="fa fa-clock-o bigger-110"></i>
                </span>
                {!! Form::text("datetime_range_default_start", $model->datetime_range_default_start) !!}
            </div>           
            <span class="input-group-addon">
                <i class="fa fa-arrow-right"></i>
            </span>
            <div class="input-group" id="datetime-picker-time-end-{{$uniqueId}}">
                {!! Form::text("datetime_range_default_end", $model->datetime_range_default_end) !!}
                <span class="input-group-addon datepickerbutton">
                    <i class="fa fa-clock-o bigger-110"></i>
                </span>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
	jQuery("#datetime-picker-time-start-{{$uniqueId}}, #datetime-picker-time-end-{{$uniqueId}}")
    .not('.datetime-picker-added').addClass('datetime-picker-added')
    .datetimepicker(
	{
        format: 'YYYY-MM-DD HH:mm:ss',
        useSeconds: true,
		pick12HourFormat: false,
		autoclose: true,
		minuteStep: 1,
        useCurrent: true
	});
    
    jQuery("#datetime-picker-time-start-{{$uniqueId}}").on("dp.change",function (e) {
        jQuery('#datetime-picker-time-end-{{$uniqueId}}').data("DateTimePicker").setMinDate(e.date);
    });
    
    jQuery("#datetime-picker-time-end-{{$uniqueId}}").on("dp.change",function (e) {
        jQuery('#datetime-picker-time-start-{{$uniqueId}}').data("DateTimePicker").setMaxDate(e.date);
    });
</script>