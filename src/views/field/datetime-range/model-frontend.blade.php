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
        $valueStart = $model->{$field->code . '_start'}->setTimezone(config('app.timezone'));
    }
    else
    {
        $valueStart = $field->datetime_range_default_start->setTimezone(config('app.timezone'));
    }

    if ($model->exists && $model->{$field->code . '_end'})
    {
        $valueEnd = $model->{$field->code . '_end'}->setTimezone(config('app.timezone'));
    }
    else
    {
        $valueEnd = $field->datetime_range_default_end->setTimezone(config('app.timezone'));
    }
    
    $domAttrStart['placeholder'] = ($placeholder = $field->datetime_range_default_start) ? $placeholder : $field->translate('title');
    $domAttrEnd = $domAttrStart;
    $domAttrEnd['placeholder'] = ($placeholder = $field->datetime_range_default_end) ? $placeholder : $field->translate('title');
    $domAttrEnd['id'] = $field->code . '-end-' . $uniqueId;

    $random = str_random();
	
	$controllerAction->addCssFile(asset('packages/telenok/core/js/bootstrap/lib/datetimepicker/datetimepicker.css'));
	$controllerAction->addJsFile(asset('packages/telenok/core/js/bootstrap/lib/moment.js'));
	$controllerAction->addJsFile(asset('packages/telenok/core/js/bootstrap/lib/datetimepicker/datetimepicker.js'));

?>

<div class="form-group">
	{!! Form::label("{$field->code}_start" , $field->translate('title'), array('class' => 'col-xs-2 control-label text-right')) !!}
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
                {!! Form::text($field->code . '_start', $valueStart, $domAttrStart) !!}
            </div>           
            <span class="input-group-addon">
                <i class="fa fa-arrow-right"></i>
            </span>
            <div class="input-group" id="datetime-picker-time-end-{{$random}}">
                {!! Form::text($field->code . '_end', $valueEnd, $domAttrEnd) !!}
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
                  class="help-button" data-original-title="{{$controller->LL('tooltip.description')}}">?</span>
            @endif
        </div>
    </div>
</div>

<?php 
$jsCode = <<<EOF
<script type="text/javascript">
	jQuery(function(){
		jQuery("#datetime-picker-time-start-$random, #datetime-picker-time-end-$random")
		.not(".datetime-picker-added").addClass("datetime-picker-added")
		.datetimepicker(
		{
			format: "YYYY-MM-DD HH:mm:ss",
			useSeconds: true,
			pick12HourFormat: false,
			autoclose: true,
			minuteStep: 1,
			useCurrent: true
		});

		jQuery("#datetime-picker-time-start-$random").on("dp.change",function (e) {
			jQuery("#datetime-picker-time-end-$random").data("DateTimePicker").setMinDate(e.date);
		});

		jQuery("#datetime-picker-time-end-$random").on("dp.change",function (e) {
			jQuery("#datetime-picker-time-start-$random").data("DateTimePicker").setMaxDate(e.date);
		});
	});
</script>
EOF;
		
$controllerAction->addJsCode($jsCode); 

?>
 