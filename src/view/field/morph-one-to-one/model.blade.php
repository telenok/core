<?php

    $method = camel_case($field->code);
    $jsUnique = str_random();

	$domAttr = ['disabled' => 'disabled', 'class' => 'col-xs-5 col-sm-5'];

	$title = '';
	$id = 0; 

	if ($model->exists && $result = $model->{$method})
	{
		$title = $result->translate('title');
		$id = $result->id;
	}
	
	$disabledCreateLinkedType = false;

	$linkedType = $controller->getLinkedModelType($field);

	if (!app('auth')->can('create', 'object_type.' . $linkedType->code) && $field->morph_one_to_one_has)
	{
		$disabledCreateLinkedType = true;
	}
?>

    <div class="form-group" data-field-key='{{ $field->code }}'>
        {!! Form::label($field->code, $field->translate('title'), ['class'=>'col-sm-3 control-label no-padding-right']) !!}
        <div class="col-sm-9"> 
            @if ($field->icon_class)
            <span class="input-group-addon">
                <i class="{{ $field->icon_class }}"></i>
            </span>
            @endif
            
            {!! Form::hidden($field->code, $id) !!}
            {!! Form::text(str_random(), ($id ? "[{$id}] " : "") . $title, $domAttr ) !!}
            
			@if ( 
					((!$model->exists && $field->allow_create && $permissionCreate) 
						|| 
					($model->exists && $field->allow_update && $permissionUpdate))
				)
            <button onclick="chooseMorphO2O{{$jsUnique}}(this, '{!! $urlWizardChoose !!}'); return false;" data-toggle="modal" class="btn btn-sm" type="button">
                <i class="fa fa-bullseye"></i>
                {{ $controller->LL('btn.choose') }}
            </button>
            @endif
			
			@if ( 
					((!$model->exists && $field->allow_create && $permissionCreate) 
						|| 
					($model->exists && $field->allow_update && $permissionUpdate)) && !$disabledCreateLinkedType
				)
            <button onclick="createMorphO2O{{$jsUnique}}(this, '{!! $urlWizardCreate !!}'); return false;" data-toggle="modal" class="btn btn-sm" type="button">
                <i class="fa fa-plus"></i>
                {{ $controller->LL('btn.create') }}
            </button>
            @endif
			
			@if ( 
					((!$model->exists && $field->allow_create && $permissionCreate) 
						|| 
					($model->exists && $field->allow_update && $permissionUpdate))
				)
            <button onclick="editMorphO2O{{$jsUnique}}(this, '{!! $urlWizardEdit !!}'); return false;" data-toggle="modal" class="btn btn-sm btn-success" type="button">
                <i class="fa fa-pencil"></i>
                {{ $controller->LL('btn.edit') }}
            </button>
            @endif

			@if ( 
					((!$model->exists && $field->allow_create && $permissionCreate) 
						|| 
					($model->exists && $field->allow_update && $permissionUpdate))
				)
            <button onclick="deleteMorphO2O{{$jsUnique}}(this); return false;" data-toggle="modal" class="btn btn-sm btn-danger" type="button">
                <i class="fa fa-trash-o"></i>
                {{ $controller->LL('btn.delete') }}
            </button>
            @endif

            @if ($field->translate('description'))
            <span title="" data-content="{{ $field->translate('description') }}" data-placement="right" data-trigger="hover" data-rel="popover" 
                  class="help-button" data-original-title="{{trans('core::default.tooltip.description')}}">?</span>
            @endif
        </div>
    </div>

    <script type="text/javascript">
        
        function createMorphO2O{{$jsUnique}}(obj, url) 
        {
            var $block = jQuery(obj).closest('div.form-group');

            jQuery.ajax({
                url: url,
                method: 'get',
                dataType: 'json'
            }).done(function(data) {

                if (!jQuery('#modal-{{$jsUnique}}').size())
                {
                    jQuery('body').append('<div id="modal-{{$jsUnique}}" class="modal fade" role="dialog" aria-labelledby="label"></div>');
                }
				
				var $modal = jQuery('#modal-{{$jsUnique}}');

                $modal.data('model-data', function(data)
                {
                    jQuery('input[type="text"]', $block).val(data.title);
                    jQuery('input[type="hidden"]', $block).val(data.id);
                });
						
				$modal.html(data.tabContent);
						
				$modal.modal('show').on('hidden', function() 
                { 
                    jQuery(this).empty(); 
                });
            });
        }

        function editMorphO2O{{$jsUnique}}(obj, url) 
        {
            var $block = jQuery(obj).closest('div.form-group');

            var id = jQuery('input[type="hidden"]', $block).val();
            
            if (id == 0) return false;
            
            url = url.replace('--id--', id);

            jQuery.ajax({
                url: url,
                method: 'get',
                dataType: 'json'
            }).done(function(data) {

                if (!jQuery('#modal-{{$jsUnique}}').size())
                {
                    jQuery('body').append('<div id="modal-{{$jsUnique}}" class="modal fade" role="dialog" aria-labelledby="label"></div>');
                }

				var $modal = jQuery('#modal-{{$jsUnique}}');

                $modal.data('model-data', function(data)
                {
                    jQuery('input[type="text"]', $block).val(data.title);
                    jQuery('input[type="hidden"]', $block).val(data.id);

                });
						
				$modal.html(data.tabContent);
						
				$modal.modal('show').on('hidden', function() 
                { 
                    jQuery(this).empty(); 
                });
            });
        }

        function deleteMorphO2O{{$jsUnique}}(obj) 
        {
            var $block = jQuery(obj).closest('div.form-group');

            jQuery('input[type="text"]', $block).val('');
            jQuery('input[type="hidden"]', $block).val(0);
        }

        function chooseMorphO2O{{$jsUnique}}(obj, url) 
        {
            var $block = jQuery(obj).closest('div.form-group');

            jQuery.ajax({
                url: url,
                method: 'get',
                dataType: 'json'
            }).done(function(data) {

                if (!jQuery('#modal-{{$jsUnique}}').size())
                {
                    jQuery('body').append('<div id="modal-{{$jsUnique}}" class="modal fade" role="dialog" aria-labelledby="label"></div>');
                }
				
				var $modal = jQuery('#modal-{{$jsUnique}}');

                $modal.data('model-data', function(data)
                {
                    jQuery('input[type="text"]', $block).val(data.title);
                    jQuery('input[type="hidden"]', $block).val(data.id);
                });
						
				$modal.html(data.tabContent);
						
				$modal.modal('show').on('hidden', function() 
				{
                    jQuery(this).empty(); 
                });
            });
        }
        
    </script>