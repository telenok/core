@extends('core::layout.backend')

@section('head')
    <title>File browse</title>
    @parent
@stop

@section('body')
    <body class="no-skin telenok-backend">
        <div class="main-container">
            <div class="main-content clearfix">

                
                
                <div class="row">
                    <div class="col-sm-12">
                        <div class="tabbable">
                            <ul class="nav nav-tabs" id="myTab">
                                <li class="active">
                                    <a data-toggle="tab" href="#public-file-list">
                                        <i class="green ace-icon fa fa-home bigger-120"></i>
                                        Public file list
                                    </a>
                                </li>

                                <li>
                                    <a data-toggle="tab" href="#database-file-list">
                                        <i class="green ace-icon fa fa-home bigger-120"></i>
                                        Database file list
                                    </a>
                                </li>
                            </ul>

                            <div class="tab-content">
                                <div id="public-file-list" class="tab-pane fade in active">
                                
                                    <div class="row">
                                        <ul>
                                            <?php
                                            
                                            $imageProcessing = app('\App\Telenok\Core\Support\Image\Processing');
                                            
                                            ?>
                                            
                                            @foreach($files as $k => $file)
                                            <li class="col-lg-3 col-sm-6 col-xs-12">
                                                <div class="thumbnail search-thumbnail">
                                                    
                                                    <img src="{!! $imageProcessing->cachedPublicImageUrl($currentDir . '/' . $file->getRelativePathname(), 300, 300) !!}"
                                                         class="media-object" 
                                                         style="width: 100%; display: block;" 
                                                         data-holder-rendered="true">
                                                    <div class="caption">
                                                        <div class="clearfix">
                                                            <span class="pull-right label label-grey info-label">London</span>

                                                        </div>

                                                        <h3 class="search-title">
                                                            <a class="blue" href="#">{{ $file->getRelativePathname() }}</a>
                                                        </h3>
                                                        <p>{{ $file->getSize() }}</p>
                                                    </div>
                                                </div>
                                            </li>

                                            @if ($k > 0 && ($k+1) % 3 == 0)
                                            <li class="clearfix visible-lg-block"></li>
                                            @endif

                                            @endforeach
                                        </ul>
                                    </div>

                                    <script type="text/javascript">
                                        (function()
                                        {
                                            var columns = []; 

                                            <?php
                                            
                                                $model = app('\App\Telenok\Core\Model\Object\Field');
                                                $fields = $model->getFieldList(); 

                                            ?>

                                            @foreach($fields as $key => $field)

                                                @if ($key==0)
                                                    columns.push({ 
                                                        data : "choose", 
                                                        title : "{{ $controller->LL('btn.choose') }}", 
                                                        orderable : false
                                                    });
                                                @endif

                                                columns.push({
                                                    data : "{{ $field->code }}",
                                                    title : "{{ $field->translate('title_list') }}", 
                                                    orderable : {{ (int)$field->allow_sort ? "true" : "false" }}
                                                });

                                            @endforeach
                                            /*
                                            telenok.addDataTable({
                                                domId : 'table-file-list',
                                                ajax : '{!! URL::route("telenok.module.objects-lists.wizard.list",
                                                            ['typeId' => \App\Telenok\Core\Model\Object\Type::where('code', 'object_field')
                                                                ->withPermission()->active()->pluck('id')]) !!}',
                                                dom : "<'row'<'col-md-6'B><'col-md-6'f>r>t<'row'<'col-md-6'i><'col-md-6'p>>",
                                                columns : columns,
                                                pageLength : 10,
                                                order : []
                                            });*/
                                        })();
                                    </script>

                                    
                                    
                                </div>

                                <div id="database-file-list" class="tab-pane fade">
                                    <p>Food truck fixie locavore, accusamus mcsweeney's marfa nulla single-origin coffee squid.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                
                <script>
/*
                    jQuery(function()
                    {
                        jQuery.ajax("{!! 
                            route(
                                'telenok.module.objects-lists.wizard.choose', 
                                [
                                    'typeId' => \App\Telenok\Core\Model\Object\Type::where('code', 'file')
                                        ->withPermission()->active()->pluck('id')
                                ])
                            !!}")
                        .done(function(data)
                        {
                            jQuery("#public-file-list").html(data.tabContent);
                        });
                    });
*/
                </script>

            </div>            
        </div>
	</body>
@stop