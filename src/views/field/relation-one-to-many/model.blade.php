<?php

    $method = camel_case($field->code);
    $linkedField = $field->relation_one_to_many_has ? 'relation_one_to_many_has' : 'relation_one_to_many_belong_to';
    $jsUnique = str_random();
	
	$disabledCreateLinkedType = false;

	$linkedType = $controller->getLinkedModelType($field);
	
	if (!\Auth::can('create', 'object_type.' . $linkedType->code))
	{
		$disabledCreateLinkedType = true;
	}
?>

@if ($field->relation_one_to_many_has)

    <div class="widget-box transparent">
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
                    
                    jQuery('ul.nav-tabs#telenok-{{$controller->getKey()}}-{{$jsUnique}}-tab a:first').tab('show');
    
                    var presentation = telenok.getPresentation('{{ $parentController->getPresentationModuleKey()}}');
                    
                    var aoColumns = [];
                    var aButtons = []; 

							@foreach($controller->getFormModelTableColumn($field, $model, $jsUnique) as $row)
                                aoColumns.push({!! json_encode($row) !!});
							@endforeach

							aButtons.push({
                                            "sExtends": "text",
                                            "sButtonText": "<i class='fa fa-refresh smaller-90'></i> {{ $parentController->LL('list.btn.refresh') }}",
                                            'sButtonClass': 'btn-sm',
                                            "fnClick": function(nButton, oConfig, oFlash) {
                                                jQuery('#' + "telenok-{{$controller->getKey()}}-{{$jsUnique}}").dataTable().fnReloadAjax();
                                            }
                                        });

							@if ($model->exists && $field->allow_update && $permissionUpdate)
								aButtons.push({
                                            "sExtends": "text",
                                            "sButtonText": "<i class='fa fa-trash-o smaller-90'></i> {{ $parentController->LL('list.btn.delete.all') }}",
                                            'sButtonClass': 'btn-sm btn-danger',
                                            "fnClick": function(nButton, oConfig, oFlash) {
                                                removeAllO2MHas{{$jsUnique}}();
                                            }
                                        });
							@endif

							if (aoColumns.length)
							{
								presentation.addDataTable({
									domId: "telenok-{{$controller->getKey()}}-{{$jsUnique}}",
									bRetrieve : true,
									aoColumns : aoColumns,
									aaSorting: [],
									iDisplayLength : {{$displayLength}},
									sAjaxSource : '{!! URL::route($controller->getRouteListTable(), ["id" => (int)$model->getKey(), "fieldId" => $field->getKey(), "uniqueId" => $jsUnique]) !!}', 
									oTableTools: {
										aButtons : aButtons
									}
								});
							}

							aButtons = [];
							
							@if ( 
									((!$model->exists && $field->allow_create && $permissionCreate) 
										|| 
									($model->exists && $field->allow_update && $permissionUpdate)) && !$disabledCreateLinkedType
								)
							aButtons.push({
                                            "sExtends": "text",
                                            "sButtonText": "<i class='fa fa-plus smaller-90'></i> {{ $parentController->LL('list.btn.create') }}",
                                            'sButtonClass': 'btn-success btn-sm',
                                            "fnClick": function(nButton, oConfig, oFlash) {
                                                createO2MHas{{$jsUnique}}(this, '{!! URL::route($controller->getRouteWizardCreate(), [ 'id' => $field->relation_one_to_many_has, 'saveBtn' => 1, 'chooseBtn' => 1]) !!}');
                                            }
                                        });
							@endif	
 
							aButtons.push({
                                            "sExtends": "text",
                                            "sButtonText": "<i class='fa fa-refresh smaller-90'></i> {{ $parentController->LL('list.btn.choose') }}",
                                            'sButtonClass': 'btn-yellow btn-sm',
                                            "fnClick": function(nButton, oConfig, oFlash) {
                                                chooseO2MHas{{$jsUnique}}(this, '{!! URL::route($controller->getRouteWizardChoose(), ['id' => $controller->getChooseTypeId($field, $linkedField)]) !!}');
                                            }
                                        }); 

							if (aoColumns.length)
							{
								presentation.addDataTable({
									domId: "telenok-{{$controller->getKey()}}-{{$jsUnique}}-addition",
									sDom: "<'row'<'col-md-6'T>r>t<'row'<'col-md-6'T>>",
									bRetrieve : true,
									aoColumns : aoColumns,
									aaSorting: [],
									aaData : [], 
									oTableTools: {
										aButtons : aButtons
									}
								});
							}
                </script>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        function addO2MHas{{$jsUnique}}(val) 
        {
            jQuery('<input type="hidden" class="{{$field->code}}_add_{{$jsUnique}}" name="{{$field->code}}_add[]" value="'+val+'" />')
                    .insertBefore("table#telenok-{{$controller->getKey()}}-{{$jsUnique}}");

            jQuery("input.{{$field->code}}_delete_{{$jsUnique}}[value='"+val+"']").remove();
            jQuery("input.{{$field->code}}_delete_{{$jsUnique}}[value='*']").remove();
        }
        
        function removeO2MHas{{$jsUnique}}(val) 
        {
            jQuery('<input type="hidden" class="{{$field->code}}_delete_{{$jsUnique}}" name="{{$field->code}}_delete[]" value="'+val+'" />')
                    .insertBefore("table#telenok-{{$controller->getKey()}}-{{$jsUnique}}");

            jQuery("input.{{$field->code}}_add_{{$jsUnique}}[value='"+val+"']").remove();
            jQuery("input.{{$field->code}}_delete_{{$jsUnique}}[value='*']").remove(); 
        }
        
        function removeAllO2MHas{{$jsUnique}}() 
        {
            jQuery("input.{{$field->code}}_delete_{{$jsUnique}}").remove();
            
            var $table = jQuery("#telenok-{{$controller->getKey()}}-{{$jsUnique}}");
            
            jQuery('<input type="hidden" class="{{$field->code}}_delete_{{$jsUnique}}" name="{{$field->code}}_delete[]" value="*" />')
                    .insertBefore($table);
            
            jQuery('tbody tr', $table).addClass('line-through red');
            jQuery('tbody tr button.trash-it i', $table).removeClass('fa fa-trash-o').addClass('fa fa-power-off');
            jQuery('tbody tr button.trash-it', $table).removeClass('btn-danger').addClass('btn-success');
        }
        
        function createO2MHas{{$jsUnique}}(obj, url) 
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
					data.tableManageItem = '<button class="btn btn-minier btn-danger trash-it" title="{{$controller->LL('list.btn.delete')}}" onclick="deleteO2MHasAddition{{$jsUnique}}(this); return false;">'
                        + '<i class="fa fa-trash-o"></i></button>';
				
                    var $dt = jQuery("table#telenok-{{$controller->getKey()}}-{{$jsUnique}}-addition").dataTable();
                    var a = $dt.fnAddData(data, true);
                    var oSettings = $dt.fnSettings();
                    var nTr = oSettings.aoData[ a[0] ].nTr;

                    addO2MHas{{$jsUnique}}(data.id);
                    
                });
				
				$modal.html(data.tabContent);
					
				$modal.modal('show').on('hidden', function() 
                { 
                    jQuery(this).html(""); 
                });
            });
        }
        
        function editTableRow{{$jsUnique}}(obj, url) 
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

                });

                $modal.html(data.tabContent);

                $modal.modal('show').on('hidden', function() 
                { 
                    jQuery(this).html(""); 
                });
            });
        }

        function deleteTableRow{{$jsUnique}}(obj) 
        {
            var $dt = jQuery("#telenok-{{$controller->getKey()}}-{{$jsUnique}}").dataTable();
            var $tr = jQuery(obj).closest("tr");

            var data = $dt.fnGetData($tr[0]);

            $tr.toggleClass('line-through red');
            jQuery('button.trash-it i', $tr).toggleClass('fa fa-trash-o').toggleClass('fa fa-power-off');
            jQuery('button.trash-it', $tr).toggleClass('btn-danger').toggleClass('btn-success');

            removeO2MHas{{$jsUnique}}(data.id);
        }

        function deleteO2MHasAddition{{$jsUnique}}(obj) 
        {
            var $dt = jQuery("table#telenok-{{$controller->getKey()}}-{{$jsUnique}}-addition").dataTable();
            var $tr = jQuery(obj).closest("tr");
            
            var data = $dt.fnGetData($tr[0]);
            var rownum = $dt.fnGetPosition($tr[0]);
                $dt.fnDeleteRow(rownum);
            
            removeO2MHas{{$jsUnique}}(data.id);
        } 

        function chooseO2MHas{{$jsUnique}}(obj, url) 
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
					data.tableManageItem = '<button class="btn btn-minier btn-danger trash-it" title="{{$controller->LL('list.btn.delete')}}" onclick="deleteO2MHasAddition{{$jsUnique}}(this); return false;">'
                        + '<i class="fa fa-trash-o"></i></button>';
				
                    var $dt = jQuery("table#telenok-{{$controller->getKey()}}-{{$jsUnique}}-addition").dataTable();
                    var a = $dt.fnAddData(data, true);
                    var oSettings = $dt.fnSettings();
                    var nTr = oSettings.aoData[ a[0] ].nTr;

                    addO2MHas{{$jsUnique}}(data.id);

                });
				
				$modal.html(data.tabContent);
					
				$modal.modal('show').on('hidden', function() 
                { 
                    jQuery(this).html(""); 
                });
            });
        }

    </script>

