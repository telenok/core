<!-- Single button -->
<div class="btn-group">
	<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
		 <span class="fa fa-bars"></span>
	</button>
	<ul class="dropdown-menu">

		@if (app('auth')->can('read', $item))
		<li><a href="{!! route($controller->getRouterEdit(), ['id' => $item->getKey()]) !!}">{{ $controller->LL('list.btn.edit') }}</a></li>
		@endif

		@if (app('auth')->can('delete', $item))
		<li><a href="#" onclick='
			
				if (confirm("{{$controller->LL('notice.sure')}}"))
				{
					jQuery(this).closest("table").data("deleteRow")(this, {{ $item->getKey() }});
				}

				return false;

			   '>{{ $controller->LL('list.btn.delete') }}</a></li>
		@endif
	</ul>
</div>