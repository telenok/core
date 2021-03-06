<?php
    
    $domAttr = ['class' => 'col-md-6', 'disabled' => 'disabled'];
    $method = camel_case($field->code);
    $jsUnique = str_random();

	$disabledCreateLinkedType = false;

	$linkedType = $controller->getLinkedModelType($field);

	if (!app('auth')->can('create', 'object_type.' . $linkedType->code))
	{
		$disabledCreateLinkedType = true;
	}
?>
    <div class="widget-box transparent" data-field-key='{{ $field->code }}'>
        <div class="widget-header widget-header-small">
			<h4 class="row">
				<span class="col-sm-12">
					<i class="ace-icon fa fa-list-ul"></i>
					{{ $field->translate('title_list') }}
				</span>
			</h4>
        </div>
        <div class="widget-body">
			
            <div class="widget-main form-group field-list">

                <ul class="nav nav-tabs" id="telenok-{{$controller->getKey()}}-{{$jsUnique}}-tab">
                    <li class="active">
                        <a data-toggle="tab" href="#telenok-{{$controller->getKey()}}-{{$jsUnique}}-tab-current">
                            <i class="fa fa-list bigger-110"></i>
                            {{$controller->LL('current')}}
                        </a>
                    </li>
					@if ( 
							((!$model->exists && $field->allow_create && $permissionCreate) 
								|| 
							($model->exists && $field->allow_update && $permissionUpdate))
						) 
					<li>
                        <a data-toggle="tab" href="#telenok-{{$controller->getKey()}}-{{$jsUnique}}-tab-addition">
                            <i class="green fa fa-plus bigger-110"></i>
                            {{$controller->LL('addition')}}
                        </a>
                    </li>
					@endif
                </ul>

                <div class="tab-content">
					<div id="telenok-{{$controller->getKey()}}-{{$jsUnique}}-tab-current" class="tab-pane in active">
                        <table class="table table-striped table-bordered table-hover" id="telenok-{{$controller->getKey()}}-{{$jsUnique}}" role="grid"></table>
                    </div>  
					@if ( 
							((!$model->exists && $field->allow_create && $permissionCreate) 
								|| 
							($model->exists && $field->allow_update && $permissionUpdate))
						)
                    <div id="telenok-{{$controller->getKey()}}-{{$jsUnique}}-tab-addition" class="tab-pane">
                        <table class="table table-striped table-bordered table-hover" id="telenok-{{$controller->getKey()}}-{{$jsUnique}}-addition" role="grid"></table>
                    </div>
					@endif
                </div>

            
                <script type="text/javascript">
                (function()
                {
                    jQuery('ul.nav-tabs#telenok-{{$controller->getKey()}}-{{$jsUnique}}-tab a:first').tab('show');

                    var presentation = telenok.getPresentation('{{ $controllerParent->getPresentationModuleKey()}}');

                    var columns = []; 
                    var buttons = [];

                    @foreach($controller->getFormModelTableColumn($field, $model, $jsUnique) as $row)
                    columns.push({!! json_encode($row) !!});
                    @endforeach
							
                    buttons.push({
                        text : "<i class='fa fa-refresh smaller-90'></i> {{ $controllerParent->LL('list.btn.refresh') }}",
                        className : 'btn-sm',
                        action : function (e, dt, button, config)
                        {
                            dt.ajax.reload();
                        }
                    });

                    @if ($model->exists && $field->allow_update && $permissionUpdate)
                        buttons.push({
                            text : "<i class='fa fa-trash-o smaller-90'></i> {{ $controllerParent->LL('list.btn.delete.all') }}",
                            className : 'btn-sm btn-danger',
                            action : function (e, dt, button, config)
                            {
                                removeMorphAllM2M{{$jsUnique}}();
                            }
                        });
                    @endif

                    if (columns.length)
                    {
                        telenok.addDataTable({
                            domId: "telenok-{{$controller->getKey()}}-{{$jsUnique}}",
                            retrieve : true,
                            columns : columns,
                            order: [],
                            pageLength : {{$pageLength}},
                            ajax : '{!! $urlListTable !!}', 
                            buttons: buttons
                        });
                    }
							
                    buttons = [];

                    @if ( 
                            ((!$model->exists && $field->allow_create && $permissionCreate) 
                                || 
                            ($model->exists && $field->allow_update && $permissionUpdate)) && !$disabledCreateLinkedType
                        )
                    buttons.push({
                        text : "<i class='fa fa-plus smaller-90'></i> {{ $controllerParent->LL('list.btn.create') }}",
                        className : 'btn-success btn-sm',
                        action : function (e, dt, button, config)
                        {
                            createMorphM2M{{$jsUnique}}('{!! $urlWizardCreate !!}');
                        }
                    });
                    @endif	
							
                    buttons.push({
                        text : "<i class='fa fa-refresh smaller-90'></i> {{ $controllerParent->LL('list.btn.choose') }}",
                        className : 'btn-yellow btn-sm',
                        action : function (e, dt, button, config)
                        {
                            chooseMorphM2M{{$jsUnique}}('{!! $urlWizardChoose !!}');
                        }
                    }); 
							
                    if (columns.length)
                    {
                        telenok.addDataTable({
                            domId: "telenok-{{$controller->getKey()}}-{{$jsUnique}}-addition",
                            dom: "<'row'<'col-md-6'B>r>t<'row'<'col-md-6'B>>",
                            retrieve : true,
                            columns : columns,
                            order: [],
                            data : [], 
                            buttons: buttons
                        });
                    }
                })();
                </script>
 
            </div>
        </div>
    </div>
 

    <script type="text/javascript">
        
        function addMorphM2M{{$jsUnique}}(val) 
        {
            jQuery('<input type="hidden" class="{{$field->code}}_add_{{$jsUnique}}" name="{{$field->code}}_add[]" value="'+val+'" />')
                    .insertBefore("table#telenok-{{$controller->getKey()}}-{{$jsUnique}}");

            jQuery("input.{{$field->code}}_delete_{{$jsUnique}}[value='"+val+"']").remove();
            jQuery("input.{{$field->code}}_delete_{{$jsUnique}}[value='*']").remove();
        }
        
        function removeMorphM2M{{$jsUnique}}(val) 
        {
            jQuery('<input type="hidden" class="{{$field->code}}_delete_{{$jsUnique}}" name="{{$field->code}}_delete[]" value="'+val+'" />')
                    .insertBefore("table#telenok-{{$controller->getKey()}}-{{$jsUnique}}");

            jQuery("input.{{$field->code}}_add_{{$jsUnique}}[value='"+val+"']").remove();
            jQuery("input.{{$field->code}}_delete_{{$jsUnique}}[value='*']").remove(); 
        }
        
        function removeMorphAllM2M{{$jsUnique}}() 
        {
            jQuery("input.{{$field->code}}_delete_{{$jsUnique}}").remove();
            
            var $table = jQuery("#telenok-{{$controller->getKey()}}-{{$jsUnique}}");
            
            jQuery('<input type="hidden" class="{{$field->code}}_delete_{{$jsUnique}}" name="{{$field->code}}_delete[]" value="*" />')
                    .insertBefore($table);
            
            jQuery('tbody tr', $table).addClass('line-through red');
            jQuery('tbody tr button.trash-it i', $table).removeClass('fa fa-trash-o').addClass('fa fa-power-off');
            jQuery('tbody tr button.trash-it', $table).removeClass('btn-danger').addClass('btn-success');
        }

        function createMorphM2M{{$jsUnique}}(url) 
        {
            jQuery.ajax({
                url: url,
                method: 'get',
                dataType: 'json'
            }).done(function(data) {
				
                if (!jQuery('#modal-{{$jsUnique}}').size())
                {
                    jQuery('body').append('<div id="modal-{{$jsUnique}}" class="modal fade" role="dialog" aria-labelledby="label"></div>');
                }

				var $modal = jQuery('#modal-{{$jsUnique}}');

                $modal.data('model-data', function(data)
                {
					data.tableManageItem = '<button class="btn btn-minier btn-danger trash-it" title="{{$controller->LL('list.btn.delete')}}" onclick="deleteMorphM2MAddition{{$jsUnique}}(this); return false;">'
                        + '<i class="fa fa-trash-o"></i></button>';
					
                    var $dt = jQuery("table#telenok-{{$controller->getKey()}}-{{$jsUnique}}-addition").dataTable();
                    var a = $dt.fnAddData(data, true);
                    var oSettings = $dt.fnSettings();
                    var nTr = oSettings.aoData[ a[0] ].nTr;

                    addMorphM2M{{$jsUnique}}(data.id);
                    
                });
					
				$modal.html(data.tabContent);
					
				$modal.modal('show').on('hidden', function() 
                { 
                    jQuery(this).empty(); 
                });
            });
        }

        function editTableRow{{$field->code}}{{$uniqueId}}(obj, url) 
        {
            jQuery.ajax({
                url: url,
                method: 'get',
                dataType: 'json'
            }).done(function(data) {

                if (!jQuery('#modal-{{$jsUnique}}').size())
                {
                    jQuery('body').append('<div id="modal-{{$jsUnique}}" class="modal fade" role="dialog" aria-labelledby="label"></div>');
                }

                var $modal = jQuery('#modal-{{$jsUnique}}');

                $modal.data('model-data', function(data)
                {  
                    var $table = jQuery("#telenok-{{$controller->getKey()}}-{{$jsUnique}}");
                    var $dt = $table.dataTable();
                    var $tr = jQuery(obj).closest('tr');
                        $dt.fnUpdate({title: data.title}, $tr[0], 1);

                })

                $modal.html(data.tabContent);

                $modal.modal('show').on('hidden', function() 
                { 
                    jQuery(this).empty(); 
                });
            });
        }

        function deleteTableRow{{$field->code}}{{$uniqueId}}(obj) 
        {
            var $dt = jQuery("#telenok-{{$controller->getKey()}}-{{$jsUnique}}").dataTable();
            var $tr = jQuery(obj).closest("tr");

            var data = $dt.fnGetData($tr[0]);

            $tr.toggleClass('line-through red');
            jQuery('button.trash-it i', $tr).toggleClass('fa fa-trash-o').toggleClass('fa fa-power-off');
            jQuery('button.trash-it', $tr).toggleClass('btn-danger').toggleClass('btn-success');

            removeMorphM2M{{$jsUnique}}(data.id);
        }

        function deleteMorphM2MAddition{{$jsUnique}}(obj) 
        {
            var $dt = jQuery("table#telenok-{{$controller->getKey()}}-{{$jsUnique}}-addition").dataTable();
            var $tr = jQuery(obj).closest("tr");
            
            var data = $dt.fnGetData($tr[0]);
            var rownum = $dt.fnGetPosition($tr[0]);
                $dt.fnDeleteRow(rownum);
            
            removeMorphM2M{{$jsUnique}}(data.id);
        } 

        function chooseMorphM2M{{$jsUnique}}(url) 
        {
            jQuery.ajax({
                url: url,
                method: 'get',
                dataType: 'json'
            }).done(function(data) {
				
                if (!jQuery('#modal-{{$jsUnique}}').size())
                {
                    jQuery('body').append('<div id="modal-{{$jsUnique}}" class="modal fade" role="dialog" aria-labelledby="label"></div>');
                }

				var $modal = jQuery('#modal-{{$jsUnique}}');

                $modal.data('model-data', function(data)
                {
					data.tableManageItem = '<button class="btn btn-minier btn-danger trash-it" title="{{$controller->LL('list.btn.delete')}}" onclick="deleteMorphM2MAddition{{$jsUnique}}(this); return false;">'
                        + '<i class="fa fa-trash-o"></i></button>';
				
                    var $dt = jQuery("table#telenok-{{$controller->getKey()}}-{{$jsUnique}}-addition").dataTable();
                    var a = $dt.fnAddData(data, true);
                    var oSettings = $dt.fnSettings();
                    var nTr = oSettings.aoData[ a[0] ].nTr;

                    addMorphM2M{{$jsUnique}}(data.id);

                });
					
				$modal.html(data.tabContent);
					
				$modal.modal('show').on('hidden', function() 
                { 
                    jQuery(this).empty(); 
                });
            });
        }

    </script>