<div class="container-grid-{{$controller->getUniqueId()}}">

    <table class="table table-striped table-bordered table-hover table-responsive" id="table-{{$controller->getUniqueId()}}" role="grid">
		
	</table>

		
		
	
<?php

ob_start();

?>

	<script type="text/javascript">

		jQuery(function()
		{
			var param = {};
			var aoColumns = [];
			var aButtons = [];
			
			aoColumns.push({ 
				"mData": "tableCheckAll", 
				"sTitle": '<label><input type="checkbox" name="checkHeader" class="ace ace-switch ace-switch-6" ' 
						+ 'onclick="var tb=jQuery(\'#table-{{$controller->getUniqueId()}}\').dataTable();'
						+ 'var chbx = jQuery(\'input[name=tableCheckAll\\\\[\\\\]]\', tb.fnGetNodes());'
						+ 'chbx.prop(\'checked\', jQuery(\'input[name=checkHeader]\', tb).prop(\'checked\'));"><span class="lbl"></span></label>', 
				"mDataProp": null, 
				"sClass": "center", 
				"sWidth": "20px", 
				"sDefaultContent": '<label><input type="checkbox" class="ace ace-switch ace-switch-6" name="tableCheckAll[]"><span class="lbl"></span></label>',
				"bSortable": false
			});
			
			<?php
			
			$fields = $controller->getFields();
			
			?>

			@include('core::widget.grid.row')
			@include('core::widget.grid.buttonTop')

			param = {
				"searchDelay": 1000,
				"multipleSelection": true,
				"aoColumns": aoColumns,
				"aaSorting": [],
				"autoWidth": false,
				"bProcessing": true,
				"bServerSide": true,
				"bDeferRender": '',
				"bJQueryUI": false,
				"sAjaxSource": "{!! $controller->getUrlList()!!}",
				"iDisplayLength": 100,
				"sDom": "<'row'<'col-md-9'T><'col-md-3'f>r>t<'row'<'col-md-9'T><'col-md-3'p>>",
				"oTableTools": {
					"aButtons": aButtons
				},
                "oSearch": {"sSearch": ""},
				"oLanguage": {
					"oPaginate": {
						"sNext": "{{ trans('core::default.btn.next') }}",
						"sPrevious": "{{ trans('core::default.btn.prev') }}", 
					},
					"sEmptyTable": "{{ trans('core::default.table.empty') }}",
					"sSearch": "{{ trans('core::default.btn.search') }} ",
					"sSearchPlaceholder": "{{ trans('core::default.table.placeholder.search') }} ",
					"sInfo": "{{ trans('core::default.table.showed') }}",
					"sInfoEmpty": "{{ trans('core::default.table.empty.showed') }}",
					"sZeroRecords": "{{ trans('core::default.table.empty.filtered') }}",
					"sInfoFiltered": ""
				}
			};

			jQuery('#table-{{$controller->getUniqueId()}}').dataTable(param);

		});
	</script>

<?php

$jsCode = ob_get_contents();

ob_end_clean();

$controllerAction->addJsCode($jsCode); 

$controllerAction->addCssFile(asset('packages/telenok/core/css/jquery-ui.css'), 'jquery-ui'); 
$controllerAction->addCssFile(asset('packages/telenok/core/js/bootstrap/css/bootstrap.min.css'), 'bootstrap');
$controllerAction->addCssFile(asset('packages/telenok/core/js/jquery.datatables/jquery.datatables.tabletool.css'), 'datatables.tabletool');

$controllerAction->addJsFile(asset('packages/telenok/core/js/jquery.js'), 'jquery'); 
$controllerAction->addJsFile(asset('packages/telenok/core/js/jquery-ui.js'), 'jquery-ui'); 
$controllerAction->addJsFile(asset('packages/telenok/core/js/bootstrap/js/bootstrap.min.js'), 'bootstrap'); 
$controllerAction->addJsFile(asset('packages/telenok/core/js/jquery.datatables/jquery.datatables.js'), 'datatables'); 
$controllerAction->addJsFile(asset('packages/telenok/core/js/jquery.datatables/jquery.datatables.bootstrap.js'), 'datatables.bootstrap'); 
$controllerAction->addJsFile(asset('packages/telenok/core/js/jquery.datatables/jquery.datatables.tabletool.js'), 'datatables.tabletool'); 

?>

</div>
