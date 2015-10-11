<div class="container-grid-{{$controller->getUniqueId()}}">

	<table class="table table-striped table-bordered table-hover table-responsive" 
		   id="table-{{$controller->getUniqueId()}}"></table>
	
<?php

ob_start();

?>

	<script type="text/javascript">

		jQuery(function()
		{
			var param = {};
			var aoColumns = [];
			var aButtons = []; 
			
			<?php
			
			$fields = $controller->getFields();
			
			?>

			@include($controller->getViewRow())

			@include($controller->getViewButtonTop())

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
				"iDisplayLength": {{ $controller->getDisplayLength() }},
				"sDom": "<'row'<'col-md-8'T><'col-md-4'f>r>t<'row'<'col-md-5'T><'col-md-6'p>>",
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


			@if ($deleteRouter = $controller->getRouterDelete())
			jQuery('#table-{{$controller->getUniqueId()}}').data('deleteRow', function(obj, id)
			{
				jQuery.ajax({
					url: "{!! route($deleteRouter, ['id' => '--id--']) !!}".replace("--id--", id),
					type: "post",

				}).done(function(data)
				{
					if (data.success == 1)
					{
						jQuery(obj).closest("tr").remove();
					}
				});
			});
			@endif

		}); 
		
	</script>

<?php

$jsCode = ob_get_contents();

ob_end_clean();

$controllerAction->addJsCode($jsCode); 

$controllerAction->addCssFile(asset('packages/telenok/core/css/jquery-ui.css'), 'jquery-ui', 0); 
$controllerAction->addCssFile(asset('packages/telenok/core/js/bootstrap/css/bootstrap.min.css'), 'bootstrap', 10);
$controllerAction->addCssFile(asset('packages/telenok/core/js/jquery.datatables/jquery.datatables.css'), 'datatables', 20);
$controllerAction->addCssFile(asset('packages/telenok/core/js/jquery.datatables/jquery.datatables.tabletool.css'), 'datatables.tabletool', 21);
$controllerAction->addCssFile(asset('packages/telenok/core/js/jquery.datatables/jquery.datatables.bootstrap.css'), 'datatables.bootstrap', 22); 

$controllerAction->addJsFile(asset('packages/telenok/core/js/jquery.js'), 'jquery', 0); 
$controllerAction->addJsFile(asset('packages/telenok/core/js/jquery-ui.js'), 'jquery-ui', 1); 
$controllerAction->addJsFile(asset('packages/telenok/core/js/bootstrap/js/bootstrap.min.js'), 'bootstrap', 10); 
$controllerAction->addJsFile(asset('packages/telenok/core/js/jquery.datatables/jquery.datatables.js'), 'datatables', 11); 
$controllerAction->addJsFile(asset('packages/telenok/core/js/jquery.datatables/jquery.datatables.bootstrap.js'), 'datatables.bootstrap', 15);
$controllerAction->addJsFile(asset('packages/telenok/core/js/jquery.datatables/jquery.datatables.tabletool.js'), 'datatables.tabletool', 19); 

?>

</div>
