@extends('core::layout.model')


<script type="text/javascript">
  
if (!telenok.hasPresentation('{{$presentationModuleKey}}'))
{
    var presentationTreeTab{{$uniqueId}} = Clazzzz.extend(
    {
        init: function()
        {
            this.presentationDomId = '';
            this.moduleKey = '';
            this.presentationParam = {};
        },
        getPresentationDomId: function()
        {
            return this.presentationDomId;
        },
        setParam: function(param)
        {
            this.presentationParam = param;
            this.presentationDomId = telenok.getPresentationDomId(param.presentation);
            this.moduleKey = param.key;
            return this;
        },
        addDataTable: function(param)
        {
            var buttons = param.buttons || [];
            var this_ = this;

            @section('tableListBtnCreate')
                if (param.tableListBtnCreate)
                {
                    buttons.push(param.tableListBtnCreate);
                }
                else 
                {
                    buttons.push({
                        text: "<i class='fa fa-plus smaller-90'></i> {{ $controller->LL('list.btn.create') }}",
                        className : 'btn-success btn-sm' + (param.btnCreateDisabled ? ' disabled ' : ''),
                        action : function (e, dt, button, config)
                        {
                            if (param.btnCreateDisabled || !param.btnCreateUrl) return false;
                            else this_.addTabByURL({url : param.btnCreateUrl});
                        }
                    });
                }
            @show

            @section('tableListBtnRefresh')
                if (param.tableListBtnRefresh)
                {
                    buttons.push(param.tableListBtnRefresh);
                }
                else 
                {
                    buttons.push({
                            text : "<i class='fa fa-refresh smaller-90'></i> {{ $controller->LL('list.btn.refresh') }}",
                            className : 'btn-sm',
                            action : function (e, dt, button, config)
                            {
                                dt.ajax.reload();
                            }
                        });
                }
            @show 

            @section('tableListBtnSelected')
                if (param.tableListBtnSelected)
                {
                    buttons.push(param.tableListBtnSelected);
                }
                else 
                {
                    buttons.push({
                        extend: 'collection',
                        className : 'btn btn-sm btn-light',
                        text : "<i class='fa fa-check-square-o smaller-90'></i> {{ $controller->LL('list.btn.select') }}",
                        buttons : [ 
                            {
                                text : "<i class='fa fa-pencil-square-o'></i> {{ $controller->LL('btn.edit') }}",
                                action : function (e, dt, button, config)
                                {
                                    if (param.btnListEditUrl)
                                    {
                                        this_.addTabByURL({
                                            url: param.btnListEditUrl, 
                                            data: jQuery('input[name=tableCheckAll\\[\\]]:checked', dt.table().body()).serialize() 
                                        });
                                    }
                                }
                            },
                            {
                                text : "<i class='fa fa-lock'></i> {{ $controller->LL('btn.lock') }}",
                                action : function (e, dt, button, config)
                                {
                                    if (param.btnListLockUrl && jQuery('input[name=tableCheckAll\\[\\]]:checked', dt.table().body()).size())
                                    {
                                        jQuery.ajax({
                                            url: param.btnListLockUrl, 
                                            data: jQuery('input[name=tableCheckAll\\[\\]]:checked', dt.table().body()).serialize(),
                                            method: 'post',
                                            dataType: 'json'
                                        }).done(function(data) 
                                        {
                                            if (data.success == 1)
                                            {
                                                jQuery.gritter.add({
                                                    title: '{{$controller->LL('notice.saved')}}! {{$controller->LL('notice.saved.description')}}',
                                                    text: '{{$controller->LL('notice.saved.thank.you')}}!',
                                                    class_name: 'gritter-success gritter-light',
                                                    time: 3000,
                                                });
                                            }
                                        }); 
                                    }
                                }
                            },
                            {
                                text : "<i class='fa fa-unlock'></i> {{ $controller->LL('btn.unlock') }}",
                                action : function (e, dt, button, config)
                                {
                                    if (param.btnListUnlockUrl && jQuery('input[name=tableCheckAll\\[\\]]:checked', dt.table().body()).size())
                                    {
                                        jQuery.ajax({
                                            url: param.btnListUnlockUrl, 
                                            data: jQuery('input[name=tableCheckAll\\[\\]]:checked', dt.table().body()).serialize(),
                                            method: 'post',
                                            dataType: 'json'
                                        }).done(function(data) 
                                        {
                                            if (data.success == 1)
                                            {
                                                jQuery.gritter.add({
                                                    title: '{{$controller->LL('notice.saved')}}! {{$controller->LL('notice.saved.description')}}',
                                                    text: '{{$controller->LL('notice.saved.thank.you')}}!',
                                                    class_name: 'gritter-success gritter-light',
                                                    time: 3000,
                                                });
                                            }
                                        }); 
                                    }
                                }
                            },
                            {
                                text : "<i class='fa fa-trash-o'></i> {{ $controller->LL('btn.delete') }}",
                                className :  (param.btnListDeleteDisabled ? ' disabled ' : ''),
                                action : function (e, dt, button, config)
                                {
                                    if (param.btnListDeleteDisabled || !param.btnListDeleteUrl) return false;
                                    else 
                                    {
                                        jQuery.ajax({
                                            url: param.btnListDeleteUrl,
                                            method: 'post',
                                            dataType: 'json',
                                            data: jQuery('input[name=tableCheckAll\\[\\]]:checked', dt.table().body()).serialize() 
                                        }).done(function(data)
                                        {
                                            if (data.success)
                                            {
                                                jQuery('input[name=tableCheckAll\\[\\]]:checked', dt.table().body()).closest("tr").remove();
                                            }
                                        });
                                    }
                                }
                            }
                        ]
                    });
                }
            @show

            @section('tableListBtnFilter')
                if (param.tableListBtnFilter)
                {
                    buttons.push(param.tableListBtnFilter);
                }
                else 
                {
                    buttons.push({
                        text : "<i class='fa fa-search'></i> {{ $controller->LL('btn.filter') }}",
                        className : 'btn btn-sm btn-light',
                        action : function (e, dt, button, config)
                        {
                            jQuery('div.filter', dt.table().body().closest('div.container-table')).toggle();
                        }
                    });
                }
            @show 				

            param = jQuery.extend({}, {
                columns : [],
                autoWidth : false,
                processing : true,
                serverSide : param.ajax ? true : false,
                deferRender : true,
                JQueryUI : false,
                pageLength : {{ $pageLength }},
                dom : "<'row'<'col-md-9'B><'col-md-3'f>r>t<'row'<'col-md-9'B><'col-md-3'p>>",
                @section('tableListBtn')
                buttons : buttons,
                @show
                language : {
                    paginate : {
                        next : "{{ trans('core::default.btn.next') }}",
                        previous : "{{ trans('core::default.btn.prev') }}", 
                    },
                    emptyTable : "{{ trans('core::default.table.empty') }}",
                    search : "{{ trans('core::default.btn.search') }} ",
                    info : "{{ trans('core::default.table.showed') }}",
                    infoEmpty : "{{ trans('core::default.table.empty.showed') }}",
                    zeroRecords : "{{ trans('core::default.table.empty.filtered') }}",
                    infoFiltered : "",
                }
            }, param);

            jQuery('#' + param.domId).DataTable(param);

            return this;
        },
        reloadDataTableOnClick: function(param)
        {
            if (jQuery('#' + this.getPresentationDomId() + '-grid-' + param.gridId).size())
            {
                jQuery('#' + this.getPresentationDomId() + '-grid-' + param.gridId)
                    .DataTable().ajax.url(param.url + (param.data ? '?' + jQuery.param(param.data) : '')).load();
            }
            return this;
        },
        showSkeleton: function()
        {
            return this;
        },
        callMe: function(param)
        {
            return this;
        }
    });

    @section('addPresentation')
    telenok.addPresentation('{{$presentationModuleKey}}', new presentationTreeTab{{$uniqueId}}());
    @show
}
</script>

