<?php
ob_start();
?>

@yield('body')

<?php
$htmlCode = ob_get_contents();

ob_end_clean();
?>
<!doctype html>
<html> 
    <head>
        @section('head')
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta name="csrf-token" content="{{csrf_token()}}" /> 

        <base href="/" />

        <!--[if lt IE 9]> 
        <script src="packages/telenok/core/js/html5shiv/html5shiv.js">
        <![endif]-->
        {!! Html::style('packages/telenok/core/css/jquery-ui.css') !!}
        {!! Html::style('packages/telenok/core/css/jquery.gritter.css') !!}
        {!! Html::style('packages/telenok/core/js/bootstrap/css/bootstrap.min.css') !!}
        {!! Html::style('packages/telenok/core/js/bootstrap/css/font-awesome.css') !!}

        {!! Html::style('packages/telenok/core/js/dropzone/dropzone.css') !!}

        {!! Html::style('packages/telenok/core/js/bootstrap/css/ace-fonts.css') !!}
        {!! Html::style('packages/telenok/core/js/bootstrap/css/ace.css') !!}
        {!! Html::style('packages/telenok/core/js/bootstrap/css/ace-skins.min.css') !!}
        {!! Html::style('packages/telenok/core/js/bootstrap/lib/datetimepicker/datetimepicker.css') !!}
        {!! Html::style('packages/telenok/core/js/codemirror/codemirror.css') !!}


        {!! Html::style('packages/telenok/core/css/style.css') !!}

        {!! Html::script('packages/telenok/core/js/jquery.js') !!}
        {!! Html::script('packages/telenok/core/js/jquery-ui.js') !!}
        {!! Html::script('packages/telenok/core/js/jquery.gritter.js') !!}
        {!! Html::script('packages/telenok/core/js/jquery.punch.js') !!}
        {!! Html::script('packages/telenok/core/js/jquery.datatables/jquery.datatables.js') !!}
        {!! Html::script('packages/telenok/core/js/jquery.jstree/jstree.js') !!}

        {!! Html::style('packages/telenok/core/js/jquery.chosen/chosen.css') !!}
        {!! Html::script('packages/telenok/core/js/jquery.chosen/chosen.js') !!}

        {!! Html::style('packages/telenok/core/js/highlight/styles/vs.css') !!}
        {!! Html::script('packages/telenok/core/js/highlight/highlight.js') !!}

        <script type="text/javascript">
            if ("ontouchend" in document)
                document.write("<script src='packages/telenok/core/js/jquery.mobile.custom.min.js' type='text/javascript'>" + "<" + "/script>");
        </script>

        {!! Html::script('packages/telenok/core/js/fuelux/fuelux.wizard.min.js') !!}
        {!! Html::script('packages/telenok/core/js/bootstrap/js/bootstrap.min.js') !!}
        {!! Html::script('packages/telenok/core/js/bootstrap/js/ace-extra.js') !!}
        {!! Html::script('packages/telenok/core/js/bootstrap/js/ace-elements.js') !!}
        {!! Html::script('packages/telenok/core/js/bootstrap/js/ace.js') !!}
        {!! Html::script('packages/telenok/core/js/bootstrap/lib/moment.js') !!}
        {!! Html::script('packages/telenok/core/js/bootstrap/lib/datetimepicker/datetimepicker.js') !!}

        {!! Html::script('packages/telenok/core/js/dropzone/dropzone.js') !!}
        {!! Html::script('packages/telenok/core/js/codemirror/codemirror.js') !!}
        {!! Html::script('packages/telenok/core/js/script.js') !!}
        @show

        @foreach($controller->getCssFile() as $file)

        <link href="{!! $file['file'] !!}" rel="stylesheet" />

        @endforeach

        @foreach($controller->getCssCode() as $code)

        <style>

            {!! $code !!}

        </style>

        @endforeach

        @foreach($controller->getJsFile() as $file)

        <script src="{!! $file['file'] !!}"></script>

        @endforeach

        @foreach($controller->getJsCode() as $code)

        {!! $code !!}

        @endforeach

    </head>

    {!! $htmlCode !!} 

</html>