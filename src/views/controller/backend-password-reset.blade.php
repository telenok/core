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

                                                {!! Form::open(['route' => ['cmf.password.reset.token.process', 'token' => $token], 'method' => 'post', 'id' => 'password-reset', 'autocomplete' => "off"]) !!}
													<input type="hidden" name="_token" value="{{ csrf_token() }}">
													<input type="hidden" name="token" value="{{ $token }}">
													
													<div class="reset-notice alert alert-danger display-none" id='reset-error'>
														{!! $controller->LL('error.reset.title') !!}<br><br>
														<ul>
															<li>{{ $controller->LL('error.reset.1') }}</li>
															<li>{{ $controller->LL('error.reset.2', ['length' => config('auth.password.length-min')]) }}</li>
															<li>{{ $controller->LL('error.reset.3') }}</li>
														</ul>
													</div>

													<div class="reset-notice alert alert-success display-none" id='reset-success'>
														{!! $controller->LL('reset.ok.title') !!}<br><br>
														<ul>
															<li>{{ $controller->LL('reset.ok') }}</li>
														</ul>
													</div>

                                                    <fieldset>
                                                        <label class="block clearfix">
                                                            <span class="block input-icon input-fa fa-right">
                                                                <input type="text" name="email" class="form-control" placeholder="{{ $controller->LL('email') }}" value="" />
                                                                <i class="ace-icon fa fa-user"></i>
                                                            </span>
                                                        </label>

                                                        <label class="block clearfix">
                                                            <span class="block input-icon input-fa fa-right">
                                                                <input type="password" name="password" class="form-control" placeholder="{{ $controller->LL('password') }}" />
                                                                <i class="ace-icon fa fa-lock"></i>
                                                            </span>
                                                        </label>

                                                        <label class="block clearfix">
                                                            <span class="block input-icon input-fa fa-right">
                                                                <input type="password" name="password_confirmation" class="form-control" placeholder="{{ $controller->LL('password.confirm') }}" />
                                                                <i class="ace-icon fa fa-lock"></i>
                                                            </span>
                                                        </label>

                                                        <div class="space"></div>

                                                        <div class="clearfix">
                                                            <button type="submit" class="width-35 pull-right btn btn-sm btn-primary">
                                                                <i class="fa fa-key"></i>
                                                                {{ $controller->LL('btn.reset') }}
                                                            </button>
                                                        </div>

                                                        <div class="space-4"></div>
                                                    </fieldset>
                                                {!! Form::close() !!}

                                            </div><!--/widget-main-->

                                            <div class="toolbar clearfix">
                                                <div>
                                                    <a href="{!! route("cmf.login.content") !!}" class="forgot-password-link">
                                                        <i class="fa fa-arrow-left"></i>
                                                        {{ $controller->LL('back-login') }}
                                                    </a>
                                                </div>
                                            </div>
                                        </div><!--/widget-body-->
                                    </div><!--/login-box--> 
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
			
			jQuery("form#password-reset").submit(function()
			{
				$.ajax({
						type: "POST",
						url: "{!! route('cmf.password.reset.token.process', ['token' => $token]) !!}",
						data: jQuery(this).serialize(),
						dataType: 'json',
						success: function(data)
						{
							jQuery('div.reset-notice').hide();
							
							if (data.error == 1)
							{
								jQuery('#reset-error').show();
							}
							else if (data.success == 1)
							{
								jQuery('#reset-success').show();
							}
						}
					});

				return false;
			}); 
        </script>
    </body>
@stop
