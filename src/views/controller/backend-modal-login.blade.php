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
                                    Sorry, your session has expired
                                </h4>

                                <div class="space-6"></div>

                                {!! Form::open(['route' => 'telenok.login.process', 'method' => 'post', 'id' => 'form-session-expired']) !!}
                                    
                                    <div id="login-error" class="login-notice alert alert-danger display-none">
                                        <strong>Whoops!</strong> There were some problems with your input<br><br>
                                        <ul>
                                            <li>Wrong username or password</li>
                                        </ul>
                                    </div>

                                    <fieldset>
                                        <label class="block clearfix">
                                            <span class="block input-icon input-fa fa-right">
                                                <input type="text" value="" placeholder="Your login" class="form-control" name="username">
                                                <i class="ace-icon fa fa-user"></i>
                                            </span>
                                        </label>

                                        <label class="block clearfix">
                                            <span class="block input-icon input-fa fa-right">
                                                <input type="password" placeholder="Your password" class="form-control" autocomplete="off" name="password">
                                                <i class="ace-icon fa fa-lock"></i>
                                            </span>
                                        </label>

                                        <div class="space"></div>

                                        <div class="clearfix">
                                            <label class="inline">
                                                <input type="checkbox" value="1" name="remember">
                                                <span class="lbl"> Remember Me</span>
                                            </label>

                                            <button class="width-35 pull-right btn btn-sm btn-primary" type="submit">
                                                <i class="fa fa-key"></i>
                                                Login
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
            
            jQuery('#modal-autologout').modal('show').modal({
                backdrop: 'static',
                keyboard: false
            });
        };
        
        var hideModalLogin = function()
        {
            logined = true;
            
            jQuery('#modal-autologout').modal('hide');
            
            jQuery('#modal-autologout form').get(0).reset();
            
            $('#modalElement').on('hidden', function(){
                $(this).data('modal', null);
            });
        };

        setInterval(function()
        {
            if (logined)
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
                    }
                });
            }
        }, 10000);
        
        jQuery(document).ajaxSuccess(function(event,request, settings, data)
        {
            if (settings.dataType == 'json' && data.error == 'unauthorized' && logined) 
            {
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