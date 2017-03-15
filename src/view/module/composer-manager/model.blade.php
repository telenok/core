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
        <button type="submit" class="btn btn-success" onclick="jQuery(this).closest('form').data('btn-clicked', 'save');" autofocus="autofocus">
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



<div class="modal-dialog">
    <div class="modal-content">

        <div class="modal-header table-header">
            <button data-dismiss="modal" class="close" type="button">×</button>
            <h4>{{ \App\Vendor\Telenok\Core\Model\Object\Type::where('code', (string)$model->getTable())->first()->translate('title_list') }}</h4>
        </div>
        <div class="modal-body" style="padding: 15px; position: relative;">
            <div class="widget-main">
                <table class="table table-striped table-bordered table-hover" id="table-{{$gridId}}" role="grid"></table>
            </div>
        </div>

        <script type="text/javascript">
            (function()
            {
                var columns = [];

                @foreach($fields as $key => $field)

                    @if ($key==0)
                        columns.push({
                            data : "choose",
                            title : "{{ $controller->LL('btn.choose') }}",
                            orderable : false
                        });
                @endif

                columns.push({
                    data : "{{ $field->code }}",
                    title : "{{ $field->translate('title_list') }}",
                    orderable : {{ (int)$field->allow_sort ? "true" : "false" }}
                });

                @endforeach

                telenok.addDataTable({
                    domId : 'table-{{$gridId}}',
                    ajax : '{!! URL::route("telenok.module.{$controller->getKey()}.wizard.list", ["typeId" => empty($typeList) ? $type->getKey() : $typeList]) !!}',
                    dom : "<'row'<'col-md-6'B><'col-md-6'f>r>t<'row'<'col-md-6'i><'col-md-6'p>>",
                    columns : columns,
                    pageLength : 10,
                    order : []
                });
            })();
        </script>
    </div>
</div>
