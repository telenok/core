
		<?php

		$buttonTop = \Illuminate\Support\Collection::make($controller->getButtonTop());
		
		?>

		@foreach($controller->getButtonTopOrder() as $buttonKey)
		
			@if ($b = $buttonTop->get($buttonKey))

			aButtons.push({ 

				{!! $b($controller) !!}

			});

			@endif

		@endforeach