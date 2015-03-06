<!doctype html>
<html> 
    <head>
    @section('head')
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0" /> 
        <base href="/" />
		<!--[if lt IE 9]> 
		<script src="packages/telenok/core/js/html5shiv/html5shiv.js">
		<![endif]-->
        {!! HTML::style('packages/telenok/core/css/jquery-ui.css') !!}
        {!! HTML::style('packages/telenok/core/css/jquery.gritter.css') !!}
        {!! HTML::style('packages/telenok/core/js/bootstrap/css/bootstrap.min.css') !!}
        {!! HTML::style('packages/telenok/core/js/bootstrap/css/font-awesome.css') !!}
        {!! HTML::style('packages/telenok/core/js/bootstrap/css/ace-fonts.css') !!}
        {!! HTML::style('packages/telenok/core/js/bootstrap/css/ace.css') !!}
        {!! HTML::style('packages/telenok/core/js/bootstrap/css/ace-skins.min.css') !!}
        {!! HTML::style('packages/telenok/core/js/bootstrap/lib/datetimepicker/datetimepicker.css') !!}
        {!! HTML::style('packages/telenok/core/js/jquery.datatables/jquery.datatables.tabletool.css') !!}

        {!! HTML::style('packages/telenok/core/js/dropzone/dropzone.css') !!}
		
        {!! HTML::style('packages/telenok/core/css/style.css') !!} 

        {!! HTML::script('packages/telenok/core/js/jquery.js') !!}
        {!! HTML::script('packages/telenok/core/js/jquery-ui.js') !!}
        {!! HTML::script('packages/telenok/core/js/jquery.gritter.js') !!}
        {!! HTML::script('packages/telenok/core/js/jquery.punch.js') !!}
        {!! HTML::script('packages/telenok/core/js/jquery.datatables/jquery.datatables.js') !!}
        {!! HTML::script('packages/telenok/core/js/jquery.datatables/jquery.datatables.tabletool.js') !!}
        {!! HTML::script('packages/telenok/core/js/jquery.datatables/jquery.datatables.bootstrap.js') !!}
        {!! HTML::script('packages/telenok/core/js/jquery.jstree/jstree.js') !!}
        
        {!! HTML::style('packages/telenok/core/js/jquery.chosen/chosen.css') !!}
        {!! HTML::script('packages/telenok/core/js/jquery.chosen/chosen.js') !!}

        <script type="text/javascript">
            if("ontouchend" in document) document.write("<script src='packages/telenok/core/js/jquery.mobile.custom.min.js' type='text/javascript'>"+"<"+"/script>");
        </script>

        {!! HTML::script('packages/telenok/core/js/fuelux/fuelux.wizard.min.js') !!}
        {!! HTML::script('packages/telenok/core/js/bootstrap/js/bootstrap.min.js') !!}
        {!! HTML::script('packages/telenok/core/js/bootstrap/js/ace-extra.js') !!}
        {!! HTML::script('packages/telenok/core/js/bootstrap/js/ace-elements.js') !!}
        {!! HTML::script('packages/telenok/core/js/bootstrap/js/ace.js') !!}
        {!! HTML::script('packages/telenok/core/js/bootstrap/lib/moment.js') !!}
        {!! HTML::script('packages/telenok/core/js/bootstrap/lib/datetimepicker/datetimepicker.js') !!}
 
        {!! HTML::script('packages/telenok/core/js/dropzone/dropzone.js') !!}
        {!! HTML::script('packages/telenok/core/js/script.js') !!}
    @show
    </head>

    
    @yield('body')
</html>