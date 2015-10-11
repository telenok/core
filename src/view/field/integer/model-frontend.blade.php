    <?php 
        $domAttr = ['id' => $field->code . '-' . $uniqueId, 'placeholder' => $field->integer_default, 'class' => 'col-xs-10 col-sm-5'];
        $disabled = false;

        if (!$model->exists) 
        {
            $value = $field->integer_default;
        }
        else
        {
            $value = $model->{$field->code};
        }

        if ( (!$model->exists && (!$field->allow_create || !$permissionCreate)) || ($model->exists && (!$field->allow_update || !$permissionUpdate)) )
        {
            $domAttr['disabled'] = 'disabled';
            $disabled = true;
        }

    ?>

	<div class="form-group" data-field-key='{{ $field->code }}'>
		{!! Form::label("{$field->code}", $field->translate('title'), array('class'=>'col-xs-2 control-label text-right')) !!}
		<div class="col-xs-10">
			@if ($field->icon_class)
			<span class="input-group-addon">
				<i class="{{ $field->icon_class }}"></i>
			</span>
			@endif 

			{!! Form::text($disabled ? str_random() : "{$field->code}", $value, $domAttr) !!}

			@if ($field->translate('description'))
			<span title="" data-content="{{ $field->translate('description') }}" data-placement="right" data-trigger="hover" data-rel="popover" 
				  class="help-button" data-original-title="{{trans('core::default.tooltip.description')}}">?</span>
			@endif 
		</div>
	</div>