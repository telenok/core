<?php
    
    $domAttr = ['class' => 'col-md-6', 'disabled' => 'disabled'];
    $method = camel_case($field->code);
    $jsUnique = str_random();
 
    $linkedField = $field->relation_many_to_many_has ? 'relation_many_to_many_has' : 'relation_many_to_many_belong_to';
	
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
                    
                    if (columns.length)
                    {
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
                                    removeAllM2M{{$jsUnique}}();
                                }
                            });
                        @endif

                        telenok.addDataTable({
                            domId : "telenok-{{$controller->getKey()}}-{{$jsUnique}}",
                            retrieve : true,
                            columns : columns,
                            order : [],
                            pageLength : {{$pageLength}},
                            ajax : '{!! $urlListTable !!}', 
                            buttons : buttons
                        });
                    }
                    
                    buttons = [];

                    if (columns.length)
                    {
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
                                createM2M{{$jsUnique}}('{!! $urlWizardCreate !!}');
                            }
                        });
                        @endif	

                        buttons.push({
                            text : "<i class='fa fa-refresh smaller-90'></i> {{ $controllerParent->LL('list.btn.choose') }}",
                            className : 'btn-yellow btn-sm',
                            action : function (e, dt, button, config)
                            {
                                chooseM2M{{$jsUnique}}('{!! $urlWizardChoose !!}');
                            }
                        });
                            
                        telenok.addDataTable({
                            domId : "telenok-{{$controller->getKey()}}-{{$jsUnique}}-addition",
                            dom : "<'row'<'col-md-6'B>r>t<'row'<'col-md-6'T>>",
                            retrieve : true,
                            columns : columns,
                            order : [],
                            data : [], 
                            buttons : buttons
                        });
                    }
                })();
                </script>
            </div>
        </div>
    </div>
 

    <script type="text/javascript">
        
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
            $table.DataTable().clear().draw('page');

            jQuery('<input type="hidden" class="{{$field->code}}_delete_{{$jsUnique}}" name="{{$field->code}}_delete[]" value="*" />')
                        .insertBefore($table);
        }

        function createM2M{{$jsUnique}}(url) 
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

                    jQuery("table#telenok-{{$controller->getKey()}}-{{$jsUnique}}-addition").DataTable().row.add(data).draw();

                    addM2M{{$jsUnique}}(data.id);
                });

				$modal.html(data.tabContent);

				$modal.modal('show').on('hidden', function() 
                { 
                    jQuery(this).html(""); 
                });
            });
        }

        function editTableRow{{$field->code}}{{$uniqueId}}(obj, url) 
        {
            jQuery.ajax({
                url: url,
                method: 'get',
                dataType: 'json'
            })
            .done(function(data) 
            {

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

        function deleteTableRow{{$field->code}}{{$uniqueId}}(obj) 
        {
            var $dt = jQuery("#telenok-{{$controller->getKey()}}-{{$jsUnique}}").DataTable();
            var row = $dt.row( jQuery(obj).parents('tr') )
            var data = row.data();

                row.remove().draw();

            removeM2M{{$jsUnique}}(data.id);
        }

        function deleteM2MAddition{{$jsUnique}}(obj) 
        {
            var $dt = jQuery("table#telenok-{{$controller->getKey()}}-{{$jsUnique}}-addition").DataTable();
            
            var row = $dt.row( jQuery(obj).parents('tr') )
            var data = row.data();

                row.remove().draw(); 
            
            removeM2M{{$jsUnique}}(data.id);
        } 

        function chooseM2M{{$jsUnique}}(url) 
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
					
                    jQuery("table#telenok-{{$controller->getKey()}}-{{$jsUnique}}-addition").DataTable().row.add(data).draw();

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

@if ($model->{$linkedField})

    <div class="form-group">
        {!! Form::label('relation_many_to_many_default', $controller->LL('property.default'), array('class'=>'col-sm-3 control-label no-padding-right')) !!}
        <div class="col-sm-9">
            <select class="chosen-select" multiple data-placeholder="{{$controller->LL('notice.choose')}}" 
                    id="relation_many_to_many_default-{{$jsUnique}}" name="relation_many_to_many_default[]">

                <option></option>

            <?php

                $subjects = \App\Telenok\Core\Model\Object\Sequence::getModelByTypeId($model->{$linkedField})
                    ->active()->withPermission()
                    ->whereIn($model->getKeyName(), $model->relation_many_to_many_default->all())
                    ->get(['id', 'title']);

                foreach ($subjects as $subject) 
                {
                    echo "<option value='{$subject->getKey()}' selected='selected'>[#{$subject->id}] {$subject->translate('title')}</option>";
                }
            ?>
            </select>
            <script type="text/javascript">
                jQuery("#relation_one_to_many_default-{{$jsUnique}}").ajaxChosen({
                    keepTypingMsg: "{{ $controller->LL('notice.typing') }}",
                    lookingForMsg: "{{ $controller->LL('notice.looking-for') }}",
                    type: "GET",
                    url: "{!! route($controller->getRouteListTitle(), ['id' => (int)$model->{$linkedField}]) !!}",
                    dataType: "json",
                    minTermLength: 1
                },
                function (data)
                {
                    var results = [];

                    jQuery.each(data, function (i, val) 
                    {
                        results.push({value: val.value, text: val.text});
                    });

                    return results;
                },
                {
                    width: "300px",
                    no_results_text: "{{ $controller->LL('notice.not-found') }}"
                });
            </script>
        </div>
    </div>
@endif