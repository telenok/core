<?php

    $jsContentUnique = str_random();

?>


<div class="container-table">

    <div class="table-header">{{ $controller->LL("list.name") }}</div>

    <div class="filter display-none">
        <div class="widget-box transparent">
            <div class="widget-header">
                <h5 class="widget-title smaller">{{ $controller->LL('table.filter.header') }}</h5>
                <span class="widget-toolbar no-border">
                    <a data-action="collapse" href="#">
                        <i class="ace-icon fa fa-chevron-up"></i>
                    </a>
                </span>
            </div>

            <div class="widget-body">
                <div class="widget-main">
                    <form class="form-horizontal" onsubmit="return false;">
                        
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right">{{$controller->LL('filter.name')}}</label>
                            <div class="col-sm-9">
                                <input type="text" value="" name="filter[name]"> 
                            </div>
                        </div>
 
                        <div class="form-group center">
							<div class="hr hr-8 dotted"></div>
							<button onclick="presentationTableFilter{{$jsContentUnique}}(this);" class="btn btn-sm btn-info">
								<i class="fa fa-search bigger-110"></i>
                            {{ $controller->LL('btn.search') }}
							</button>
							<button onclick="presentationTableFilter{{$jsContentUnique}}(this, true);" type="reset" class="btn btn-sm">
								<i class="fa fa-eraser bigger-110"></i>
                                {{ $controller->LL('btn.clear') }}
							</button>
						</div>
                        
                    </form>
                </div>
            </div>
        </div>
    </div>


    <table class="table table-striped table-bordered table-hover" id="telenok-{{$controller->getPresentation()}}-presentation-grid-{{$gridId}}" role="grid"></table>



    <script type="text/javascript">

        (function()
        {
            var currentDirectory{{$jsContentUnique}} = '{!! $currentDirectory !!}';

            var presentation = telenok.getPresentation('{{$controller->getPresentationModuleKey()}}');
            var columns = [];

            columns.push({ "mData": "tableCheckAll", "sTitle": 
                    '<label><input type="checkbox" class="ace ace-checkbox-2" name="checkHeader" onclick="var tb=jQuery(\'#' 
                    + presentation.getPresentationDomId() + '-grid-{{$gridId}}\').dataTable();' 
                    + 'var chbx = jQuery(\'input[name=tableCheckAll\\\\[\\\\]]\', tb.fnGetNodes());' 
                    + 'chbx.prop(\'checked\', jQuery(\'input[name=checkHeader]\', tb).prop(\'checked\'));">'
                    + '<span class="lbl">' 
                    + '</span></label>',
                    "mDataProp": null, "sClass": "center", "sWidth": "20px", 
                    "sDefaultContent": '<input type="checkbox" class="ace ace-checkbox-2" name="checkHeader" value=><span class="lbl"></span>', 
                    "bSortable": false});
                
            columns.push({ "mData": "tableManageItem", "sTitle": "", "bSortable": false });

            @foreach((array)$fields as $key => $field)
                @if ($key==0)
                    columns.push({ "mData": "{{ $field}}", "sTitle": "{{ $controller->LL("field." . $field) }}", "bSortable": false });
                @else
                    columns.push({ "mData": "{{ $field}}", "sTitle": "{{ $controller->LL("field." . $field) }}", "bSortable": false });
                @endif
            @endforeach

            presentation.addDataTable({
                columns : columns,
                order : [],
                ajax : '{!! $controller->getRouterList(['uniqueId' => $jsContentUnique]) !!}',
                domId: presentation.getPresentationDomId() + "-grid-{{$gridId}}",
                buttons: [
                    {
                        text : "<i class='fa fa-list'></i> {{ $controller->LL('list.btn.action') }}",
                        className : 'btn btn-sm btn-success',
                        buttons : [ 
                            {
                                text : "<i class='fa fa-pencil'></i> {{ $controller->LL('list.btn.edit.composer.json') }}",
                                action : function (e, dt, button, config)
                                { 
                                    telenok.getPresentation('{{$controller->getPresentationModuleKey()}}').addTabByURL({
                                        url: '{!! route("telenok.module.composer-manager.composer-json.edit") !!}'
                                    }); 
                                }
                            }
                        ]
                    }
                ],
                tableListBtnCreate: false,
                tableListBtnSelected: false
            });
        })();

        function presentationTableFilter{{$jsContentUnique}}(dom_obj, erase)
        {
			var $form = jQuery(dom_obj).closest('form');
			
            if (erase)
            {
				jQuery('select option:selected', $form).removeAttr('selected');
                jQuery('.chosen, .chosen-select', $form).trigger('chosen:updated');
                jQuery('input[name="multifield_search"]', $form).val(0);
            }
            else
			{
                jQuery('input[name="multifield_search"]', $form).val(1);
			}

            
            jQuery('#telenok-{{$controller->getPresentation()}}-presentation-grid-{{$gridId}}')
                .DataTable().ajax.url('{!! $controller->getRouterList() !!}?' + (erase ? '' : jQuery.param($form.serializeArray()))).load();
        }
    </script>
</div>