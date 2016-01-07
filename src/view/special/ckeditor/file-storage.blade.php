<div class="row">
    <div class="col-md-8">
        <div class="form-group">
            <label class="control-label" for="select-directory-{{$jsUnique}}">Select directory</label>
            <select class="form-control" id="select-directory-{{$jsUnique}}">

                <?php
                
                    $collection = collect($controller->storageDirectoryList())->transform(function($item) use ($controller)
                    {
                        return trim(str_replace($controller->getRootDirectory(), '', $item), '\\/');
                    });

                ?>
                
                <option value="">/</option>

                @foreach($collection as $c)
                    <option value="{{$c}}" @if ($currentDirectory == $c) selected @endif>{{$c}}</option>
                @endforeach

            </select>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <label class="control-label" for="search-file-{{$jsUnique}}">Search file by name</label>
            <input type="text" value="" id="search-file-{{$jsUnique}}" class="form-control" placeholder="Search file"/>
        </div>
    </div>
    <div class="col-md-2">
        <div class="btn-group">
            <label class="control-label" for="actions-{{$jsUnique}}">Actions</label>
            <button type="button" id="actions-{{$jsUnique}}" class="btn btn-default dropdown-toggle"
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Choose action <span class="caret"></span>
            </button>
            <ul class="dropdown-menu">
                <li><a href="javascript:void(0);" data-toggle="modal" data-target="#create-directory-{{$jsUnique}}">Create directory</a></li>
                <li><a href="javascript:void(0);" id="dropdown-upload-directory-{{$jsUnique}}">Upload files</a></li>
            </ul>
        </div>
    </div>
</div>

<ul class="row" style="padding: 0 0 0 0; margin: 15px 0 0 0;">

<?php 

    $i = 0; 
?>
    
    @foreach($files as $file)

<?php

    $i++; 

?>

    <li class="col-lg-3 col-md-3 col-sm-6 col-xs-12" style="list-style: none;">
        <div class="thumbnail search-thumbnail">

            @if ($controller->isImage($file))

                @if (!$controller->existsCache($file, 300, 300, 
                    \App\Telenok\Core\Support\Image\Processing::TODO_RESIZE))

                    <?php
                        $controller->createCache($file, 300, 300, \App\Telenok\Core\Support\Image\Processing::TODO_RESIZE);
                    ?>

                @endif

                <img src="{!! $controller->urlCache($file, 300, 300, 
                    \App\Telenok\Core\Support\Image\Processing::TODO_RESIZE) !!}"
                    class="media-object" 
                    style="width: 100%; display: block;" 
                    data-holder-rendered="true">
            @endif

            <div class="caption">
                <div class="clearfix">
                    <div class="btn-group pull-right" role="group" aria-label="...">

                        @if ($controller->isImage($file))
                        <button class="btn-file-edit btn btn-info btn-xs"
                            data-filename='{{ pathinfo($file, PATHINFO_BASENAME) }}'
                            data-src='{!! $controller->urlCache($file, 300, 300, 
                                \App\Telenok\Core\Support\Image\Processing::TODO_RESIZE) !!}'
                            >Edit</button>
                        @endif

                        <button class="btn-file-choose btn btn-success btn-xs"
                            data-filename='{{ $file }}'
                            data-src='{!! $controller->urlCache($file, 300, 300, 
                                \App\Telenok\Core\Support\Image\Processing::TODO_RESIZE) !!}'
                            >Choose</button>
                    </div>
                </div>

                <h3 class="search-title" style="word-wrap: break-word;">
                    <span>{{ pathinfo($file, PATHINFO_BASENAME) }}</span>
                </h3>
                <p>{{ app('filesystem')->size($file) }}</p>
            </div>
        </div>
    </li>

    @if ($i > 0 && $i % 4 == 0)
    <li class="clearfix visible-lg-block visible-md-block visible-sm-block visible-xs-block"></li>
    @elseif ($i > 0 && $i % 6 == 0)
    <li class="clearfix visible-sm-block"></li>
    @else
    <li class="clearfix visible-sm-block"></li>
    @endif

    @endforeach
</ul>



<!-- Show the cropped image in modal -->
<div class="modal" id="create-directory-{{$jsUnique}}" aria-hidden="true" 
     aria-labelledby="getCroppedCanvasTitle" role="dialog" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="getCroppedCanvasTitle">Create directory</h4>
            </div>
            <div class="modal-body">
                
                <div class="input-group">
                    <span class="input-group-addon" id="basic-addon1">Name</span>
                    <input type="text" class="form-control" 
                        id="input-create-directory-{{$jsUnique}}"
                        placeholder="Name" aria-describedby="basic-addon1">
                </div> 
                
            </div>
            <div class="modal-footer">
                <a class="btn btn-success" id="btn-create-directory-{{$jsUnique}}" href="javascript:void(0);">Create</a>
                <button class="btn" data-dismiss="modal"> Закрыть </button>
            </div>
        </div>
    </div>
