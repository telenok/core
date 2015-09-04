<div class="col-xs-12">
	@if ($controller->getConfig('showTabs', true))
	<div class="tabbable">
		<ul class="nav nav-tabs" id='form-nav-{{$controller->getUniqueId()}}'>

		@foreach($controller->getEventResource()->get('type')->tab()->active()->get() as $tab) 

			@if ($tab->field()->active()->get()->filter(function($item) use ($controller) { return $controller->getEventResource()->get('fields')->contains($item->getKey()); })->count())
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

			@foreach($controller->getEventResource()->get('type')->tab()->active()->get()->sortBy('tab_order') as $tab) 

			<div id="{{$controller->getUniqueId()}}_{{$tab->code}}" class="tab-pane">

				@foreach($tab->field()->active()->get()->filter(function($item) use ($controller) { return $controller->getEventResource()->get('fields')->contains($item->getKey()); })->sortBy('field_order') as $field) 

					@include($controller->getFieldView())

				@endforeach 

			</div>

			@endforeach

		</div>

			<?php

			ob_start();

			?>
		
			@section('scriptForm')

			<script type="text/javascript">
				
				jQuery("ul#form-nav-{{$controller->getUniqueId()}} a:first").tab('show');

			</script>
			
			@show
			
			<?php

			$jsCode = ob_get_contents();

			ob_end_clean();

			$controllerAction->addJsCode($jsCode); 

			?>
	</div>
	@else
	
		@foreach($controller->getFields()->sortBy('field_order') as $field) 

			@include($controller->getFieldView())

		@endforeach 
		
		<?php

		ob_start();

		?>

		@section('scriptForm')
		@show

		<?php

		$jsCode = ob_get_contents();

		ob_end_clean();

		$controllerAction->addJsCode($jsCode); 

		?>
		
	@endif
</div>	
