
@include('core::field.common-view.field-view')

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
 

<div class="form-group">
    {!! Form::label("date_default", $controller->LL('property.date_default'), array('class'=>'col-sm-3 control-label no-padding-right')) !!}
	<div class="col-sm-3">
        <div class="input-group" id="date-picker-time-{{$uniqueId}}">
			<span class="input-group-addon datepickerbutton">
				<i class="fa fa-clock-o bigger-110"></i>
			</span>
			{!! Form::text("date_default", $model->date_default) !!}
		</div>
	</div>
</div>

<script type="text/javascript">
	jQuery("#date-picker-time-{{$uniqueId}}").not('.date-picker-added').addClass('date-picker-added').datetimepicker(
	{
        format: "YYYY-MM-DD",
        useSeconds: true,
		pick12HourFormat: false,
		autoclose: true,
		minuteStep: 1,
        useCurrent: true
	});
</script>