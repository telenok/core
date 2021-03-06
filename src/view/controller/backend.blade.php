@extends('core::layout.backend')

@section('head')
    <title>Backend</title>
    @parent
    
        <script>
            var CKEDITOR_BASEPATH = '/packages/telenok/core/js/ckeditor/';
        </script>
        {!! Html::script('packages/telenok/core/js/ckeditor/ckeditor.js') !!}
        {!! Html::script('packages/telenok/core/js/ckeditor_addons/fixes/bootstrap.js') !!}
        {!! Html::script('telenok/ckeditor.custom.config.js') !!}

        {!! Html::style('packages/telenok/core/js/jquery.cropper/cropper.css') !!}
        {!! Html::script('packages/telenok/core/js/jquery.cropper/cropper.js') !!}

@stop

@section('body')
    <body class="no-skin telenok-backend">
        <div class="navbar navbar-default navbar-fixed-top">
            <div id="navbar-container" class="navbar-container">
                <button data-target="#sidebar" id="menu-toggler" class="navbar-toggle menu-toggler pull-left" type="button">
					<span class="sr-only">Toggle sidebar</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
                <div class="navbar-header pull-left">
                    <a class="navbar-brand" href="telenok/"><small>{{config('app.backend.brand')}}</small></a>
                </div>
                <div class="navbar-buttons navbar-header pull-right" role="navigation">
                    <ul class="nav ace-nav">

                        @foreach($listModuleMenuTop as $itemFirstLevel)

                        @if (!$itemFirstLevel->get('parent'))

                            @if ($itemFirstLevel->get('li'))
                                {!! $itemFirstLevel->get('li') !!}
                            @else
                            <li>
                            @endif

                            {!! $itemFirstLevel->get('content') !!}

                                <ul class="user-menu dropdown-menu-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close" id="user_menu">
                                    @foreach($listModuleMenuTop as $itemSecondLevel)

                                    @if ($itemFirstLevel->get('key') == $itemSecondLevel->get('parent'))

                                        @if ($itemSecondLevel->get('devider_before'))
                                            <li class="divider"></li>
                                        @endif

                                        @if ($itemFirstLevel->get('li'))
                                            {!! $itemFirstLevel->get('li') !!}
                                        @else
                                        <li>
                                        @endif

                                        {!! $itemSecondLevel->get('content') !!}

                                        @if ($itemSecondLevel->get('devider_after'))
                                            <li class="divider"></li>
                                        @endif

                                        </li>
                                    @endif

                                    @endforeach
                                </ul>
                            </li>

                        @endif

                        @endforeach

                        <!--
                        <li class="light-blue user-profile">

                            <a data-toggle="dropdown" href="#" class="user-menu dropdown-toggle">
                                <img class="nav-user-photo" src="/packages/telenok/core/image/anonym.png" title="Anonym">
                                <span id="user_info">
                                    Welcome,John!
                                </span>
                                <i class="fa fa-caret-down"></i>
                            </a>
                            <ul class="pull-right dropdown-menu dropdown-yellow dropdown-caret dropdown-closer" id="user_menu">
                                <li><a href="#"><i class="fa fa-cog"></i> Settings</a></li>
                                <li><a href="#"><i class="fa fa-user"></i> Profile</a></li>
                                <li class="divider"></li>
                                <li><a href="#"><i class="fa fa-power-off"></i> Logout</a></li>
                            </ul> 
                        </li>

                        -->
                    </ul>
                </div>
            </div>
        </div>

        <div class="main-container">
            <div id="sidebar" class="sidebar responsive sidebar-fixed
                @if (app('auth')->user()->configuration && app('auth')->user()->configuration->get('ui-backend.sidebar-collapse'))
                menu-min
                @endif
                ">
                <div class="sidebar-shortcuts">
                    <div class="sidebar-shortcuts-large">
                        @foreach($listModuleGroup as $listModuleGroupItem)
                        <button title='{{$listModuleGroupItem->getName()}}' onclick='jQuery("ul.telenok-sidebar").hide(); jQuery("ul.telenok-sidebar-{{$listModuleGroupItem->getKey()}}").show();' class="btn btn-sm telenok-sidebar-{{ $listModuleGroupItem->getKey() }} {{ $listModuleGroupItem->getButton() }}"><i class="{{ $listModuleGroupItem->getIcon() }}"></i></button>
                        @endforeach
                    </div>
                    <div class="sidebar-shortcuts-mini">
                        @foreach($listModuleGroup as $listModuleGroupItem)
                        <span class="btn {{ $listModuleGroupItem->getButton() }}"></span>
                        @endforeach
                    </div>
                </div> 

                @foreach($listModuleGroup as $listModuleGroupItem) 
                <ul class="nav nav-list telenok-sidebar telenok-sidebar-{{$listModuleGroupItem->getKey()}}">
                    @foreach($listModule as $listModuleItem)
                    
                        @if ($listModuleGroupItem->getKey() == $listModuleItem->getGroup())

                            @if ($listModuleItem->isParentAndSingle())
                            <li class="parent-single">
                                <a  data-navigo href="#/module/{{ $listModuleItem->getKey() }}">
                                    <i class="menu-icon {{ $listModuleItem->getIcon() }}"></i>
                                    <span class="menu-text">{{ $listModuleItem->getName() }}</span>
                                </a>
                            </li>
                            @elseif (!$listModuleItem->getParent())
                            <li>
                                <a  class="dropdown-toggle" href="#"
                                    data-menu="module-{{$listModuleItem->getKey()}}">
                                    <i class="menu-icon {{ $listModuleItem->getIcon() }}"></i>
                                    <span class="menu-text">{{ $listModuleItem->getName() }}</span>
                                    <b class="arrow fa fa-angle-down"></b>
                                </a>
                                <ul class="submenu">

                                    @foreach($listModule as $item)
                                    @if ($item->getParent() == $listModuleItem->getKey())

									<li class="">
										<a  data-navigo href="#/module/{{ $item->getKey() }}"
                                            data-menu-parent="module-{{$listModuleItem->getKey()}}"
                                            data-menu="module-{{$listModuleItem->getKey()}}-{{$item->getKey()}}">
											<i class="menu-icon fa fa-caret-right"></i>
											{{ $item->getName() }}
										</a>
										<b class="arrow"></b>
									</li>
                                    @endif
                                    @endforeach
                                </ul>
                            </li>
                            @endif
                            
                        @endif
                    
                    @endforeach
                </ul>
                @endforeach
				<div id="sidebar-collapse" class="sidebar-toggle sidebar-collapse">
					<i data-icon2="ace-icon fa fa-angle-double-right" data-icon1="ace-icon fa fa-angle-double-left" 
                        @if (app('auth')->user()->configuration && app('auth')->user()->configuration->get('ui-backend.sidebar-collapse'))
                        class="ace-icon fa fa-angle-double-right"
                        @else
                        class="ace-icon fa fa-angle-double-left"
                        @endif
                        data-telenok-sidebar-collapse="{{app('auth')->user()->configuration && app('auth')->user()->configuration->get('ui-backend.sidebar-collapse')}}"
                        onclick="
                            jQuery(this).data('telenok-sidebar-collapse', jQuery(this).data('telenok-sidebar-collapse') ? 0 : 1);
                            telenok.updateUserUISetting('ui-backend.sidebar-collapse', jQuery(this).data('telenok-sidebar-collapse'));"   
                    ></i>
				</div>
			</div>


            <div class="main-content clearfix">
                <div class="breadcrumbs breadcrumbs-fixed">
                    <ul class="breadcrumb">
                        <li><i class="ace-icon fa fa-home home-icon"></i> {{ $controller->LL('home') }}</li> 
                    </ul>

                    <div class="nav-search"> 
                        <form class="form-inline" onsubmit="backendCommonSearch(this); return false;">
                            <span class="input-icon">
                                <input type="text" placeholder="{{$controller->LL('btn.search')}} ..." class="input-small search-query nav-search-input" />
                                <i class="fa fa-search nav-search-icon"></i>
                            </span>
                        </form>
                        
                        <script type="text/javascript">
                            function backendCommonSearch(obj)
                            { 
                                telenok.addModule(
                                    "object-sequence", 
                                    "{!! route("telenok.module.objects-sequence.action.param", []) !!}", 
                                    function(moduleKey) 
                                    {
                                        param = telenok.getModule(moduleKey);

                                        param.addTree = false;
                                        param.addTab = true;

                                        param.data = param.data || {};
                                        
                                        param.data = jQuery.extend({}, param.data, {search: jQuery('input', obj).val()})

                                        telenok.setModuleParam(moduleKey, param);                                  

                                        telenok.processModuleContent(moduleKey);
                                    }
                                );
                            }
                        </script>
                    </div>
                </div>
                
                <div class="clearfix page-content"></div>
                
            </div>
        </div>



        
		<script type="text/javascript">
            jQuery.ajaxSetup({
                beforeSend: function (xhr)
                {
                   xhr.setRequestHeader("X-CSRF-TOKEN", jQuery('meta[name="csrf-token"]').attr('content'));
                }
            });
		</script>
		
        @include('core::controller.backend-modal-login')
        
		@foreach($controller->getJsFile() as $file)

		<script src="{!! $file['file'] !!}"></script>

		@endforeach

		@foreach($controller->getJsCode() as $code)

			{!! $code !!} 

		@endforeach


        <script>

            <?php
                app('telenok.repository')->getModule()->each(function($item)
                {
                    echo $item->getNavigoRouterCode();
                });
            ?>

        </script>

	</body>
@stop