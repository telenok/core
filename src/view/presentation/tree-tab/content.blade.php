<div class="container-table">

    <div class="table-header">{{ Lang::get("{$controller->getPackage()}::module/{$controller->getKey()}.list.name")}}</div>

    <div class="filter display-none">
        <div class="widget-box transparent">
            <div class="widget-header">
                <h5 class="widget-title smaller">{{ $controller->LL('table.filter.header') }}</h5>
                <span class="widget-toolbar no-border">
                    <a data-action="collapse" href="#">
                        <i class="ace-icon fa fa-chevron-up"></i>
                    </a>
                </span>
            </div>

            <div class="widget-body">
                <div class="widget-main">
                    <form class="form-horizontal" onsubmit="return false;">
                        
                        <button class="btn btn-info btn-sm" onclick="return false;">
                            <i class="fa fa-key bigger-110"></i>
                            {{ $controller->LL('btn.search') }}
                        </button>
                        <button class="btn btn-sm" type="reset">
                                <i class="fa fa-eraser bigger-110"></i>
                                {{ $controller->LL('btn.clear') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <table class="table table-striped table-bordered table-hover" id="telenok-{{$controller->getPresentation()}}-presentation-grid-{{$gridId}}" role="grid"></table>



    <script type="text/javascript">
        (function()
        {
            var presentation = telenok.getPresentation('{{$controller->getPresentationModuleKey()}}');
            var columns = [];

            columns.push({ 
                data : "tableCheckAll",
                title : 
                    '<label><input type="checkbox" class="ace ace-checkbox-2" name="checkHeader" onclick="var tb=jQuery(\'#' 
                    + presentation.getPresentationDomId() + '-grid-{{$gridId}}\').dataTable();' 
                    + 'var chbx = jQuery(\'input[name=tableCheckAll\\\\[\\\\]]\', tb.fnGetNodes());' 
                    + 'chbx.prop(\'checked\', jQuery(\'input[name=checkHeader]\', tb).prop(\'checked\'));">'
                    + '<span class="lbl">' 
                    + '</span></label>',
                className : "center", 
                width : "20px", 
                defaultContent : '<input type="checkbox" class="ace ace-checkbox-2" name="checkHeader" value=><span class="lbl"></span>', 
                orderable : false
            });

            columns.push({ data : "tableManageItem", title : "", orderable : false });

            @foreach((array)$fields as $key => $field)
                @if ($key==0)
                    columns.push({ data : "{{ $field->code }}", title : "№", className : "center", width : "40px" });
                    columns.push({ data : "{{ $field->code }}", title : "{{ $controller->LL('entity.'.$field->code) }}" });
                @else
                    columns.push({ data : "{{ $field->code }}", title : "{{ $controller->LL('entity.'.$field->code) }}" });
                @endif
            @endforeach

            presentation.addDataTable({
                columns : columns,
                order: [],
                ajax : '{!! $controller->getRouterList() !!}',
                domId: presentation.getPresentationDomId() + "-grid-{{$gridId}}",
                btnCreateUrl : '#/module/{{ $controller->getParent() }}/{{ $controller->getKey() }}/action-param/{!!
                    urlencode($controller->getRouterActionParam()) !!}/tab/create/{!!
                    urlencode( $controller->getRouterCreate(['id' => $type->getKey()]) ) !!}/',
                btnListEditUrl : '{!! $controller->getRouterListEdit() !!}',
                btnListDeleteUrl : '{!! $controller->getRouterListDelete() !!}',
                btnListLockUrl : '{!! $controller->getRouterListLock() !!}',
                btnListUnlockUrl : '{!! $controller->getRouterListUnlock() !!}'
            });
        })();
    </script>
</div>