<div class="modal-dialog">
	<div class="modal-content">

		<div class="modal-header table-header">
			<button data-dismiss="modal" class="close" type="button">×</button>
			<h4>{{ \App\Telenok\Core\Model\Object\Type::where('code', $model->getTable())->first()->translate('title_list') }}</h4>
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