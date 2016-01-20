<?php 

    $i = 0; 
?>
    
    @foreach($files as $file)

<?php

    $i++; 

?>

<li class="col-lg-3 col-md-3 col-sm-6 col-xs-12" style="list-style: none;">
    <div class="thumbnail search-thumbnail">

        @if ($file->upload->isImage())

            @if (!$file->upload->existsCache(300, 300, 
                    \App\Telenok\Core\Support\Image\Processing::TODO_RESIZE))

                <?php
                    $file->upload->createCache(300, 300, \App\Telenok\Core\Support\Image\Processing::TODO_RESIZE);
                ?>

            @endif

            <img src="{!! $file->upload->downloadImageLink(300, 300, 
                    \App\Telenok\Core\Support\Image\Processing::TODO_RESIZE) !!}"
                class="media-object" 
                style="width: 100%; display: block;" 
                data-holder-rendered="true">
        @endif

        <div class="caption">
            <div class="clearfix">
                <div class="btn-group pull-right" role="group" aria-label="...">

                    @if ($file->upload->isImage())
                    <button class="btn-file-edit btn btn-info btn-xs"
                        data-filename='{{ $file->upload->filename() }}'
                        data-src='{!! $file->upload->downloadImageLink() !!}'
                        >{{$controller->LL('btn.edit')}}</button>
                    @endif

                    <button class="btn-file-choose btn btn-success btn-xs"
                        data-filename='{{ $file->upload->filename() }}'
                        data-src='{!! $file->upload->downloadImageLink() !!}'
                        >{{$controller->LL('btn.choose')}}</button>
                </div>
            </div>

            <h3 class="search-title" style="word-wrap: break-word;">
                <span>{{ $file->translate('title') }}</span>
            </h3>
            <p>{{ $file->upload->size() }}</p>
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

<script>
(function()
{
    var $modal = jQuery('#modal-dialog-{{$jsUnique}}').closest('.modal');

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

    jQuery('#model-search-file-{{$jsUnique}}').off('input').on('input', function()
    {
        getModelList{{$jsUnique}}(this.value);
    });
    
})();
</script>
 