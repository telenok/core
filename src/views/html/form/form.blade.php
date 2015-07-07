<div class="col-xs-12"> 
	<div class="tabbable">
		<ul class="nav nav-tabs" id='form-nav-{{$controller->getUniqueId()}}'>

		@foreach($controller->getModelType()->tab()->active()->get() as $tab) 

			@if ($tab->field()->active()->get()->filter(function($item) use ($controller) { return $controller->getFields()->contains($item->getKey()); })->count())
			<li>
				<a data-toggle="tab" href="#{{$controller->getUniqueId()}}_{{$tab->code}}">
					@if ($tab->icon_class)
					<i class="{{$tab->icon_class}}"></i>
					@endif
					{{$tab->translate('title')}}
				</a>
			</li>
			@endif

		@endforeach

		</ul> 

		<div class="tab-content">

			@foreach($controller->getModelType()->tab()->active()->get()->sortBy('tab_order') as $tab) 

			<div id="{{$controller->getUniqueId()}}_{{$tab->code}}" class="tab-pane">

				@foreach($tab->field()->active()->get()->filter(function($item) use ($controller) { return $controller->getFields()->contains($item->getKey()); })->sortBy('field_order') as $field) 

					@include($controller->getFieldView())

				@endforeach 

			</div>

			@endforeach

		</div>

		<script type="text/javascript">
			@section('scriptForm')

			jQuery("ul#form-nav-{{$controller->getUniqueId()}} a:first").tab('show');

			@show
		</script>
	</div>
</div>	
