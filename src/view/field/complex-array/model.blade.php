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

<div class="form-group" data-field-key='{{ $field->code }}'>
    {!! Form::label($disabled ? str_random() : "{$field->code}", $field->translate('title'), array('class'=>'col-sm-3 control-label no-padding-right')) !!}
	<div class="col-sm-9">

		@if ($value->isEmpty())
			<?php
				$domAttr['disabled'] = 'disabled';
			?>
			{!! Form::text("", 'Empty data', $domAttr) !!}
		@else
			<?php
				$domAttr['disabled'] = 'disabled';
			?>

			{!! Form::text("", 'Not editable Object Collection', $domAttr) !!}
		@endif

	</div>
</div>