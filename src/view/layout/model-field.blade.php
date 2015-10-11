
<script type="text/javascript">
    if (telenok.addDataTable == undefined)
    {
        telenok.addDataTable = function(param)
        {
            jQuery('#' + param.domId).dataTable(
                jQuery.extend({}, {
                    "searchDelay": 1000,
                    "multipleSelection": true,
                    "aoColumns": [],
                    "autoWidth": false,
                    "bProcessing": true,
                    "bServerSide": param.sAjaxSource ? true : false,
                    "bDeferRender": '',
                    "bJQueryUI": false,
                    "iDisplayLength": (param.iDisplayLength ? param.iDisplayLength : 20),
                    "sDom": "<'row'<'col-md-9'T><'col-md-3'f>r>t<'row'<'col-md-9'T><'col-md-3'p>>",
                    "oTableTools": {
                        "aButtons": aButtons
                    },
                    "oLanguage": {
                        "oPaginate": {
                            "sNext": "{{ trans('core::default.btn.next') }}",
                            "sPrevious": "{{ trans('core::default.btn.prev') }}", 
                        },
                        "sEmptyTable": "{{ trans('core::default.table.empty') }}",
                        "sSearch": "{{ trans('core::default.btn.search') }} ",
                        "sSearchPlaceholder": "{{ trans('core::default.table.placeholder.search') }} ",
                        "sInfo": "{{ trans('core::default.table.showed') }}",
                        "sInfoEmpty": "{{ trans('core::default.table.empty.showed') }}",
                        "sZeroRecords": "{{ trans('core::default.table.empty.filtered') }}",
                        "sInfoFiltered": "",
                    }
                }, param));
        };
    }
</script>