			
			<?php
			
			$n = 0;
			
			?>

			@foreach($fields as $key => $field)

                @if ($controller->enableColumnAction())
                    aoColumns.push({ "mData": "tableManageItem", "sTitle": "", "bSortable": false }); 
                @endif
            
				@if ($n++ == 0)
					@if ($controller->enableColumnSelect())
						aoColumns.push({ 
							"mData": "tableCheckAll", 
							"sTitle": '<label><input type="checkbox" name="checkHeader" class="ace ace-switch ace-switch-6" ' 
									+ 'onclick="var tb=jQuery(\'#table-{{$controller->getUniqueId()}}\').DataTable();'
									+ 'var chbx = jQuery(\'input[name=tableCheckAll\\\\[\\\\]]\', tb.fnGetNodes());'
									+ 'chbx.prop(\'checked\', jQuery(\'input[name=checkHeader]\', tb).prop(\'checked\'));"><span class="lbl"></span></label>', 
							"mDataProp": null, 
							"sClass": "center", 
							"sWidth": "20px", 
							"sDefaultContent": '<label><input type="checkbox" class="ace ace-switch ace-switch-6" name="tableCheckAll[]"><span class="lbl"></span></label>',
							"bSortable": false
						});
					@endif 
				@endif
					
				aoColumns.push({ 
					"mData": "{{ $field->code }}", 
					"sTitle": "{{ $field->translate('title_list') }}", 
					"bSortable": @if ($field->allow_sort) true @else false @endif 
				});
				
			@endforeach