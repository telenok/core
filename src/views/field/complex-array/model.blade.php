
<?php
$domAttr = ['id' => $field->code . '-' . $uniqueId, 'class' => $field->css_class?: 'col-xs-5 col-sm-5'];
$disabled = false;

$value = $model->{$field->code};

if ( (!$model->exists && (!$field->allow_create || !$permissionCreate)) || ($model->exists && (!$field->allow_update || !$permissionUpdate)) )
{
	$domAttr['disabled'] = 'disabled';
	$disabled = true;
}
?> 

<div class="form-group">
    {!! Form::label($disabled ? str_random() : "{$field->code}", $field->translate('title'), array('class'=>'col-sm-3 control-label no-padding-right')) !!}
	<div class="col-sm-9">
		
		@if ((is_string($value) && mb_strlen($value) < 250) || is_numeric($value))
			{!! Form::text("{$field->code}", $value, $domAttr) !!}
		@elseif (is_string($value) && mb_strlen($value) >= 250)
		
			<?php
				$domAttr['class'] = $field->css_class?: 'form-control';
			?>
		
			{!! Form::textarea("{$field->code}", $value, $domAttr ) !!}
			
		@elseif ($value instanceof \Illuminate\Support\Collection)
		
			<?php 	
				$domAttr['disabled'] = 'disabled';
			?> 
		
			{!! Form::text("", 'Not editable Object Collection', $domAttr) !!}
		
		@else
		
			<?php 	
				$domAttr['disabled'] = 'disabled';
			?> 
	
			{!! Form::text("", 'Empty data', $domAttr) !!}
		
		@endif
		
	</div>
</div>