</div><!-- /.modal -->


 

<script>
(function()
{
    var $modal = jQuery('#modal-dialog-{{$jsUnique}}').closest('.modal');
    var $modalCreateDirectory = jQuery('#create-directory-{{$jsUnique}}');

    jQuery('body').append($modalCreateDirectory);

    jQuery('#create-directory-{{$jsUnique}}').on('shown.bs.modal', function ()
    {
        var maxZ = 0;

        jQuery('*').each(function()
        {
            if (parseInt(jQuery(this).css('zIndex')) > maxZ) maxZ = parseInt(jQuery(this).css('zIndex'));
        });

        jQuery(this).css('zIndex', maxZ);
    });

    jQuery('#select-directory-{{$jsUnique}}').on('change', function()
    {
        getStorageList{{$jsUnique}}();
    });

    jQuery('.btn-file-edit', $modal).on('click', function()
    {
        if (!jQuery('#modal-cropper-{{$jsUnique}}').size())
        {
            jQuery('body').append('<div id="modal-cropper-{{$jsUnique}}" class="modal" role="dialog" aria-labelledby="label"></div>');
        }

        var $modalCropper = jQuery('#modal-cropper-{{$jsUnique}}');

        $modalCropper.on('hidden.bs.modal', function ()
        {
            jQuery(this).empty();
            jQuery('#image{{$jsUnique}}').cropper('destroy');
            jQuery('#getCroppedCanvasModal{{$jsUnique}}').data('bs.modal', null).remove();
        });

        jQuery.ajax({
            url: "{!! route('telenok.ckeditor.modal-cropper') !!}",
            method: 'get',
            dataType: 'html',
            data: { 
                file_url : jQuery(this).data('src'),
                allow_new: {{ (int)$allowNew }},
                allow_blob: {{ (int)$allowBlob }},
                js_unique: "{{$jsUnique}}"
            }
        })
        .done(function(data) 
        {
            $modalCropper.html(data).modal('show')

            var maxZ = 0;

            jQuery('*').each(function()
            {
                if (parseInt(jQuery(this).css('zIndex')) > maxZ) maxZ = parseInt(jQuery(this).css('zIndex'));
            });

            $modalCropper.css('zIndex', maxZ);
        });

        $modalCropper.data('newImageCreate', function(data)
        {
            var formData = new FormData();

            formData.append('blob', data.blob);
            formData.append('mime', data.mime);
            formData.append('directory', jQuery('#select-directory-{{$jsUnique}}').val());

            jQuery.ajax({
                url: "ckeditor/image/create",
                method: "POST",
                data: formData,
                processData: false,
                contentType: false,
            })
            .done(function(data) 
            {
                $modal.data('setFileSrc')({
                    src: data.src,
                    filename: data.filename
                });
                
                $modal.modal('hide');
            });
        });
    });

    jQuery('.btn-file-choose', $modal).on('click', function(event)
    {
        event.preventDefault();

        var $this = jQuery(this);

        $modal.data('setFileSrc')({
            src: $this.data('src'),
            filename: $this.data('filename')
        });
    });

    jQuery('#btn-create-directory-{{$jsUnique}}').click(function(event)
    {
        event.preventDefault();
        console.log('asdasasd');
        jQuery.ajax({
            url: "ckeditor/directory/create",
            method: "POST",
            data: {
                directory: jQuery('#select-directory-{{$jsUnique}}').val(),
                name: jQuery('#input-create-directory-{{$jsUnique}}').val()
            }
        })
        .done(function(data) 
        {
            jQuery('#select-directory-{{$jsUnique}}').prepend("<option value='" 
                + data.directory + "' selected='selected'>" 
                + data.directory + "</option>");

            $modalCreateDirectory.modal('hide');
            jQuery('#input-create-directory-{{$jsUnique}}').val("");
        })
        .fail(function(data) 
        {
            alert("Sorry, cant create directory. Name should contains only alphanumeric symbols.");
        });
    });
    
    jQuery("#dropdown-upload-directory-{{$jsUnique}}").dropzone({ 
        url: "{{route('telenok.ckeditor.file.upload')}}",
        autoDiscover: false,
        previewTemplate : '<div style="display:none"></div>',
        params: {
            directory: jQuery('#select-directory-{{$jsUnique}}').val()
        },
        headers: {
            'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
        },
        init: function () 
        {
            this.on("queuecomplete", function (file) 
            {
                getStorageList{{$jsUnique}}();
            });
        }
    });

})();
</script>
 