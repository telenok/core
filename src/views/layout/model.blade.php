<div class="container-model container-model-{{$uniqueId}}">

<script type="text/javascript"> 

@section('script')

    jQuery('[data-rel=popover]', '#model-ajax-{{$uniqueId}}').popover({container:'body'});

	jQuery(".datetime-picker").not('.datetime-picker-added').addClass('datetime-picker-added').datetimepicker(
	{
		pick12HourFormat: true,
		autoclose: true,
		todayBtn: true,
		minuteStep: 1
	});

	@yield('ajaxLock')  

    jQuery('#model-ajax-{{$uniqueId}}').on('submit', function(e) 
	{
        e.preventDefault();

        var $container = jQuery(this).closest('div.container-model');
        var $el = jQuery(this);

        var button_type = jQuery(this).data('btn-clicked');
        
		@yield('buttonType')
		
		@yield('beforeAjax')

        jQuery.ajax({
            url: $el.attr('action'),
            type: 'post',
            data: (new FormData(this)),
            dataType: 'json',
            cache: false,
			processData: false,
			contentType: false,
		})
		.done(function(data, textStatus, jqXHR) {
			
		@section('ajaxDone')
				
			if (button_type == 'save.close' || button_type == 'delete.close')
			{
				var divId = $el.closest('div.tab-pane').attr('id');

				jQuery('li a[href=#' + divId + '] i.fa.fa-times').click();
			}
			else if (button_type=='save')
			{
				jQuery.gritter.add({
					title: '{{$controller->LL('notice.saved')}}! {{$controller->LL('notice.saved.description')}}',
					text: '{{$controller->LL('notice.saved.thank.you')}}!',
					class_name: 'gritter-success gritter-light',
					time: 3000,
				});
				
				$el.closest('div.container-model-{{$uniqueId}}').html(data.tabContent); 
			}
				
		@show

		})
		.fail(function(jqXHR, textStatus, errorThrown) {

		@section('ajaxFail')

			var jsonResponse = jQuery.parseJSON(jqXHR.responseText);

			try
			{
				var jsonError = jQuery.parseJSON(jsonResponse.error.message);
			}
			catch(e)
			{
				var jsonError = jsonResponse.error.message;
			}

			var $errorContainer = jQuery('div.error-container', $el);

			var errorGritterText = [];

			jQuery('div.alert-danger, div.alert-success, div.alert-warning', $container).remove();

			if (jsonError instanceof Array && jsonError.length) 
			{
				jQuery.each(jsonError.reverse(), function(i,v) {
					$errorContainer.prepend('<div class="alert alert-danger">' + v + '<button data-dismiss="alert" class="close" type="button"><i class="fa fa-times"></i></button></div>');
					errorGritterText.unshift("- " + v);
				});
			}
			else if (typeof jsonError === "string")
			{
				errorGritterText.push(jsonError);
				$errorContainer.prepend('<div class="alert alert-danger">' + jsonError + '<button data-dismiss="alert" class="close" type="button"><i class="fa fa-times"></i></button></div>');
			}

			jQuery.gritter.add({
				title: '{{$controller->LL('notice.error')}}! {{$controller->LL('notice.error.undefined')}}',
				text: errorGritterText.join("<br>"),
				class_name: 'gritter-error gritter-light',
				time: 5000,
			});

		@show

		});
	});

@show

</script>

@section('notice')
    @if (isset($success) && !empty($success))
    <div class="alert alert-block alert-success">
        <button data-dismiss="alert" class="close" type="button">
            <i class="fa fa-times"></i>
        </button>
        <p>
            <strong>
                <i class="fa fa-check"></i>
                {{ $controller->LL('notice.saved') }}!
            </strong>
            {{ $controller->LL('notice.saved.description') }}
        </p>
    </div>
    @endif

    @if (isset($warning))
        @foreach((array)$warning as $w)
        <div class="alert alert-block alert-warning">
            <button data-dismiss="alert" class="close" type="button">
                <i class="fa fa-times"></i>
            </button>
            <p>
                <strong>
                    <i class="fa fa-exclamation-triangle"></i>
                    {{ $controller->LL('notice.warning') }}
                </strong>
                {{$w}}
            </p>
        </div>
        @endforeach
    @endif
@show


@section('form')

{!! Form::open(array('url' => $routerParam, 'files' => true, 'id' => "model-ajax-$uniqueId", 'class' => 'form-horizontal')) !!}

	@section('errorContainer')
    <div class="error-container"></div>
	@show 

	@yield('lockedContainer')
	@yield('formField') 

	@section('formBtn')
    <div class='form-actions center no-margin'>
        <button type="submit" class="btn btn-success" onclick="jQuery(this).closest('form').data('btn-clicked', 'save');">
            {{ $controller->LL('btn.save') }}
        </button>
        <button type="submit" class="btn btn-info" onclick="jQuery(this).closest('form').data('btn-clicked', 'save.close');">
            {{ $controller->LL('btn.save.close') }}
        </button>
        <button type="submit" class="btn" onclick="jQuery(this).closest('form').data('btn-clicked', 'close');">
            {{ $controller->LL('btn.close') }}
        </button>
    </div>
	@show

{!! Form::close() !!}
     
@show
 
</div>
 