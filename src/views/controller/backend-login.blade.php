@extends('core::layout.backend')

@section('head')
    <title>{{ $controller->LL('title-page') }}</title>
    @parent
@stop
        

@section('body')
    <body class="login-layout">
        
        
        <div class="main-container">
            <div class="main-content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="login-container">
                            <div class="row">
                                <div class="center">
                                    <h1>
                                        <i class="fa fa-leaf green"></i>
                                        <span class="green">{{\Config::get('app.backend.brand')}}</span>
                                    </h1>
                                    <h4 class="blue">&copy; Telenok CMS</h4>
                                </div>
                            </div>

                            <div class="space-6"></div>

                            <div class="row">
                                <div class="position-relative">
                                    <div id="login-box" class="login-box visible widget-box no-border">
                                        <div class="widget-body">
                                            <div class="widget-main">
                                                <h4 class="header blue lighter bigger">
                                                    <i class="fa fa-coffee green"></i>
                                                    {{ $controller->LL('please-fill') }}
                                                </h4>

                                                <div class="space-6"></div>

                                                {!! Form::open(['route' => 'cmf.login', 'method' => 'post']) !!}
                                                    <fieldset>
                                                        <label class="block clearfix">
                                                            <span class="block input-icon input-fa fa-right">
                                                                <input type="text" name="username" class="form-control" placeholder="{{ $controller->LL('username') }}" />
                                                                <i class="ace-icon fa fa-user"></i>
                                                            </span>
                                                        </label>

                                                        <label class="block clearfix">
                                                            <span class="block input-icon input-fa fa-right">
                                                                <input type="password" name="password" autocomplete="off" class="form-control" placeholder="{{ $controller->LL('password') }}" />
                                                                <i class="ace-icon fa fa-lock"></i>
                                                            </span>
                                                        </label>

                                                        <div class="space"></div>

                                                        <div class="clearfix">
                                                            <label class="inline">
                                                                <input type="checkbox" name='remember' value="1" />
                                                                <span class="lbl"> {{ $controller->LL('remember') }}</span>
                                                            </label>

                                                            <button type="submit" class="width-35 pull-right btn btn-sm btn-primary">
                                                                <i class="fa fa-key"></i>
                                                                {{ $controller->LL('login') }}
                                                            </button>
                                                        </div>

                                                        <div class="space-4"></div>
                                                    </fieldset>
                                                {!! Form::close() !!}

                                            </div><!--/widget-main-->

                                            <div class="toolbar clearfix">
                                                <div>
                                                    <a href="#" onclick="show_box('forgot-box'); return false;" class="forgot-password-link">
                                                        <i class="fa fa-arrow-left"></i>
                                                        {{ $controller->LL('forgot-password') }}
                                                    </a>
                                                </div>
                                            </div>
                                        </div><!--/widget-body-->
                                    </div><!--/login-box-->

                                    <div id="forgot-box" class="forgot-box widget-box no-border">
                                        <div class="widget-body">
                                            <div class="widget-main">
                                                <h4 class="header red lighter bigger">
                                                    <i class="fa fa-key"></i>
                                                    {{ $controller->LL('retrieve-password') }}
                                                </h4>

                                                <div class="space-6"></div>
                                                <p>
                                                    {{ $controller->LL('title-email-password') }}
                                                </p>

                                                <form>
                                                    <fieldset>
                                                        <label class="block clearfix">
                                                            <span class="block input-icon input-fa fa-right">
                                                                <input type="email" class="col-md-12" placeholder="Email" />
                                                                <i class="ace-icon fa fa-envelope"></i>
                                                            </span>
                                                        </label>

                                                        <div class="clearfix">
                                                            <button onclick="return false;" class="width-35 pull-right btn btn-sm btn-danger">
                                                                <i class="fa fa-lightbulb-o"></i>
                                                                {{ $controller->LL('send-me') }}
                                                            </button>
                                                        </div>
                                                    </fieldset>
                                                </form>
                                            </div><!--/widget-main-->

                                            <div class="toolbar center">
                                                <a href="#" onclick="show_box('login-box'); return false;" class="back-to-login-link">
                                                    {{ $controller->LL('back-login') }}
                                                    <i class="fa fa-arrow-right"></i>
                                                </a>
                                            </div>
                                        </div><!--/widget-body-->
                                    </div><!--/forgot-box-->

                                </div><!--/position-relative-->
                            </div>
                        </div>
                    </div><!--/.span-->
                </div><!--/.row-->
            </div>
        </div><!--/.main-container-->

        
        
        
        
        
        
        
        
        
        
        
        <div class="container main-container display-none">
            <div class="main-content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="login-container">
                            <div class="row">
                                <div class="center">
                                    <h1>
                                        <i class="fa fa-leaf green"></i>
                                        <span class="red">Ace</span>
                                        <span class="white">Application</span>
                                    </h1>
                                    <h4 class="blue">&copy; Company Name</h4>
                                </div>
                            </div>

                            <div class="space-6"></div>

                            <div class="row">
                                <div class="position-relative">
                                    <div id="login-box" class="visible widget-box no-border">
                                        <div class="widget-body">
                                            <div class="widget-main">
                                                <h4 class="header blue lighter bigger">
                                                    <i class="fa fa-coffee green"></i>
                                                    Please Enter Your Information
                                                </h4>

                                                <div class="space-6"></div>

                                                <form action="{!! URL::route('cmf.login') !!}" method="post">
                                                    <fieldset>
                                                        <label>
                                                            <span class="block input-icon input-fa fa-right">
                                                                <input type="text" class="col-md-12" placeholder="Username" />
                                                                <i class="fa fa-user"></i>
                                                            </span>
                                                        </label>

                                                        <label>
                                                            <span class="block input-icon input-fa fa-right">
                                                                <input type="password" class="col-md-12" placeholder="Password" />
                                                                <i class="fa fa-lock"></i>
                                                            </span>
                                                        </label>

                                                        <div class="space"></div>

                                                        <div class="row">
                                                            <label class="col-md-8">
                                                                <input type="checkbox" />
                                                                <span class="lbl"> Remember Me</span>
                                                            </label>

                                                            <button onclick="return false;" class="col-md-4 btn btn-sm btn-primary">
                                                                <i class="fa fa-key"></i>
                                                                Login
                                                            </button>
                                                        </div>
                                                    </fieldset>
                                                </form>
                                            </div><!--/widget-main-->

                                            <div class="toolbar clearfix">
                                                <div>
                                                    <a href="#" onclick="show_box('forgot-box');
                                                                    return false;" class="forgot-password-link">
                                                        <i class="fa fa-arrow-left"></i>
                                                        I forgot my password
                                                    </a>
                                                </div>

                                                <div>
                                                    <a href="#" onclick="show_box('signup-box');
                                                                    return false;" class="user-signup-link">
                                                        I want to register
                                                        <i class="fa fa-arrow-right"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div><!--/widget-body-->
                                    </div><!--/login-box-->

                                    <div id="forgot-box" class="widget-box no-border">
                                        <div class="widget-body">
                                            <div class="widget-main">
                                                <h4 class="header red lighter bigger">
                                                    <i class="fa fa-key"></i>
                                                    Retrieve Password
                                                </h4>

                                                <div class="space-6"></div>
                                                <p>
                                                    Enter your email and to receive instructions
                                                </p>

                                                <form>
                                                    <fieldset>
                                                        <label>
                                                            <span class="block input-icon input-fa fa-right">
                                                                <input type="email" class="col-md-12" placeholder="Email" />
                                                                <i class="fa fa-envelope"></i>
                                                            </span>
                                                        </label>

                                                        <div class="row">
                                                            <button onclick="return false;" class="col-md-5 col-md-offset-7 btn btn-sm btn-danger">
                                                                <i class="fa fa-lightbulb-o"></i>
                                                                Send Me!
                                                            </button>
                                                        </div>
                                                    </fieldset>
                                                </form>
                                            </div><!--/widget-main-->

                                            <div class="toolbar center">
                                                <a href="#" onclick="show_box('login-box');
                                                                    return false;" class="back-to-login-link">
                                                    Back to login
                                                    <i class="fa fa-arrow-right"></i>
                                                </a>
                                            </div>
                                        </div><!--/widget-body-->
                                    </div><!--/forgot-box-->

                                    <div id="signup-box" class="widget-box no-border">
                                        <div class="widget-body">
                                            <div class="widget-main">
                                                <h4 class="header green lighter bigger">
                                                    <i class="fa fa-users blue"></i>
                                                    New User Registration
                                                </h4>

                                                <div class="space-6"></div>
                                                <p>
                                                    Enter your details to begin:
                                                </p>

                                                <form>
                                                    <fieldset>
                                                        <label>
                                                            <span class="block input-icon input-fa fa-right">
                                                                <input type="email" class="col-md-12" placeholder="Email" />
                                                                <i class="fa fa-envelope"></i>
                                                            </span>
                                                        </label>

                                                        <label>
                                                            <span class="block input-icon input-fa fa-right">
                                                                <input type="text" class="col-md-12" placeholder="Username" />
                                                                <i class="fa fa-user"></i>
                                                            </span>
                                                        </label>

                                                        <label>
                                                            <span class="block input-icon input-fa fa-right">
                                                                <input type="password" class="col-md-12" placeholder="Password" />
                                                                <i class="fa fa-lock"></i>
                                                            </span>
                                                        </label>

                                                        <label>
                                                            <span class="block input-icon input-fa fa-right">
                                                                <input type="password" class="col-md-12" placeholder="Repeat password" />
                                                                <i class="fa fa-retweet"></i>
                                                            </span>
                                                        </label>

                                                        <label>
                                                            <input type="checkbox" />
                                                            <span class="lbl">
                                                                I accept the
                                                                <a href="#">User Agreement</a>
                                                            </span>
                                                        </label>

                                                        <div class="space-24"></div>

                                                        <div class="row">
                                                            <button type="reset" class="col-md-6 btn btn-sm">
                                                                <i class="fa fa-refresh"></i>
                                                                Reset
                                                            </button>

                                                            <button onclick="return false;" class="col-md-6 btn btn-sm btn-success">
                                                                Register
                                                                <i class="fa fa-arrow-right fa fa-on-right"></i>
                                                            </button>
                                                        </div>
                                                    </fieldset>
                                                </form>
                                            </div>

                                            <div class="toolbar center">
                                                <a href="#" onclick="show_box('login-box');
                                                                    return false;" class="back-to-login-link">
                                                    <i class="fa fa-arrow-left"></i>
                                                    Back to login
                                                </a>
                                            </div>
                                        </div><!--/widget-body-->
                                    </div><!--/signup-box-->
                                </div><!--/position-relative-->
                            </div>
                        </div>
                    </div><!--/span-->
                </div><!--/row-->
            </div>
        </div><!--/.fluid-container-->

        <!--basic scripts-->

        <!--page specific plugin scripts-->

        <!--inline scripts related to this page-->

        <script type="text/javascript">
            function show_box(id) {
                jQuery('.widget-box.visible').removeClass('visible');
                jQuery('#' + id).addClass('visible');
            }
        </script>
    </body>
@stop
