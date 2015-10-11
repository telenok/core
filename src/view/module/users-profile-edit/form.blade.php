
    <div class="row">
		<div class="col-xs-12"> 
			<div class="tabbable">
				<ul class="nav nav-tabs" id='form-nav-{{$uniqueId}}'>

                    <?php
                    
                    $fields = $fields->reject(function($item) 
                    { 
                        return in_array($item->code, [
                            'id', 
                            'permission', 
                            'locked_by_user', 
                            'deleted_by', 
                            'created_by_user',
                            'updated_by_user',
                            'created_by',
                            'updated_by',
                            'locked_by',
                            'group',
                            'active',
                            'active_at',
                            'configuration',
                        ], true);
                    });
                    
                    ?>
                    
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
						
                        <?php
                        
                        $fieldInForm = $tab->field()->active()->get()->filter(function($item) use ($fields) { return $fields->contains($item->getKey()); })->sortBy('field_order');
                        
                        ?> 
                        
                        <div class="row">
                            <div class="col-xs-12 col-sm-4">
                                <?php
                                    $field = $fieldInForm->filter(function($item) { return $item->code == 'avatar'; })->first(); 
                                ?>
                                @if ($field)
                                @include($controller->getPresentationFormFieldListView()) 
                                @endif
                            </div>

                            <div class="vspace-12-sm"></div>

                            <div class="col-xs-12 col-sm-8">
                                <div class="form-group">
                                    @foreach($fieldInForm->filter(function($item) { return in_array($item->code, ['firstname', 'lastname', 'middlename'], true); }) as $field) 
                                        @include($controller->getPresentationFormFieldListView()) 
                                    @endforeach
                                </div>
                            </div>
                        </div>
						@foreach($fieldInForm->filter(function($item) { return !in_array($item->code, ['avatar', 'firstname', 'lastname', 'middlename'], true); }) as $field) 
                            @include($controller->getPresentationFormFieldListView()) 
                        @endforeach
							

					</div>

					@endforeach

				</div>
			</div>
		</div>	
	</div>
