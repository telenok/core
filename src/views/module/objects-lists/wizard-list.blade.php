<div class="modal-dialog">
	<div class="modal-content">

		<div class="modal-header table-header">
			<button data-dismiss="modal" class="close" type="button">×</button>
			<h4>{{ \App\Model\Telenok\Object\Type::where('code', $model->getTable())->first()->translate('title_list') }}</h4>
		</div>
		<div class="modal-body" style="padding: 15px; position: relative;">
			<div class="widget-main">
				<table class="table table-striped table-bordered table-hover" id="table-{{$gridId}}" role="grid"></table>
			</div>
		</div>

		<script type="text/javascript">

			var aoColumns = []; 
			@foreach($fields as $key => $field)
				@if ($key==0)
					aoColumns.push({ "mData": "choose", "sTitle": "{{ $controller->LL('btn.choose') }}", "bSortable": false });
				@endif
				aoColumns.push({ "mData": "{{ $field->code }}", "sTitle": "{{ $field->translate('title_list') }}"});
			@endforeach

			jQuery('#table-{{$gridId}}').dataTable({
				"multipleSelection": true,
				"aaSorting": [],
				"bAutoWidth": true,
				"bProcessing": true,
				"bServerSide": true,
				"sAjaxSource" : '{!! URL::route("cmf.module.{$controller->getKey()}.wizard.list", ["id" => empty($typeList) ? $type->getKey() : $typeList]) !!}',
				"bDeferRender": '',
				"bJQueryUI": false,
				"sDom": "<'row'<'col-md-6'T><'col-md-6'f>r>t<'row'<'col-md-6'i><'col-md-6'p>>",
				"aoColumns" : aoColumns,
				"oTableTools": {"aButtons": []},
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
			});
		</script>
	</div>
</div>