@extends('core::presentation.tree-tab.presentation')

	@section('tableListBtn')
	"aButtons": 
				[{
					"sExtends": "text",
					"sButtonText": "<i class='fa fa-refresh smaller-90'></i> {{ $controller->LL('list.btn.refresh') }}",
					'sButtonClass': 'btn-sm',
					"fnClick": function(nButton, oConfig, oFlash) {
						jQuery('#' + param.domId).dataTable().fnReloadAjax();
					}
				}]
	@stop