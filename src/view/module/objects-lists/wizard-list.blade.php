<div class="modal-dialog">
	<div class="modal-content">

		<div class="modal-header table-header">
			<button data-dismiss="modal" class="close" type="button">×</button>
			<h4>{{ \App\Telenok\Core\Model\Object\Type::where('code', $model->getTable())->first()->translate('title_list') }}</h4>
		</div>
		<div class="modal-body" style="padding: 15px; position: relative;">
			<div class="widget-main">
				<table class="table table-striped table-bordered table-hover" id="table-{{$gridId}}" role="grid"></table>
			</div>
		</div>

		<script type="text/javascript">
            (function()
            {
                var columns = []; 
                @foreach($fields as $key => $field)
                @if ($key==0)
                columns.push({ "mData": "choose", "sTitle": "{{ $controller->LL('btn.choose') }}", "bSortable": false });
                @endif
                columns.push({ "mData": "{{ $field->code }}", "sTitle": "{{ $field->translate('title_list') }}"});
                @endforeach

                jQuery('#table-{{$gridId}}').dataTable({
                    order: [],
                    autoWidth : true,
                    processing : true,
                    serverSide : true,
                    ajax : '{!! URL::route("telenok.module.{$controller->getKey()}.wizard.list", ["id" => empty($typeList) ? $type->getKey() : $typeList]) !!}',
                    deferRender : true,
                    JQueryUI : false,
                    dom : "<'row'<'col-md-6'T><'col-md-6'f>r>t<'row'<'col-md-6'i><'col-md-6'p>>",
                    columns : columns,
                    buttons : [],
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
                        infoFiltered : ""
                    }
                });
            })();
		</script>
	</div>
</div>