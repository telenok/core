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
                    
                    jQuery("#telenok-{{$controller->getKey()}}-{{$jsUnique}}")
                        .on('xhr.dt', function ( e, settings, json, xhr )
                        {
                            jQuery("input.{{$field->code}}_delete_{{$jsUnique}}").remove();
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
                        dom : "<'row'<'col-md-6'B>r>t<'row'<'col-md-6'B>>",
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

    function markDeleted{{$jsUnique}}(val)
    {
        var $table = jQuery("#telenok-{{$controller->getKey()}}-{{$jsUnique}}");
        var $dt = $table.dataTable();
        var $tr = jQuery("tbody tr", $table);

        $tr.each(function(i, tr)
        {
            var data = $dt.fnGetData(tr);

            if (data.id == val)
            {
                jQuery(tr).addClass('line-through red');
                jQuery('button.trash-it i', tr).addClass('fa-power-off').removeClass('fa-trash-o');
                jQuery('button.trash-it', tr).addClass('btn-danger').removeClass('btn-success');
            }
        });
    }

    function addM2MAdditional{{$jsUnique}}(val) 
    {
        jQuery('<input type="hidden" class="{{$field->code}}_add_{{$jsUnique}}" name="{{$field->code}}_add[]" value="'+val+'" />')
                .insertBefore("table#telenok-{{$controller->getKey()}}-{{$jsUnique}}");
    }

    function removeM2M{{$jsUnique}}(val) 
    {
        var $table = jQuery("#telenok-{{$controller->getKey()}}-{{$jsUnique}}");

        if (jQuery("input.{{$field->code}}_delete_{{$jsUnique}}[value='*']").size())
        {
            jQuery('tr', $table).removeClass('line-through red');
            jQuery('button.trash-it i', $table).removeClass('fa-power-off').addClass('fa-trash-o');
            jQuery('button.trash-it', $table).removeClass('btn-danger').addClass('btn-success');
        }

        jQuery("input.{{$field->code}}_delete_{{$jsUnique}}[value='*']").remove();

        jQuery('<input type="hidden" class="{{$field->code}}_delete_{{$jsUnique}}" name="{{$field->code}}_delete[]" value="'+val+'" />')
                .insertBefore("table#telenok-{{$controller->getKey()}}-{{$jsUnique}}");

        markDeleted{{$jsUnique}}(val);
    }

    function removeM2MAddition{{$jsUnique}}(val) 
    {
        jQuery("input.{{$field->code}}_add_{{$jsUnique}}[value='"+val+"']").remove();
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

                addM2MAdditional{{$jsUnique}}(data.id);
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
                jQuery(this).empty(); 
            });
        });
    }

    function deleteTableRow{{$field->code}}{{$uniqueId}}(obj) 
    {
        var $dt = jQuery("#telenok-{{$controller->getKey()}}-{{$jsUnique}}").dataTable();
        var $tr = jQuery(obj).closest("tr");

        var data = $dt.fnGetData($tr[0]);

        removeM2M{{$jsUnique}}(data.id);
    }

    function deleteM2MAddition{{$jsUnique}}(obj) 
    {
        var $dt = jQuery("table#telenok-{{$controller->getKey()}}-{{$jsUnique}}-addition").dataTable();
        var $tr = jQuery(obj).closest("tr");

        var data = $dt.fnGetData($tr[0]);
        var rownum = $dt.fnGetPosition($tr[0]);
            $dt.fnDeleteRow(rownum);

        removeM2MAddition{{$jsUnique}}(data.id);
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

                addM2MAdditional{{$jsUnique}}(data.id);
            });

            $modal.html(data.tabContent);

            $modal.modal('show').on('hidden', function() 
            { 
                jQuery(this).empty(); 
            });
        });
    }
</script>