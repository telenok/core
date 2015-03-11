@extends('core::layout.model')


@section('script')

	@parent
	
	@section('ajaxLock')
	
		function ajaxLock{{$uniqueId}}()
		{ 
			if (!jQuery("#model-ajax-{{$uniqueId}}").size() || !{{ intval($model->getKey()) }})
			{
				return;
			}

			setTimeout(function() { ajaxLock{{$uniqueId}}(); }, {{ $controller->getLockInFormPeriod() * 700}});

			jQuery.ajax({
				url: '{{ $controller->getRouterLock() }}',
				type: 'post',
				data: { id: {{ intval($model->getKey()) }} },
				dataType: 'json',
				cache: false
			});
		}

		setTimeout(function() { ajaxLock{{$uniqueId}}(); }, 2000);

	@stop
	
	@section('buttonType')
 
        if (button_type=='close')
        {	
			var divId = $el.closest('div.tab-pane').attr('id');

			jQuery('li a[href=#' + divId + '] i.fa.fa-times').click();
			
			return;
        }
		else if (button_type == 'delete.close')
		{ 
			if (confirm('{{ $controller->LL('notice.sure') }}'))
			{
				$el.attr('action', "{{$controller->getRouterDelete(['id' => $model->getKey()])}}");
			}
			else
			{
				return;
			}
		}
	@stop

@stop


@section('form')

	@parent
    
	@section('formBtn')
	
    <div class='form-actions center no-margin'>
        <button type="submit" class="btn btn-success" onclick="jQuery(this).closest('form').data('btn-clicked', 'save');">
            {{$controller->LL('btn.save')}}
        </button>
        <button type="submit" class="btn btn-info" onclick="jQuery(this).closest('form').data('btn-clicked', 'save.close');">
            {{$controller->LL('btn.save.close')}}
        </button>
        <button type="submit" class="btn" onclick="jQuery(this).closest('form').data('btn-clicked', 'close');">
            {{$controller->LL('btn.close')}}
        </button>
    </div>

	@stop
     
@stop
 
 