
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
	{!! Form::label("upload_allow_size", $controller->LL('property.upload_allow_size'), array('class'=>'col-sm-3 control-label no-padding-right')) !!}
	<div class="col-sm-9"> 
		{!! Form::text("upload_allow_size", $model->upload_allow_size, array('class'=>'ace ace-switch ace-switch-3')) !!}
	</div>
</div>

<div class="form-group">
	{!! Form::label('upload_allow_ext', $controller->LL('property.upload_allow_ext'), array('class'=>'col-sm-3 control-label no-padding-right')) !!}
	<div class="col-sm-9">
		<select multiple="multiple" class="form-control" data-placeholder="{{$controller->LL('property.upload_allow_ext')}}" id="upload_allow_ext{{$uniqueId}}" name="upload_allow_ext[]">

			<?php
				$allowedExt = $model->upload_allow_ext->all();
			?>

			@foreach(\App\Telenok\Core\Model\File\FileExtension::all()->sort(function($a, $b) { return strcmp($a->extension, $b->extension); }) as $extension)

			<option value="{{$extension->extension}}" @if (in_array($extension->extension, $allowedExt, true)) selected="selected" @endif >[{{$extension->extension}}] {{$extension->translate('title')}}</option>

			@endforeach
		</select>
	</div>
</div>

<div class="form-group">
	{!! Form::label('upload_allow_mime', $controller->LL('property.upload_allow_mime'), array('class'=>'col-sm-3 control-label no-padding-right')) !!}
	<div class="col-sm-9">
		<select class="" multiple="multiple" data-placeholder="{{$controller->LL('property.upload_allow_mime')}}" id="upload_allow_mime{{$uniqueId}}" name="upload_allow_mime[]">

			<?php
				$allowedMime = $model->upload_allow_mime->all();
			?>

			@foreach(\App\Telenok\Core\Model\File\FileMimeType::all()->sort(function($a, $b) { return strcmp($a->mime_type, $b->mime_type); }) as $mimeType)

			<option value="{{$mimeType->mime_type}}" @if (in_array($mimeType->mime_type, $allowedMime, true)) selected="selected" @endif >[{{$mimeType->mime_type}}] {{$mimeType->translate('title')}}</option>

			@endforeach
		</select>
	</div>
</div>
<script type="text/javascript">
    jQuery("#upload_allow_ext{{$uniqueId}}").chosen({ 
        keepTypingMsg: "{{$controller->LL('notice.typing')}}",
        lookingForMsg: "{{$controller->LL('notice.looking-for')}}", 
        minTermLength: 1,
        width: "400px",
        create_option: true,
        persistent_create_option: true,
        skip_no_results: true,
        search_contains: true
    });
    
    jQuery("#upload_allow_mime{{$uniqueId}}").chosen({ 
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

<div class="form-group">
	{!! Form::label('', $controller->LL('property.upload_storage_default_local'), array('class'=>'col-sm-3 control-label no-padding-right')) !!}
	<div class="col-sm-9">
		{!! Form::text("", config('filesystems.default'), ['disabled' => 'disabled']) !!}
	</div>
</div>

<div class="form-group">
	{!! Form::label('', $controller->LL('property.upload_storage_default_cloud'), array('class'=>'col-sm-3 control-label no-padding-right')) !!}
	<div class="col-sm-9">
		{!! Form::text("", config('filesystems.cloud'), ['disabled' => 'disabled']) !!}
	</div>
</div>

<div class="form-group">
	{!! Form::label('upload_storage', $controller->LL('property.upload_storage'), array('class'=>'col-sm-3 control-label no-padding-right')) !!}
	<div class="col-sm-9">

		<select multiple="multiple" class="form-control" data-placeholder="{{$controller->LL('property.upload_storage')}}" id="upload_storage{{$uniqueId}}" name="upload_storage[]">

			<?php 

				$disks = [
					'default_local' => 'default_local',
					'default_cloud' => 'default_cloud',
				] + (array)config('filesystems.disks');
			?>

			@foreach($disks as $k => $d)

			<option value="{{$k}}" @if (($model->upload_storage->isEmpty() && $k == 'default_local') || $model->upload_storage->search($k) !== FALSE)selected="selected"@endif>{{$k}}</option>

			@endforeach

		</select>

	</div>
</div>
