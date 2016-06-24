

<?php 

    $i = 0; 
?>
    
    @foreach($files->all() as $file)

<?php

    $i++; 

?>

    <li class="col-lg-3 col-md-3 col-sm-6 col-xs-12" style="list-style: none;">
        <div class="thumbnail search-thumbnail">

            @if ($controller->isImage($file))

                @if (!$controller->existsCache($file, 300, 300, 
                    \App\Vendor\Telenok\Core\Support\Image\Processing::TODO_RESIZE))

                    <?php
                        $controller->createCache($file, 300, 300, \App\Vendor\Telenok\Core\Support\Image\Processing::TODO_RESIZE);
                    ?>

                @endif

                <img src="{!! $controller->urlCache($file, 300, 300, 
                    \App\Vendor\Telenok\Core\Support\Image\Processing::TODO_RESIZE) !!}"
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
                            data-src='{{$file}}'
                            >{{$controller->LL('btn.edit')}}</button>
                        @endif

                        <button class="btn-file-choose btn btn-success btn-xs"
                            data-filename='{{ pathinfo($file, PATHINFO_BASENAME) }}'
                            data-src='{{$file}}'
                            >{{$controller->LL('btn.choose')}}</button>
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
            <div class="modal-header table-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="getCroppedCanvasTitle">{{$controller->LL('create.directory')}}</h4>
            </div>
            <div class="modal-body">
                
                <div class="input-group">
                    <span class="input-group-addon" id="basic-addon1">{{$controller->LL('name')}}</span>
                    <input type="text" class="form-control" 
                        id="input-create-directory-{{$jsUnique}}"
                        placeholder="Name" aria-describedby="basic-addon1">
                </div> 
                
            </div>
            <div class="modal-footer">
                <a class="btn btn-success" id="btn-create-directory-{{$jsUnique}}" href="javascript:void(0);">Create</a>
                <button class="btn" data-dismiss="modal"> {{$controller->LL('btn.close')}} </button>
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

    $modalCreateDirectory.off('shown.bs.modal').on('shown.bs.modal', function ()
    {
        telenok.maxZ('*', this);
    });

    jQuery('#select-directory-{{$jsUnique}}').off('change').on('change', function()
    {
        getStorageList{{$jsUnique}}();
    });

    jQuery('.btn-file-edit', $modal).off('click').on('click', function()
    {
        if (!jQuery('#modal-cropper-{{$jsUnique}}').size())
        {
            jQuery('body').append('<div id="modal-cropper-{{$jsUnique}}" class="modal" role="dialog" aria-labelledby="label"></div>');
        }

        var $modalCropper = jQuery('#modal-cropper-{{$jsUnique}}');

        $modalCropper.off('hidden.bs.modal').on('hidden.bs.modal', function ()
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

            telenok.maxZ('*', $modalCropper);
        });

        $modalCropper.data('setImageBlob', function(data)
        {
            $modal.data('setFileSrc')({src: data});

            $modal.modal('hide');
        });

        $modalCropper.data('newImageCreate', function(data)
        {
            var formData = new FormData();

            formData.append('blob', data.blob);
            formData.append('mime', data.mime);
            formData.append('directory', jQuery('#select-directory-{{$jsUnique}}').val());

            jQuery.ajax({
                url: "{{route('telenok.ckeditor.image.create')}}",
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

    jQuery('.btn-file-choose', $modal).off('click').on('click', function(event)
    {
        event.preventDefault();

        var $this = jQuery(this);

        $modal.data('setFileSrc')({
            src: $this.data('src'),
            filename: $this.data('filename')
        });
        
        $modal.modal('hide');
    });

    jQuery('#btn-create-directory-{{$jsUnique}}').off('click').click(function(event)
    {
        event.preventDefault();

        jQuery.ajax({
            url: "{!! route('telenok.ckeditor.directory.create') !!}",
            method: "POST",
            data: {
                directory: jQuery('#select-directory-{{$jsUnique}}').val(),
                name: jQuery('#input-create-directory-{{$jsUnique}}').val()
            }
        })
        .done(function(data) 
        {
            $modalCreateDirectory.modal('hide');
            jQuery('#input-create-directory-{{$jsUnique}}').val("");

            var $select = jQuery('#select-directory-{{$jsUnique}}');
            
            $select.prepend("<option value='" 
                + data.directory + "'>" 
                + data.directory + "</option>");
            
            var $selectList = jQuery('option', $select);

            $selectList.detach().sort(function(a, b)
            {
                a = a.value;
                b = b.value;
 
                return a > b ? 1 : (b > a ? -1 : 0);
            });

            $select.append($selectList);

            $select.val(data.directory);
            
            getStorageList{{$jsUnique}}();
        })
        .fail(function(data) 
        {
            alert("Sorry, cant create directory. Name should contains only alphanumeric symbols.");
        });
    });
    
    try
    {
        Dropzone.forElement("#dropdown-upload-directory-{{$jsUnique}}").destroy();
    }
    catch(e){}
    
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

    jQuery('#storage-search-file-{{$jsUnique}}').off('input').on('input', function()
    {
        var str = this.value;
        jQuery('#storage-list-{{$jsUnique}} li .search-title').each(function()
        {
            var text = jQuery(this).text().toLowerCase();
            (text.indexOf(str) >= 0) ? jQuery(this).closest('li').show() : jQuery(this).closest('li').hide();
        });
    });
    
})();
</script>
 