<div class="modal-dialog">
	<div class="modal-content">

		<div class="modal-header table-header">
			<button data-dismiss="modal" class="close" type="button">×</button>
			<h4>{{ $controller->LL('wizard.file.header') }}</h4>
		</div>
		<div class="modal-body" style="padding: 15px; position: relative;">
			<script type="text/javascript">
				jQuery("#tree-{{$uniqueId}}").jstree({
					"themes": {
						"theme": "proton",
						"url": "packages/telenok/core/js/jquery.jstree/themes/proton/style.css"
					},
					"contextmenu" : {
						'items' : {
							'create' : {
								"label" : "{{ $controller->LL('btn.create') }}",
								"action" : function (obj) { this.create(obj); }
							},
							"rename" : {
								"label" : "{{ $controller->LL('btn.rename') }}",
								"action" : function (obj) { this.rename(obj); }
						   },
							'remove' : {
								"label" : "{{ $controller->LL('btn.delete') }}",
								"action" : function (obj) { this.create(obj); }
							}, 
							'ccp' : false  
						}
					},
					"crrm": {
						"move": {
							"default_position": "first",
							"check_move": function(m) {
								return (m.o[0].id === "thtml_1") ? false : true;
							}
						}
					},
					"json_data": {
						"ajax": {
							"url" : function( node ){
								if( node == -1 ){
									return "{!! URL::route("cmf.module.file-browser.wizard.tree") !!}";
								} else {
									return "{!! URL::route("cmf.module.file-browser.wizard.tree") !!}?id=" + encodeURIComponent(node.data( "path" ));
								}
							},
							"data": function(n) {
								return {id: n.attr("id")};
							}
						}
					},
					"plugins": ["themes", "json_data", "ui", "crrm", "contextmenu"]
				})
				.bind("create.jstree", function(e, data) {
					if (data.rslt.parent == -1) 
					{
						alert("{{ $controller->LL('notice.error') }}!");
						jQuery.jstree.rollback(data.rlbk);
						return;
					} 

					jQuery.getJSON(
						"{!! URL::route("cmf.module.file-browser.wizard.process") !!}?op=create&path=" + encodeURIComponent(data.rslt.parent.data( "path" )) + "&new=" + encodeURIComponent(data.rslt.name),
						function(mes) {
							data.rslt.obj.data( "path", mes.path).attr('id', mes.id)
						});
				})
				.bind("rename.jstree", function(e, data) {
					if (data.rslt.parent == -1) 
					{
						alert("{{ $controller->LL('notice.error') }}!");
						jQuery.jstree.rollback(data.rlbk);
						return;
					} 

					jQuery.getJSON(
						"{!! URL::route("cmf.module.file-browser.wizard.process") !!}?op=rename&path=" + encodeURIComponent(data.rslt.obj.data( "path" )) + "&new=" + encodeURIComponent(data.rslt.new_name),
						function(data) {
						});
				});
			</script>

			<div class="row telenok-tree">
				<div id="tree-{{$uniqueId}}"></div>
			</div>

		</div>
		<div class="modal-footer">
			<a class="btn btn-info" onclick="
					var $modal = jQuery(this).closest('.modal');
					jQuery('#tree-{{$uniqueId}}').jstree('get_selected').each(function(){    
						$modal.data('path')( jQuery(this).data('path') ); 
						$modal.modal('hide');
					});">{{ $controller->LL('btn.choose') }}</a>
			<a class="btn" data-dismiss="modal">{{ $controller->LL('btn.close') }}</a>
		</div>
	</div>
</div>