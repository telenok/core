	<div class="row">
		<div class="col-xs-12"> 
			<div class="tabbable">
				<ul class="nav nav-tabs" id='form-nav-{{$uniqueId}}'>

					@foreach($type->tab()->active()->get() as $tab) 

					@if ($tab->field()->active()->get()->filter(function($item) use ($fields) { return $fields->contains($item->getKey()); })->count())
					<li>
						<a data-toggle="tab" href="#{{$uniqueId}}_{{$tab->code}}">
							@if ($tab->icon_class)
							<i class="{{$tab->icon_class}}"></i>
							@endif
							{{$tab->translate('title')}}
						</a>
					</li>
					@endif
					
					@endforeach
				</ul>

				<script type="text/javascript">
					@section('scriptForm')
				
					jQuery("ul#form-nav-{{$uniqueId}} li:first a").click();
				
					@show
				</script>

				<div class="tab-content">

					@foreach($type->tab()->active()->get()->sortBy('tab_order') as $tab) 

					<div id="{{$uniqueId}}_{{$tab->code}}" class="tab-pane in">
						
						@foreach($tab->field()->active()->get()->filter(function($item) use ($fields) { return $fields->contains($item->getKey()); })->sortBy('field_order') as $field) 

							@include($controller->getPresentationFormFieldListView())
							
						@endforeach 

					</div>

					@endforeach

				</div>
			</div>
		</div>	
	</div>
