			@foreach($fields as $key => $field)

				@if ($controller->enableColumnSelect())
				aoColumns.push({ 
					"mData": "{{ $field->code }}", 
					"sTitle": "{{ $field->translate('title_list') }}", 
					"bSortable": @if ($field->allow_sort) true @else false @endif 
				});
				@endif
				
				@if ($controller->enableColumnAction() && ($key==1 && $fields->count() > 1) || ($key==0 && $fields->count() < 2) )
					aoColumns.push({ "mData": "tableManageItem", "sTitle": "{{ $controller->LL('action') }}", "bSortable": false }); 
				@endif
				
			@endforeach