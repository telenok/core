<?php 
    $domAttr = [/*'class' => $field->css_class?: 'col-xs-5 col-sm-5', */'id' => 'id-file-upload-' . $field->code . '-' . $uniqueId];
    $disabled = false;

    $jsUnique = str_random();

	if ( (!$model->exists && (!$field->allow_create || !$permissionCreate)) || ($model->exists && (!$field->allow_update || !$permissionUpdate)) )
    {
        $domAttr['disabled'] = 'disabled';
        $disabled = true; 
    }
?> 

<div class="form-group" data-field-key='{{ $field->code }}'>
	{!! Form::label("{$field->code}", $field->translate('title'), array('class'=>'col-sm-3 control-label no-padding-right')) !!}
    <div class="col-sm-8">

        <div class="row">
            <div class="col-sm-6">
            @if ($model->{$field->code}->exists())

                @if ($model->{$field->code}->isImage())
                <img src="{!! $model->{$field->code}->downloadImageLink(140, 140) !!}" title="{{$model->translate('title')}}" />
                <br>
                @endif
            @elseif ($model->{$field->code}->path())
                <i class="fa fa-exclamation-triangle"></i> Empty
                <br>
            @endif

            @if ($field->upload_allow_ext->count())
                Allowed extension [{{ $field->upload_allow_ext->implode(', ') }}]
            <br>
            @endif

            {!! Form::file($field->code, $domAttr) !!}
            {!! Form::hidden($field->code . '_blob', null, ['id' => $field->code . '-' . $uniqueId . '-blob']) !!}
            </div>
            
            <div class="col-sm-6">
                <div class="dropdown">
                    <a class="btn btn-default no-hover btn-transparent btn-xs dropdown-toggle" href="#" role="button" style="border:none;"
                            type="button" id="' . $random . '" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                        <span class="glyphicon glyphicon-menu-hamburger text-muted"></span>
                        Action
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="' . $random . '">
                        
                        <li><a href="#" 
                               onclick="showEditForm{{$jsUnique}}(0, 0); return false;">
                            <i class="fa fa-file-image-o"></i> Select image upload</a>
                        </li>
                        
                        <li><a href="#" onclick="return false;"> 
                            <i class="fa fa-upload"></i> Select file upload</a>
                        </li>
                        
                        @if ($model->{$field->code}->exists())
                        <li>
                            <a href="{!! $model->{$field->code}->downloadStreamLink() !!}" 
                               title="{{$model->translate('title')}}"
                               target="_blank"><i class="fa fa-download"></i> Download file</a>
                        </li>
                        
                        @if ($model->{$field->code}->isImage())
                        <li><a href="#"
                           onclick="showEditForm{{$jsUnique}}({{$model->id}}, {{$field->id}}); return false;"
                           title="{{$model->translate('title')}}"
                           target="_blank"><i class="fa fa-pencil"></i> Edit image</a></a></li>
                        @endif
                        @endif
                        
                    </ul>
                </div>
                
            </div>            

        </div>
        
		@if ($field->translate('description'))
		<span title="" data-content="{{ $field->translate('description') }}" data-placement="right" data-trigger="hover" data-rel="popover" 
			  class="help-button" data-original-title="{{trans('core::default.tooltip.description')}}">?</span>
		@endif
    </div>
</div>


<script type="text/javascript">
    
$('#id-file-upload-{{$field->code}}-{{$uniqueId}}').ace_file_input({
    no_file: 'No File ...',
    btn_choose: 'Choose',
    btn_change: 'Change',
    droppable: false,
    onchange: null,
    thumbnail: 'small',//large, fit, small
    icon_remove: 'fa fa-times',
    maxSize: {{ (int)$field->upload_allow_size }}
    //whitelist:'gif|png|jpg|jpeg'
    //blacklist:'exe|php'
    //onchange:''
    //
});

function showEditForm{{$jsUnique}}(model_id, field_id)
{
    if (!jQuery('#modal-{{$jsUnique}}').size())
    {
        jQuery('body').append('<div id="modal-{{$jsUnique}}" class="modal" role="dialog" aria-labelledby="label"></div>');
    }

    var $modal = jQuery('#modal-{{$jsUnique}}');

    $modal.data('setImageBlob', function(data)
    {
        jQuery('#{{$field->code}}-{{$uniqueId}}-blob').val(data);
    });

    $modal.on('hidden.bs.modal', function ()
    {
        jQuery(this).empty();
        jQuery('#image{{$jsUnique}}').cropper('destroy');
        jQuery('#getCroppedCanvasModal{{$jsUnique}}').data('bs.modal', null).remove();
    });

    jQuery.ajax({
        url: "{!! route('telenok.field.upload.modal-cropper') !!}",
        method: 'get',
        dataType: 'html',
        data: { model_id : model_id, field_id: field_id, js_unique: "{{$jsUnique}}" }
    })
    .done(function(data) 
    {
        $modal.html(data).modal('show');
    });
}
</script>