<div class="modal fade" id="modal-autologout">
    <div class="modal-dialog" style="width: 405px; height: 310px;">
        <div class="modal-content">
            <div class="modal-body">
                <div class="position-relative">
                    <div class="login-box visible widget-box no-border" id="login-box">
                        <div class="widget-body">
                            <div class="widget-main">
                                <h4 class="header blue lighter bigger" style="margin-top: 0;">
                                    <i class="fa fa-user green"></i>
                                    {{ $controller->LL('session-expired') }}
                                </h4>

                                <div class="space-6"></div>

                                <form action="{!! route('telenok.login.process') !!}" method='post' id='form-session-expired'>
                                    
                                    <div id="login-error" class="login-notice alert alert-danger display-none">
                                        {!! $controller->LL('error.login.title') !!}<br><br>
                                        <ul>
                                            <li>{{ $controller->LL('error.login') }}</li>
                                        </ul>
                                    </div>

                                    <fieldset>
                                        <label class="block clearfix">
                                            <span class="block input-icon input-fa fa-right">
                                                <input type="text" value="" placeholder="{{ $controller->LL('username') }}" class="form-control" name="username">
                                                <i class="ace-icon fa fa-user"></i>
                                            </span>
                                        </label>

                                        <label class="block clearfix">
                                            <span class="block input-icon input-fa fa-right">
                                                <input type="password" placeholder="{{ $controller->LL('password') }}" class="form-control" autocomplete="off" name="password">
                                                <i class="ace-icon fa fa-lock"></i>
                                            </span>
                                        </label>

                                        <div class="space"></div>

                                        <div class="clearfix">
                                            
                                            @if (!session('expire_on_close'))
                                            <label class="inline">
                                                <input type="checkbox" value="1" name="remember">
                                                <span class="lbl"> {{ $controller->LL('remember') }}</span>
                                            </label>
                                            @endif 

                                            <button class="width-35 pull-right btn btn-sm btn-primary" type="submit">
                                                <i class="fa fa-key"></i>
                                                {{ $controller->LL('login') }}
                                            </button>
                                        </div>

                                        <div class="space-4"></div>
                                    </fieldset>
                                </form>
                            </div><!--/widget-main-->
                        </div><!--/widget-body-->
                    </div><!--/login-box-->
                    <!--/forgot-box-->
                </div>
            </div>
        </div>
    </div>
</div>

<?php

ob_start();

?>

<script>

    jQuery(function()
    {
        var logined = true;
        
        var showModalLogin = function()
        {
            logined = false;
            
            jQuery('#modal-autologout').modal({
                backdrop: 'static',
                keyboard: false
            })
            .modal('show');
        };
        
        var hideModalLogin = function()
        {
            logined = true;
            
            jQuery('#modal-autologout').modal('hide');
            
            jQuery('#modal-autologout form').get(0).reset();
        };
        
        var validateSession = function()
        {
            jQuery.ajax({
                url: "{!! route('telenok.validate.session') !!}",
                dataType: "json",
                success: function(data)
                {
                    if (!data.logined)
                    {
                        showModalLogin();
                    }

                    if (data.csrf_token)
                    {
                        jQuery('meta[name="csrf-token"]').attr('content', data.csrf_token);
                    }
                }
            });
        }

        setInterval(function()
        {
            if (logined)
            {
                validateSession();
            }
        }, 60000);
        
        jQuery(document).ajaxSuccess(function(event,request, settings, data)
        {
            if (settings.dataType == 'json' && data.error == 'unauthorized' && logined) 
            {
                validateSession();
                showModalLogin();
            }
        });
        
        jQuery('#form-session-expired').submit(function()
        {
            jQuery.ajax({
                url: "{!! route('telenok.login.process') !!}",
                dataType: "json",
                data: jQuery(this).serialize(),
                method: 'post',
                success: function(data)
                {
                    if (data.success)
                    {
                        hideModalLogin();
                        jQuery('#login-error').hide();
                    }
                    else
                    {
                        jQuery('#login-error').show();
                    }
                }
            });
                
            return false;
        });
        
    });

</script>

<?php

$jsCode = ob_get_contents();

ob_end_clean();
	
$controller->addJsCode($jsCode);

?>	