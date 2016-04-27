<?php

    $method = camel_case($field->code);
    $jsUnique = str_random();

	$disabledCreateLinkedType = false;

	$linkedType = $controller->getLinkedModelType($field);

	if (!app('auth')->can('create', 'object_type.' . $linkedType->code))
	{
		$disabledCreateLinkedType = true;
	}
?>

@if ($field->relation_one_to_many_has)

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
                                    removeAllO2MHas{{$jsUnique}}();
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

                            jQuery("#telenok-{{$controller->getKey()}}-{{$jsUnique}}")
                                .on('xhr.dt', function ( e, settings, json, xhr )
                                {
                                    jQuery("input.{{$field->code}}_delete_{{$jsUnique}}").remove();
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
                                createO2MHas{{$jsUnique}}('{!! $urlWizardCreate !!}');
                            }
                        });
                        @endif

                        buttons.push({
                            text : "<i class='fa fa-refresh smaller-90'></i> {{ $controllerParent->LL('list.btn.choose') }}",
                            className : 'btn-yellow btn-sm',
                            action : function (e, dt, button, config)
                            {
                                chooseO2MHas{{$jsUnique}}('{!! $urlWizardChoose !!}');
                            }
                        });

                        if (columns.length)
                        {
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

        function addO2MHasAdditional{{$jsUnique}}(val)
        {
            jQuery('<input type="hidden" class="{{$field->code}}_add_{{$jsUnique}}" name="{{$field->code}}_add[]" value="'+val+'" />')
                    .insertBefore("table#telenok-{{$controller->getKey()}}-{{$jsUnique}}");
        }

        function removeO2MHas{{$jsUnique}}(val)
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

        function removeO2MHasAddition{{$jsUnique}}(val)
        {
            jQuery("input.{{$field->code}}_add_{{$jsUnique}}[value='"+val+"']").remove();
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

        function createO2MHas{{$jsUnique}}(url)
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

                    addO2MHasAdditional{{$jsUnique}}(data.id);
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

            removeO2MHas{{$jsUnique}}(data.id);
        }

        function deleteO2MHasAddition{{$jsUnique}}(obj)
        {
            var $dt = jQuery("table#telenok-{{$controller->getKey()}}-{{$jsUnique}}-addition").dataTable();
            var $tr = jQuery(obj).closest("tr");

            var data = $dt.fnGetData($tr[0]);
            var rownum = $dt.fnGetPosition($tr[0]);
                $dt.fnDeleteRow(rownum);

            removeO2MHasAddition{{$jsUnique}}(data.id);
        }

        function chooseO2MHas{{$jsUnique}}(url)
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

                    addO2MHasAdditional{{$jsUnique}}(data.id);
                });

				$modal.html(data.tabContent);

				$modal.modal('show').on('hidden', function()
                {
                    jQuery(this).empty();
                });
            });
        }

    </script>

@elseif ($field->relation_one_to_many_belong_to)

    <?php

        $domAttr = ['disabled' => 'disabled', 'class' => 'col-xs-5 col-sm-5'];

        $title = '';
        $id = 0;

        if ($model->exists && $result = $model->{$method}()->first())
        {
            $title = $result->translate('title');
            $id = $result->id;
        }

		$disabledCreateLinkedType = false;

		$linkedType = $controller->getLinkedModelType($field);

		if (!app('auth')->can('create', 'object_type.' . $linkedType->code))
		{
			$disabledCreateLinkedType = true;
		}
    ?>

    <div class="form-group" data-field-key='{{ $field->code }}'>
        {!! Form::label("{$field->code}", $field->translate('title'), array('class'=>'col-sm-3 control-label no-padding-right')) !!}
        <div class="col-sm-9">
            {!! Form::hidden("{$field->code}", $id) !!}
            {!! Form::text(str_random(), ($id ? "[{$id}] " : "") . $title, $domAttr ) !!}

			@if (
					((!$model->exists && $field->allow_create && $permissionCreate)
						||
					($model->exists && $field->allow_update && $permissionUpdate))
				)
            <button onclick="chooseO2MBelongTo{{$jsUnique}}(this, '{!! $urlWizardChoose !!}'); return false;" data-toggle="modal" class="btn btn-sm" type="button">
                <i class="fa fa-bullseye"></i>
                {{ $controller->LL('btn.choose') }}
            </button>
			@endif

			@if (
					((!$model->exists && $field->allow_create && $permissionCreate)
						||
					($model->exists && $field->allow_update && $permissionUpdate)) && !$disabledCreateLinkedType
				)
            <button onclick="createO2MBelongTo{{$jsUnique}}(this, '{!! $urlWizardCreate !!}'); return false;" data-toggle="modal" class="btn btn-sm" type="button">
                <i class="fa fa-plus"></i>
                {{ $controller->LL('btn.create') }}
            </button>
			@endif


			@if (
					((!$model->exists && $field->allow_create && $permissionCreate)
						||
					($model->exists && $field->allow_update && $permissionUpdate))
				)
            <button onclick="editO2MBelongTo{{$jsUnique}}(this, '{!! $urlWizardEdit !!}'); return false;" data-toggle="modal" class="btn btn-sm btn-success" type="button">
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
                    jQuery(this).empty();
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
                    jQuery(this).empty();
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
                    jQuery(this).empty();
                });
            });
        }

    </script>

@endif