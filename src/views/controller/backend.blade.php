@extends('core::layout.backend')

@section('head')
    <title>Backend</title>
    @parent
@stop

@section('body')
    <body class="no-skin telenok-backend">
        <div class="navbar navbar-default navbar-fixed-top">
            <div class="navbar-inner">
				<a class="navbar-brand" href="telenok/"><small>{{\Config::get('app.backend.brand')}}</small></a>
				<ul class="nav ace-nav pull-right">

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
							<img class="nav-user-photo" src="packages/telenok/core/image/anonym.png" alt="Anonym">
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
                                <a href="#" onclick='
                                    telenok.addModule( "{{ $listModuleItem->getKey() }}", "{!! $listModuleItem->getRouterActionParam() !!}", function(moduleKey) {
                                                telenok.processModuleContent(moduleKey);
                                            }); 
                                            return false;'>
                                    <i class="menu-icon {{ $listModuleItem->getIcon() }}"></i>
                                    <span class="menu-text">{{ $listModuleItem->getName() }}</span>
                                </a>
                            </li>
                            @elseif (!$listModuleItem->getParent())
                            <li>
                                <a class="dropdown-toggle" href="#">
                                    <i class="menu-icon {{ $listModuleItem->getIcon() }}"></i>
                                    <span class="menu-text">{{ $listModuleItem->getName() }}</span>
                                    <b class="arrow fa fa-angle-down"></b>
                                </a>
                                <ul class="submenu"> 
									
                                    @foreach($listModule as $item)
                                    @if ($item->getParent() == $listModuleItem->getKey())
									
									<li class="">
										<a href="#" onclick='telenok.addModule("{{ $item->getKey() }}", "{!! $item->getRouterActionParam() !!}", function(moduleKey) {
                                                telenok.processModuleContent(moduleKey);
                                            });
                                            return false;'>
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
                        <li><i class="ace-icon fa fa-home home-icon"></i> <a href="telenok/">{{ $controller->LL('home') }}</a></li> 
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
                                    "{!! \URL::route("cmf.module.objects-sequence.action.param", []) !!}", 
                                    function(moduleKey) 
                                    {
                                        param = telenok.getModule(moduleKey);

                                        param.addTree = false;
                                        param.addTab = true;

                                        param.data = param.data || {};
                                        
                                        param.data = jQuery.extend({}, param.data, {sSearch: jQuery('input', obj).val()})

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


        <div class="modal fade backend-notice">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header table-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h4>{{$controller->LL("notice.title")}}</h4>
					</div>
					<div class="modal-body">
					</div>
					<div class="modal-footer">
						<button class="btn" data-dismiss="modal">{{$controller->LL("btn.close")}}</button> 
					</div>
				</div>
			</div>
        </div>
        
        
</body>
@stop