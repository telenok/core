@extends('core::presentation.tree-tab-object.tree')

	@section('select_node') 

        //data.inst.toggle_node(data.rslt.obj);

		if (data.rslt.obj.data("module"))
		{
			telenok.addModule(data.rslt.obj.data("moduleKey"), data.rslt.obj.data("moduleRouterActionParam"), function(moduleKey) 
			{
                var param = telenok.getModule(moduleKey) || {};

                if (!param.preCallingPresentationFlag)
                {
                    telenok.preCallingPresentation(moduleKey);
                }

                if (telenok.hasPresentation(param.presentationModuleKey))
                {
					param.addTree = false;

                    telenok.getPresentation(param.presentationModuleKey).callMe(param);

					param.addTree = true;

                    telenok.getPresentation(param.presentationModuleKey).setParam(param);
                }

                telenok.postCallingPresentation(moduleKey); 

			});		
		}
		else
		{
			telenok.getPresentation('{{$controller->getPresentationModuleKey()}}')
                .addTabByURL({
                    url: '{!! $controller->getRouterContent(['typeId' => '__typeId__']) !!}'.replace('__typeId__', data.rslt.obj.data('id')),
                    after: function() 
                    {
                        telenok.getPresentation('{{$controller->getPresentationModuleKey()}}').reloadDataTableOnClick({
                            "url": '{!! $controller->getRouterList() !!}', 
                            "data": { "typeId": data.rslt.obj.data("id") },
                            "gridId": data.rslt.obj.data("gridId")
                        });
                    }});
		}
		
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
                    url = '{!! $controller->getRouterListTree() !!}';
                }
                else
                {
                    nodeId = jQuery(node).attr('id');
                    url = '{!! $controller->getRouterListTree(['typeId' => '__typeId__']) !!}'.replace('__typeId__', nodeId);
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
            "url": '{!! $controller->getRouterListTree() !!}'
        }
    },
    @stop