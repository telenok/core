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
		if (confirm('{{ $controller->LL('notice.sure') }}'))
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
	
	{!! Form::hidden('modelType', $modelType) !!}
	{!! Form::hidden('modelPath', $model ? $model->getRealPath() : '') !!}

	
	<div class="form-group">
		{!! Form::label("directory", $controller->LL('field.directory'), array('class'=>'col-sm-3 control-label no-padding-right')) !!}

		<div class="col-sm-9">
			{!! Form::text('directory', $modelCurrentDirectory->getRealPath(), ['readonly' => 'readonly', 'class' => 'col-xs-5 col-sm-5']) !!}
		</div>
	</div>

	
	@if ($modelType == 'directory')

	<div class="form-group">
		{!! Form::label("name", $controller->LL('field.directory.name'), array('class'=>'col-sm-3 control-label no-padding-right')) !!}

		<div class="col-sm-9">
			{!! Form::text('name', $model ? $model->getFilename() : '', ['class' => 'col-xs-5 col-sm-5']) !!}
		</div>
	</div>
	
	@elseif ($modelType == 'file')

	<div class="form-group">
		{!! Form::label("name", $controller->LL('field.file.name'), array('class'=>'col-sm-3 control-label no-padding-right')) !!}

		<div class="col-sm-9">
			{!! Form::text('name', $model ? $model->getFilename() : '', ['class' => 'col-xs-5 col-sm-5']) !!}
		</div>
	</div>

	<div class="form-group">
		{!! Form::label("content", $controller->LL('field.file.content'), array('class'=>'col-sm-3 control-label no-padding-right')) !!}

		<div class="col-sm-9">
			@if ($model && $model->getSize() >= $controller->getMaxSizeToView())

			{{ $controller->LL('error.file-too-big') }}

			@else

			{!! Form::textarea('content', $model ? \File::get($model->getRealPath()) : '', ['class' => 'form-control']) !!}

			@endif
		</div>
	</div>

	@endif

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
 
