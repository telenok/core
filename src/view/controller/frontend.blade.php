
<?php

ob_start();

?>
        @if (isset($content['center']))
            @foreach($content['center'] as $widget)
                {!! $widget !!}
            @endforeach
        @endif
		
<?php

$htmlCode = ob_get_contents();

ob_end_clean();
	
?>	
		
<!DOCTYPE html>
<html>
	<head>
		<meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
		<title>{{$page->translate('title_ceo')}}</title>
        <meta name="description" content="{{$page->translate('description_ceo')}}"></meta>
        <meta name="keywords" content="{{$page->translate('keywords_ceo')}}"></meta>
		<meta name="csrf-token" content="{{ csrf_token() }}" />		
		
		@foreach($controllerAction->getCssFile() as $file)

		<link href="{!! $file['file'] !!}" rel="stylesheet" />

		@endforeach
		
		@foreach($controllerAction->getCssCode() as $code)

		<style>

			{!! $code !!}

		</style>

		@endforeach		

	</head>
	<body>
		
		{!! $htmlCode !!}
		
		<?php

			//$controllerAction->addCssFile('http://fonts.googleapis.com/css?family=Open+Sans', 'fonts.googleapis');
			
			//$controllerAction->addJsFile('js/custom.js', 'custom', 10000000);

		?>
		
		@foreach($controllerAction->getJsFile() as $file)

		<script src="{!! $file['file'] !!}"></script>

		@endforeach

		@foreach($controllerAction->getJsCode() as $code)

			{!! $code !!} 

		@endforeach
		
		<script type="text/javascript">
			jQuery(function () {
				jQuery.ajaxSetup({
					headers: { 'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content') }
				});
			});
		</script>
		
	</body>
</html 