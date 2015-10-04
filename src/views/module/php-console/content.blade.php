<div class="row">
    <div class="col-xs-12">

        {!! Form::open(array('url' => route('telenok.module.php-console.process-code'), 'id' => "model-ajax-$uniqueId")) !!}

        <div>
            {!! Form::label("content", $controller->LL('content')) !!}
            {!! Form::textarea("content", '', ['class' => 'form-control']) !!}
        </div>
        
        <div class='form-actions center no-margin'>
            <button type="submit" class="btn btn-success" onclick="jQuery(this).closest('form').data('btn-clicked', 'save');">
                {{ $controller->LL('btn.run') }}
            </button>
        </div>
        
        <div>
            {!! Form::label("result", $controller->LL('result')) !!}
            {!! Form::textarea("result", '', ['id' => "model-ajax-$uniqueId-result", 'class' => 'form-control', 'readonly' => 'readonly']) !!}
        </div> 

        {!! Form::close() !!}

        <script type="text/javascript"> 

            jQuery('#model-ajax-{{$uniqueId}}').on('submit', function(e) 
            {
                e.preventDefault();

                jQuery.ajax({
                    url: jQuery(this).attr('action'),
                    type: 'post',
                    data: (new FormData(this)),
                    dataType: 'json',
                    cache: false,
                    processData: false,
                    contentType: false
                })
                .fail(function(error, textStatus)
                {
                    jQuery('#model-ajax-{{$uniqueId}}-result').val(error.responseText);
                })
                .done(function(data, textStatus, jqXHR) 
                {
                    jQuery('#model-ajax-{{$uniqueId}}-result').val(data.result);
                });
            });

        </script>

    </div>
</div>