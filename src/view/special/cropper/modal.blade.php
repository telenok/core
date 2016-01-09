
<div class="modal-dialog" id="modal-dialog-{{$jsUnique}}" style="width: 1210px;" role="document">
    <div class="modal-content">

        <div class="modal-header table-header">
            <button data-dismiss="modal" class="close" type="button">×</button>
            <h4>{{ $controller->LL('wizard.file.header') }}</h4>
        </div>
        <div class="modal-body" style="padding: 15px; position: relative;">


            <div class="container">
                <div class="row">
                    <div class="col-md-9">
                        <div class="cropping-img-container">
                            <img id="image{{$jsUnique}}" src="{!! $path !!}" alt="">
                        </div>
                    </div>
                    <div class="col-md-3">

                        <h3 class="page-header">{{ $controller->LL('preview') }}:</h3>

                        <div class="cropping-docs-preview clearfix">
                            <div class="cropping-img-preview cropping-preview-lg"></div>
                            <div class="cropping-img-preview cropping-preview-md"></div>
                            <div class="cropping-img-preview cropping-preview-sm"></div>
                            <div class="cropping-img-preview cropping-preview-xs"></div>
                        </div>


                        <div class="cropping-docs-data">
                            <div class="input-group input-group-sm">
                                <label class="input-group-addon" for="dataX">X</label>
                                <input type="text" class="form-control" id="dataX{{$jsUnique}}" placeholder="x">
                                <span class="input-group-addon">px</span>
                            </div>
                            <div class="input-group input-group-sm">
                                <label class="input-group-addon" for="dataY">Y</label>
                                <input type="text" class="form-control" id="dataY{{$jsUnique}}" placeholder="y">
                                <span class="input-group-addon">px</span>
                            </div>
                            <div class="input-group input-group-sm">
                                <label class="input-group-addon" for="dataWidth">{{ $controller->LL('width') }}</label>
                                <input type="text" class="form-control" id="dataWidth{{$jsUnique}}" placeholder="{{ $controller->LL('width') }}">
                                <span class="input-group-addon">px</span>
                            </div>
                            <div class="input-group input-group-sm">
                                <label class="input-group-addon" for="dataHeight">{{ $controller->LL('height') }}</label>
                                <input type="text" class="form-control" id="dataHeight{{$jsUnique}}" placeholder="{{ $controller->LL('height') }}">
                                <span class="input-group-addon">px</span>
                            </div>
                            <div class="input-group input-group-sm">
                                <label class="input-group-addon" for="dataRotate">{{ $controller->LL('rotate') }}</label>
                                <input type="text" class="form-control" id="dataRotate{{$jsUnique}}" placeholder="{{ $controller->LL('rotate') }}">
                                <span class="input-group-addon">{{ $controller->LL('degree') }}</span>
                            </div>
                            <div class="input-group input-group-sm">
                                <label class="input-group-addon" for="dataScaleX">{{ $controller->LL('scalex') }}</label>
                                <input type="text" class="form-control" id="dataScaleX{{$jsUnique}}" placeholder="{{ $controller->LL('scalex') }}">
                            </div>
                            <div class="input-group input-group-sm">
                                <label class="input-group-addon" for="dataScaleY">{{ $controller->LL('scaley') }}</label>
                                <input type="text" class="form-control" id="dataScaleY{{$jsUnique}}" placeholder="{{ $controller->LL('scaley') }}">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-9 cropping-docs-buttons">
                        <div class="btn-group">
                            <button type="button" class="btn btn-primary" data-method="setDragMode" data-option="move" title="{{ $controller->LL('move') }}">
                                <span class="cropping-docs-tooltip" data-toggle="tooltip" title="jQuery().cropper(&quot;setDragMode&quot;, &quot;move&quot;)">
                                    <span class="fa fa-arrows"></span>
                                </span>
                            </button>
                            <button type="button" class="btn btn-primary" data-method="setDragMode" data-option="crop" title="{{ $controller->LL('crop') }}">
                                <span class="cropping-docs-tooltip" data-toggle="tooltip" title="jQuery().cropper(&quot;setDragMode&quot;, &quot;crop&quot;)">
                                    <span class="fa fa-crop"></span>
                                </span>
                            </button>
                            <button type="button" class="btn btn-primary" data-method="reset" title="{{ $controller->LL('reset') }}">
                                <span class="cropping-docs-tooltip" data-toggle="tooltip" title="jQuery().cropper(&quot;reset&quot;)">
                                    <span class="fa fa-refresh"></span>
                                </span>
                            </button>
                        </div>

                        <div class="btn-group">
                            <button type="button" class="btn btn-primary" data-method="zoom" data-option="0.1" title="{{ $controller->LL('zoomin') }}">
                                <span class="cropping-docs-tooltip" data-toggle="tooltip" title="jQuery().cropper(&quot;zoom&quot;, 0.1)">
                                    <span class="fa fa-search-plus"></span>
                                </span>
                            </button>
                            <button type="button" class="btn btn-primary" data-method="zoom" data-option="-0.1" title="{{ $controller->LL('zoomout') }}">
                                <span class="cropping-docs-tooltip" data-toggle="tooltip" title="jQuery().cropper(&quot;zoom&quot;, -0.1)">
                                    <span class="fa fa-search-minus"></span>
                                </span>
                            </button>
                        </div>

                        <div class="btn-group">
                            <button type="button" class="btn btn-primary" data-method="move" data-option="-10" data-second-option="0" title="{{ $controller->LL('move.left') }}">
                                <span class="cropping-docs-tooltip" data-toggle="tooltip" title="jQuery().cropper(&quot;move&quot;, -10, 0)">
                                    <span class="fa fa-arrow-left"></span>
                                </span>
                            </button>
                            <button type="button" class="btn btn-primary" data-method="move" data-option="10" data-second-option="0" title="{{ $controller->LL('move.right') }}">
                                <span class="cropping-docs-tooltip" data-toggle="tooltip" title="jQuery().cropper(&quot;move&quot;, 10, 0)">
                                    <span class="fa fa-arrow-right"></span>
                                </span>
                            </button>
                            <button type="button" class="btn btn-primary" data-method="move" data-option="0" data-second-option="-10" title="{{ $controller->LL('move.up') }}">
                                <span class="cropping-docs-tooltip" data-toggle="tooltip" title="jQuery().cropper(&quot;move&quot;, 0, -10)">
                                    <span class="fa fa-arrow-up"></span>
                                </span>
                            </button>
                            <button type="button" class="btn btn-primary" data-method="move" data-option="0" data-second-option="10" title="{{ $controller->LL('move.down') }}">
                                <span class="cropping-docs-tooltip" data-toggle="tooltip" title="jQuery().cropper(&quot;move&quot;, 0, 10)">
                                    <span class="fa fa-arrow-down"></span>
                                </span>
                            </button>
                        </div>

                        <div class="btn-group">
                            <button type="button" class="btn btn-primary" data-method="rotate" data-option="-45" title="{{ $controller->LL('rotate.left') }}">
                                <span class="cropping-docs-tooltip" data-toggle="tooltip" title="jQuery().cropper(&quot;rotate&quot;, -45)">
                                    <span class="fa fa-rotate-left"></span>
                                </span>
                            </button>
                            <button type="button" class="btn btn-primary" data-method="rotate" data-option="45" title="{{ $controller->LL('rotate.right') }}">
                                <span class="cropping-docs-tooltip" data-toggle="tooltip" title="jQuery().cropper(&quot;rotate&quot;, 45)">
                                    <span class="fa fa-rotate-right"></span>
                                </span>
                            </button>
                        </div>

                        <div class="btn-group">
                            <button type="button" class="btn btn-primary" data-method="scaleX" data-option="-1" title="{{ $controller->LL('flip.horizontal') }}">
                                <span class="cropping-docs-tooltip" data-toggle="tooltip" title="jQuery().cropper(&quot;scaleX&quot;, -1)">
                                    <span class="fa fa-arrows-h"></span>
                                </span>
                            </button>
                            <button type="button" class="btn btn-primary" data-method="scaleY" data-option="-1" title="{{ $controller->LL('flip.vertical') }}">
                                <span class="cropping-docs-tooltip" data-toggle="tooltip" title="jQuery().cropper(&quot;scaleY&quot;-1)">
                                    <span class="fa fa-arrows-v"></span>
                                </span>
                            </button>
                        </div>

                        <div class="btn-group">
                            <button type="button" class="btn btn-primary" data-method="getData" data-option data-target="#putData">
                                <span class="cropping-docs-tooltip" data-toggle="tooltip" title="jQuery().cropper(&quot;getData&quot;)">
                                    {{ $controller->LL('data.get')}}
                                </span>
                            </button>
                            <button type="button" class="btn btn-primary" data-method="setData" data-target="#putData">
                                <span class="cropping-docs-tooltip" data-toggle="tooltip" title="jQuery().cropper(&quot;setData&quot;, data)">
                                    {{ $controller->LL('data.set')}}
                                </span>
                            </button>
                        </div>
                        
                        <div class="btn-group">
                            <label class="btn btn-primary cropping-btn-upload" for="inputImage{{$jsUnique}}" title="{{ $controller->LL('upload') }}">
                                <input type="file" class="sr-only" id="inputImage{{$jsUnique}}" name="file" accept="image/*">
                                <span class="cropping-docs-tooltip" data-toggle="tooltip">
                                    <span class="fa fa-upload"></span>
                                    {{ $controller->LL('image.upload') }}
                                </span>
                            </label>
                            <button type="button" class="btn btn-success" data-method="getCroppedCanvas">
                                <span class="cropping-docs-tooltip" data-toggle="tooltip" title="jQuery().cropper(&quot;getCroppedCanvas&quot;)">
                                    <span class="fa fa-download"></span>
                                    {{ $controller->LL('image.download') }}
                                </span>
                            </button>
                        </div>

                        <div class="btn-group btn-group-crop">
                            <button type="button" class="btn btn-primary" data-method="getCroppedCanvas" data-option="{ &quot;width&quot;: 160, &quot;height&quot;: 90 }">
                                <span class="cropping-docs-tooltip" data-toggle="tooltip" title="jQuery().cropper(&quot;getCroppedCanvas&quot;, { width: 160, height: 90 })">
                                    160&times;90
                                </span>
                            </button>
                            <button type="button" class="btn btn-primary" data-method="getCroppedCanvas" data-option="{ &quot;width&quot;: 320, &quot;height&quot;: 180 }">
                                <span class="cropping-docs-tooltip" data-toggle="tooltip" title="jQuery().cropper(&quot;getCroppedCanvas&quot;, { width: 320, height: 180 })">
                                    320&times;180
                                </span>
                            </button>
                        </div>

                        <!-- Show the cropped image in modal -->
                        <div class="modal cropping-docs-cropped" id="getCroppedCanvasModal{{$jsUnique}}" aria-hidden="true" 
                             aria-labelledby="getCroppedCanvasTitle" role="dialog" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header table-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                        <h4 class="modal-title" id="getCroppedCanvasTitle">{{ $controller->LL('cropped') }}</h4>
                                    </div>
                                    <div class="modal-body"></div>
                                    <div class="modal-footer">

                                        @if ($allowNew)
                                        <span class="dropdown">
                                            <button class="btn btn-default dropdown-toggle" type="button" 
                                                    id="new-blob-{{$jsUnique}}" data-toggle="dropdown" 
                                                    aria-haspopup="true" aria-expanded="true">
                                                <span class="fa fa-upload"></span>
                                                {{ $controller->LL('new.as') }} ...
                                                <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu" aria-labelledby="new-blob-{{$jsUnique}}" 
                                                id="new-select-{{$jsUnique}}">
                                                <li><a href="#" data-mime='image/jpeg'>{{ $controller->LL('new.as') }} JPEG</a></li>
                                                <li><a href="#" data-mime='image/gif'>{{ $controller->LL('new.as') }} GIF</a></li>
                                                <li><a href="#" data-mime='image/png'>{{ $controller->LL('new.as') }} PNG</a></li>
                                            </ul>
                                        </span>
                                        @endif

                                        @if ($allowBlob)
                                        <span class="dropdown">
                                            <button class="btn btn-default dropdown-toggle" type="button" 
                                                    id="upload-blob-{{$jsUnique}}" data-toggle="dropdown" 
                                                    aria-haspopup="true" aria-expanded="true">
                                                <span class="fa fa-upload"></span>
                                                {{ $controller->LL('set.as') }} ...
                                                <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu" aria-labelledby="upload-blob-{{$jsUnique}}" 
                                                id="upload-select-{{$jsUnique}}">
                                                <li><a href="#" data-mime='image/jpeg'>{{ $controller->LL('set.as')}} JPEG</a></li>
                                                <li><a href="#" data-mime='image/gif'>{{ $controller->LL('set.as')}} GIF</a></li>
                                                <li><a href="#" data-mime='image/png'>{{ $controller->LL('set.as')}} PNG</a></li>
                                            </ul>
                                        </span>
                                        @endif

                                        <a class="btn btn-primary" id="download{{$jsUnique}}" href="javascript:void(0);" download="cropped.png">Download</a>
                                        <button class="btn" data-dismiss="modal">{{ $controller->LL('btn.close')}}</button>
                                    </div>
                                </div>
                            </div>
                        </div><!-- /.modal -->

                        <div class="btn-group">
                            <button type="button" class="btn btn-primary" data-method="moveTo" data-option="0">
                                <span class="cropping-docs-tooltip" data-toggle="tooltip" title="cropper.moveTo(0)">
                                    0,0
                                </span>
                            </button>
                            <button type="button" class="btn btn-primary" data-method="zoomTo" data-option="1">
                                <span class="cropping-docs-tooltip" data-toggle="tooltip" title="cropper.zoomTo(1)">
                                    100%
                                </span>
                            </button>
                            <button type="button" class="btn btn-primary" data-method="rotateTo" data-option="180">
                                <span class="cropping-docs-tooltip" data-toggle="tooltip" title="cropper.rotateTo(180)">
                                    180°
                                </span>
                            </button>
                        </div>

                        <input type="text" class="form-control" id="putData" placeholder="{{ $controller->LL('input.data')}}">

                    </div><!-- /.docs-buttons -->

                    <div class="col-md-3 cropping-docs-toggles">
                        <!-- <h3 class="page-header">Toggles:</h3> -->
                        <div class="btn-group btn-group-justified" data-toggle="buttons">
                            <label class="btn btn-primary active">
                                <input type="radio" class="sr-only" id="aspectRatio0" name="aspectRatio" value="1.7777777777777777">
                                <span class="cropping-docs-tooltip" data-toggle="tooltip" title="aspectRatio: 16 / 9">
                                    16:9
                                </span>
                            </label>
                            <label class="btn btn-primary">
                                <input type="radio" class="sr-only" id="aspectRatio1" name="aspectRatio" value="1.3333333333333333">
                                <span class="cropping-docs-tooltip" data-toggle="tooltip" title="aspectRatio: 4 / 3">
                                    4:3
                                </span>
                            </label>
                            <label class="btn btn-primary">
                                <input type="radio" class="sr-only" id="aspectRatio2" name="aspectRatio" value="1">
                                <span class="cropping-docs-tooltip" data-toggle="tooltip" title="aspectRatio: 1 / 1">
                                    1:1
                                </span>
                            </label>
                            <label class="btn btn-primary">
                                <input type="radio" class="sr-only" id="aspectRatio3" name="aspectRatio" value="0.6666666666666666">
                                <span class="cropping-docs-tooltip" data-toggle="tooltip" title="aspectRatio: 2 / 3">
                                    2:3
                                </span>
                            </label>
                            <label class="btn btn-primary">
                                <input type="radio" class="sr-only" id="aspectRatio4" name="aspectRatio" value="NaN">
                                <span class="cropping-docs-tooltip" data-toggle="tooltip" title="aspectRatio: NaN">
                                    {{ $controller->LL('free')}}
                                </span>
                            </label>
                        </div>

                        <div class="btn-group btn-group-justified" data-toggle="buttons">
                            <label class="btn btn-primary active">
                                <input type="radio" class="sr-only" id="viewMode0" name="viewMode" value="0" checked>
                                <span class="cropping-docs-tooltip" data-toggle="tooltip" title="View Mode 0">
                                    VM0
                                </span>
                            </label>
                            <label class="btn btn-primary">
                                <input type="radio" class="sr-only" id="viewMode1" name="viewMode" value="1">
                                <span class="cropping-docs-tooltip" data-toggle="tooltip" title="View Mode 1">
                                    VM1
                                </span>
                            </label>
                            <label class="btn btn-primary">
                                <input type="radio" class="sr-only" id="viewMode2" name="viewMode" value="2">
                                <span class="cropping-docs-tooltip" data-toggle="tooltip" title="View Mode 2">
                                    VM2
                                </span>
                            </label>
                            <label class="btn btn-primary">
                                <input type="radio" class="sr-only" id="viewMode3" name="viewMode" value="3">
                                <span class="cropping-docs-tooltip" data-toggle="tooltip" title="View Mode 3">
                                    VM3
                                </span>
                            </label>
                        </div>
                    </div><!-- /.docs-toggles -->
                </div>

            </div>

        </div>
        <div class="modal-footer">
            <a class="btn" data-dismiss="modal">{{ $controller->LL('btn.close') }}</a>
        </div>
    </div>
