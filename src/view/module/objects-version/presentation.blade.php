@extends('core::presentation.tree-tab.presentation')

@section('tableListBtn')
    buttons : [{
        text : "<i class='fa fa-refresh smaller-90'></i> {{ $controller->LL('list.btn.refresh') }}",
        action : function (e, dt, button, config)
        {
            dt.ajax.reload();
        }
    }],
@stop