<div class="row">
    <div class="col-xs-12">

        <div class="tabbable">
            <ul id="myTab" class="nav nav-tabs">
                <li class="active">
                    <a href="#select" data-toggle="tab" aria-expanded="true">
                        <i class="green ace-icon fa fa-database bigger-120"></i>
                        {{ $controller->LL('tab.title.select') }}
                    </a>
                </li>

                <li class="">
                    <a href="#statement" data-toggle="tab" aria-expanded="false">
                        <i class="red ace-icon fa fa-database bigger-120"></i>
                        {{ $controller->LL('tab.title.statement') }}
                    </a>
                </li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane fade active in" id="select">

                    {!! Form::open(array('url' => route('telenok.module.database-console.process-select'), 'id' => "model-db-select-$uniqueId")) !!}

                    <div>
                        {!! Form::label("content", $controller->LL('content.select')) !!}
                        {!! Form::textarea("content", '', ['class' => 'form-control']) !!}
                    </div>

                    <div class='form-actions center no-margin'>
                        <button type="submit" class="btn btn-success" onclick="jQuery(this).closest('form').data('btn-clicked', 'save');">
                            {{ $controller->LL('btn.run') }}
                        </button>
                    </div>

                    <div class="result-error display-none">
                        {!! Form::label("result", $controller->LL('error.select')) !!}
                        {!! Form::textarea("result", '', ['class' => "form-control result", 'readonly' => 'readonly']) !!}
                    </div> 
                    
                    <div class="result-success display-none" style="overflow: auto;">

                        <table class="table table-striped table-bordered table-hover result">
                            <caption>{{$controller->LL('result.title')}}</caption>
                            <thead>
                                <tr>

                                </tr>
                            </thead>
                            <tbody>


                            </tbody>
                        </table>

                    </div>

                    {!! Form::close() !!}
                    
                </div>

                <div class="tab-pane fade" id="statement">

                    {!! Form::open(array('url' => route('telenok.module.database-console.process-statement'), 'id' => "model-db-statement-$uniqueId")) !!}

                    <div>
                        {!! Form::label("content", $controller->LL('content.statement')) !!}
                        {!! Form::textarea("content", '', ['class' => 'form-control']) !!}
                    </div>

                    <div class='form-actions center no-margin'>
                        <button type="submit" class="btn btn-success" onclick="jQuery(this).closest('form').data('btn-clicked', 'save');">
                            {{ $controller->LL('btn.run') }}
                        </button>
                    </div>

                    <div>
                        {!! Form::label("result", $controller->LL('result.title')) !!}
                        {!! Form::textarea("result", '', ['class' => "form-control result", 'readonly' => 'readonly']) !!}
                    </div> 

                    {!! Form::close() !!}

                </div>
            </div>
        </div>

        <script type="text/javascript">

            jQuery('#model-db-select-{{$uniqueId}}, #model-db-statement-{{$uniqueId}}').on('submit', function (e)
            {
                e.preventDefault();

                jQuery.ajax({
                    url: jQuery(this).attr('action'),
                    context: this,
                    type: 'post',
                    data: (new FormData(this)),
                    dataType: 'json',
                    cache: false,
                    processData: false,
                    contentType: false
                })
                .fail(function (error, textStatus)
                {
                    if (this.id == "model-db-select-{{$uniqueId}}")
                    {
                        jQuery('.result-error', this).show();
                        jQuery('.result-success', this).hide();
                    }

                    jQuery('.result', this).val(error.responseText);
                })
                .done(function (data, textStatus, jqXHR)
                {                    
                    if (this.id == "model-db-select-{{$uniqueId}}")
                    {
                        jQuery('.result-error', this).hide();
                        jQuery('.result-success', this).show();
                        
                        var $table = jQuery('table.result', this); 
                        var $head = jQuery('thead tr', $table);
                        var $body = jQuery('tbody', $table);

                        $table.show();
                        $head.empty();
                        $body.empty();

                        if (data.length)
                        {
                            jQuery.each(data[0], function(k, v)
                            {
                                jQuery('<td></td>').text(k.substr(0, 100)).appendTo($head);
                            });

                            jQuery.each(data, function(k, v)
                            {
                                var $tr = jQuery('<tr></tr>');

                                jQuery.each(v, function(k_, v_)
                                {
                                    jQuery('<td></td>').text(v_).appendTo($tr);
                                });

                                $body.append($tr);
                            });
                        }
                        else
                        {
                            $body.append('<tr><td>{{$controller->LL('result.select.empty')}}</td></tr>');
                        }
                    }
                    else
                    {
                        jQuery('.result', this).val('{{$controller->LL('result.total')}}'.replace(':total', data));
                    }
                });
            });

        </script>

    </div>
</div>