<?php
    
    $method = camel_case($field->code);
    $jsUnique = str_random();

	$disabledCreateFile = false;  
	
	if (!\Auth::can('create', 'object_type.file'))
	{
		$disabledCreateFile = true;
	} 
	
    $linkedField = $field->relation_many_to_many_has ? 'relation_many_to_many_has' : 'relation_many_to_many_belong_to';
?>
    <div class="widget-box transparent">
        <div class="widget-header widget-header-small">
            <h4>
                <i class="fa fa-list-ul"></i>
                {{ $field->translate('title_list') }}
            </h4> 
        </div>
        <div class="widget-body"> 
 
            <div class="widget-main field-list">

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
					
                    @if ( 
							((!$model->exists && $field->allow_create && $permissionCreate) || ($model->exists && $field->allow_update && $permissionUpdate))
								&&
							!$disabledCreateFile
						)
                    <li>
                        <a data-toggle="tab" href="#telenok-{{$controller->getKey()}}-{{$jsUnique}}-tab-upload">
                            <i class="green fa fa-upload bigger-110"></i>
                            {{$controller->LL('upload')}}
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
					
                    @if ( 
							((!$model->exists && $field->allow_create && $permissionCreate) || ($model->exists && $field->allow_update && $permissionUpdate))
								&&
							!$disabledCreateFile
						)
                    <div id="telenok-{{$controller->getKey()}}-{{$jsUnique}}-tab-upload" class="tab-pane ">
						<button onclick="Dropzone.forElement('div#telenok-{{$controller->getKey()}}-{{$jsUnique}}-tab-upload-dropzone').processQueue(); return false;" class="btn btn-sm btn-success">Upload</button>
						<div id="telenok-{{$controller->getKey()}}-{{$jsUnique}}-tab-upload-dropzone" class="form-group dropzone">
						</div>
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
                                                removeAllM2M{{$jsUnique}}();
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
									($model->exists && $field->allow_update && $permissionUpdate)) && !$disabledCreateFile
								)
							aButtons.push({
								"sExtends": "text",
								"sButtonText": "<i class='fa fa-plus smaller-90'></i> {{ $parentController->LL('list.btn.create') }}",
								'sButtonClass': 'btn-success btn-sm',
								"fnClick": function(nButton, oConfig, oFlash) {
									createM2M{{$jsUnique}}(this, '{!! URL::route($controller->getRouteWizardCreate(), [ 'id' => $field->{$linkedField}, 'saveBtn' => 1, 'chooseBtn' => 1]) !!}');
								}
							});
							@endif	
							
							@if ( 
									((!$model->exists && $field->allow_create && $permissionCreate) 
										|| 
									($model->exists && $field->allow_update && $permissionUpdate)) 
								)
							aButtons.push({
									"sExtends": "text",
									"sButtonText": "<i class='fa fa-refresh smaller-90'></i> {{ $parentController->LL('list.btn.choose') }}",
									'sButtonClass': 'btn-yellow btn-sm',
									"fnClick": function(nButton, oConfig, oFlash) {
										chooseM2M{{$jsUnique}}(this, '{!! URL::route($controller->getRouteWizardChoose(), ['id' => $field->{$linkedField}]) !!}');
									}
							});
							@endif

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
		try
		{
			jQuery("div#telenok-{{$controller->getKey()}}-{{$jsUnique}}-tab-upload-dropzone").dropzone({
					url: "{!! URL::route($controller->getRouteUpload()) !!}",
					paramName: "upload", // The name that will be used to transfer the file
					maxFilesize: 2.5, // MB
					addRemoveLinks : true,
					dictDefaultMessage :
					'<span class="bigger-150 bolder"><i class="fa fa-caret-right red"></i> Drop files</span> to upload \
					<span class="smaller-80 grey">(or click)</span> <br /> \
					<i class="upload-icon fa fa-cloud-upload blue fa fa-3x"></i>',
					dictResponseError: 'Error while uploading file!',
					autoProcessQueue: false,
					//change the previewTemplate to use Bootstrap progress bars
					previewTemplate: "<div class=\"dz-preview dz-file-preview\">\n  <div class=\"dz-details\">\n    <div class=\"dz-filename\"><span data-dz-name></span></div>\n    <div class=\"dz-size\" data-dz-size></div>\n    <img data-dz-thumbnail />\n  </div>\n  <div class=\"progress progress-small progress-success progress-striped active\"><span class=\"bar\" data-dz-uploadprogress></span></div>\n  <div class=\"dz-success-mark\"><span></span></div>\n  <div class=\"dz-error-mark\"><span></span></div>\n  <div class=\"dz-error-message\"><span data-dz-errormessage></span></div>\n</div>"
				});

			Dropzone.forElement('div#telenok-{{$controller->getKey()}}-{{$jsUnique}}-tab-upload-dropzone')
				.on("success", function(file, id) {
					jQuery('div#telenok-{{$controller->getKey()}}-{{$jsUnique}}-tab-upload').append(
						'<input type="hidden" class="{{$field->code}}_add_{{$jsUnique}}" name="{{$field->code}}_add[]" value="' + id + '" />'
					);
				});

			Dropzone.forElement('div#telenok-{{$controller->getKey()}}-{{$jsUnique}}-tab-upload-dropzone').on("sending", function(file, xhr, formData) {
					formData.append("title", file.name);
				});
		}
		catch(e) {}
			
        function addM2M{{$jsUnique}}(val) 
        {
            jQuery('<input type="hidden" class="{{$field->code}}_add_{{$jsUnique}}" name="{{$field->code}}_add[]" value="'+val+'" />')
                    .insertBefore("table#telenok-{{$controller->getKey()}}-{{$jsUnique}}");

            jQuery("input.{{$field->code}}_delete_{{$jsUnique}}[value='"+val+"']").remove();
            jQuery("input.{{$field->code}}_delete_{{$jsUnique}}[value='*']").remove();
        }
        
        function removeM2M{{$jsUnique}}(val) 
        {
            jQuery('<input type="hidden" class="{{$field->code}}_delete_{{$jsUnique}}" name="{{$field->code}}_delete[]" value="'+val+'" />')
                    .insertBefore("table#telenok-{{$controller->getKey()}}-{{$jsUnique}}");

            jQuery("input.{{$field->code}}_add_{{$jsUnique}}[value='"+val+"']").remove();
            jQuery("input.{{$field->code}}_delete_{{$jsUnique}}[value='*']").remove(); 
        }
        
        function removeAllM2M{{$jsUnique}}() 
        {
            jQuery("input.{{$field->code}}_delete_{{$jsUnique}}").remove();
            
            var $table = jQuery("#telenok-{{$controller->getKey()}}-{{$jsUnique}}");
            
            jQuery('<input type="hidden" class="{{$field->code}}_delete_{{$jsUnique}}" name="{{$field->code}}_delete[]" value="*" />')
                    .insertBefore($table);
            
            jQuery('tbody tr', $table).addClass('line-through red');
            jQuery('tbody tr button.trash-it i', $table).removeClass('fa fa-trash-o').addClass('fa fa-power-off');
            jQuery('tbody tr button.trash-it', $table).removeClass('btn-danger').addClass('btn-success');
        }

        function createM2M{{$jsUnique}}(obj, url) 
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
					data.tableManageItem = '<button class="btn btn-minier btn-danger trash-it" title="{{$controller->LL('list.btn.delete')}}" onclick="deleteM2MAddition{{$jsUnique}}(this); return false;">'
                        + '<i class="fa fa-trash-o"></i></button>';
					
                    var $dt = jQuery("table#telenok-{{$controller->getKey()}}-{{$jsUnique}}-addition").dataTable();
                    var a = $dt.fnAddData(data, true);
                    var oSettings = $dt.fnSettings();
                    var nTr = oSettings.aoData[ a[0] ].nTr;

                    addM2M{{$jsUnique}}(data.id);
                    
                });
					
				$modal.html(data.tabContent);
					
				$modal.modal('show').on('hidden', function() 
                { 
                    jQuery(this).html(""); 
                });
            });
        }
        
        function editM2M{{$jsUnique}}(obj, url) 
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

        function deleteM2M{{$jsUnique}}(obj) 
        {
            var $dt = jQuery("#telenok-{{$controller->getKey()}}-{{$jsUnique}}").dataTable();
            var $tr = jQuery(obj).closest("tr");
            
            var data = $dt.fnGetData($tr[0]);
            
            $tr.toggleClass('line-through red');
            jQuery('button.trash-it i', $tr).toggleClass('fa fa-trash-o').toggleClass('fa fa-power-off');
            jQuery('button.trash-it', $tr).toggleClass('btn-danger').toggleClass('btn-success');
            
            removeM2M{{$jsUnique}}(data.id);
        }

        function deleteM2MAddition{{$jsUnique}}(obj) 
        {
            var $dt = jQuery("table#telenok-{{$controller->getKey()}}-{{$jsUnique}}-addition").dataTable();
            var $tr = jQuery(obj).closest("tr");
            
            var data = $dt.fnGetData($tr[0]);
            var rownum = $dt.fnGetPosition($tr[0]);
                $dt.fnDeleteRow(rownum);
            
            removeM2M{{$jsUnique}}(data.id);
        } 

        function chooseM2M{{$jsUnique}}(obj, url) 
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
				
				$modal = jQuery('#modal-{{$jsUnique}}');

                $modal.data('model-data', function(data)
                {
					data.tableManageItem = '<button class="btn btn-minier btn-danger trash-it" title="{{$controller->LL('list.btn.delete')}}" onclick="deleteM2MAddition{{$jsUnique}}(this); return false;">'
                        + '<i class="fa fa-trash-o"></i></button>';
				
                    var $dt = jQuery("table#telenok-{{$controller->getKey()}}-{{$jsUnique}}-addition").dataTable();
                    var a = $dt.fnAddData(data, true);
                    var oSettings = $dt.fnSettings();
                    var nTr = oSettings.aoData[ a[0] ].nTr;

                    addM2M{{$jsUnique}}(data.id);

                });
				
				$modal.html(data.tabContent);
					
				$modal.modal('show').on('hidden', function() 
                { 
                    jQuery(this).html(""); 
                });
            });
        }

    </script>