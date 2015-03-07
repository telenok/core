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
            var aButtons = [];
            var this_ = this;

            @section('tableListBtnCreate')
                if (param.tableListBtnCreate)
                {
                    aButtons.push(param.tableListBtnCreate);
                }
                else 
                {
                    aButtons.push({
                        "sExtends": "text",
                        "sButtonText": "<i class='fa fa-plus smaller-90'></i> {{ $controller->LL('list.btn.create') }}",
                        'sButtonClass': 'btn-success btn-sm' + (param.btnCreateDisabled ? ' disabled ' : ''),
                        "fnClick": function(nButton, oConfig, oFlash) {
                            if (param.btnCreateDisabled || !param.btnCreateUrl) return false;
                            else this_.addTabByURL({url : param.btnCreateUrl});
                        }
                    });
                }
            @show

            @section('tableListBtnRefresh')
                if (param.tableListBtnRefresh)
                {
                    aButtons.push(param.tableListBtnRefresh);
                }
                else 
                {
                    aButtons.push({
                            "sExtends": "text",
                            "sButtonText": "<i class='fa fa-refresh smaller-90'></i> {{ $controller->LL('list.btn.refresh') }}",
                            'sButtonClass': 'btn-sm',
                            "fnClick": function(nButton, oConfig, oFlash) {
                                jQuery('#' + param.domId).dataTable().fnReloadAjax();
                            }
                        });
                }
            @show 

            @section('tableListBtnSelected')
                if (param.tableListBtnSelected)
                {
                    aButtons.push(param.tableListBtnSelected);
                }
                else 
                {
                    aButtons.push({
                        "sExtends": "collection",
                        'sButtonClass': 'btn btn-sm btn-light',
                        "sButtonText": "<i class='fa fa-check-square-o smaller-90'></i> {{ $controller->LL('list.btn.select') }}",
                        "aButtons": [ 
                            {
                                "sExtends": "text",
                                "sButtonText": "<i class='fa fa-pencil-square-o'></i> {{ $controller->LL('btn.edit') }}",
                                "fnClick": function(nButton, oConfig, oFlash) 
                                    {
                                        if (param.btnListEditUrl)
                                        {
                                            this_.addTabByURL({
                                                url: param.btnListEditUrl, 
                                                data: jQuery('input[name=tableCheckAll\\[\\]]:checked', this.dom.table).serialize() 
                                            });
                                        }
                                }
                            },
                            {
                                "sExtends": "text",
                                "sButtonText": "<i class='fa fa-lock'></i> {{ $controller->LL('btn.lock') }}",
                                "fnClick": function(nButton, oConfig, oFlash) 
                                {
                                    if (param.btnListLockUrl && jQuery('input[name=tableCheckAll\\[\\]]:checked', this.dom.table).size())
                                    {
                                        jQuery.ajax({
                                            url: param.btnListLockUrl, 
                                            data: jQuery('input[name=tableCheckAll\\[\\]]:checked', this.dom.table).serialize(),
                                            method: 'get',
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
                                "sExtends": "text",
                                "sButtonText": "<i class='fa fa-unlock'></i> {{ $controller->LL('btn.unlock') }}",
                                "fnClick": function(nButton, oConfig, oFlash) 
                                {
                                    if (param.btnListUnlockUrl && jQuery('input[name=tableCheckAll\\[\\]]:checked', this.dom.table).size())
                                    {
                                        jQuery.ajax({
                                            url: param.btnListUnlockUrl, 
                                            data: jQuery('input[name=tableCheckAll\\[\\]]:checked', this.dom.table).serialize(),
                                            method: 'get',
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
                                "sExtends": "text",
                                'sButtonClass':  (param.btnListDeleteDisabled ? ' disabled ' : ''),
                                "sButtonText": "<i class='fa fa-trash-o'></i> {{ $controller->LL('btn.delete') }}",
                                "fnClick": function(nButton, oConfig, oFlash) {
                                    if (param.btnListDeleteDisabled || !param.btnListDeleteUrl) return false;
                                    else 
                                    {
                                        var this_ = this;

                                        jQuery.ajax({
                                            url: param.btnListDeleteUrl,
                                            method: 'post',
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
                            }
                        ]
                    });
                }
            @show		

            @section('tableListBtnFilter')
                if (param.tableListBtnFilter)
                {
                    aButtons.push(param.tableListBtnFilter);
                }
                else 
                {
                    aButtons.push({
                            "sExtends": "text",
                            'sButtonClass': 'btn btn-sm btn-light',
                            "sButtonText": "<i class='fa fa-search'></i> {{ $controller->LL('btn.filter') }}",
                            "fnClick": function(nButton, oConfig, oFlash) {
                                jQuery('div.filter', jQuery(this.dom.table).closest('div.container-table')).toggle();
                            }
                        });
                }
            @show 				

            param = jQuery.extend({}, {
                "multipleSelection": true,
                "aoColumns": [],
                "autoWidth": false,
                "bProcessing": true,
                "bServerSide": param.sAjaxSource ? true : false,
                "bDeferRender": '',
                "bJQueryUI": false,
                "iDisplayLength": {{ $iDisplayLength }},
                "sDom": "<'row'<'col-md-6'T><'col-md-6'f>r>t<'row'<'col-md-6'T><'col-md-6'p>>",
                "oTableTools": {
                    @section('tableListBtn')
                    "aButtons": aButtons
                    @show 				
                },
                "oLanguage": {
                    "oPaginate": {
                        "sNext": "{{ \Lang::get('core::default.btn.next') }}",
                        "sPrevious": "{{ \Lang::get('core::default.btn.prev') }}", 
                    },
                    "sEmptyTable": "{{ \Lang::get('core::default.table.empty') }}",
                    "sSearch": "{{ \Lang::get('core::default.btn.search') }} ",
                    "sInfo": "{{ \Lang::get('core::default.table.showed') }}",
                    "sInfoEmpty": "{{ \Lang::get('core::default.table.empty.showed') }}",
                    "sZeroRecords": "{{ \Lang::get('core::default.table.empty.filtered') }}",
                    "sInfoFiltered": "",
                }
            }, param);

            jQuery('#' + param.domId).dataTable(param);

            return this;
        },
        reloadDataTableOnClick: function(param)
        {
            if (jQuery('#' + this.getPresentationDomId() + '-grid-' + param.gridId).size())
            {
                jQuery('#' + this.getPresentationDomId() + '-grid-' + param.gridId)
                        .dataTable()
                        .fnReloadAjax(param.url + (param.data ? '?' + jQuery.param(param.data) : ''));
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

<div class="modal-backdrop fade in"></div>

<div class="modal-dialog">
	<div class="modal-content">

		<div class="modal-header table-header">
			<button data-dismiss="modal" class="close" type="button">×</button>
			<h4>{{ \App\Model\Telenok\Object\Type::where('code', $model->getTable())->first()->translate('title') }}</h4>
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
				@if (\Input::get('chooseBtn') && $model->exists)
				
				<script type="text/javascript">
					<?php
					
						$config = app('telenok.config')->getObjectFieldController();

						$put = \Illuminate\Support\Collection::make(); 

						if (\Input::get('chooseSequence') && $model->exists())
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
				@if (\Input::get('saveBtn') && ( (!$model->exists && \Auth::can('create', 'object_type.' . $type->code)) || ($model->exists && \Auth::can('update', $model->getKey())) ))
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
