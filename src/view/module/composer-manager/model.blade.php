@extends('core::layout.model')

@section('script')

	@parent 
	
	@section('buttonType')
	if (button_type=='close')
	{	
		var divId = $el.closest('div.tab-pane').attr('id');

		jQuery('li a[href=#' + divId + '] i.fa.fa-times').click();

		return;
	}
	else if (button_type == 'delete.close')
	{ 
		@if ($model)
		if (confirm('{{ $controller->LL('notice.sure.delete') }}'))
		{
			$el.attr('action', "{!! $controller->getRouterDelete(['id' => $model->getRealPath()]) !!}");
		}
		else
		{
			return;
		}
		@endif
	}
	@stop 

@stop


@section('form')

	@parent 

	@section('formField')
	
	<div class="form-group">
		<div class="col-sm-9">
            {{$content}}
		</div>
	</div>
	@stop



	@section('formBtn')
    <div class='form-actions center no-margin'>
        <button type="submit" class="btn btn-success" onclick="jQuery(this).closest('form').data('btn-clicked', 'save');">
            {{$controller->LL('btn.save')}}
        </button>
        <button type="submit" class="btn btn-info" onclick="jQuery(this).closest('form').data('btn-clicked', 'save.close');">
            {{$controller->LL('btn.save.close')}}
        </button>
        @if ($model)
        <button type="submit" class="btn btn-danger" onclick="jQuery(this).closest('form').data('btn-clicked', 'delete.close');">
            {{$controller->LL('btn.delete')}}
        </button>
        @endif
        <button type="submit" class="btn" onclick="jQuery(this).closest('form').data('btn-clicked', 'close');">
            {{$controller->LL('btn.close')}}
        </button>
    </div>
	@stop

@stop
 
