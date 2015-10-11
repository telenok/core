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

                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right">{{$controller->LL('filter.contain')}}</label>
                            <div class="col-sm-9">
                                <input type="text" value="" name="filter[contain]"> 
                            </div>
                        </div>
                        
                        <div class="form-group ">
                            <label class="col-sm-3 control-label no-padding-right">{{$controller->LL('filter.size')}}</label>
                            <div class="col-sm-9">
                                <div class="input-group col-sm-1">
                                    <div class="input-group">
                                        <span class="input-group-addon datepickerbutton">
                                            <i class="fa fa-circle-o bigger-110"></i>
                                        </span>
                                        <input type="text" value="" name="filter[size][min]">
                                    </div>           
                                    <span class="input-group-addon">
                                        <i class="fa fa-arrow-right"></i>
                                    </span>
                                    <div class="input-group">
                                        <input type="text" value="" name="filter[size][max]">
                                        <span class="input-group-addon datepickerbutton">
                                            <i class="fa fa-circle bigger-110"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group ">
                            <label class="col-sm-3 control-label no-padding-right">{{$controller->LL('filter.last.modify')}}</label>
                            <div class="col-sm-9">
                                <div class="input-group col-sm-1">
                                    <div id="datetime-picker-last-modify-start-{{$jsContentUnique}}" class="input-group">
                                        <span class="input-group-addon datepickerbutton">
                                            <i class="fa fa-clock-o bigger-110"></i>
                                        </span>
                                        <input type="text" value="" name="filter[last_modify][start]">
                                    </div>           
                                    <span class="input-group-addon">
                                        <i class="fa fa-arrow-right"></i>
                                    </span>
                                    <div id="datetime-picker-last-modify-end-{{$jsContentUnique}}" class="input-group">
                                        <input type="text" value="" name="filter[last_modify][end]">
                                        <span class="input-group-addon datepickerbutton">
                                            <i class="fa fa-clock-o bigger-110"></i>
                                        </span>
                                    </div>
                                </div>

                                <script type="text/javascript">
                                    jQuery("#datetime-picker-last-modify-start-{{$jsContentUnique}}, #datetime-picker-last-modify-end-{{$jsContentUnique}}").datetimepicker(
                                    {
                                        format: "YYYY-MM-DD HH:mm:ss",
                                        useSeconds: true,
                                        pick12HourFormat: false,
                                        autoclose: true,
                                        minuteStep: 1,
                                        useCurrent: true
                                    });
                                </script> 
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

		var currentDirectory{{$jsContentUnique}} = '{!! $currentDirectory !!}';

        var presentation = telenok.getPresentation('{{$controller->getPresentationModuleKey()}}');
        var aoColumns = []; 
                aoColumns.push({ "mData": "tableCheckAll", "sTitle": '<label><input type="checkbox" name="checkHeader" class="ace ace-switch ace-switch-6" onclick="var tb=jQuery(\'#' + 
                            presentation.getPresentationDomId() + '-grid-{{$gridId}}\').dataTable();var chbx = jQuery(\'input[name=tableCheckAll\\\\[\\\\]]\', tb.fnGetNodes());chbx.prop(\'checked\', jQuery(\'input[name=checkHeader]\', tb).prop(\'checked\'));"><span class="lbl"></span></label>',
							"mDataProp": null, "sClass": "center", "sWidth": "20px", 
							"sDefaultContent": '<label><input type="checkbox" class="ace ace-switch ace-switch-6" name="tableCheckAll[]"><span class="lbl"></span></label>', 
							"bSortable": false});
                @foreach((array)$fields as $key => $field)
                    @if ($key==0)
                        aoColumns.push({ "mData": "{{ $field}}", "sTitle": "{{ $controller->LL("field." . $field) }}", "bSortable": false });
                        aoColumns.push({ "mData": "tableManageItem", "sTitle": "{{ $controller->LL('action') }}", "bSortable": false });
                    @else
                        aoColumns.push({ "mData": "{{ $field}}", "sTitle": "{{ $controller->LL("field." . $field) }}", "bSortable": false });
                    @endif
                @endforeach

                presentation.addDataTable({
                    aoColumns : aoColumns,
					aaSorting: [],
                    sAjaxSource : '{!! $controller->getRouterList(['uniqueId' => $jsContentUnique]) !!}',
                    domId: presentation.getPresentationDomId() + "-grid-{{$gridId}}", 
					tableListBtnCreate: {
							"sExtends": "collection",
							'sButtonClass': 'btn btn-sm btn-success',
							"sButtonText": "<i class='fa fa-plus smaller-90'></i> {{ $controller->LL('list.btn.create') }}",
							"aButtons": [ 
								{
									"sExtends": "text",
									"sButtonText": "<i class='fa fa-folder'></i> {{ $controller->LL('btn.create.directory') }}",
									"fnClick": function(nButton, oConfig, oFlash) 
									{ 
										telenok.getPresentation('{{$controller->getPresentationModuleKey()}}').addTabByURL({
											url: '{!! $controller->getRouterCreate() !!}',
											data: {
												currentDirectory: currentDirectory{{$jsContentUnique}},
												modelType : 'directory'
											}
										}); 
									}
								},
								{
									"sExtends": "text",
									'sButtonClass': '',
									"sButtonText": "<i class='fa fa-file'></i> {{ $controller->LL('btn.create.file') }}",
									"fnClick": function(nButton, oConfig, oFlash) {
										telenok.getPresentation('{{$controller->getPresentationModuleKey()}}').addTabByURL({
											url: '{!! $controller->getRouterCreate() !!}', 
											data: {
												currentDirectory: currentDirectory{{$jsContentUnique}},
												modelType : 'file'
											}
										}); 
									}
								}
							]
						},
                    tableListBtnSelected: {
							"sExtends": "collection",
							'sButtonClass': 'btn btn-sm btn-light',
							"sButtonText": "<i class='fa fa-check-square-o smaller-90'></i> {{ $controller->LL('list.btn.select') }}",
							"aButtons": [ 
								{
									"sExtends": "text",
									"sButtonText": "<i class='fa fa-pencil-square-o'></i> {{ $controller->LL('btn.edit') }}",
									"fnClick": function(nButton, oConfig, oFlash) 
										{
                                            telenok.getPresentation('{{$controller->getPresentationModuleKey()}}').addTabByURL({
                                                url: '{!! $controller->getRouterListEdit() !!}', 
                                                data: jQuery('input[name=tableCheckAll\\[\\]]:checked', this.dom.table).serialize() 
                                            });
                                        }
								},
								{
									"sExtends": "text",
									'sButtonClass': '',
									"sButtonText": "<i class='fa fa-trash-o'></i> {{ $controller->LL('btn.delete') }}",
									"fnClick": function(nButton, oConfig, oFlash) 
                                        {
                            				var this_ = this;

                                            jQuery.ajax({
                                                url: '{!! $controller->getRouterListDelete() !!}',
                                                method: 'get',
                                                dataType: 'json',
                                                data: jQuery('input[name=tableCheckAll\\[\\]]:checked', this.dom.table).serialize() 
                                            }).done(function(data) {
                                                if (data.success) {
                                                    jQuery('input[name=tableCheckAll\\[\\]]:checked', this_.dom.table).closest("tr").remove();
                                                }
                                                else {
                                                    //
                                                }  
                                            }); 
                                        }
								}
							]
						}
                });
                
                
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
                .dataTable()
                .fnReloadAjax('{!! $controller->getRouterList() !!}?' + (erase ? '' : jQuery.param($form.serializeArray())));
        }
                
    </script>
</div>