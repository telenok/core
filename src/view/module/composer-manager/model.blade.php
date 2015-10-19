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



@section('notice')
    @if (isset($success) && !empty($success))
    <div class="alert alert-block alert-success">
        <button data-dismiss="alert" class="close" type="button">
            <i class="fa fa-times"></i>
        </button>
        <p>
            <strong>
                <i class="fa fa-check"></i>
                {{ $controller->LL('notice.saved') }}
            </strong>
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
@stop


@section('form')

	@parent 

	@section('formField')
	
    {!! Form::hidden('id', $id) !!}
    
	<div class="form-group">
		<div class="col-sm-9">
            <?php
            
            echo nl2br(htmlspecialchars($content));
            
            ?>
		</div>
	</div>
	@stop



	@section('formBtn')
    <div class='form-actions center no-margin'>
        <button type="submit" class="btn btn-success" onclick="jQuery(this).closest('form').data('btn-clicked', 'save');">
            {{$controller->LL('btn.update.package')}}
        </button>
        <button type="submit" class="btn btn-info" onclick="jQuery(this).closest('form').data('btn-clicked', 'save.close');">
            {{$controller->LL('btn.update.close.package')}}
        </button>
        <button type="submit" class="btn btn-danger" onclick="jQuery(this).closest('form').data('btn-clicked', 'delete.close');">
            {{$controller->LL('btn.delete')}}
        </button>
        <button type="submit" class="btn" onclick="jQuery(this).closest('form').data('btn-clicked', 'close');">
            {{$controller->LL('btn.close')}}
        </button>
    </div>
	@stop

@stop
 
