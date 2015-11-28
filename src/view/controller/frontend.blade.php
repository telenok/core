
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
        <meta name="description" content="{{$page->translate('description_ceo')}}" />
        <meta name="keywords" content="{{$page->translate('keywords_ceo')}}" />
		<meta name="csrf-token" content="{{ csrf_token() }}" />		
		
		@foreach($controllerRequest->getCssFile() as $file)

		<link href="{!! $file['file'] !!}" rel="stylesheet" />

		@endforeach
		
		@foreach($controllerRequest->getCssCode() as $code)

			{!! $code !!}

		@endforeach		

	</head>
	<body>
		
		{!! $htmlCode !!}
		
		<?php

			//$controllerRequest->addCssFile('http://fonts.googleapis.com/css?family=Open+Sans', 'fonts.googleapis');
			
			//$controllerRequest->addJsFile('js/custom.js', 'custom', 10000000);
			
            $controllerRequest->addJsFile('packages/telenok/core/js/jquery.js', 'jquery', 0);

		?>
		
		@foreach($controllerRequest->getJsFile() as $file)

		<script src="{!! $file['file'] !!}"></script>

		@endforeach

		@foreach($controllerRequest->getJsCode() as $code)

			{!! $code !!} 

		@endforeach
		
		<script type="text/javascript">
            jQuery.ajaxSetup({
                beforeSend: function (xhr)
                {
                   xhr.setRequestHeader("X-CSRF-TOKEN", jQuery('meta[name="csrf-token"]').attr('content'));
                }
            });
		</script>
		
	</body>
</html>