@elseif ($field->relation_one_to_many_belong_to) 

    <?php 
    
        $domAttr = ['disabled' => 'disabled', 'class' => 'col-xs-5 col-sm-5'];

        $title = '';
        $id = 0;

        if ($model->exists && $result = $model->$method()->first())
        {
            $title = $result->translate('title');
            $id = $result->id;
        }

		$disabledCreateLinkedType = false;

		$linkedType = $controller->getLinkedModelType($field);

		if (!\Auth::can('create', 'object_type.' . $linkedType->code))
		{
			$disabledCreateLinkedType = true;
		}
    ?> 

    <div class="form-group">
        {!! Form::label("{$field->code}", $field->translate('title'), array('class'=>'col-sm-3 control-label no-padding-right')) !!}
        <div class="col-sm-9"> 
            {!! Form::hidden("{$field->code}", $id) !!}
            {!! Form::text(str_random(), ($id ? "[{$id}] " : "") . $title, $domAttr ) !!}
            
			@if ( 
					((!$model->exists && $field->allow_create && $permissionCreate) 
						|| 
					($model->exists && $field->allow_update && $permissionUpdate))
				)
            <button onclick="chooseO2MBelongTo{{$jsUnique}}(this, '{!! URL::route($controller->getRouteWizardChoose(), ['id' => $controller->getChooseTypeId($field, $linkedField)]) !!}'); return false;" data-toggle="modal" class="btn btn-sm" type="button">
                <i class="fa fa-bullseye"></i>
                {{ $controller->LL('btn.choose') }}
            </button>
			@endif
				
			@if ( 
					((!$model->exists && $field->allow_create && $permissionCreate) 
						|| 
					($model->exists && $field->allow_update && $permissionUpdate)) && !$disabledCreateLinkedType
				)
            <button onclick="createO2MBelongTo{{$jsUnique}}(this, '{!! URL::route($controller->getRouteWizardCreate(), [ 'id' => $field->{$linkedField}, 'saveBtn' => 1, 'chooseBtn' => 1]) !!}'); return false;" data-toggle="modal" class="btn btn-sm" type="button">
                <i class="fa fa-plus"></i>
                {{ $controller->LL('btn.create') }}
            </button>
			@endif
			
			
			@if ( 
					((!$model->exists && $field->allow_create && $permissionCreate) 
						|| 
					($model->exists && $field->allow_update && $permissionUpdate))
				)
            <button onclick="editO2MBelongTo{{$jsUnique}}(this, '{!! URL::route($controller->getRouteWizardEdit(), ['id' => '--id--', 'saveBtn' => 1]) !!}'); return false;" data-toggle="modal" class="btn btn-sm btn-success" type="button">
                <i class="fa fa-pencil"></i>
                {{ $controller->LL('btn.edit') }}
            </button>
			@endif
				
			@if ( 
					((!$model->exists && $field->allow_create && $permissionCreate) 
						|| 
					($model->exists && $field->allow_update && $permissionUpdate))
				)
            <button onclick="deleteO2MBelongTo{{$jsUnique}}(this); return false;" data-toggle="modal" class="btn btn-sm btn-danger" type="button">
                <i class="fa fa-trash-o"></i>
                {{ $controller->LL('btn.delete') }}
            </button>
			@endif

        </div>
    </div>

    <script type="text/javascript">
        
        function createO2MBelongTo{{$jsUnique}}(obj, url) 
        {
            var $block = jQuery(obj).closest('div.form-group');

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
                    jQuery('input[type="text"]', $block).val(data.title);
                    jQuery('input[type="hidden"]', $block).val(data.id);

                });
						
				$modal.html(data.tabContent);
						
				$modal.modal('show').on('hidden', function() 
                { 
                    jQuery(this).html(""); 
                });
            });
        }

        function editO2MBelongTo{{$jsUnique}}(obj, url) 
        {
            var $block = jQuery(obj).closest('div.form-group');

            var id = jQuery('input[type="hidden"]', $block).val();
            
            if (id == 0) return false;
            
            url = url.replace('--id--', id);

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
                    jQuery('input[type="text"]', $block).val(data.title);
                    jQuery('input[type="hidden"]', $block).val(data.id);

                });
						
				$modal.html(data.tabContent);
						
				$modal.modal('show').on('hidden', function() 
                { 
                    jQuery(this).html(""); 
                });
            });
        }

        function deleteO2MBelongTo{{$jsUnique}}(obj) 
        {
            var $block = jQuery(obj).closest('div.form-group');

            jQuery('input[type="text"]', $block).val('');
            jQuery('input[type="hidden"]', $block).val(0);
        }

        function chooseO2MBelongTo{{$jsUnique}}(obj, url) 
        {
            var $block = jQuery(obj).closest('div.form-group');

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
                    jQuery('input[type="text"]', $block).val(data.title);
                    jQuery('input[type="hidden"]', $block).val(data.id);
                });
						
				$modal.html(data.tabContent);
						
				$modal.modal('show').on('hidden', function() 
				{ 
                    jQuery(this).html(""); 
                });
            });
        }
        
    </script>

@endif