</div>

<script>

    (function ()
    {
        var console = window.console || {log: function () {}};
        var $image = jQuery('#image{{$jsUnique}}');
        var $modal = $image.closest('.modal');
        var $download = jQuery('#download{{$jsUnique}}');
        var $blob = jQuery('#upload-select-{{$jsUnique}}');
        var $new = jQuery('#new-select-{{$jsUnique}}');
        var $dataX = jQuery('#dataX{{$jsUnique}}');
        var $dataY = jQuery('#dataY{{$jsUnique}}');
        var $dataHeight = jQuery('#dataHeight{{$jsUnique}}');
        var $dataWidth = jQuery('#dataWidth{{$jsUnique}}');
        var $dataRotate = jQuery('#dataRotate{{$jsUnique}}');
        var $dataScaleX = jQuery('#dataScaleX{{$jsUnique}}');
        var $dataScaleY = jQuery('#dataScaleY{{$jsUnique}}');
        var $croppedCanvasModal = jQuery('#getCroppedCanvasModal{{$jsUnique}}');

        jQuery('body').append($croppedCanvasModal);

        $modal.on('hidden', function ()
        {
            $image.cropper('destroy');
            $croppedCanvasModal.data('bs.modal', null);
            $croppedCanvasModal.remove();
        });

        $blob.on('click', 'a', function (event)
        {
            event.preventDefault();

            $modal.data('setImageBlob')($image.cropper('getCroppedCanvas').toDataURL(jQuery(this).data('mime'), 1));

            $croppedCanvasModal.modal('hide');
            $modal.modal('hide');
        });

        $new.on('click', 'a', function (event)
        {
            event.preventDefault();

            $modal.data('newImageCreate')({'mime': jQuery(this).data('mime'), blob: $image.cropper('getCroppedCanvas').toDataURL(jQuery(this).data('mime'), 1)});

            $croppedCanvasModal.modal('hide');
            $modal.modal('hide');
        });

        $image.one("load", function() 
        {
            var options = {
                aspectRatio: 16 / 9,
                preview: '#modal-dialog-{{$jsUnique}} .cropping-img-preview',
                crop: function (e) 
                {
                    $dataX.val(Math.round(e.x));
                    $dataY.val(Math.round(e.y));
                    $dataHeight.val(Math.round(e.height));
                    $dataWidth.val(Math.round(e.width));
                    $dataRotate.val(e.rotate);
                    $dataScaleX.val(e.scaleX);
                    $dataScaleY.val(e.scaleY);
                }
            };

            // Tooltip
            jQuery('[data-toggle="tooltip"]', $modal).tooltip();

            // Cropper
            $image.on({
                'build.cropper': function (e) {
                    //console.log(e.type);
                },
                'built.cropper': function (e) {
                    //console.log(e.type);
                },
                'cropstart.cropper': function (e) {
                    //console.log(e.type, e.action);
                },
                'cropmove.cropper': function (e) {
                    //console.log(e.type, e.action);
                },
                'cropend.cropper': function (e) {
                    //console.log(e.type, e.action);
                },
                'crop.cropper': function (e) {
                    //console.log(e.type, e.x, e.y, e.width, e.height, e.rotate, e.scaleX, e.scaleY);
                },
                'zoom.cropper': function (e) {
                    //console.log(e.type, e.ratio);
                }
            }).cropper(options);

            // Buttons
            if (!$.isFunction(document.createElement('canvas').getContext)) 
            {
                jQuery('button[data-method="getCroppedCanvas"]', $modal).prop('disabled', true);
            }

            if (typeof document.createElement('cropper').style.transition === 'undefined') 
            {
                jQuery('button[data-method="rotate"]', $modal).prop('disabled', true);
                jQuery('button[data-method="scale"]', $modal).prop('disabled', true);
            }

            // Download
            if (typeof $download[0].download === 'undefined') 
            {
                $download.addClass('disabled');
            }

            // Options
            jQuery('.cropping-docs-toggles', $modal).on('change', 'input', function()
            {
                var $this = jQuery(this);
                var name = $this.attr('name');
                var type = $this.prop('type');
                var cropBoxData;
                var canvasData;

                if (!$image.data('cropper')) 
                {
                    return;
                }

                if (type === 'checkbox') 
                {
                    options[name] = $this.prop('checked');
                    cropBoxData = $image.cropper('getCropBoxData');
                    canvasData = $image.cropper('getCanvasData');

                    options.built = function()
                    {
                        $image.cropper('setCropBoxData', cropBoxData);
                        $image.cropper('setCanvasData', canvasData);
                    };
                }
                else if (type === 'radio') 
                {
                    options[name] = $this.val();
                }

                $image.cropper('destroy').cropper(options);
            });

            // Methods
            jQuery('.cropping-docs-buttons', $modal).on('click', '[data-method]', function()
            {
                var $this = jQuery(this);
                var data = $this.data();
                var $target;
                var result;

                if ($this.prop('disabled') || $this.hasClass('disabled'))
                {
                    return;
                }

                if ($image.data('cropper') && data.method) 
                {
                    data = $.extend({}, data); // Clone a new one

                    if (typeof data.target !== 'undefined') 
                    {
                        $target = jQuery(data.target);

                        if (typeof data.option === 'undefined') 
                        {
                            try 
                            {
                                data.option = JSON.parse($target.val());
                            }
                            catch (e) 
                            {
                                console.log(e.message);
                            }
                        }
                    }

                    result = $image.cropper(data.method, data.option, data.secondOption);

                    switch (data.method) 
                    {
                        case 'scaleX':
                        case 'scaleY':
                                jQuery(this).data('option', -data.option);
                            break;

                        case 'getCroppedCanvas':
                            if (result) 
                            {

                                // Bootstrap's Modal
                                $croppedCanvasModal.modal('show').find('.modal-body').html(result);

                                jQuery('canvas', $croppedCanvasModal).addClass('img-responsive');

                                var maxZ = 0;

                                jQuery('*').each(function()
                                {
                                    if (parseInt(jQuery(this).css('zIndex')) > maxZ) maxZ = parseInt(jQuery(this).css('zIndex'));
                                });

                                $croppedCanvasModal.css('zIndex', maxZ);

                                if (!$download.hasClass('disabled')) {
                                    $download.attr('href', result.toDataURL());
                                }
                            }

                            break;
                    }

                    if ($.isPlainObject(result) && $target) 
                    {
                        try 
                        {
                            $target.val(JSON.stringify(result));
                        }
                        catch (e) 
                        {
                            console.log(e.message);
                        }
                    }
                }
            });

            // Keyboard
            jQuery(document.body).on('keydown', function (e) 
            {

                if (!$image.data('cropper') || this.scrollTop > 300) 
                {
                    return;
                }

                switch (e.which) 
                {
                    case 37:
                            e.preventDefault();
                            $image.cropper('move', -1, 0);
                        break;

                    case 38:
                            e.preventDefault();
                            $image.cropper('move', 0, -1);
                        break;

                    case 39:
                            e.preventDefault();
                            $image.cropper('move', 1, 0);
                        break;

                    case 40:
                            e.preventDefault();
                            $image.cropper('move', 0, 1);
                        break;
                }
            });
            
            // Import image
            var $inputImage = jQuery('#inputImage{{$jsUnique}}');
            var URL = window.URL || window.webkitURL;
            var blobURL;

            if (URL)
            {
                $inputImage.change(function()
                {
                    var files = this.files;
                    var file;

                    if (!$image.data('cropper'))
                    {
                        return;
                    }

                    if (files && files.length)
                    {
                        file = files[0];

                        if (/^image\/\w+$/.test(file.type))
                        {
                            blobURL = URL.createObjectURL(file);
                            $image.one('built.cropper', function ()
                            {
                                // Revoke when load complete
                                URL.revokeObjectURL(blobURL);
                            })
                            .cropper('reset').cropper('replace', blobURL);

                            $inputImage.val('');
                        }
                        else 
                        {
                            window.alert('Please choose an image file.');
                        }
                    }
                });
            }
            else 
            {
                $inputImage.prop('disabled', true).parent().addClass('disabled');
            }
            
        })
                .each(function() 
        {
            if (this.complete) jQuery(this).load();
        });
    })();
</script>