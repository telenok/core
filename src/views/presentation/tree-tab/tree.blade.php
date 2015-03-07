<script type="text/javascript">
    jQuery("#tree-{{$id}}").jstree({
        "themes": {
			"theme": "proton",
			"url": "packages/telenok/core/js/jquery.jstree/themes/proton/style.css"
        },
        "core": {"initially_open": ["root-not-delete"]},
        "crrm": {
            "move": {
                "default_position": "first",
                "check_move": function(m) {
                    return (m.o[0].attr("rel") === "folder") ? true : false;
                }
            }
        },
        "types" : {
            "valid_children" : [ "root" ],
            "types" : {
                "root" : {
                    "icon" : { 
                        //"image" : "packages/telenok/core/css/jstree/root.png" 
                    },
                    "valid_children" : [ "default" ],
                    "hover_node" : false
                },
                "folder" : {
                    "icon" : { 
                        //"image" : "packages/telenok/core/css/jstree/folder.png" 
                    },
                    "valid_children" : [ "default" ],
                    "hover_node" : false
                },
                "default" : {
                    "valid_children" : [ "default" ]
                }
            }
        },
        
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
                        url = '{!! $controller->getRouterListTree(['id' => '__id__']) !!}'.replace('__id__', nodeId);
                    }

                    return url;
                }
            }
        },
        @show
        
        @section("search")
        "search" : {
            "case_insensitive": true,
            "ajax": {
                "url": '{!! $controller->getRouterListTree() !!}'
            }
        },
        @show
        
        "plugins": ["themes", "json_data", "ui", "crrm", "search", "types", "state"]
    })
    .bind("select_node.jstree", function(event, data) 
    {
		@section("select_node")
		
        //data.inst.toggle_node(data.rslt.obj);

        telenok.getPresentation('{{$controller->getPresentationModuleKey()}}')
                .addTabByURL({
                    url: '{!! $controller->getRouterContent() !!}',
                    after: function() 
                    {
                        telenok.getPresentation('{{$controller->getPresentationModuleKey()}}').reloadDataTableOnClick({
                            "url": '{!! $controller->getRouterList() !!}', 
                            "data": { "treeId": data.rslt.obj.data("id") },
                            "gridId": data.rslt.obj.data("gridId")
                        });
                    }});
		@show
    });
</script>

<div class="widget-box span">
    <div class="widget-header widget-header-flat">
        <h4 class="lighter widget-title smaller">{{$treeChoose}}</h4>
        <span class="widget-toolbar">

            <a data-action="settings" href="#" class="dropdown-toggle" data-toggle="dropdown">
                <i class="fa fa-search"></i>
            </a>

            <ul class="dropdown-menu dropdown-caret">
                <li>
                    <div class="input-group" style="margin: 0 6px;">
                        <input type="text" placeholder="{{ $controller->LL('btn.search') }}..." 
                                onclick="event.stopPropagation();" 
                                onkeyup="if (event.keyCode == 13) jQuery('button.search-me', jQuery(this).closest('div')).trigger('click');"
                                class="form-control" style="float: none; width: 200px;">
                        <span class="input-group-btn">
                            <button onclick="jQuery('#tree-{{$id}}').jstree('search', jQuery(this).closest('div').find('input').val());return false;" title="{{$controller->LL('btn.search')}}" type="button" class="search-me btn btn-info btn-sm">
                                <i class="fa fa-search"></i>
                            </button>
                            <button onclick="jQuery('#tree-{{$id}}').jstree('clear_search');jQuery(this).closest('div').find('input').val('');return false;" title="{{$controller->LL('btn.clear')}}" type="button" class="btn btn-sm">
                                <i class="fa fa-times"></i>
                            </button>					
                        </span>								
                    </div>
                </li>
            </ul>

            <a data-action="reload" href="#" onclick="jQuery('#tree-{{$id}}').jstree('refresh');return false;">
                <i class="fa fa-refresh"></i>
            </a>
        </span>
    </div>

    <div class="widget-body">
        <div class="widget-main padding-8">
            <div id="tree-{{$id}}"></div>
        </div>
    </div>
</div>