@section('script')
	@parent

	@section('ajaxDone')

		jQuery.gritter.add({
			title: '{{$controller->LL('notice.saved')}}! {{$controller->LL('notice.saved.description')}}',
			text: '{{$controller->LL('notice.saved.thank.you')}}!',
			class_name: 'gritter-success gritter-light',
			time: 3000,
		});

		$el.closest('div.modal').html(data.tabContent); 

	@stop

@stop

<div class="modal-dialog">
	<div class="modal-content">

		<div class="modal-header table-header">
			<button data-dismiss="modal" class="close" type="button">×</button>
			<h4>{{ \App\Vendor\Telenok\Core\Model\Object\Type::where('code', (string)$model->getTable())->first()->translate('title') }}</h4>
		</div>

@section('notice')
	@parent
@stop

@section('form') 

{!! Form::model($model, array('url' => $routerParam, 'files' => true, 'id'=>"model-ajax-$uniqueId", 'class'=>'form-horizontal')) !!}
	
		{!! Form::hidden($model->getKeyName(), $model->getKey()) !!}

		<div class="modal-body" style="padding: 15px; position: relative;">
			<div class="widget-main">

				{!! $controller->getFormContent($model, $type, $fields, $uniqueId) !!}
					
			</div>
		</div>
		<div class="modal-footer">

			<div class="center no-margin">
				@if (app('request')->get('chooseBtn') && $model->exists)
				
				<script type="text/javascript">
					<?php
					
						$config = app('telenok.config.repository')->getObjectFieldController();

						$put = collect(); 

						if (app('request')->get('chooseSequence') && $model->exists())
						{
							$listModelField = $model->sequence;
						}
						else
						{
							$listModelField = $model;
						}
						
						foreach ($listModelField->getFieldList() as $field)
						{ 
							$put->put($field->code, $config->get($field->key)->getListFieldContent($field, $listModelField, $type));
						}
					?>
					
					var chooseWizard = {!! $put->toJson() !!};
				</script> 
 
				<button class="btn btn-success" onclick="
					var $modal = jQuery(this).closest('.modal');
						$modal.data('model-data')(chooseWizard); 
						$modal.modal('hide');
						return false;">
					<i class="fa fa-bullseye"></i>
					{{ $controller->LL('btn.choose') }}
				</button>
				@endif
				@if (\Input::get('saveBtn') && ( (!$model->exists && app('auth')->can('create', 'object_type.' . $type->code)) || ($model->exists && app('auth')->can('update', $model->getKey())) ))
				<button type="submit" class="btn btn-info">
					{{ $controller->LL('btn.save') }}
				</button>
				@endif
				<button class="btn" data-dismiss="modal">
					{{ $controller->LL('btn.close') }}
				</button>
			</div>


		</div>
	</div>
</div>
{!! Form::close() !!}
@stop
