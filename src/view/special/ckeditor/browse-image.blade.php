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
                                
                                    <ul class="row">
                                        @foreach(app('\App\Telenok\Core\Model\Object\Field')->get() as $k => $image)

                                        <li class="col-lg-3 col-sm-6 col-xs-12">
                                            <div class="thumbnail search-thumbnail">

                                                <img data-src="holder.js/100px200?theme=gray" class="media-object" alt="100%x200" style="height: 200px; width: 100%; display: block;" src="data:image/svg+xml;charset=UTF-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22284%22%20height%3D%22200%22%20viewBox%3D%220%200%20284%20200%22%20preserveAspectRatio%3D%22none%22%3E%3C!--%0ASource%20URL%3A%20holder.js%2F100px200%3Ftheme%3Dgray%0ACreated%20with%20Holder.js%202.8.0.%0ALearn%20more%20at%20http%3A%2F%2Fholderjs.com%0A(c)%202012-2015%20Ivan%20Malopinsky%20-%20http%3A%2F%2Fimsky.co%0A--%3E%3Cdefs%3E%3Cstyle%20type%3D%22text%2Fcss%22%3E%3C!%5BCDATA%5B%23holder_1519d6f04ac%20text%20%7B%20fill%3A%23AAAAAA%3Bfont-weight%3Abold%3Bfont-family%3AArial%2C%20Helvetica%2C%20Open%20Sans%2C%20sans-serif%2C%20monospace%3Bfont-size%3A14pt%20%7D%20%5D%5D%3E%3C%2Fstyle%3E%3C%2Fdefs%3E%3Cg%20id%3D%22holder_1519d6f04ac%22%3E%3Crect%20width%3D%22284%22%20height%3D%22200%22%20fill%3D%22%23EEEEEE%22%2F%3E%3Cg%3E%3Ctext%20x%3D%22102%22%20y%3D%22106.6%22%3E284x200%3C%2Ftext%3E%3C%2Fg%3E%3C%2Fg%3E%3C%2Fsvg%3E" data-holder-rendered="true">
                                                <div class="caption">
                                                    <div class="clearfix">
                                                        <span class="pull-right label label-grey info-label">London</span>

                                                    </div>

                                                    <h3 class="search-title">
                                                        <a class="blue" href="#">{{ $image->translate('title') }}</a>
                                                    </h3>
                                                    <p>{{ $image->translate('dedscription') }}</p>
                                                </div>
                                            </div>
                                        </li>

                                        @if ($k%3 == 0)
                                        <li class="clearfix visible-lg-block"></li>
                                        @endif

                                        @endforeach
                                    </ul>

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