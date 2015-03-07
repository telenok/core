
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
	{!! Form::label('file_many_to_many_allow_ext', $controller->LL('property.file_many_to_many_allow_ext'), array('class'=>'col-sm-3 control-label no-padding-right')) !!}
	<div class="col-sm-9">
		<select multiple="multiple" class="form-control" data-placeholder="{{$controller->LL('property.file_many_to_many_allow_ext')}}" id="file_many_to_many_allow_ext{{$uniqueId}}" name="file_many_to_many_allow_ext[]">

			<?php
				$allowedExt = $model->file_many_to_many_allow_ext->all();
			?>

			@foreach(\App\Model\Telenok\File\FileExtension::all()->sort(function($a, $b) { return strcmp($a->extension, $b->extension); }) as $extension)

			<option value="{{$extension->extension}}" @if (in_array($extension->extension, $allowedExt, true)) selected="selected" @endif >[{{$extension->extension}}] {{$extension->translate('title')}}</option>

			@endforeach
		</select>
	</div>
</div>
<div class="form-group">
	{!! Form::label('file_many_to_many_allow_mime', $controller->LL('property.file_many_to_many_allow_mime'), array('class'=>'col-sm-3 control-label no-padding-right')) !!}
	<div class="col-sm-9">
		<select class="" multiple="multiple" data-placeholder="{{$controller->LL('property.file_many_to_many_allow_mime')}}" id="file_many_to_many_allow_mime{{$uniqueId}}" name="file_many_to_many_allow_mime[]">

			<?php
				$allowedMime = $model->file_many_to_many_allow_mime->all();
			?>

			@foreach(\App\Model\Telenok\File\FileMimeType::all()->sort(function($a, $b) { return strcmp($a->mime_type, $b->mime_type); }) as $mimeType)

			<option value="{{$mimeType->mime_type}}" @if (in_array($mimeType->mime_type, $allowedMime, true)) selected="selected" @endif >[{{$mimeType->mime_type}}] {{$mimeType->translate('title')}}</option>

			@endforeach
		</select>
	</div>
</div>
<script type="text/javascript">
    jQuery("#file_many_to_many_allow_ext{{$uniqueId}}").chosen({ 
        keepTypingMsg: "{{$controller->LL('notice.typing')}}",
        lookingForMsg: "{{$controller->LL('notice.looking-for')}}", 
        minTermLength: 1,
        width: "400px",
        create_option: true,
        persistent_create_option: true,
        skip_no_results: true,
        search_contains: true
    });
    
    jQuery("#file_many_to_many_allow_mime{{$uniqueId}}").chosen({ 
        keepTypingMsg: "{{$controller->LL('notice.typing')}}",
        lookingForMsg: "{{$controller->LL('notice.looking-for')}}", 
        minTermLength: 1,
        width: "400px",
        create_option: true,
        persistent_create_option: true,
        skip_no_results: true,
        search_contains: true
    });
</script>
