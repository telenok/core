<!doctype html>
<html>
	<head>
		<meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
		<title>{{$page->translate('title_ceo')}}</title>
        <meta name="description" content="{{$page->translate('description_ceo')}}"></meta>
        <meta name="keywords" content="{{$page->translate('keywords_ceo')}}"></meta>
	</head>
	<body> 
        
        @if (isset($content['center']))
            @foreach($content['center'] as $widget)
                {!! $widget !!}
            @endforeach
        @endif
	</body>
</html>