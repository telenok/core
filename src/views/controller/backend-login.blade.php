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
                                        <span class="green">{{config('app.backend.brand')}}</span>
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

                                                {!! Form::open(['route' => 'cmf.login.process', 'method' => 'post', 'id' => 'login']) !!}
												
													<div class="login-notice alert alert-danger display-none" id='login-error'>
														{!! $controller->LL('error.login.title') !!}<br><br>
														<ul>
															<li>{{ $controller->LL('error.login') }}</li>
														</ul>
													</div>

                                                    <fieldset>
                                                        <label class="block clearfix">
                                                            <span class="block input-icon input-fa fa-right">
                                                                <input type="text" name="username" class="form-control" placeholder="{{ $controller->LL('username') }}" value="{{ old('username') }}" />
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
                                                                <input type="checkbox" name='remember' value="1" @if (old('remember'))checked="checked"@endif/>
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

                                                {!! Form::open(['route' => 'cmf.password.reset.email.process', 'method' => 'post', 'id' => 'password-reset']) !!}

													<div class="password-reset-notice alert alert-danger display-none" id='password-reset-error'>
														{!! $controller->LL('error.password.reset.title') !!}<br><br>
														<ul>
															<li>{{ $controller->LL('error.password.reset') }}</li>
														</ul>
													</div>

													<div class="password-reset-notice alert alert-success display-none" id='password-reset-success'>
														<strong>Okey!</strong> The good news for you.<br><br>
														<ul>
															<li>{{ $controller->LL('email.sent') }}</li>
														</ul>
													</div>

                                                    <fieldset>
                                                        <label class="block clearfix">
                                                            <span class="block input-icon input-fa fa-right">
                                                                <input type="text" class="col-md-12" name="email" placeholder="{{ $controller->LL('reset.password.placeholder') }}" />
                                                                <i class="ace-icon fa fa-envelope"></i>
                                                            </span>
                                                        </label>

                                                        <div class="clearfix">
                                                            <button class="width-35 pull-right btn btn-sm btn-danger">
                                                                <i class="fa fa-lightbulb-o"></i>
                                                                {{ $controller->LL('send-me') }}
                                                            </button>
                                                        </div>
                                                    </fieldset>
                                                {!! Form::close() !!}
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

        <!--basic scripts-->

        <!--page specific plugin scripts-->

        <!--inline scripts related to this page-->

        <script type="text/javascript">
            function show_box(id) 
			{
                jQuery('.widget-box.visible').removeClass('visible');
                jQuery('#' + id).addClass('visible');
            }
			
			jQuery("#password-reset").submit(function()
			{
				jQuery.ajax({
						type: "POST",
						url: "{!! route('cmf.password.reset.email.process') !!}",
						data: jQuery(this).serialize(),
						dataType: 'json',
						success: function(data)
						{
							jQuery('div.password-reset-notice').hide();
							
							if (data.error == 1)
							{
								jQuery('#password-reset-error').show();
							}
							else if (data.success == 1)
							{
								jQuery('#password-reset-success').show();
							}
						}
					});

				return false;
			});
			
			jQuery("#login").submit(function()
			{
				jQuery.ajax({
						type: "POST",
						url: "{!! route('cmf.login.process') !!}",
						data: jQuery(this).serialize(),
						dataType: 'json',
						success: function(data)
						{
							jQuery('div.login-notice').hide();
							
							if (data.error == 1)
							{
								jQuery('#login-error').show();
							}
							else if (data.success == 1)
							{
								window.location.href = data.redirect;
							}
						}
					});

				return false;
			});
        </script>
		
		@foreach($controller->getJsFile() as $file)

		<script src="{!! $file['file'] !!}"></script>

		@endforeach

		@foreach($controller->getJsCode() as $code)

			{!! $code !!} 

		@endforeach
    </body>
@stop
