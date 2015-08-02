<div class="container-model-{{$controller->getUniqueId()}}">

	
<?php

ob_start();

?>

<script type="text/javascript">

@section('script')

	@yield('ajaxLock')  

	jQuery(function()
	{
		jQuery('#model-{{$controller->getUniqueId()}}').on('submit', function(e) 
		{
			e.preventDefault();

			var $container = jQuery(this).closest('div.container-model');
			var $el = jQuery(this);

			var $errorContainer = jQuery('div.error-container', $el);

			var button_type = jQuery(this).data('btn-clicked');

			@section('buttonType')
				if (button_type == 'delete.close')
				{ 
					if (confirm('{{ $controller->LL('notice.sure.delete') }}'))
					{
						$el.attr('action', "{!! $controller->getUrlDelete(['id' => $controller->getEventResource()->get('model')->getKey()]) !!}");
					}
				}
			@show

			@yield('beforeAjax')

			jQuery.ajax({
				@section('ajaxData')
					url: $el.attr('action'),
					type: 'post',
					data: (new FormData(this)),
					dataType: 'json',
					cache: false,
					processData: false,
					contentType: false
				@show
			})
			.done(function(data, textStatus, jqXHR) {

				@section('ajaxDone')

				if (data.redirect)
				{
					window.location = data.redirect;
				}
				else if (data.success == 0)
				{
					$errorContainer.prepend('<div class="alert alert-danger">{{$controller->LL('notice.error.undefined')}}<button data-dismiss="alert" class="close" type="button"><i class="fa fa-times"></i></button></div>');
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

				@show

			});
		});
	});

@show

</script>

<?php

$jsCode = ob_get_contents();

ob_end_clean();

$controllerAction->addJsCode($jsCode); 
	
?>

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

{!! Form::open(array('url' => $urlParam, 'files' => true, 'id' => "model-{$controller->getUniqueId()}", 'class' => $controller->getFormClass())) !!}

	@section('errorContainer')
    <div class="error-container"></div>
	@show 

	@yield('lockedContainer')

	@section('formField')

		{!! Form::hidden('id', $controller->getEventResource()->get('model')->getKey()) !!}

		@if ($controller->getEventResource()->get('model')->exists)
			{!! Form::hidden('redirect_after_update', $controller->getRedirectAfterUpdate() ) !!}
			{!! Form::hidden('redirect_after_delete', $controller->getRedirectAfterDelete() ) !!}
		@else
			{!! Form::hidden('redirect_after_store', $controller->getRedirectAfterStore() ) !!}
		@endif
		
		@include($controller->getFormView())

	@show

	@section('formBtn')
	<div class='col-xs-12' style="margin-top: 15px;">

		@section('btnList')

		@if ((isset($canCreate) && $canCreate) || (isset($canUpdate) && $canUpdate))
		<button type="submit" class="btn btn-success" onclick="jQuery(this).closest('form').data('btn-clicked', 'save');">
			{{ $controller->LL('btn.save') }}
		</button>
		@endif

		@if (isset($canDelete) && $canDelete)
		<button type="submit" class="btn btn-danger" onclick="jQuery(this).closest('form').data('btn-clicked', 'delete.close');">
			{{ $controller->LL('btn.delete') }}
		</button>
		@endif

		@show

	</div>
	@show

{!! Form::close() !!}
     
@show
 
</div>
 