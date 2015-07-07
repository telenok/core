<div class="container-model-{{$controller->getUniqueId()}}">

<script type="text/javascript"> 

@section('script')

	@yield('ajaxLock')  

    jQuery('#model-ajax-{{$controller->getUniqueId()}}').on('submit', function(e) 
	{
        e.preventDefault();

        var $container = jQuery(this).closest('div.container-model');
        var $el = jQuery(this);

        var button_type = jQuery(this).data('btn-clicked');
        
		@yield('buttonType')
		
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

			@show

		})
		.fail(function(jqXHR, textStatus, errorThrown) {

			@section('ajaxFail')

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

{!! Form::open(array('url' => $routerParam, 'files' => true, 'id' => "model-{$controller->getUniqueId()}", 'class' => $controller->getFormClass())) !!}

	@section('errorContainer')
    <div class="error-container"></div>
	@show 

	@yield('lockedContainer')

	@section('formField')

		{!! Form::hidden($controller->getModel()->getKeyName(), $controller->getModel()->getKey()) !!}

		@include($controller->getFormView())

	@show

	@section('formBtn')
	<div class='col-xs-12' style="margin-top: 15px;">

		@section('btnList')

		@if ( (isset($canCreate) && $canCreate) || (isset($canUpdate) && $canUpdate) )
		<button type="submit" class="btn btn-success" onclick="jQuery(this).closest('form').data('btn-clicked', 'save');">
			{{ $controller->LL('btn.save') }}
		</button>

		<button type="submit" class="btn btn-info" onclick="jQuery(this).closest('form').data('btn-clicked', 'save.close');">
			{{ $controller->LL('btn.save.close') }}
		</button>
		@endif

		@if (isset($canDelete) && $canDelete)
		<button type="submit" class="btn btn-danger" onclick="jQuery(this).closest('form').data('btn-clicked', 'delete.close');">
			{{ $controller->LL('btn.delete') }}
		</button>
		@endif

		<button type="submit" class="btn" onclick="jQuery(this).closest('form').data('btn-clicked', 'close');">
			{{ $controller->LL('btn.close') }}
		</button>

		@show

	</div>
	@show

{!! Form::close() !!}
     
@show
 
</div>
 