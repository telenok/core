@extends('core::presentation.tree-tab.tree')

	@section('select_node')

	//data.inst.toggle_node(data.rslt.obj);

	telenok.getPresentation('{{$controller->getPresentationModuleKey()}}')
			.addTabByURL({
				url: '{!! $controller->getRouterContent(['treeId' => '__treeId__', 'typeId' => $typeId]) !!}'.replace('__treeId__', data.rslt.obj.data('id')),
				after: function() {
					telenok.getPresentation('{{$controller->getPresentationModuleKey()}}').reloadDataTableOnClick({
						url: '{!! $controller->getRouterList() !!}', 
						data: { treeId: data.rslt.obj.data("id"), 'typeId': {{$typeId}} },
						gridId: data.rslt.obj.data("gridId")
					});
				}});
	@stop
    
    
    @section("json_data")
    "json_data": {
        "progressive_render": true,
        "ajax" : {
            "type": 'GET',
            "url": function (node) 
            {
                var nodeId = "", url = "";

                if (!jQuery(node).attr('id')) 
                {
                    url = '{!! $controller->getRouterListTree(['typeId' => $typeId]) !!}';
                }
                else
                {
                    nodeId = jQuery(node).attr('id');
                    url = '{!! $controller->getRouterListTree(['treeId' => '__treeId__', 'typeId' => $typeId]) !!}'.replace('__treeId__', nodeId);
                }

                return url;
            }
        }
    },
    @stop

    @section("search")
    "search" : {
        "case_insensitive": true,
        "ajax": {
            "url": '{!! $controller->getRouterListTree(['typeId' => $typeId]) !!}'
        }
    },
    @stop
