
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
    {!! Form::label("file_many_to_many_allow_size", $controller->LL('property.file_many_to_many_allow_size'), array('class'=>'col-sm-3 control-label no-padding-right')) !!}
    <div class="col-sm-9">
        {!! Form::text("file_many_to_many_allow_size", $model->file_many_to_many_allow_size, array('class'=>'ace ace-switch ace-switch-3')) !!}
    </div>
</div>

<div class="form-group">

    {!! Form::label("", $controller->LL('property.categories'), array('class'=>'col-sm-3 control-label no-padding-right')) !!}

    <div class="col-sm-5">
        {!! Form::select('file_many_to_many_allow_categories[]',
            \App\Vendor\Telenok\Core\Model\File\FileCategory::active()->get(['title', 'id'])
                ->transform(function($item) {
                    return ['title' => $item->translate('title'), 'id' => $item->id];
                })->sortBy('title')->pluck('title', 'id'),
            $model->file_many_to_many_allow_categories->toArray(),
            [
                'id' => 'select-file-many-to-many-category-' . $uniqueId,
                'multiple' => 'multiple'
            ]) !!}
    </div>
    <script type="text/javascript">
        jQuery("#select-file-many-to-many-category-{{ $uniqueId }}").on("chosen:showing_dropdown", function()
        {
            telenok.maxZ("*", jQuery(this).parent().find("div.chosen-drop"));
        }).chosen({
            create_option: false,
            keepTypingMsg: "{{ $controller->LL('notice.typing') }}",
            lookingForMsg: "{{ $controller->LL('notice.looking-for') }}",
            width: '350px',
            search_contains: true
        });
    </script>
</div>

<div class="form-group">

    {!! Form::label("", $controller->LL('property.permission.read'), array('class'=>'col-sm-3 control-label no-padding-right')) !!}

    <div class="col-sm-5">
        <select class="chosen" multiple
                data-placeholder="{{$controller->LL('notice.choose')}}"
                id="select-file-many-to-many-permission-{{$uniqueId}}"
                name="file_many_to_many_allow_permission[]">
            <?php

            $sequence = new \App\Vendor\Telenok\Core\Model\Object\Sequence();

            $selectedIds = $model->file_many_to_many_allow_permission->all();

            $subjects = \App\Vendor\Telenok\Core\Model\Object\Sequence::active()
                    ->whereIn('id', (array)$selectedIds)
                    ->get();

            foreach ($subjects as $subject) {
                echo "<option value='{$subject->getKey()}' selected='selected'>[{$subject->translate('title_type')}#{$subject->id}] {$subject->translate('title')}</option>";
            }
            ?>
        </select>
    </div>
    <script type="text/javascript">
        jQuery("#select-file-many-to-many-permission-{{$uniqueId}}").ajaxChosen({
                    keepTypingMsg: "{{ $controller->LL('notice.typing') }}",
                    lookingForMsg: "{{ $controller->LL('notice.looking-for') }}",
                    type: "GET",
                    url: "{!! $urlListTitle !!}",
                    dataType: "json",
                    minTermLength: 1
                },
                function (data)
                {
                    var results = [];

                    jQuery.each(data, function (i, val) {
                        results.push({ value: val.value, text: val.text });
                    });

                    return results;
                },
                {
                    width: "100%",
                    no_results_text: "{{ $controller->LL('notice.not-found') }}"
                });
    </script>
</div>



<div class="form-group">
	{!! Form::label('file_many_to_many_allow_ext', $controller->LL('property.file_many_to_many_allow_ext'), array('class'=>'col-sm-3 control-label no-padding-right')) !!}
	<div class="col-sm-9">

        {!! Form::hidden('file_many_to_many_allow_ext[]', '') !!}

        <select multiple="multiple" class="form-control" data-placeholder="{{$controller->LL('property.file_many_to_many_allow_ext')}}" id="file_many_to_many_allow_ext{{$uniqueId}}" name="file_many_to_many_allow_ext[]">

			<?php
				$allowedExt = $model->file_many_to_many_allow_ext->all();
			?>

			@foreach(\App\Vendor\Telenok\Core\Model\File\FileExtension::all()->sort(function($a, $b) { return strcmp($a->extension, $b->extension); }) as $extension)

			<option value="{{$extension->extension}}" @if (in_array($extension->extension, $allowedExt, true)) selected="selected" @endif >[{{$extension->extension}}] {{$extension->translate('title')}}</option>

			@endforeach
		</select>
	</div>
</div>
<div class="form-group">
	{!! Form::label('file_many_to_many_allow_mime', $controller->LL('property.file_many_to_many_allow_mime'), array('class'=>'col-sm-3 control-label no-padding-right')) !!}
	<div class="col-sm-9">

        {!! Form::hidden('file_many_to_many_allow_mime[]', '') !!}

        <select class="" multiple="multiple" data-placeholder="{{$controller->LL('property.file_many_to_many_allow_mime')}}" id="file_many_to_many_allow_mime{{$uniqueId}}" name="file_many_to_many_allow_mime[]">

			<?php
				$allowedMime = $model->file_many_to_many_allow_mime->all();
			?>

			@foreach(\App\Vendor\Telenok\Core\Model\File\FileMimeType::all()->sort(function($a, $b) { return strcmp($a->mime_type, $b->mime_type); }) as $mimeType)

			<option value="{{$mimeType->mime_type}}" @if (in_array($mimeType->mime_type, $allowedMime, true)) selected="selected" @endif >[{{$mimeType->mime_type}}] {{$mimeType->translate('title')}}</option>

			@endforeach
		</select>
	</div>
</div>
<script type="text/javascript">
    jQuery("#file_many_to_many_allow_ext{{$uniqueId}}").on("chosen:showing_dropdown", function()
    {
        telenok.maxZ("*", jQuery(this).parent().find("div.chosen-drop"));
    })
    .chosen({ 
        keepTypingMsg: "{{$controller->LL('notice.typing')}}",
        lookingForMsg: "{{$controller->LL('notice.looking-for')}}", 
        minTermLength: 1,
        width: "400px",
        create_option: true,
        persistent_create_option: true,
        skip_no_results: true,
        search_contains: true
    });
    
    jQuery("#file_many_to_many_allow_mime{{$uniqueId}}").on("chosen:showing_dropdown", function()
    {
        telenok.maxZ("*", jQuery(this).parent().find("div.chosen-drop"));
    })
    .chosen({ 
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
