@extends('core::layout.model')


@section('script')
	@parent
	
	@section('ajaxLock')
	@stop
	
	@section('buttonType')
 
        if (button_type=='close')
        {	
			var divId = $el.closest('div.tab-pane').attr('id');

			jQuery('li a[href=#' + divId + '] i.fa.fa-times').click();
			
			return;
        }
	@stop

    @section('ajaxDone')
        @parent
        
        else if (button_type=='started')
        {
            jQuery.gritter.add({
                title: '{{$controller->LL('notice.started')}}! ',
                text: '{{$controller->LL('notice.started.description')}}!',
                class_name: 'gritter-success gritter-light',
                time: 3000,
            });

            $el.closest('div.container-model-{{$uniqueId}}').html(data.tabContent); 
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
                {{ $controller->LL('notice.started') }}!
            </strong>
            {{ $controller->LL('notice.started.description') }}
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
	
	@section('lockedContainer')
	@stop

	@section('formField')

	{!! Form::hidden($model->getKeyName(), $model->getKey()) !!}

	<div class="row">
		<div class="col-xs-12"> 
            <h2>{{$model->translate('title')}}</h2>
		</div>	
	</div>

	<div class="row">
		<div class="col-xs-12"> 
            <p class="lead">{{$model->translate('description')}}</p>
		</div>	
	</div>
    
    @if (!isset($success) || !$success)
    
        <?php

            $parameters = $model->parameter()->active()->get();
            $collectionParameters = app('telenok.config')->getWorkflowParameter();

            foreach ($parameters->all() as $key => $p)
            {
                echo $collectionParameters->get($p->key)->getFormModelContent($controller, $model, $p, $uniqueId);
            }
        ?>

    @endif
    
	@stop
	
	
	@section('formBtn')
    <div class='form-actions center no-margin'>
		
		@if (isset($canStart) && $canStart)
        <button type="submit" class="btn btn-success" onclick="jQuery(this).closest('form').data('btn-clicked', 'started');">
            {{ $controller->LL('btn.start') }}
        </button>
        @endif

		<button type="submit" class="btn" onclick="jQuery(this).closest('form').data('btn-clicked', 'close');">
            {{ $controller->LL('btn.close') }}
        </button>
		
    </div>
	@stop
     
@stop
