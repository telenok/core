
<script type="text/javascript">
    
    var telenok = telenok || {};
    
    if (telenok.addDataTable == undefined)
    {
        telenok.addDataTable = function(param)
        {
            jQuery('#' + param.domId).dataTable(
                jQuery.extend({}, {
                    searchDelay : 1000,
                    columns : [],
                    autoWidth : false,
                    processing : true,
                    serverSide : param.ajax ? true : false,
                    deferRender : true,
                    jQueryUI : false,
                    pageLength : (param.pageLength ? param.pageLength : 20),
                    dom : "<'row'<'col-md-9'B><'col-md-3'f>r>t<'row'<'col-md-9'T><'col-md-3'p>>",
                    buttons : param.buttons || [],
                    language : {
                        paginate : {
                            next : "{{ trans('core::default.btn.next') }}",
                            previous : "{{ trans('core::default.btn.prev') }}", 
                        },
                        emptyTable : "{{ trans('core::default.table.empty') }}",
                        search : "{{ trans('core::default.btn.search') }} ",
                        searchPlaceholder : "{{ trans('core::default.table.placeholder.search') }} ",
                        info : "{{ trans('core::default.table.showed') }}",
                        infoEmpty : "{{ trans('core::default.table.empty.showed') }}",
                        zeroRecords : "{{ trans('core::default.table.empty.filtered') }}",
                        infoFiltered : ""
                    }
                }, param));
        };
    }
</script>