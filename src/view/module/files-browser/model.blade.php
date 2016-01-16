@extends('core::layout.model')

@section('script')

    @parent 
    
    @section('beforeAjax')
    
    if (typeof mirror_code_{{$uniqueId}} !== "undefined")
    {    
        jQuery('#codemirrortextarea{{$uniqueId}}').val(mirror_code_{{$uniqueId}}.getValue());
    }
    
    @stop

    @section('buttonType')
    
    if (button_type=='close')
    {	
        var divId = $el.closest('div.tab-pane').attr('id');

        jQuery('li a[href=#' + divId + '] i.fa.fa-times').click();

        return;
    }
    else if (button_type == 'delete.close')
    { 
        @if ($model)

        if (confirm('{{ $controller->LL('notice.sure.delete') }}'))
        {
            $el.attr('action', "{!! $controller->getRouterDelete(['id' => $model->getRealPath()]) !!}");
        }
        else
        {
            return;
        }

        @endif
    }
    
    @stop 

@stop


@section('form')

    @parent 

    @section('formField')

        {!! Form::hidden('modelType', $modelType) !!}
        {!! Form::hidden('modelPath', $model ? $model->getRealPath() : '') !!}


        <div class="form-group">
            {!! Form::label("directory", $controller->LL('field.directory'), array('class'=>'col-sm-3 control-label no-padding-right')) !!}

            <div class="col-sm-9">
                {!! Form::text('directory', $modelCurrentDirectory->getRealPath(), ['readonly' => 'readonly', 'class' => 'col-xs-5 col-sm-5']) !!}
            </div>
        </div>


        @if ($modelType == 'directory')

        <div class="form-group">
            {!! Form::label("name", $controller->LL('field.directory.name'), array('class'=>'col-sm-3 control-label no-padding-right')) !!}

            <div class="col-sm-9">
                {!! Form::text('name', $model ? $model->getFilename() : '', ['class' => 'col-xs-5 col-sm-5']) !!}
            </div>
        </div>

        @elseif ($modelType == 'file')

        <div class="form-group">
            {!! Form::label("name", $controller->LL('field.file.name'), array('class'=>'col-sm-3 control-label no-padding-right')) !!}

            <div class="col-sm-9">
                {!! Form::text('name', $model ? $model->getFilename() : '', ['class' => 'col-xs-5 col-sm-5']) !!}
            </div>
        </div>

        <div class="widget-box transparent">
            <div class="widget-header widget-header-small">
                <h4 class="row">
                    <span class="col-sm-12">
                        <i class="ace-icon fa fa-list-ul"></i>
                        {{ $controller->LL('field.file.content') }}
                    </span>
                </h4>
            </div>
            <div class="widget-body"> 
                <div class="widget-main form-group">

                @if ($model && $model->getSize() >= $controller->getMaxSizeToView())

                <p>{{ $controller->LL('error.file-too-big') }}</p>

                @else
                
                {!! Form::textarea('content', 
                    mb_convert_encoding(
                        $content = ($model ? file_get_contents($model->getRealPath()) : ''),
                        'UTF-8',
                        mb_detect_encoding($content, 'auto')),
                    [
                        'class' => 'col-xs-12 col-sm-12', 
                        'rows' => 30,
                        'id' => 'codemirrortextarea' . $uniqueId
                    ]) !!}

                    <style type="text/css">
                        .CodeMirror {
                            height: auto;
                        }
                    </style>
                    
                <script>
                    
                    CodeMirror.modeURL = "packages/telenok/core/js/codemirror/mode/%N/%N.js";
                    var mirror_mode_{{$uniqueId}} = CodeMirror.findModeByExtension(@if ($model) "{{$model->getExtension()}}" @else "php" @endif);

                    var mirror_val_{{$uniqueId}} = mirror_mode_{{$uniqueId}} ? mirror_mode_{{$uniqueId}}.mode : 'php';

                    var mirror_code_{{$uniqueId}} = CodeMirror.fromTextArea(
                            document.getElementById('codemirrortextarea{{$uniqueId}}'),
                            {
                                lineNumbers: true,
                                mode: mirror_val_{{$uniqueId}},
                                matchBrackets: true,
                                autoCloseBrackets: true,
                                viewportMargin: Infinity,
                                styleActiveLine: true
                            }
                        );

                    mirror_code_{{$uniqueId}}.setOption("mode", mirror_val_{{$uniqueId}});
                    CodeMirror.autoLoadMode(mirror_code_{{$uniqueId}}, mirror_val_{{$uniqueId}});
                    
                    setTimeout(function()
                    {
                        mirror_code_{{$uniqueId}}.refresh();
                    }, 1);
                </script>
                    
                @endif
                </div>
            </div>
        </div>

        @endif

    @stop


    @section('formBtn')
    <div class='form-actions center no-margin'>
        <button type="submit" class="btn btn-success" onclick="jQuery(this).closest('form').data('btn-clicked', 'save');" autofocus="autofocus">
            {{$controller->LL('btn.save')}}
        </button>
        <button type="submit" class="btn btn-info" onclick="jQuery(this).closest('form').data('btn-clicked', 'save.close');">
            {{$controller->LL('btn.save.close')}}
        </button>
        @if ($model)
        <button type="submit" class="btn btn-danger" onclick="jQuery(this).closest('form').data('btn-clicked', 'delete.close');">
            {{$controller->LL('btn.delete')}}
        </button>
        @endif
        <button type="submit" class="btn" onclick="jQuery(this).closest('form').data('btn-clicked', 'close');">
            {{$controller->LL('btn.close')}}
        </button>
    </div>
    @stop

@stop

