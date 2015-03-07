@extends('core::layout.backend')

@section('head')
<title>{{ $controller->LL('title-page') }}</title>
@parent
@stop


@section('body')
<body>


    <div class="main-container">

        <div class="main-content">
       
            <div class="page-content">
                <div class="row">
                    <div class="col-md-12">
                        <!--PAGE CONTENT BEGINS-->

                        <div class="error-container">
                            <div class="well">
                                <h1 class="grey lighter smaller">
                                    <span class="blue bigger-125">
                                        <i class="fa fa-sitemap"></i>
                                        403 
                                    </span>
                                    {{$controller->LL('forbidden')}}
                                </h1>

                                <hr>
                                <h3 class="lighter smaller">{{$controller->LL('forbidden-description')}}</h3>

                                <div>
                                    <form class="form-inline">
                                        <span class="input-icon">
                                            <i class="fa fa-search"></i>

                                            <input type="text" placeholder="Give it a search..." class="input-sm search-query tree-search-query">
                                        </span>
                                        <button onclick="return false;" class="btn btn-sm">Go!</button>
                                    </form>

                                    <div class="space"></div>
                                    <h4 class="smaller">Try one of the following:</h4>

                                    <ul class="unstyled spaced inline bigger-110">
                                        <li>
                                            <i class="fa fa-hand-o-right blue"></i>
                                            Re-check the url for typos
                                        </li>

                                        <li>
                                            <i class="fa fa-hand-o-right blue"></i>
                                            Read the faq
                                        </li>

                                        <li>
                                            <i class="fa fa-hand-o-right blue"></i>
                                            Tell us about it
                                        </li>
                                    </ul>
                                </div>

                                <hr>
                                <div class="space"></div>

                                <div class="row">
                                    <div class="center">
                                        <a class="btn btn-grey" href="#">
                                            <i class="fa fa-arrow-left"></i>
                                            Go Back
                                        </a>

                                        <a class="btn btn-primary" href="#">
                                            <i class="fa fa-tachometer"></i>
                                            Dashboard
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div><!--PAGE CONTENT ENDS-->
                    </div><!--/.span-->
                </div><!--/.row-->
            </div><!--/.page-content-->
        </div><!--/.main-content-->
    </div>




</body>
@stop
